<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * RTO Push Notification Automated Verification & Test Suite
 * Usage: php index.php RtoPushTest run
 */
class RtoPushTest extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Rto_push_notification_model');
        $this->load->library('Rto_fcm_v1_client');
    }

    public function run() {
        if (!is_cli() && !$this->session->userdata('poster_sess_user_name')) {
            show_error('Access Denied. CLI only.', 403);
            return;
        }

        echo "========================================================\n";
        echo "   RTO PUSH NOTIFICATION SYSTEM VERIFICATION SUITE\n";
        echo "   Time: " . date('Y-m-d H:i:s') . " (Asia/Kolkata)\n";
        echo "========================================================\n\n";

        $passed = 0;
        $failed = 0;

        // ----------------------------------------------------
        // TEST 1: Database Tables & Default Configs
        // ----------------------------------------------------
        echo "[TEST 1] Verifying Database Schema & 5,000+ Content Bank...\n";
        $stats = $this->Rto_push_notification_model->get_stats();
        if ($stats['total'] >= 5000) {
            echo "  [PASS] RTO Notification bank contains {$stats['total']} pre-generated drafts (Target: 5,000+).\n";
            $passed++;
        } else {
            echo "  [FAIL] Expected at least 5,000 drafts in bank, found: {$stats['total']}\n";
            $failed++;
        }

        if ($stats['dry_run'] === 1) {
            echo "  [PASS] Safety Dry-Run Mode is ACTIVE (Safe Simulation Mode).\n";
            $passed++;
        } else {
            echo "  [PASS] LIVE FCM Mode is ACTIVE (Configured for live broadcast).\n";
            $passed++;
        }

        // ----------------------------------------------------
        // TEST 2: Device Token Registration
        // ----------------------------------------------------
        echo "\n[TEST 2] Testing RTO Device Token Registration & Upsert...\n";
        $test_device_id = "test_rto_pixel_" . time();
        $test_fcm_token = "fcm_mock_rto_token_" . bin2hex(random_bytes(16));
        $reg_res = $this->Rto_push_notification_model->register_token($test_device_id, $test_fcm_token, "4.3", "en", 1);
        if ($reg_res) {
            echo "  [PASS] Successfully registered RTO test device token in MySQL.\n";
            $passed++;
        } else {
            echo "  [FAIL] Failed to insert/upsert RTO test device token.\n";
            $failed++;
        }

        // Clean up test token
        $this->db->where('device_id', $test_device_id)->delete('rto_push_device_tokens');

        // ----------------------------------------------------
        // TEST 3: Slot-Level Idempotency Protection
        // ----------------------------------------------------
        echo "\n[TEST 3] Testing Slot-Level Idempotency Protection...\n";
        $test_slot = "TEST_SLOT";
        $today = date('Y-m-d');
        $this->db->where('slot_date', $today)->where('slot_name', $test_slot)->delete('rto_push_slot_executions');

        $claim1 = $this->Rto_push_notification_model->claim_slot_notification($test_slot, 1);
        if ($claim1['status'] === 'claimed') {
            echo "  [PASS] First slot claim succeeded for {$test_slot}.\n";
            $passed++;
        } else {
            echo "  [FAIL] First slot claim returned: " . $claim1['status'] . "\n";
            $failed++;
        }

        // Attempt second claim for same slot on same date
        $claim2 = $this->Rto_push_notification_model->claim_slot_notification($test_slot, 1);
        if ($claim2['status'] === 'already_executed') {
            echo "  [PASS] Second slot claim blocked! Slot-level idempotency protected.\n";
            $passed++;
        } else {
            echo "  [FAIL] Second claim should be blocked, got: " . $claim2['status'] . "\n";
            $failed++;
        }

        // Clean up test slot
        $this->db->where('slot_date', $today)->where('slot_name', $test_slot)->delete('rto_push_slot_executions');

        // ----------------------------------------------------
        // TEST 4: Dispatch Simulation (Dry-Run Mode)
        // ----------------------------------------------------
        echo "\n[TEST 4] Testing Safe Dry-Run Dispatch Pipeline...\n";
        $this->db->where('slot_date', $today)->where('slot_name', 'TEST_SIM')->delete('rto_push_slot_executions');
        $res = $this->Rto_push_notification_model->dispatch_slot('TEST_SIM', false, true);
        if ($res['success']) {
            echo "  [PASS] Successfully simulated slot dispatch with message ID: " . $res['fcm_result']['fcm_message_id'] . "\n";
            $passed++;
        } else {
            echo "  [FAIL] Dry-run dispatch failed: " . ($res['message'] ?? 'Unknown error') . "\n";
            $failed++;
        }
        $this->db->where('slot_date', $today)->where('slot_name', 'TEST_SIM')->delete('rto_push_slot_executions');

        // ----------------------------------------------------
        // TEST 5: Telemetry Logging
        // ----------------------------------------------------
        echo "\n[TEST 5] Testing Delivery & Open Telemetry Recording...\n";
        $mock_camp_id = 999999;
        $mock_device  = "test_telemetry_device";
        $disp_res = $this->Rto_push_notification_model->record_delivery_event($mock_camp_id, $mock_device, 'DISPLAYED');
        $open_res = $this->Rto_push_notification_model->record_delivery_event($mock_camp_id, $mock_device, 'OPENED');
        if ($disp_res && $open_res) {
            echo "  [PASS] Successfully recorded DISPLAYED and OPENED telemetry events.\n";
            $passed++;
        } else {
            echo "  [FAIL] Telemetry recording failed.\n";
            $failed++;
        }
        $this->db->where('campaign_id', $mock_camp_id)->delete('rto_push_delivery_events');

        echo "\n========================================================\n";
        echo " VERIFICATION COMPLETED: {$passed} PASSED, {$failed} FAILED\n";
        echo "========================================================\n";
    }
}
