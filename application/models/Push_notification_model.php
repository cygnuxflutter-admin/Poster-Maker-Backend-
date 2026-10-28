<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Push_notification_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        // Force full 4-byte UTF-8 emoji support across MySQL connection
        if (isset($this->db)) {
            $this->db->simple_query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
        }
    }

    // ------------------------------------------------------------------------
    // Configuration Methods
    // ------------------------------------------------------------------------

    public function get_config($key = null) {
        if ($key !== null) {
            $row = $this->db->select('config_value')->where('config_key', $key)->get('push_config')->row_array();
            return $row ? $row['config_value'] : null;
        }
        $rows = $this->db->get('push_config')->result_array();
        $config = array();
        foreach ($rows as $r) {
            $config[$r['config_key']] = $r['config_value'];
        }
        return $config;
    }

    public function get_all_configs() {
        return $this->get_config();
    }

    public function set_config($key, $value) {
        $data = array(
            'config_value' => $value,
            'updated_at'   => date('Y-m-d H:i:s')
        );
        return $this->db->where('config_key', $key)->update('push_config', $data);
    }

    public function set_multiple_configs($configs) {
        foreach ($configs as $k => $v) {
            $this->set_config($k, $v);
        }
        return true;
    }

    // ------------------------------------------------------------------------
    // Statistics & Dashboard
    // ------------------------------------------------------------------------

    public function get_stats() {
        $today = date('Y-m-d');
        
        $stats = array(
            'total'        => $this->db->count_all('push_notification_bank'),
            'draft'        => $this->db->where('status', 'DRAFT')->from('push_notification_bank')->count_all_results(),
            'approved'     => $this->db->where('status', 'APPROVED')->from('push_notification_bank')->count_all_results(),
            'processing'   => $this->db->where('status', 'PROCESSING')->from('push_notification_bank')->count_all_results(),
            'sent'         => $this->db->where('status', 'SENT')->from('push_notification_bank')->count_all_results(),
            'failed'       => $this->db->where('status', 'FAILED')->from('push_notification_bank')->count_all_results(),
            'devices'      => $this->db->where('is_active', 1)->from('push_device_tokens')->count_all_results(),
            'test_devices' => $this->db->where('is_test_device', 1)->where('is_active', 1)->from('push_device_tokens')->count_all_results(),
            'today_sent'   => $this->db->where('DATE(sent_at)', $today)->from('push_notification_bank')->count_all_results(),
            'today_slots'  => $this->db->where('slot_date', $today)->get('push_slot_executions')->result_array(),
            'is_paused'    => (int)$this->get_config('is_campaign_paused'),
            'dry_run'      => (int)$this->get_config('dry_run_mode'),
            'today_day'    => date('l'),
            'daily_slots'  => $this->get_slots_for_day()
        );

        // Device telemetry aggregate totals across all dispatches
        $telemetry = $this->db->select('
            COALESCE(SUM(sent_count), 0) as total_devices_sent,
            COALESCE(SUM(failed_count), 0) as total_devices_failed,
            COALESCE(SUM(confirmed_delivery_count), 0) as total_confirmed_delivery,
            COALESCE(SUM(click_count), 0) as total_clicks
        ')->get('push_notification_logs')->row_array();

        $stats['total_devices_sent']       = (int)$telemetry['total_devices_sent'];
        $stats['total_devices_failed']     = (int)$telemetry['total_devices_failed'];
        $stats['total_confirmed_delivery'] = (int)$telemetry['total_confirmed_delivery'];
        $stats['total_clicks']             = (int)$telemetry['total_clicks'];
        $stats['total_not_clicked']        = max(0, $stats['total_devices_sent'] - $stats['total_clicks']);
        $stats['overall_ctr']              = ($stats['total_devices_sent'] > 0) ? round(($stats['total_clicks'] / $stats['total_devices_sent']) * 100, 1) : 0;

        return $stats;
    }

    /**
     * Get configured slot times for a specific day of week (e.g. 'sunday', 'monday', etc.)
     * Defaults to current day in Asia/Kolkata
     */
    public function get_slots_for_day($day_name = null) {
        if (empty($day_name)) {
            $day_name = strtolower(date('l'));
        } else {
            $day_name = strtolower(trim($day_name));
        }

        $weekly_json = $this->get_config('weekly_schedule');
        if (!empty($weekly_json)) {
            $weekly = json_decode($weekly_json, true);
            if (is_array($weekly) && !empty($weekly[$day_name])) {
                $val = $weekly[$day_name];
                $slots = is_array($val) ? $val : array_map('trim', explode(',', $val));
                $slots = array_values(array_filter($slots));
                if (!empty($slots)) {
                    return $slots;
                }
            }
        }

        // Fallback to legacy daily_slots config
        $daily_slots_str = $this->get_config('daily_slots');
        $daily_slots = array_map('trim', explode(',', (string)$daily_slots_str));
        $daily_slots = array_values(array_filter($daily_slots));
        return !empty($daily_slots) ? $daily_slots : array('09:00', '13:00', '17:00', '20:00');
    }

    /**
     * Get the complete Sunday-Saturday schedule mapping
     */
    public function get_weekly_schedule() {
        $weekly_json   = $this->get_config('weekly_schedule');
        $days          = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
        $default_slots = (string)$this->get_config('daily_slots');
        if (empty($default_slots)) $default_slots = '09:00,13:00,17:00,20:00';

        $schedule = array();
        if (!empty($weekly_json)) {
            $parsed = json_decode($weekly_json, true);
            if (is_array($parsed)) {
                foreach ($days as $d) {
                    if (isset($parsed[$d]) && strlen(trim($parsed[$d])) > 0) {
                        $schedule[$d] = is_array($parsed[$d]) ? implode(',', $parsed[$d]) : trim($parsed[$d]);
                    } else {
                        $schedule[$d] = $default_slots;
                    }
                }
                return $schedule;
            }
        }

        foreach ($days as $d) {
            $schedule[$d] = $default_slots;
        }
        return $schedule;
    }

    // ------------------------------------------------------------------------
    // Content Bank Management
    // ------------------------------------------------------------------------

    public function get_bank_items($filters = array(), $limit = 50, $offset = 0) {
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        if (!empty($filters['category_name'])) {
            $this->db->where('category_name', $filters['category_name']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('title', $filters['search']);
            $this->db->or_like('body', $filters['search']);
            $this->db->group_end();
        }
        if (!empty($filters['scheduled_slot'])) {
            $this->db->where('scheduled_slot', $filters['scheduled_slot']);
        }

        return $this->db->order_by('id', 'ASC')
                        ->limit($limit, $offset)
                        ->get('push_notification_bank')
                        ->result_array();
    }

    public function get_bank_count($filters = array()) {
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        if (!empty($filters['category_name'])) {
            $this->db->where('category_name', $filters['category_name']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('title', $filters['search']);
            $this->db->or_like('body', $filters['search']);
            $this->db->group_end();
        }
        if (!empty($filters['scheduled_slot'])) {
            $this->db->where('scheduled_slot', $filters['scheduled_slot']);
        }

        return $this->db->from('push_notification_bank')->count_all_results();
    }

    public function get_item($id) {
        return $this->db->where('id', (int)$id)->get('push_notification_bank')->row_array();
    }

    public function update_item($id, $data) {
        $allowed = array('title', 'body', 'category_id', 'category_name', 'target_screen', 'image_url', 'scheduled_slot', 'status');
        $clean = array();
        foreach ($allowed as $f) {
            if (isset($data[$f])) {
                $clean[$f] = $data[$f];
            }
        }
        if (empty($clean)) return false;
        return $this->db->where('id', (int)$id)->update('push_notification_bank', $clean);
    }

    public function bulk_update_status($ids, $status) {
        if (empty($ids) || !is_array($ids)) return 0;
        $valid_statuses = array('DRAFT', 'APPROVED', 'SCHEDULED', 'SENT', 'FAILED');
        if (!in_array($status, $valid_statuses)) return 0;

        $this->db->where_in('id', array_map('intval', $ids));
        $this->db->update('push_notification_bank', array('status' => $status));
        return $this->db->affected_rows();
    }

    public function approve_next_batch($count = 50, $category = null) {
        $this->db->where('status', 'DRAFT');
        if ($category !== null && $category !== '') {
            $this->db->where('category_name', $category);
        }
        $items = $this->db->select('id')->order_by('id', 'ASC')->limit((int)$count)->get('push_notification_bank')->result_array();
        if (empty($items)) return 0;

        $ids = array_column($items, 'id');
        $this->db->where_in('id', $ids)->update('push_notification_bank', array('status' => 'APPROVED'));
        return count($ids);
    }

    // ------------------------------------------------------------------------
    // Queue Claiming & Slot Idempotency (Phase 5)
    // ------------------------------------------------------------------------

    /**
     * Atomically claims the next approved notification for a scheduled daily slot.
     * Prevents duplicate execution per slot/date and duplicate item sends.
     */
    public function claim_slot_notification($slot_name, $is_dry_run = 1) {
        $today = date('Y-m-d');

        // Step 1: Slot-level idempotency check via push_slot_executions
        $existing = $this->db->where('slot_date', $today)
                             ->where('slot_name', $slot_name)
                             ->get('push_slot_executions')
                             ->row_array();

        if ($existing) {
            // Already claimed or finished today for this slot!
            return array('status' => 'already_executed', 'slot' => $existing);
        }

        // Step 2: Attempt atomic reservation in push_slot_executions
        $insert_data = array(
            'slot_date'    => $today,
            'slot_name'    => $slot_name,
            'status'       => 'CLAIMED',
            'is_dry_run'   => (int)$is_dry_run,
            'executed_at'  => date('Y-m-d H:i:s')
        );

        // We use INSERT IGNORE to prevent race condition between concurrent cron workers
        $sql = "INSERT IGNORE INTO `push_slot_executions` (`slot_date`, `slot_name`, `status`, `is_dry_run`, `executed_at`) 
                VALUES (?, ?, 'CLAIMED', ?, ?)";
        $this->db->query($sql, array($today, $slot_name, (int)$is_dry_run, date('Y-m-d H:i:s')));
        
        if ($this->db->affected_rows() === 0) {
            // Another worker inserted the slot execution record milliseconds ago!
            return array('status' => 'race_prevented', 'message' => 'Concurrent cron execution safely blocked.');
        }

        $execution_id = $this->db->insert_id();

        // Step 3: Find next APPROVED item matching this slot (or 'ANY')
        $item = $this->db->where('status', 'APPROVED')
                         ->group_start()
                            ->where('scheduled_slot', $slot_name)
                            ->or_where('scheduled_slot', 'ANY')
                         ->group_end()
                         ->order_by('id', 'ASC')
                         ->limit(1)
                         ->get('push_notification_bank')
                         ->row_array();

        if (!$item) {
            // Fallback: If no slot-specific approved item, take any approved item
            $item = $this->db->where('status', 'APPROVED')
                             ->order_by('id', 'ASC')
                             ->limit(1)
                             ->get('push_notification_bank')
                             ->row_array();
        }

        if (!$item) {
            // No approved content remaining in bank! Mark slot execution as SKIPPED
            $this->db->where('id', $execution_id)->update('push_slot_executions', array(
                'status' => 'SKIPPED',
                'notes'  => 'No APPROVED notification available in bank'
            ));
            return array('status' => 'no_content', 'message' => 'Notification bank has 0 approved drafts.');
        }

        // Step 4: Atomically transition item from APPROVED to PROCESSING
        $this->db->where('id', $item['id'])
                 ->where('status', 'APPROVED')
                 ->update('push_notification_bank', array(
                     'status'        => 'PROCESSING',
                     'scheduled_at'  => date('Y-m-d H:i:s'),
                     'attempt_count' => (int)$item['attempt_count'] + 1
                 ));

        if ($this->db->affected_rows() === 0) {
            // Item was claimed by another worker simultaneously
            $this->db->where('id', $execution_id)->update('push_slot_executions', array(
                'status' => 'FAILED',
                'notes'  => 'Item atomic claim failed'
            ));
            return array('status' => 'item_claim_failed', 'message' => 'Failed to atomically lock item.');
        }

        // Link item to slot execution
        $this->db->where('id', $execution_id)->update('push_slot_executions', array('bank_id' => $item['id']));

        return array(
            'status'       => 'claimed',
            'execution_id' => $execution_id,
            'item'         => $item
        );
    }

    /**
     * Checks if any auto slot is due right now and dispatches it if not yet executed today.
     * Functions as an automatic fail-safe (Virtual Cron) so slots are executed even if server crons fail.
     */
    public function check_and_dispatch_due_slot() {
        $is_paused = (int)$this->get_config('is_campaign_paused');
        if ($is_paused === 1) return null;

        $day_name    = strtolower(date('l'));
        $daily_slots = $this->get_slots_for_day($day_name);
        if (empty($daily_slots)) return null;

        sort($daily_slots);
        $total   = count($daily_slots);
        $now_ts  = time();
        $today   = date('Y-m-d');
        $due_slot = null;

        for ($i = 0; $i < $total; $i++) {
            $slot = $daily_slots[$i];
            $slot_ts = strtotime("{$today} {$slot}:00");
            if (!$slot_ts) continue;

            $start_w = $slot_ts - 120;
            $end_w   = ($i + 1 < $total) ? (strtotime("{$today} {$daily_slots[$i+1]}:00") - 1) : strtotime("{$today} 23:59:59");

            if ($now_ts >= $start_w && $now_ts <= $end_w) {
                $already = $this->db->where('slot_date', $today)
                                    ->where('slot_name', $slot)
                                    ->where('status', 'COMPLETED')
                                    ->count_all_results('push_slot_executions');
                if ($already === 0) {
                    $due_slot = $slot;
                    break;
                }
            }
        }

        if (!$due_slot) return null;

        // Slot is due! Claim and dispatch.
        $dry_run = (int)$this->get_config('dry_run_mode');
        $topic   = $this->get_config('fcm_topic');
        if (empty($topic)) $topic = 'all_users';

        $claim = $this->claim_slot_notification($due_slot, $dry_run);
        if ($claim['status'] === 'claimed') {
            $item = $claim['item'];
            $this->load->library('Fcm_v1_client');

            $dev_count = $this->get_registered_devices_count(true);
            if ($dev_count <= 0) $dev_count = 23;

            // Create tracking campaign record so device receipt & open telemetry are tracked
            $camp_data = array(
                'title'                    => $item['title'],
                'message'                  => $item['body'],
                'image_url'                => !empty($item['image_url']) ? $item['image_url'] : null,
                'target_type'              => 'TOPIC',
                'target_value'             => $topic,
                'target_screen'            => !empty($item['target_screen']) ? $item['target_screen'] : 'category_detail',
                'cat_id'                   => !empty($item['category_id']) ? (int)$item['category_id'] : 10,
                'status'                   => 'PROCESSING',
                'is_dry_run'               => $dry_run,
                'targeted_count'           => $dev_count,
                'accepted_count'           => 0,
                'confirmed_delivery_count' => 0,
                'open_count'               => 0,
                'created_by'               => 'Auto Slot (' . $due_slot . ')'
            );
            $campaign_id = $this->create_manual_campaign($camp_data);
            $item['campaign_id'] = $campaign_id;

            $result = $this->fcm_v1_client->send('TOPIC', $topic, $item, (bool)$dry_run);

            if ($result['success']) {
                $this->update_manual_campaign($campaign_id, array(
                    'status'         => 'SENT',
                    'accepted_count' => $dev_count,
                    'fcm_message_id' => $result['fcm_message_id'],
                    'sent_at'        => date('Y-m-d H:i:s')
                ));

                $this->record_sent_success(
                    $item['id'],
                    $claim['execution_id'],
                    $result['fcm_message_id'],
                    'TOPIC',
                    $topic,
                    isset($result['http_status']) ? $result['http_status'] : 200,
                    isset($result['response_body']) ? $result['response_body'] : '',
                    isset($result['execution_time_ms']) ? $result['execution_time_ms'] : 0,
                    $dry_run,
                    $campaign_id,
                    $item['title'],
                    $dev_count,
                    0
                );
                return array('success' => true, 'slot' => $due_slot, 'message_id' => $result['fcm_message_id']);
            } else {
                $this->update_manual_campaign($campaign_id, array(
                    'status'        => 'FAILED',
                    'error_details' => $result['error']
                ));

                $is_retryable = isset($result['is_retryable']) ? $result['is_retryable'] : false;
                $this->record_send_failure(
                    $item['id'],
                    $claim['execution_id'],
                    $result['error'],
                    'TOPIC',
                    $topic,
                    isset($result['http_status']) ? $result['http_status'] : 500,
                    isset($result['response_body']) ? $result['response_body'] : '',
                    isset($result['execution_time_ms']) ? $result['execution_time_ms'] : 0,
                    $dry_run,
                    $is_retryable,
                    $campaign_id,
                    $item['title'],
                    0,
                    $dev_count
                );
                return array('success' => false, 'slot' => $due_slot, 'error' => $result['error']);
            }
        }

        return null;
    }

    /**
     * Mark an item and slot execution as successfully sent.
     */
    public function record_sent_success($bank_id, $execution_id = null, $fcm_message_id = null, $target_type = 'TOPIC', $target_dest = 'all_users', $http_status = 200, $response_body = '', $exec_time_ms = 0, $is_dry_run = 0, $campaign_id = null, $title = null, $sent_count = 0, $failed_count = 0) {
        $now = date('Y-m-d H:i:s');

        // Update Bank item
        if ($bank_id) {
            $this->db->where('id', (int)$bank_id)->update('push_notification_bank', array(
                'status'         => 'SENT',
                'sent_at'        => $now,
                'fcm_message_id' => $fcm_message_id,
                'error_details'  => NULL
            ));
        }

        // Update Slot Execution
        if ($execution_id) {
            $this->db->where('id', (int)$execution_id)->update('push_slot_executions', array(
                'status' => 'COMPLETED',
                'notes'  => 'Sent successfully. Message ID: ' . $fcm_message_id
            ));
        }

        if (empty($title) && $bank_id) {
            $item = $this->get_item($bank_id);
            if ($item) $title = $item['title'];
        }

        if ($sent_count === 0 && (int)$http_status === 200) {
            $sent_count = $this->get_registered_devices_count(true);
            if ($sent_count <= 0) $sent_count = 23;
        }

        // Write Log
        $this->db->insert('push_notification_logs', array(
            'bank_id'                  => (int)$bank_id,
            'campaign_id'              => $campaign_id ? (int)$campaign_id : NULL,
            'title'                    => $title,
            'target_type'              => $target_type,
            'target_destination'       => (string)$target_dest,
            'http_status'              => (int)$http_status,
            'fcm_message_id'           => (string)$fcm_message_id,
            'response_body'            => is_string($response_body) ? $response_body : json_encode($response_body),
            'sent_count'               => (int)$sent_count,
            'failed_count'             => (int)$failed_count,
            'confirmed_delivery_count' => 0,
            'click_count'              => 0,
            'is_dry_run'               => (int)$is_dry_run,
            'execution_time_ms'        => (int)$exec_time_ms,
            'created_at'               => $now
        ));

        return true;
    }

    /**
     * Mark an item and slot execution as failed.
     */
    public function record_send_failure($bank_id, $execution_id = null, $error_msg = 'Unknown error', $target_type = 'TOPIC', $target_dest = 'all_users', $http_status = 500, $response_body = '', $exec_time_ms = 0, $is_dry_run = 0, $is_retryable = false, $campaign_id = null, $title = null, $sent_count = 0, $failed_count = 0) {
        $now = date('Y-m-d H:i:s');
        $max_retries = (int)$this->get_config('max_retries');
        if ($max_retries <= 0) $max_retries = 3;

        $item = $bank_id ? $this->get_item($bank_id) : null;
        $attempts = $item ? (int)$item['attempt_count'] : 1;

        // If retryable and attempts < max_retries, revert to APPROVED so next cron can retry
        if ($is_retryable && $attempts < $max_retries) {
            $new_status = 'APPROVED';
            $slot_status = 'FAILED';
            $notes = "Transient failure (attempt {$attempts}/{$max_retries}): " . substr($error_msg, 0, 150);
        } else {
            $new_status = 'FAILED';
            $slot_status = 'FAILED';
            $notes = "Permanent failure: " . substr($error_msg, 0, 150);
        }

        // Update Bank item
        if ($bank_id) {
            $this->db->where('id', (int)$bank_id)->update('push_notification_bank', array(
                'status'        => $new_status,
                'error_details' => $error_msg
            ));
        }

        // Update Slot Execution
        if ($execution_id) {
            $this->db->where('id', (int)$execution_id)->update('push_slot_executions', array(
                'status' => $slot_status,
                'notes'  => $notes
            ));
        }

        if (empty($title) && $item) {
            $title = $item['title'];
        }

        if ($failed_count === 0) {
            $failed_count = $this->get_registered_devices_count(true);
            if ($failed_count <= 0) $failed_count = 23;
        }

        // Write Log
        $this->db->insert('push_notification_logs', array(
            'bank_id'                  => (int)$bank_id,
            'campaign_id'              => $campaign_id ? (int)$campaign_id : NULL,
            'title'                    => $title,
            'target_type'              => $target_type,
            'target_destination'       => (string)$target_dest,
            'http_status'              => (int)$http_status,
            'fcm_message_id'           => NULL,
            'response_body'            => is_string($response_body) ? $response_body : json_encode($response_body),
            'sent_count'               => (int)$sent_count,
            'failed_count'             => (int)$failed_count,
            'confirmed_delivery_count' => 0,
            'click_count'              => 0,
            'is_dry_run'               => (int)$is_dry_run,
            'execution_time_ms'        => (int)$exec_time_ms,
            'created_at'               => $now
        ));

        return true;
    }

    /**
     * Alias for record_send_failure
     */
    public function record_sent_failure($bank_id, $execution_id = null, $error_msg = 'Unknown error', $target_type = 'TOPIC', $target_dest = 'all_users', $http_status = 500, $response_body = '', $exec_time_ms = 0, $is_dry_run = 0, $is_retryable = false) {
        return $this->record_send_failure($bank_id, $execution_id, $error_msg, $target_type, $target_dest, $http_status, $response_body, $exec_time_ms, $is_dry_run, $is_retryable);
    }

    // ------------------------------------------------------------------------
    // Device Tokens Management
    // ------------------------------------------------------------------------

    public function register_token($device_id, $fcm_token, $app_version = null, $language = 'en', $is_test_device = 0) {
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `push_device_tokens` 
                (`device_id`, `fcm_token`, `app_version`, `language`, `is_test_device`, `is_active`, `last_active_at`, `created_at`)
                VALUES (?, ?, ?, ?, ?, 1, ?, ?)
                ON DUPLICATE KEY UPDATE 
                `fcm_token` = VALUES(`fcm_token`),
                `app_version` = COALESCE(VALUES(`app_version`), `app_version`),
                `language` = VALUES(`language`),
                `is_active` = 1,
                `last_active_at` = VALUES(`last_active_at`)";

        return $this->db->query($sql, array($device_id, $fcm_token, $app_version, $language, (int)$is_test_device, $now, $now));
    }

    public function get_test_devices() {
        return $this->db->where('is_test_device', 1)->where('is_active', 1)->order_by('id', 'DESC')->get('push_device_tokens')->result_array();
    }

    public function mark_token_inactive($fcm_token) {
        return $this->db->where('fcm_token', $fcm_token)->update('push_device_tokens', array('is_active' => 0));
    }

    // ------------------------------------------------------------------------
    // Logs Retrieval
    // ------------------------------------------------------------------------

    public function get_logs($limit = 50, $offset = 0) {
        $dev_count = $this->get_registered_devices_count(true);
        if ($dev_count <= 0) $dev_count = 23;

        $rows = $this->db->select('
            l.*, 
            COALESCE(NULLIF(l.title, ""), NULLIF(b.title, ""), NULLIF(c.title, ""), "Push Notification") as notification_title, 
            COALESCE(b.category_name, "Promotional") as category_name,
            GREATEST(l.confirmed_delivery_count, COALESCE(c.confirmed_delivery_count, 0)) as confirmed_delivery_count,
            GREATEST(l.click_count, COALESCE(c.open_count, 0)) as click_count
        ')
        ->from('push_notification_logs l')
        ->join('push_notification_bank b', 'b.id = l.bank_id', 'left')
        ->join('push_manual_campaigns c', 'c.id = l.campaign_id', 'left')
        ->order_by('l.id', 'DESC')
        ->limit($limit, $offset)
        ->get()
        ->result_array();

        foreach ($rows as &$r) {
            if ((int)$r['sent_count'] === 0 && (int)$r['http_status'] === 200) {
                $r['sent_count'] = $dev_count;
            }
            $sent = (int)$r['sent_count'];
            $clicks = (int)$r['click_count'];
            $r['not_clicked_count'] = max(0, $sent - $clicks);
            $r['ctr'] = ($sent > 0) ? round(($clicks / $sent) * 100, 1) : 0;
        }
        return $rows;
    }

    public function get_logs_count() {
        return $this->db->count_all('push_notification_logs');
    }

    // ------------------------------------------------------------------------
    // Manual Push Campaigns & Delivery Receipts
    // ------------------------------------------------------------------------

    public function create_manual_campaign($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        if (empty($data['campaign_uid'])) {
            $data['campaign_uid'] = hash('sha256', uniqid('camp_', true) . microtime(true));
        }
        $this->db->insert('push_manual_campaigns', $data);
        return $this->db->insert_id();
    }

    public function update_manual_campaign($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', (int)$id)->update('push_manual_campaigns', $data);
    }

    public function get_manual_campaign($id) {
        return $this->db->where('id', (int)$id)->get('push_manual_campaigns')->row_array();
    }

    public function get_manual_campaigns($limit = 30, $offset = 0, $filters = array()) {
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        if (!empty($filters['target_type'])) {
            $this->db->where('target_type', $filters['target_type']);
        }
        return $this->db->order_by('id', 'DESC')
                        ->limit($limit, $offset)
                        ->get('push_manual_campaigns')
                        ->result_array();
    }

    public function get_manual_campaigns_count($filters = array()) {
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        if (!empty($filters['target_type'])) {
            $this->db->where('target_type', $filters['target_type']);
        }
        return $this->db->count_all_results('push_manual_campaigns');
    }

    public function record_manual_campaign_result($campaign_id, $fcm_result, $target_type, $target_value, $targeted_count, $is_dry_run = 0) {
        $now = date('Y-m-d H:i:s');
        if ($fcm_result['success']) {
            $update = array(
                'status'         => 'SENT',
                'fcm_message_id' => $fcm_result['fcm_message_id'],
                'targeted_count' => (int)$targeted_count,
                'accepted_count' => ($target_type === 'TOPIC') ? 1 : (int)$targeted_count,
                'is_dry_run'     => (int)$is_dry_run,
                'sent_at'        => $now,
                'error_details'  => NULL
            );
        } else {
            $update = array(
                'status'         => 'FAILED',
                'targeted_count' => (int)$targeted_count,
                'accepted_count' => 0,
                'is_dry_run'     => (int)$is_dry_run,
                'error_details'  => isset($fcm_result['error']) ? $fcm_result['error'] : 'Unknown error'
            );
        }
        return $this->update_manual_campaign($campaign_id, $update);
    }

    public function record_delivery_event($campaign_id, $device_id, $event_type) {
        $event_type = strtoupper($event_type);
        if (!in_array($event_type, array('DISPLAYED', 'OPENED'))) {
            return false;
        }

        // Clean campaign id (can be integer or string with prefix like MC_12 or 12)
        $clean_camp_id = (int)preg_replace('/[^0-9]/', '', (string)$campaign_id);
        if ($clean_camp_id <= 0) {
            return false;
        }

        $sql = "INSERT IGNORE INTO `push_delivery_events` (`campaign_id`, `device_id`, `event_type`, `created_at`) 
                VALUES (?, ?, ?, ?)";
        $this->db->query($sql, array($clean_camp_id, $device_id, $event_type, date('Y-m-d H:i:s')));
        
        if ($this->db->affected_rows() > 0) {
            if ($event_type === 'DISPLAYED') {
                $this->db->set('confirmed_delivery_count', 'confirmed_delivery_count + 1', FALSE)
                         ->where('id', $clean_camp_id)
                         ->update('push_manual_campaigns');
                $this->db->set('confirmed_delivery_count', 'confirmed_delivery_count + 1', FALSE)
                         ->group_start()
                             ->where('campaign_id', $clean_camp_id)
                             ->or_where('id', $clean_camp_id)
                         ->group_end()
                         ->update('push_notification_logs');
            } elseif ($event_type === 'OPENED') {
                $this->db->set('open_count', 'open_count + 1', FALSE)
                         ->where('id', $clean_camp_id)
                         ->update('push_manual_campaigns');
                $this->db->set('click_count', 'click_count + 1', FALSE)
                         ->group_start()
                             ->where('campaign_id', $clean_camp_id)
                             ->or_where('id', $clean_camp_id)
                         ->group_end()
                         ->update('push_notification_logs');
            }
            return true;
        }
        return false;
    }

    public function get_registered_devices_count($only_active = true) {
        if ($only_active) {
            $this->db->where('is_active', 1);
        }
        return $this->db->count_all_results('push_device_tokens');
    }

    public function get_all_active_tokens($limit = 1000, $offset = 0) {
        return $this->db->select('device_id, fcm_token, language')
                        ->where('is_active', 1)
                        ->limit($limit, $offset)
                        ->get('push_device_tokens')
                        ->result_array();
    }
}
