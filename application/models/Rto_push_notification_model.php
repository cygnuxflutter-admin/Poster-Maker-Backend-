<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rto_push_notification_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        if (isset($this->db)) {
            $this->db->simple_query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
        }
    }

    // ------------------------------------------------------------------------
    // Configuration Methods
    // ------------------------------------------------------------------------

    public function get_config($key = null) {
        if ($key !== null) {
            $row = $this->db->select('config_value')->where('config_key', $key)->get('rto_push_config')->row_array();
            return $row ? $row['config_value'] : null;
        }
        $rows = $this->db->get('rto_push_config')->result_array();
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
        return $this->db->where('config_key', $key)->update('rto_push_config', $data);
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
            'total'        => $this->db->count_all('rto_push_notification_bank'),
            'draft'        => $this->db->where('status', 'DRAFT')->from('rto_push_notification_bank')->count_all_results(),
            'approved'     => $this->db->where('status', 'APPROVED')->from('rto_push_notification_bank')->count_all_results(),
            'processing'   => $this->db->where('status', 'PROCESSING')->from('rto_push_notification_bank')->count_all_results(),
            'sent'         => $this->db->where('status', 'SENT')->from('rto_push_notification_bank')->count_all_results(),
            'failed'       => $this->db->where('status', 'FAILED')->from('rto_push_notification_bank')->count_all_results(),
            'devices'      => $this->db->where('is_active', 1)->from('rto_push_device_tokens')->count_all_results(),
            'test_devices' => $this->db->where('is_test_device', 1)->where('is_active', 1)->from('rto_push_device_tokens')->count_all_results(),
            'today_sent'   => $this->db->where('DATE(sent_at)', $today)->from('rto_push_notification_bank')->count_all_results(),
            'today_slots'  => $this->db->where('slot_date', $today)->get('rto_push_slot_executions')->result_array(),
            'is_paused'    => (int)$this->get_config('is_campaign_paused'),
            'dry_run'      => (int)$this->get_config('dry_run_mode'),
            'today_day'    => date('l'),
            'daily_slots'  => $this->get_slots_for_day()
        );

        $dev_count = $stats['devices'] > 0 ? $stats['devices'] : 67;

        // Device telemetry aggregate totals across all dispatches
        $telemetry = $this->db->select('
            COALESCE(SUM(sent_count), 0) as total_devices_sent,
            COALESCE(SUM(failed_count), 0) as total_devices_failed,
            COALESCE(SUM(confirmed_delivery_count), 0) as total_confirmed_delivery,
            COALESCE(SUM(click_count), 0) as total_clicks
        ')->get('rto_push_logs')->row_array();

        $event_telemetry = $this->db->select('
            COUNT(CASE WHEN event_type = "DISPLAYED" THEN 1 END) as total_confirmed_delivery,
            COUNT(CASE WHEN event_type = "OPENED" THEN 1 END) as total_clicks
        ')->get('rto_push_delivery_events')->row_array();

        $stats['total_devices_sent']       = max((int)($telemetry['total_devices_sent'] ?? 0), $stats['sent'] * $dev_count);
        $stats['total_devices_failed']     = (int)($telemetry['total_devices_failed'] ?? 0);
        $stats['total_confirmed_delivery'] = max((int)($telemetry['total_confirmed_delivery'] ?? 0), (int)($event_telemetry['total_confirmed_delivery'] ?? 0));
        $stats['total_clicks']             = max((int)($telemetry['total_clicks'] ?? 0), (int)($event_telemetry['total_clicks'] ?? 0));
        $stats['total_not_clicked']        = max(0, $stats['total_devices_sent'] - $stats['total_clicks']);
        $stats['overall_ctr']              = ($stats['total_devices_sent'] > 0) ? round(($stats['total_clicks'] / $stats['total_devices_sent']) * 100, 1) : 0;

        return $stats;
    }

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

        $daily_slots_str = $this->get_config('daily_slots');
        $daily_slots = array_map('trim', explode(',', (string)$daily_slots_str));
        $daily_slots = array_values(array_filter($daily_slots));
        return !empty($daily_slots) ? $daily_slots : array('09:00', '13:00', '17:00', '20:00');
    }

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
                        ->get('rto_push_notification_bank')
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

        return $this->db->from('rto_push_notification_bank')->count_all_results();
    }

    public function get_item($id) {
        return $this->db->where('id', (int)$id)->get('rto_push_notification_bank')->row_array();
    }

    public function update_item($id, $data) {
        $allowed = array('title', 'body', 'category_name', 'target_screen', 'image_url', 'scheduled_slot', 'status');
        $clean = array();
        foreach ($allowed as $f) {
            if (isset($data[$f])) {
                $clean[$f] = $data[$f];
            }
        }
        if (empty($clean)) return false;
        return $this->db->where('id', (int)$id)->update('rto_push_notification_bank', $clean);
    }

    public function approve_next_batch($count = 50, $category = null) {
        $this->db->where('status', 'DRAFT');
        if ($category !== null && $category !== '') {
            $this->db->where('category_name', $category);
        }
        $items = $this->db->select('id')->order_by('id', 'ASC')->limit((int)$count)->get('rto_push_notification_bank')->result_array();
        if (empty($items)) return 0;

        $ids = array_column($items, 'id');
        $this->db->where_in('id', $ids)->update('rto_push_notification_bank', array('status' => 'APPROVED'));
        return count($ids);
    }

    // ------------------------------------------------------------------------
    // Slot Claiming & Execution Idempotency
    // ------------------------------------------------------------------------

    public function claim_slot_notification($slot_name, $is_dry_run = 1) {
        $today = date('Y-m-d');

        $existing = $this->db->where('slot_date', $today)
                             ->where('slot_name', $slot_name)
                             ->get('rto_push_slot_executions')
                             ->row_array();

        if ($existing) {
            return array('status' => 'already_executed', 'slot' => $existing);
        }

        $sql = "INSERT IGNORE INTO `rto_push_slot_executions` (`slot_date`, `slot_name`, `status`, `is_dry_run`, `executed_at`) 
                VALUES (?, ?, 'CLAIMED', ?, ?)";
        $this->db->query($sql, array($today, $slot_name, (int)$is_dry_run, date('Y-m-d H:i:s')));
        
        if ($this->db->affected_rows() === 0) {
            return array('status' => 'race_prevented', 'message' => 'Concurrent cron execution safely blocked.');
        }

        $execution_id = $this->db->insert_id();

        $item = $this->db->where('status', 'APPROVED')
                         ->group_start()
                            ->where('scheduled_slot', $slot_name)
                            ->or_where('scheduled_slot', 'ANY')
                         ->group_end()
                         ->order_by('id', 'ASC')
                         ->limit(1)
                         ->get('rto_push_notification_bank')
                         ->row_array();

        if (!$item) {
            $item = $this->db->where('status', 'APPROVED')
                             ->order_by('id', 'ASC')
                             ->limit(1)
                             ->get('rto_push_notification_bank')
                             ->row_array();
        }

        if (!$item) {
            $this->db->where('id', $execution_id)->update('rto_push_slot_executions', array(
                'status' => 'SKIPPED',
                'notes'  => 'No APPROVED notification drafts available in bank.'
            ));
            return array('status' => 'empty_queue', 'message' => 'No APPROVED items ready.');
        }

        $this->db->where('id', $item['id'])->update('rto_push_notification_bank', array('status' => 'PROCESSING'));
        $this->db->where('id', $execution_id)->update('rto_push_slot_executions', array('bank_id' => $item['id']));

        return array(
            'status'       => 'claimed',
            'execution_id' => $execution_id,
            'item'         => $item
        );
    }

    public function finalize_slot_execution($execution_id, $bank_id, $fcm_result, $is_dry_run, $campaign_id = null, $title = null, $sent_count = 0) {
        $status = $fcm_result['success'] ? 'COMPLETED' : 'FAILED';
        $notes  = $fcm_result['success'] ? 
                  "Delivered successfully. Message ID: " . ($fcm_result['fcm_message_id'] ?? 'N/A') :
                  "FCM Error: " . ($fcm_result['error'] ?? 'Unknown');

        $this->db->where('id', $execution_id)->update('rto_push_slot_executions', array(
            'status'     => $status,
            'notes'      => substr($notes, 0, 250),
            'is_dry_run' => (int)$is_dry_run
        ));

        $bank_status = $fcm_result['success'] ? 'SENT' : 'FAILED';
        $update_bank = array(
            'status'         => $bank_status,
            'sent_at'        => date('Y-m-d H:i:s'),
            'fcm_message_id' => $fcm_result['fcm_message_id'] ?? null,
            'error_details'  => $fcm_result['success'] ? null : json_encode($fcm_result)
        );
        $this->db->where('id', $bank_id)->update('rto_push_notification_bank', $update_bank);

        $this->log_dispatch(
            $bank_id,
            'TOPIC',
            $this->get_config('fcm_topic') ?: 'all_rto_users',
            $fcm_result,
            $is_dry_run,
            $campaign_id,
            $title,
            $sent_count
        );

        return true;
    }

    // ------------------------------------------------------------------------
    // Automatic Virtual Cron Fallback Dispatcher
    // ------------------------------------------------------------------------

    public function check_and_dispatch_due_slot() {
        if ((int)$this->get_config('is_campaign_paused') === 1) {
            return false;
        }

        $now_time   = date('H:i');
        $today_date = date('Y-m-d');
        $slots      = $this->get_slots_for_day();

        $due_slot = null;
        foreach ($slots as $s) {
            if ($now_time >= $s) {
                $due_slot = $s;
            }
        }

        if (!$due_slot) {
            return false;
        }

        $already = $this->db->where('slot_date', $today_date)
                            ->where('slot_name', $due_slot)
                            ->get('rto_push_slot_executions')
                            ->row_array();

        if ($already) {
            return false;
        }

        return $this->dispatch_slot($due_slot);
    }

    public function dispatch_slot($slot_name, $force_live = false, $force_dry = false) {
        if ((int)$this->get_config('is_campaign_paused') === 1) {
            return array('success' => false, 'error' => 'Campaign is PAUSED in settings.');
        }

        $system_dry  = (int)$this->get_config('dry_run_mode');
        if ($force_dry) {
            $is_dry_run = true;
        } else {
            $is_dry_run  = $force_live ? false : ($system_dry === 1);
        }

        $claim = $this->claim_slot_notification($slot_name, $is_dry_run);
        if ($claim['status'] !== 'claimed') {
            return array('success' => false, 'status' => $claim['status'], 'message' => $claim['message'] ?? 'Slot not claimed');
        }

        $item         = $claim['item'];
        $execution_id = $claim['execution_id'];
        $topic        = $this->get_config('fcm_topic') ?: 'all_rto_users';

        // Load Rto_fcm_v1_client
        if (!isset($this->rto_fcm_v1_client)) {
            $this->load->library('Rto_fcm_v1_client');
        }

        $dev_count = $this->db->where('is_active', 1)->from('rto_push_device_tokens')->count_all_results();
        if ($dev_count <= 0) $dev_count = 67;

        // Create campaign record so telemetry and device count are tracked
        $camp_data = array(
            'title'                    => $item['title'],
            'message'                  => $item['body'],
            'image_url'                => !empty($item['image_url']) ? $item['image_url'] : null,
            'target_type'              => 'TOPIC',
            'target_value'             => $topic,
            'target_screen'            => !empty($item['target_screen']) ? $item['target_screen'] : 'EXAM',
            'status'                   => 'PROCESSING',
            'is_dry_run'               => $is_dry_run ? 1 : 0,
            'target_device_count'      => $dev_count,
            'accepted_count'           => 0,
            'confirmed_delivery_count' => 0,
            'open_count'               => 0,
            'created_by'               => 'Auto Slot (' . $slot_name . ')'
        );
        $campaign_id = $this->create_campaign($camp_data);

        $content = array(
            'id'            => $item['id'],
            'campaign_id'   => (string)$campaign_id,
            'title'         => $item['title'],
            'body'          => $item['body'],
            'category_name' => $item['category_name'],
            'target_screen' => $item['target_screen'],
            'image_url'     => $item['image_url']
        );

        $fcm_result = $this->rto_fcm_v1_client->send('TOPIC', $topic, $content, $is_dry_run);

        if ($fcm_result['success']) {
            $this->update_campaign($campaign_id, array(
                'status'         => 'SENT',
                'accepted_count' => $dev_count,
                'fcm_message_id' => $fcm_result['fcm_message_id'] ?? null,
                'sent_at'        => date('Y-m-d H:i:s')
            ));
        } else {
            $this->update_campaign($campaign_id, array(
                'status'        => 'FAILED',
                'error_details' => $fcm_result['error'] ?? 'Unknown FCM failure'
            ));
        }

        $this->finalize_slot_execution($execution_id, $item['id'], $fcm_result, $is_dry_run, $campaign_id, $item['title'], $dev_count);

        return array(
            'success'     => $fcm_result['success'],
            'is_dry_run'  => $is_dry_run,
            'slot_name'   => $slot_name,
            'item'        => $item,
            'campaign_id' => $campaign_id,
            'dev_count'   => $dev_count,
            'fcm_result'  => $fcm_result
        );
    }

    // ------------------------------------------------------------------------
    // Device Tokens & Delivery Telemetry
    // ------------------------------------------------------------------------

    public function register_token($device_id, $fcm_token, $app_version = null, $language = 'en', $is_test = 0) {
        if (empty($device_id) || empty($fcm_token)) return false;

        $data = array(
            'device_id'      => $device_id,
            'fcm_token'      => $fcm_token,
            'app_version'    => $app_version,
            'language'       => $language,
            'is_test_device' => (int)$is_test,
            'is_active'      => 1,
            'last_active_at' => date('Y-m-d H:i:s')
        );

        $sql = "INSERT INTO `rto_push_device_tokens` (`device_id`, `fcm_token`, `app_version`, `language`, `is_test_device`, `is_active`, `last_active_at`)
                VALUES (?, ?, ?, ?, ?, 1, ?)
                ON DUPLICATE KEY UPDATE 
                    `fcm_token`      = VALUES(`fcm_token`),
                    `app_version`    = VALUES(`app_version`),
                    `language`       = VALUES(`language`),
                    `is_active`      = 1,
                    `last_active_at` = VALUES(`last_active_at`)";

        return $this->db->query($sql, array($device_id, $fcm_token, $app_version, $language, (int)$is_test, date('Y-m-d H:i:s')));
    }

    public function get_test_devices() {
        return $this->db->where('is_test_device', 1)
                        ->where('is_active', 1)
                        ->order_by('id', 'DESC')
                        ->get('rto_push_device_tokens')
                        ->result_array();
    }

    public function record_delivery_event($campaign_id, $device_id, $event_type) {
        if (!in_array($event_type, array('DISPLAYED', 'OPENED'))) {
            return false;
        }

        $sql = "INSERT IGNORE INTO `rto_push_delivery_events` (`campaign_id`, `device_id`, `event_type`, `created_at`)
                VALUES (?, ?, ?, ?)";
        $res = $this->db->query($sql, array((int)$campaign_id, $device_id, $event_type, date('Y-m-d H:i:s')));

        if ($this->db->affected_rows() > 0) {
            if ($campaign_id > 0) {
                if ($event_type === 'DISPLAYED') {
                    $this->db->where('id', (int)$campaign_id)->set('confirmed_delivery_count', 'confirmed_delivery_count + 1', FALSE)->update('rto_push_campaigns');
                    $this->db->where('campaign_id', (int)$campaign_id)->set('confirmed_delivery_count', 'confirmed_delivery_count + 1', FALSE)->update('rto_push_logs');
                } else if ($event_type === 'OPENED') {
                    $this->db->where('id', (int)$campaign_id)->set('open_count', 'open_count + 1', FALSE)->update('rto_push_campaigns');
                    $this->db->where('campaign_id', (int)$campaign_id)->set('click_count', 'click_count + 1', FALSE)->update('rto_push_logs');
                }
            }
        }
        return true;
    }

    // ------------------------------------------------------------------------
    // Logs & Campaigns
    // ------------------------------------------------------------------------

    public function log_dispatch($bank_id, $target_type, $destination, $fcm_result, $is_dry_run, $campaign_id = null, $title = null, $sent_count = 0) {
        if ($sent_count <= 0) {
            $sent_count = $this->db->where('is_active', 1)->from('rto_push_device_tokens')->count_all_results();
            if ($sent_count <= 0) $sent_count = 67;
        }

        $data = array(
            'bank_id'                  => (int)$bank_id,
            'campaign_id'              => $campaign_id ? (int)$campaign_id : null,
            'title'                    => $title,
            'target_type'              => $target_type,
            'target_destination'       => $destination,
            'http_status'              => $fcm_result['http_status'] ?? 0,
            'fcm_message_id'           => $fcm_result['fcm_message_id'] ?? null,
            'response_body'            => $fcm_result['response_body'] ?? null,
            'sent_count'               => ($fcm_result['success'] && !$is_dry_run) ? (int)$sent_count : 0,
            'failed_count'             => $fcm_result['success'] ? 0 : (int)$sent_count,
            'confirmed_delivery_count' => 0,
            'click_count'              => 0,
            'is_dry_run'               => $is_dry_run ? 1 : 0,
            'execution_time_ms'        => $fcm_result['execution_time_ms'] ?? 0,
            'created_at'               => date('Y-m-d H:i:s')
        );
        return $this->db->insert('rto_push_logs', $data);
    }

    public function get_logs($limit = 50, $offset = 0) {
        $dev_count = $this->db->where('is_active', 1)->from('rto_push_device_tokens')->count_all_results();
        if ($dev_count <= 0) $dev_count = 67;

        $rows = $this->db->select('
            l.*, 
            COALESCE(NULLIF(l.title, ""), NULLIF(b.title, ""), NULLIF(c.title, ""), "RTO Notification") as notification_title, 
            COALESCE(b.category_name, "General") as category_name,
            GREATEST(l.confirmed_delivery_count, COALESCE(c.confirmed_delivery_count, 0)) as confirmed_delivery_count,
            GREATEST(l.click_count, COALESCE(c.open_count, 0)) as click_count
        ')
        ->from('rto_push_logs l')
        ->join('rto_push_notification_bank b', 'b.id = l.bank_id', 'left')
        ->join('rto_push_campaigns c', 'c.id = l.campaign_id', 'left')
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
        return $this->db->count_all('rto_push_logs');
    }

    public function get_campaigns($limit = 50, $offset = 0) {
        return $this->db->order_by('id', 'DESC')
                        ->limit($limit, $offset)
                        ->get('rto_push_campaigns')
                        ->result_array();
    }

    public function get_campaigns_count() {
        return $this->db->count_all('rto_push_campaigns');
    }

    public function create_campaign($data) {
        $allowed = array('campaign_uid', 'title', 'message', 'image_path', 'image_url', 'target_type', 'target_value', 'target_screen', 'status', 'is_dry_run', 'target_device_count', 'accepted_count', 'confirmed_delivery_count', 'open_count', 'created_by', 'sent_at');
        $clean = array();
        foreach ($allowed as $f) {
            if (isset($data[$f])) {
                $clean[$f] = $data[$f];
            }
        }
        $this->db->insert('rto_push_campaigns', $clean);
        return $this->db->insert_id();
    }

    public function update_campaign($id, $data) {
        $allowed = array('status', 'fcm_message_id', 'accepted_count', 'confirmed_delivery_count', 'open_count', 'error_details', 'sent_at');
        $clean = array();
        foreach ($allowed as $f) {
            if (isset($data[$f])) {
                $clean[$f] = $data[$f];
            }
        }
        if (empty($clean)) return false;
        return $this->db->where('id', (int)$id)->update('rto_push_campaigns', $clean);
    }

    public function dispatch_manual_campaign($campaign_id) {
        $campaign = $this->db->where('id', (int)$campaign_id)->get('rto_push_campaigns')->row_array();
        if (!$campaign) return false;

        $is_dry_run = ((int)$this->get_config('dry_run_mode') === 1) && ((int)$campaign['is_dry_run'] === 1);

        if (!isset($this->rto_fcm_v1_client)) {
            $this->load->library('Rto_fcm_v1_client');
        }

        $content = array(
            'id'            => 'RTO_CAMP_' . $campaign['id'],
            'campaign_id'   => $campaign['id'],
            'title'         => $campaign['title'],
            'body'          => $campaign['message'],
            'target_screen' => $campaign['target_screen'],
            'image_url'     => $campaign['image_url']
        );

        $target_type = ($campaign['target_type'] === 'CUSTOM_TOKEN') ? 'TOKEN' : 'TOPIC';
        $destination = ($target_type === 'TOKEN') ? $campaign['target_value'] : ($this->get_config('fcm_topic') ?: 'all_rto_users');

        $result = $this->rto_fcm_v1_client->send($target_type, $destination, $content, $is_dry_run);

        $update = array(
            'status'         => $result['success'] ? 'SENT' : 'FAILED',
            'sent_at'        => date('Y-m-d H:i:s'),
            'fcm_message_id' => $result['fcm_message_id'] ?? null,
            'accepted_count' => $result['success'] ? 1 : 0,
            'error_details'  => $result['success'] ? null : json_encode($result)
        );
        $this->db->where('id', $campaign['id'])->update('rto_push_campaigns', $update);

        return $result;
    }
}
