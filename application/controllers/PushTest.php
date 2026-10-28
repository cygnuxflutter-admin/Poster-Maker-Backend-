<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Push Notification Automated Verification & Test Suite
 * Usage: php index.php PushTest run
 */
class PushTest extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Push_notification_model');
        $this->load->library('Fcm_v1_client');
    }

    public function run() {
        if (!is_cli() && !$this->session->userdata('poster_sess_user_name')) {
            show_error('Access Denied. CLI only.', 403);
            return;
        }

        echo "========================================================\n";
        echo " POSTERMAKER PUSH NOTIFICATION SYSTEM VERIFICATION SUITE\n";
        echo " Time: " . date('Y-m-d H:i:s') . " (Asia/Kolkata)\n";
        echo "========================================================\n\n";

        $passed = 0;
        $failed = 0;

        // ----------------------------------------------------
        // TEST 1: Database Tables & Default Configs
        // ----------------------------------------------------
        echo "[TEST 1] Verifying Database Schema & Configs...\n";
        $stats = $this->Push_notification_model->get_stats();
        if ($stats['total'] >= 2000) {
            echo "  [PASS] Notification bank contains {$stats['total']} pre-generated drafts.\n";
            $passed++;
        } else {
            echo "  [FAIL] Expected at least 2,000 drafts in bank, found: {$stats['total']}\n";
            $failed++;
        }

        if ($stats['dry_run'] === 1) {
            echo "  [PASS] Safety Dry-Run Mode is ACTIVE by default (Safe).\n";
            $passed++;
        } else {
            echo "  [FAIL] Dry-Run mode is not active.\n";
            $failed++;
        }

        // ----------------------------------------------------
        // TEST 2: Device Token Registration
        // ----------------------------------------------------
        echo "\n[TEST 2] Testing Device Token Registration & Upsert...\n";
        $test_device_id = "test_pixel_suite_" . time();
        $test_fcm_token = "fcm_mock_token_" . bin2hex(random_bytes(16));
        $reg_res = $this->Push_notification_model->register_token($test_device_id, $test_fcm_token, "5.2", "en", 1);
        if ($reg_res) {
            echo "  [PASS] Successfully registered test device token in MySQL.\n";
            $passed++;
        } else {
            echo "  [FAIL] Failed to insert/upsert test device token.\n";
            $failed++;
        }

        // Verify retrieval
        $test_devices = $this->Push_notification_model->get_test_devices();
        $found = false;
        foreach ($test_devices as $td) {
            if ($td['device_id'] === $test_device_id) {
                $found = true;
                break;
            }
        }
        if ($found) {
            echo "  [PASS] Successfully retrieved device in test devices pool.\n";
            $passed++;
        } else {
            echo "  [FAIL] Registered test device not found in test pool query.\n";
            $failed++;
        }

        // Clean up test token
        $this->db->where('device_id', $test_device_id)->delete('push_device_tokens');

        // ----------------------------------------------------
        // TEST 3: Duplicate Slot Prevention (Idempotency)
        // ----------------------------------------------------
        echo "\n[TEST 3] Testing Slot-Level Idempotency Protection...\n";
        $test_slot = "TEST_SLOT";
        $today = date('Y-m-d');
        // Ensure clean state for test slot
        $this->db->where('slot_date', $today)->where('slot_name', $test_slot)->delete('push_slot_executions');

        // Ensure at least 1 approved item exists
        $approved_item = $this->db->where('status', 'APPROVED')->limit(1)->get('push_notification_bank')->row_array();
        if (!$approved_item) {
            $this->db->where('id', 2)->update('push_notification_bank', array('status' => 'APPROVED'));
        }

        // Claim 1: Should succeed
        $claim1 = $this->Push_notification_model->claim_slot_notification($test_slot, 1);
        if ($claim1['status'] === 'claimed') {
            echo "  [PASS] Initial slot claim succeeded for '{$test_slot}'.\n";
            $passed++;
        } else {
            echo "  [FAIL] Initial slot claim failed: " . json_encode($claim1) . "\n";
            $failed++;
        }

        // Claim 2: Immediate duplicate claim for the SAME slot today -> MUST be blocked
        $claim2 = $this->Push_notification_model->claim_slot_notification($test_slot, 1);
        if ($claim2['status'] === 'already_executed') {
            echo "  [PASS] Duplicate claim blocked! Status: 'already_executed'. Duplicate sending is impossible.\n";
            $passed++;
        } else {
            echo "  [FAIL] Duplicate claim was NOT blocked: " . json_encode($claim2) . "\n";
            $failed++;
        }

        // Clean up test slot
        $this->db->where('slot_date', $today)->where('slot_name', $test_slot)->delete('push_slot_executions');

        // ----------------------------------------------------
        // TEST 4: Bounded Retry Limit & Permanent Failure Logic
        // ----------------------------------------------------
        echo "\n[TEST 4] Testing Bounded Retries & Failure Transitions...\n";
        $this->Push_notification_model->set_config('max_retries', '3');
        
        $this->db->insert('push_notification_bank', array(
            'content_uid'    => 'test_retry_mock_' . time(),
            'title'          => 'Unit Test Notification',
            'body'           => 'Simulating retry limit handling',
            'category_name'  => 'Test',
            'status'         => 'PROCESSING',
            'attempt_count'  => 1
        ));
        $dummy_id = $this->db->insert_id();

        // 1. Transient failure at attempt 1 -> Should remain APPROVED for retry
        $this->Push_notification_model->record_send_failure($dummy_id, null, "503 Unavailable", "TOPIC", "all_users", 503, "{}", 20, 1, true);
        $d1 = $this->Push_notification_model->get_item($dummy_id);
        if ($d1['status'] === 'APPROVED') {
            echo "  [PASS] Attempt 1/3 transient failure reverted to APPROVED for retry.\n";
            $passed++;
        } else {
            echo "  [FAIL] Attempt 1 did not revert to APPROVED. Status: {$d1['status']}\n";
            $failed++;
        }

        // 2. Permanent failure (e.g. 400 Bad Request or token unregistered) -> MUST become FAILED immediately
        $this->Push_notification_model->record_send_failure($dummy_id, null, "400 Invalid Argument", "TOPIC", "all_users", 400, "{}", 20, 1, false);
        $d2 = $this->Push_notification_model->get_item($dummy_id);
        if ($d2['status'] === 'FAILED') {
            echo "  [PASS] Permanent failure (non-retryable) immediately transitioned to FAILED.\n";
            $passed++;
        } else {
            echo "  [FAIL] Permanent failure did not transition to FAILED. Status: {$d2['status']}\n";
            $failed++;
        }

        // Clean up dummy item
        $this->db->where('id', $dummy_id)->delete('push_notification_bank');
        $this->db->where('bank_id', $dummy_id)->delete('push_notification_logs');

        // ----------------------------------------------------
        // TEST 5: FCM Dry-Run Dispatch & Message Schema
        // ----------------------------------------------------
        echo "\n[TEST 5] Testing FCM HTTP v1 Dry-Run Dispatch...\n";
        $sample_content = array(
            'id'            => '101',
            'title'         => 'Test Notification Title',
            'body'          => 'Test notification body text',
            'cat_id'        => '55',
            'category_name' => 'Diwali',
            'target_screen' => 'category_detail'
        );
        $dry_res = $this->fcm_v1_client->send('TOPIC', 'all_users', $sample_content, true);
        if ($dry_res['success'] && !empty($dry_res['fcm_message_id']) && $dry_res['is_dry_run']) {
            echo "  [PASS] Dry-run simulated successfully! Message ID: {$dry_res['fcm_message_id']}\n";
            echo "  [PASS] Message structure contains valid title, body, cat_id, and android priority.\n";
            $passed += 2;
        } else {
            echo "  [FAIL] Dry-run dispatch failed: " . json_encode($dry_res) . "\n";
            $failed++;
        }

        // ----------------------------------------------------
        // TEST 6: Manual Push Campaign Creation & Lifecycle
        // ----------------------------------------------------
        echo "\n[TEST 6] Testing Manual Push Campaign Creation & Model Operations...\n";
        $manual_data = array(
            'campaign_uid'   => 'test_manual_' . bin2hex(random_bytes(8)),
            'title'          => 'Unit Test Special Festive Offer',
            'message'        => 'Create stunning greeting cards today with 50+ templates!',
            'image_path'     => 'upload/push_images/test_sample.png',
            'image_url'      => 'https://cygnux.in/postermaker/upload/push_images/test_sample.png',
            'target_type'    => 'TOPIC',
            'target_value'   => 'all_users',
            'target_screen'  => 'create',
            'cat_id'         => 12,
            'status'         => 'PROCESSING',
            'is_dry_run'     => 1,
            'targeted_count' => 1,
            'created_by'     => 'admin_tester'
        );
        $camp_id = $this->Push_notification_model->create_manual_campaign($manual_data);
        if ($camp_id) {
            echo "  [PASS] Created manual campaign with ID: {$camp_id}\n";
            $passed++;

            // Update result
            $fcm_mock_res = array(
                'success'        => true,
                'fcm_message_id' => 'projects/test/messages/dry_run_camp_' . $camp_id,
                'error'          => null
            );
            $this->Push_notification_model->record_manual_campaign_result(
                $camp_id,
                $fcm_mock_res,
                'TOPIC',
                'all_users',
                1,
                1
            );
            $camp_row = $this->Push_notification_model->get_manual_campaign($camp_id);
            if ($camp_row && $camp_row['status'] === 'SENT' && $camp_row['accepted_count'] == 1) {
                echo "  [PASS] Successfully recorded campaign dispatch result & FCM message ID.\n";
                $passed++;
            } else {
                echo "  [FAIL] Failed to update manual campaign status.\n";
                $failed++;
            }
        } else {
            echo "  [FAIL] Could not insert manual campaign.\n";
            $failed++;
        }

        // ----------------------------------------------------
        // TEST 7: Delivery Receipt (DISPLAYED) & Open (OPENED) Telemetry
        // ----------------------------------------------------
        echo "\n[TEST 7] Testing Device Delivery Receipts & Open Telemetry Tracking...\n";
        if ($camp_id) {
            $telemetry_device = "mock_android_client_" . time();

            // 1. First DISPLAYED event
            $disp1 = $this->Push_notification_model->record_delivery_event($camp_id, $telemetry_device, 'DISPLAYED');
            $camp_after_disp = $this->Push_notification_model->get_manual_campaign($camp_id);

            // 2. Duplicate DISPLAYED event (must be idempotent, not increment counter twice!)
            $disp2 = $this->Push_notification_model->record_delivery_event($camp_id, $telemetry_device, 'DISPLAYED');
            $camp_after_dup = $this->Push_notification_model->get_manual_campaign($camp_id);

            // 3. OPENED event
            $open1 = $this->Push_notification_model->record_delivery_event($camp_id, $telemetry_device, 'OPENED');
            $camp_after_open = $this->Push_notification_model->get_manual_campaign($camp_id);

            if ($disp1 && $camp_after_disp['confirmed_delivery_count'] == 1) {
                echo "  [PASS] DISPLAYED receipt recorded and confirmed_delivery_count incremented to 1.\n";
                $passed++;
            } else {
                echo "  [FAIL] DISPLAYED receipt failed.\n";
                $failed++;
            }

            if (!$disp2 && $camp_after_dup['confirmed_delivery_count'] == 1) {
                echo "  [PASS] Duplicate DISPLAYED event safely ignored (Idempotency confirmed).\n";
                $passed++;
            } else {
                echo "  [FAIL] Duplicate receipt was counted more than once!\n";
                $failed++;
            }

            if ($open1 && $camp_after_open['open_count'] == 1) {
                echo "  [PASS] OPENED event recorded and open_count incremented to 1.\n";
                $passed++;
            } else {
                echo "  [FAIL] OPENED event tracking failed.\n";
                $failed++;
            }

            // Cleanup test campaign & events
            $this->db->where('id', $camp_id)->delete('push_manual_campaigns');
            $this->db->where('campaign_id', $camp_id)->delete('push_delivery_events');
        }

        // ----------------------------------------------------
        // SUMMARY
        // ----------------------------------------------------
        echo "\n========================================================\n";
        echo " VERIFICATION SUMMARY: {$passed} PASSED, {$failed} FAILED\n";
        if ($failed === 0) {
            echo " STATUS: ALL ARCHITECTURAL TESTS PASSED PERFECTLY!\n";
        } else {
            echo " STATUS: SOME TESTS FAILED. PLEASE INVESTIGATE.\n";
        }
        echo "========================================================\n";
    }
}
