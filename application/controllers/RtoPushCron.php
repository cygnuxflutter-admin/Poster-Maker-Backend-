<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * RTO Vehicle App Push Notification Daily Scheduler & CLI Worker
 * Run via Crontab: php /path/to/index.php RtoPushCron run
 * Or via HTTP Cron: https://yourdomain.com/index.php/RtoPushCron/run?token=rto_cron_2026
 */
class RtoPushCron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Rto_push_notification_model');
        $this->load->library('Rto_fcm_v1_client');

        if (isset($this->db)) {
            $this->db->simple_query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
        }
    }

    public function run() {
        $token = $this->input->get('token');
        if (!is_cli() && !$this->session->userdata('poster_sess_user_name') && $token !== 'rto_cron_2026') {
            show_error('Access Denied. RtoPushCron must be executed via CLI or with a valid token.', 403);
            return;
        }

        if (!is_cli() && !headers_sent()) {
            header('Content-Type: text/plain; charset=UTF-8');
        }

        $now_str = date('Y-m-d H:i:s');
        $this->log_output("[*] RtoPushCron run started at {$now_str} (Asia/Kolkata)");

        $lock_file = APPPATH . 'cache/rto_push_cron.lock';
        $fp = fopen($lock_file, 'c+');
        if (!$fp || !flock($fp, LOCK_EX | LOCK_NB)) {
            $this->log_output("[!] Another RtoPushCron worker is currently running. Exiting cleanly.");
            if ($fp) fclose($fp);
            return;
        }

        try {
            if ((int)$this->Rto_push_notification_model->get_config('is_campaign_paused') === 1) {
                $this->log_output("[-] RTO automated campaign is currently PAUSED via admin settings.");
                return;
            }

            $day_name    = strtolower(date('l'));
            $daily_slots = $this->Rto_push_notification_model->get_slots_for_day($day_name);
            $this->log_output("[*] Active schedule for {$day_name}: " . implode(', ', $daily_slots));

            $due_slot = $this->find_due_slot($daily_slots);
            if (!$due_slot) {
                $current_time = date('H:i');
                $this->log_output("[-] Current time ({$current_time}) is not within the window of any configured slot (" . implode(', ', $daily_slots) . ").");
                return;
            }

            $this->log_output("[+] Slot due for processing: {$due_slot}");
            $res = $this->Rto_push_notification_model->dispatch_slot($due_slot);
            if ($res['success']) {
                $this->log_output("[SUCCESS] Dispatched slot {$due_slot}: " . ($res['item']['title'] ?? ''));
            } else {
                $this->log_output("[INFO] Slot {$due_slot} result: " . ($res['message'] ?? ($res['error'] ?? 'N/A')));
            }

        } catch (Exception $e) {
            $this->log_output("[!] Unhandled Exception: " . $e->getMessage());
        } finally {
            if ($fp) {
                flock($fp, LOCK_UN);
                fclose($fp);
            }
        }
    }

    public function run_slot($slot_name = null) {
        $token = $this->input->get('token');
        if (!is_cli() && !$this->session->userdata('poster_sess_user_name') && $token !== 'rto_cron_2026') {
            show_error('Access Denied.', 403);
            return;
        }

        if (empty($slot_name)) {
            $slot_name = '09:00';
        }

        $res = $this->Rto_push_notification_model->dispatch_slot($slot_name);
        echo json_encode($res, JSON_PRETTY_PRINT);
    }

    private function find_due_slot($daily_slots) {
        $now_time   = date('H:i');
        $today_date = date('Y-m-d');

        foreach ($daily_slots as $slot) {
            // Check if slot has already been executed today
            $already = $this->db->where('slot_date', $today_date)
                                ->where('slot_name', $slot)
                                ->get('rto_push_slot_executions')
                                ->row_array();

            if ($already) {
                continue; // Already processed
            }

            // Window: From slot time up to 120 minutes after
            $slot_ts = strtotime("{$today_date} {$slot}:00");
            $now_ts  = strtotime("{$today_date} {$now_time}:00");

            if ($now_ts >= $slot_ts && ($now_ts - $slot_ts) <= 7200) {
                return $slot;
            }
        }

        return null;
    }

    private function log_output($msg) {
        echo $msg . PHP_EOL;
    }
}
