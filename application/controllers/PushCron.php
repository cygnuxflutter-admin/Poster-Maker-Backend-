<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Push Notification Daily Scheduler & CLI Worker
 * Run via Crontab: php /path/to/index.php PushCron run
 */
class PushCron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Strictly set timezone
        date_default_timezone_set('Asia/Kolkata');
        
        $this->load->model('Push_notification_model');
        $this->load->library('Fcm_v1_client');

        // Force full 4-byte UTF-8 emoji support across cron runs
        if (isset($this->db)) {
            $this->db->simple_query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
        }
    }

    /**
     * Main cron entry point.
     * Checks if current time is due for any of the 4 daily slots in Asia/Kolkata.
     */
    public function run() {
        // Enforce CLI access, admin session, or secure token for web crons
        $token = $this->input->get('token');
        if (!is_cli() && !$this->session->userdata('poster_sess_user_name') && $token !== 'poster_cron_2026') {
            show_error('Access Denied. PushCron must be executed via CLI or with a valid token.', 403);
            return;
        }

        if (!is_cli() && !headers_sent()) {
            header('Content-Type: text/plain; charset=UTF-8');
        }

        $now_str = date('Y-m-d H:i:s');
        $this->log_output("[*] PushCron run started at {$now_str} (Asia/Kolkata)");

        // 1. Process concurrency mutex lock via flock
        $lock_file = APPPATH . 'cache/push_cron.lock';
        $fp = fopen($lock_file, 'c+');
        if (!$fp || !flock($fp, LOCK_EX | LOCK_NB)) {
            $this->log_output("[!] Another PushCron worker is currently running. Exiting cleanly.");
            if ($fp) fclose($fp);
            return;
        }

        try {
            // 2. Check Emergency Pause
            $is_paused = (int)$this->Push_notification_model->get_config('is_campaign_paused');
            if ($is_paused === 1) {
                $this->log_output("[-] Automated campaign is currently PAUSED via admin settings. No action taken.");
                return;
            }

            // 3. Evaluate due slot based on day-of-week (Sunday to Saturday)
            $day_name    = strtolower(date('l'));
            $daily_slots = $this->Push_notification_model->get_slots_for_day($day_name);
            $this->log_output("[*] Active schedule for {$day_name}: " . implode(', ', $daily_slots));

            $due_slot = $this->find_due_slot($daily_slots);

            if (!$due_slot) {
                $current_time = date('H:i');
                $this->log_output("[-] Current time ({$current_time}) is not within the execution window of any configured slot (" . implode(', ', $daily_slots) . ").");
                return;
            }

            $this->log_output("[+] Slot due for processing: {$due_slot}");

            // 4. Execute the slot
            $this->execute_slot($due_slot);

        } catch (Exception $e) {
            $this->log_output("[!] Unhandled Exception in PushCron: " . $e->getMessage());
        } finally {
            if (is_resource($fp)) {
                flock($fp, LOCK_UN);
                fclose($fp);
            }
        }

        $this->log_output("[*] PushCron run finished.");
    }

    /**
     * Manually triggers a specific slot (useful for testing or admin dispatch).
     * Usage: php index.php PushCron trigger_slot 09:00
     */
    public function trigger_slot($slot_name = null) {
        $token = $this->input->get('token');
        if (!is_cli() && !$this->session->userdata('poster_sess_user_name') && $token !== 'poster_cron_2026') {
            show_error('Access Denied. PushCron must be executed via CLI or with a valid token.', 403);
            return;
        }

        if (empty($slot_name)) {
            $slot_name = '09:00';
        }

        $this->log_output("[*] Manual trigger requested for slot: {$slot_name}");
        $this->execute_slot($slot_name);
    }

    /**
     * Executes the notification dispatch for a given slot.
     */
    protected function execute_slot($slot_name) {
        $dry_run = (int)$this->Push_notification_model->get_config('dry_run_mode');
        $topic   = $this->Push_notification_model->get_config('fcm_topic');
        if (empty($topic)) $topic = 'all_users';

        $this->log_output("    Mode: " . ($dry_run ? "DRY-RUN (Simulated)" : "LIVE BROADCAST"));
        $this->log_output("    Target Topic: {$topic}");

        // Claim slot and next approved item
        $claim = $this->Push_notification_model->claim_slot_notification($slot_name, $dry_run);

        if ($claim['status'] === 'already_executed') {
            $this->log_output("[-] Slot {$slot_name} has ALREADY been executed today. Slot-level idempotency protected.");
            return;
        }

        if ($claim['status'] !== 'claimed') {
            $this->log_output("[!] Could not claim slot: " . (isset($claim['message']) ? $claim['message'] : $claim['status']));
            return;
        }

        $execution_id = $claim['execution_id'];
        $item         = $claim['item'];

        $dev_count = $this->Push_notification_model->get_registered_devices_count(true);
        if ($dev_count <= 0) $dev_count = 23;

        // Create tracking campaign record so device delivery receipts & open clicks are tracked
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
            'created_by'               => 'PushCron (' . $slot_name . ')'
        );
        $campaign_id = $this->Push_notification_model->create_manual_campaign($camp_data);
        $item['campaign_id'] = $campaign_id;

        $this->log_output("[+] Claimed Draft #{$item['id']}: '{$item['title']}' [Category: {$item['category_name']}]");

        // Send via FCM HTTP v1
        $result = $this->fcm_v1_client->send('TOPIC', $topic, $item, (bool)$dry_run);

        if ($result['success']) {
            $msg_id = $result['fcm_message_id'];
            $this->Push_notification_model->update_manual_campaign($campaign_id, array(
                'status'         => 'SENT',
                'accepted_count' => $dev_count,
                'fcm_message_id' => $msg_id,
                'sent_at'        => date('Y-m-d H:i:s')
            ));

            $this->Push_notification_model->record_sent_success(
                $item['id'],
                $execution_id,
                $msg_id,
                'TOPIC',
                $topic,
                $result['http_status'],
                $result['response_body'],
                $result['execution_time_ms'],
                $dry_run,
                $campaign_id,
                $item['title'],
                $dev_count,
                0
            );
            $this->log_output("[✓] SUCCESS! Dispatched to topic '{$topic}'. Message ID: {$msg_id} (Time: {$result['execution_time_ms']}ms)");
        } else {
            $error_msg = isset($result['error']) ? $result['error'] : 'Unknown FCM failure';
            $is_retryable = isset($result['is_retryable']) ? $result['is_retryable'] : false;

            $this->Push_notification_model->update_manual_campaign($campaign_id, array(
                'status'        => 'FAILED',
                'error_details' => $error_msg
            ));

            $this->Push_notification_model->record_send_failure(
                $item['id'],
                $execution_id,
                $error_msg,
                'TOPIC',
                $topic,
                $result['http_status'],
                $result['response_body'],
                $result['execution_time_ms'],
                $dry_run,
                $is_retryable,
                $campaign_id,
                $item['title'],
                0,
                $dev_count
            );
            $this->log_output("[X] FAILED! " . $error_msg . " (Retryable: " . ($is_retryable ? 'YES' : 'NO') . ")");
        }
    }

    /**
     * Determines which configured slot (if any) is currently due.
     * Uses intelligent time-window blocks so slots are never skipped if cron runs with slight delays.
     */
    protected function find_due_slot($daily_slots) {
        $now_ts = time();
        $today = date('Y-m-d');

        // Sort slots chronologically
        sort($daily_slots);
        $total_slots = count($daily_slots);

        for ($i = 0; $i < $total_slots; $i++) {
            $slot = $daily_slots[$i];
            $slot_ts = strtotime("{$today} {$slot}:00");
            if (!$slot_ts) continue;

            // Start window: 2 minutes before the slot time
            $start_window = $slot_ts - 120;

            // End window: Until the next slot begins, or end of day (23:59:59) for the last slot
            if ($i + 1 < $total_slots) {
                $next_slot_ts = strtotime("{$today} {$daily_slots[$i + 1]}:00");
                $end_window = $next_slot_ts - 1;
            } else {
                $end_window = strtotime("{$today} 23:59:59");
            }

            // If current time is within this slot's active period
            if ($now_ts >= $start_window && $now_ts <= $end_window) {
                // Check if this slot was already completed today
                $already = $this->db->where('slot_date', $today)
                                    ->where('slot_name', $slot)
                                    ->where('status', 'COMPLETED')
                                    ->count_all_results('push_slot_executions');
                if ($already === 0) {
                    return $slot;
                }
            }
        }

        return null;
    }

    protected function log_output($msg) {
        $formatted = "[" . date('Y-m-d H:i:s') . "] " . $msg . PHP_EOL;
        echo $formatted;
        if (function_exists('ob_flush')) { @ob_flush(); }
        flush();
        log_message('info', $msg);
    }
}
