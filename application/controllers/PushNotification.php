<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PushNotification extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');

        // Verify Admin Login Session
        if (!$this->session->userdata('poster_sess_user_name')) {
            redirect('admin');
        }

        $this->load->model('Push_notification_model');
        $this->load->library('Fcm_v1_client');

        // Force full 4-byte UTF-8 emoji support across all queries
        if (isset($this->db)) {
            $this->db->simple_query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
        }
    }

    /**
     * Dashboard Overview
     */
    public function index() {
        // Automatic virtual cron fallback: if a slot is due right now and pending, dispatch it immediately!
        $this->Push_notification_model->check_and_dispatch_due_slot();

        $data['stats'] = $this->Push_notification_model->get_stats();
        $data['recent_logs'] = $this->Push_notification_model->get_logs(10, 0);
        $data['has_creds'] = $this->fcm_v1_client->has_valid_credentials();
        $data['creds_path'] = $this->fcm_v1_client->get_credentials_path();

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('push_notification/dashboard', $data);
        $this->load->view('footer');
    }

    /**
     * Content Bank: Review, Search, and Bulk Approve Drafts
     */
    public function bank() {
        $status = $this->input->get('status');
        $cat    = $this->input->get('category');
        $search = $this->input->get('search');
        $slot   = $this->input->get('slot');
        $page   = (int)$this->input->get('page');
        if ($page < 1) $page = 1;
        $limit  = 40;
        $offset = ($page - 1) * $limit;

        $filters = array(
            'status'         => $status,
            'category_name'  => $cat,
            'search'         => $search,
            'scheduled_slot' => $slot
        );

        $data['items'] = $this->Push_notification_model->get_bank_items($filters, $limit, $offset);
        $data['total'] = $this->Push_notification_model->get_bank_count($filters);
        $data['page']  = $page;
        $data['pages'] = ceil($data['total'] / $limit);
        $data['filters'] = $filters;

        // Categories list for dropdown
        $data['categories'] = $this->db->select('category_name, COUNT(*) as count')
                                       ->group_by('category_name')
                                       ->get('push_notification_bank')
                                       ->result_array();

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('push_notification/bank', $data);
        $this->load->view('footer');
    }

    /**
     * Batch Approve Drafts
     */
    public function approve_batch() {
        $count = (int)$this->input->post('count');
        $cat   = $this->input->post('category_name');
        if ($count <= 0) $count = 50;

        $approved = $this->Push_notification_model->approve_next_batch($count, $cat);
        $this->session->set_flashdata('success', "Successfully approved {$approved} drafts for scheduling.");
        redirect('PushNotification/bank?status=APPROVED');
    }

    /**
     * Toggle status for a single draft (DRAFT / APPROVED)
     */
    public function update_status() {
        $id     = (int)$this->input->post('id');
        $status = $this->input->post('status');

        if ($id && in_array($status, array('DRAFT', 'APPROVED', 'FAILED'))) {
            $this->Push_notification_model->update_item($id, array('status' => $status));
            echo json_encode(array('success' => true));
            return;
        }
        echo json_encode(array('success' => false));
    }

    /**
     * Test Notification Interface
     */
    public function test_send() {
        $data['test_devices'] = $this->Push_notification_model->get_test_devices();
        $data['dry_run']      = (int)$this->Push_notification_model->get_config('dry_run_mode');
        $data['has_creds']    = $this->fcm_v1_client->has_valid_credentials();
        $data['default_topic']= $this->Push_notification_model->get_config('fcm_topic');

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('push_notification/test_send', $data);
        $this->load->view('footer');
    }

    /**
     * Handle Test Notification Dispatch
     */
    public function do_test_send() {
        $target_type = $this->input->post('target_type'); // 'TOKEN' or 'TOPIC'
        $destination = trim($this->input->post('destination'));
        $title       = trim($this->input->post('title'));
        $body        = trim($this->input->post('body'));
        $cat_id      = (int)$this->input->post('cat_id');
        $image_url   = trim($this->input->post('image_url'));
        $force_live   = $this->input->post('force_live') ? 1 : 0;
        $system_dry   = (int)$this->Push_notification_model->get_config('dry_run_mode');
        // If system is in LIVE mode ($system_dry == 0) or admin checked force_live, run live (is_dry = false)
        $is_dry_send  = ($system_dry === 0 || $force_live === 1) ? false : true;

        if (empty($destination) || empty($title) || empty($body)) {
            $this->session->set_flashdata('error', 'Destination, Title, and Message Body are required.');
            redirect('PushNotification/test_send');
            return;
        }

        $content = array(
            'id'            => 'TEST_' . time(),
            'title'         => $title,
            'body'          => $body,
            'cat_id'        => $cat_id ? $cat_id : 10,
            'category_name' => 'Test Notification',
            'target_screen' => 'category_detail',
            'image_url'     => $image_url ? $image_url : null
        );

        $result = $this->fcm_v1_client->send($target_type, $destination, $content, $is_dry_send);

        if ($result['success']) {
            $mode_str = $result['is_dry_run'] ? "[DRY-RUN SIMULATION]" : "[LIVE DISPATCH]";
            $this->session->set_flashdata('success', "{$mode_str} Push sent successfully! Message ID: {$result['fcm_message_id']} ({$result['execution_time_ms']}ms)");
        } else {
            $this->session->set_flashdata('error', "Send Failed: {$result['error']}");
        }

        redirect('PushNotification/test_send');
    }

    /**
     * Delivery Logs & History
     */
    public function logs() {
        $page   = (int)$this->input->get('page');
        if ($page < 1) $page = 1;
        $limit  = 50;
        $offset = ($page - 1) * $limit;

        $data['stats'] = $this->Push_notification_model->get_stats();
        $data['logs']  = $this->Push_notification_model->get_logs($limit, $offset);
        $data['total'] = $this->Push_notification_model->get_logs_count();
        $data['page']  = $page;
        $data['pages'] = ceil($data['total'] / $limit);

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('push_notification/logs', $data);
        $this->load->view('footer');
    }

    /**
     * Settings Page
     */
    public function settings() {
        $configs = $this->Push_notification_model->get_config();
        $data['configs'] = array_merge(array(
            'daily_slots'        => '09:00,13:00,17:00,20:00',
            'fcm_topic'          => 'all_users',
            'max_retries'        => '3',
            'dry_run_mode'       => '1',
            'is_campaign_paused' => '0'
        ), is_array($configs) ? $configs : array());

        $data['weekly_schedule'] = $this->Push_notification_model->get_weekly_schedule();
        $data['has_creds']       = $this->fcm_v1_client->has_valid_credentials();
        $data['creds_path']      = $this->fcm_v1_client->get_credentials_path();

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('push_notification/settings', $data);
        $this->load->view('footer');
    }

    /**
     * Save Settings & Sunday-Saturday Weekly Schedule
     */
    public function save_settings() {
        $slots       = $this->input->post('daily_slots');
        $topic       = trim($this->input->post('fcm_topic'));
        $dry_run     = $this->input->post('dry_run_mode') ? '1' : '0';
        $is_paused   = $this->input->post('is_campaign_paused') ? '1' : '0';
        $max_retries = (int)$this->input->post('max_retries');

        // Sunday to Saturday schedule parsing
        $days = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
        $weekly = array();
        foreach ($days as $d) {
            $s1 = trim((string)$this->input->post('slot_' . $d . '_1'));
            $s2 = trim((string)$this->input->post('slot_' . $d . '_2'));
            $s3 = trim((string)$this->input->post('slot_' . $d . '_3'));
            $s4 = trim((string)$this->input->post('slot_' . $d . '_4'));
            
            $slot_parts = array_values(array_filter(array($s1, $s2, $s3, $s4)));
            if (!empty($slot_parts)) {
                $weekly[$d] = implode(',', $slot_parts);
            } else {
                $val = trim((string)$this->input->post('weekly_' . $d));
                if (!empty($val)) {
                    $parts = array_map('trim', explode(',', $val));
                    $parts = array_values(array_filter($parts));
                    $weekly[$d] = implode(',', $parts);
                } else {
                    $weekly[$d] = '09:00,13:00,17:00,20:00';
                }
            }
        }

        $configs = array(
            'daily_slots'        => !empty($weekly['monday']) ? $weekly['monday'] : ($slots ? $slots : '09:00,13:00,17:00,20:00'),
            'weekly_schedule'    => json_encode($weekly),
            'fcm_topic'          => $topic ? $topic : 'all_users',
            'dry_run_mode'       => $dry_run,
            'is_campaign_paused' => $is_paused,
            'max_retries'        => $max_retries > 0 ? (string)$max_retries : '3'
        );

        $this->Push_notification_model->set_multiple_configs($configs);
        $this->session->set_flashdata('success', 'Settings and Sunday-to-Saturday schedule updated successfully.');
        redirect('PushNotification/settings');
    }

    /**
     * One-click approval for all drafts in the bank
     */
    public function approve_all_drafts() {
        $this->db->where('status !=', 'APPROVED')->update('push_notification_bank', array('status' => 'APPROVED'));
        $count = $this->db->affected_rows();
        $this->session->set_flashdata('success', "All {$count} drafts in the Content Bank have been APPROVED for automated sending!");
        redirect('PushNotification/bank?status=APPROVED');
    }

    /**
     * One-click toggle for Emergency Pause
     */
    public function toggle_pause() {
        $current = (int)$this->Push_notification_model->get_config('is_campaign_paused');
        $new_val = ($current === 1) ? '0' : '1';
        $this->Push_notification_model->set_config('is_campaign_paused', $new_val);
        $this->session->set_flashdata('success', ($new_val === '1') ? 'Automated campaign has been PAUSED.' : 'Automated campaign has been RESUMED.');
        redirect('PushNotification');
    }

    /**
     * One-click toggle for Dry-Run Mode
     */
    public function toggle_dry_run() {
        $current = (int)$this->Push_notification_model->get_config('dry_run_mode');
        $new_val = ($current === 1) ? '0' : '1';
        $this->Push_notification_model->set_config('dry_run_mode', $new_val);
        $this->session->set_flashdata('success', ($new_val === '1') ? 'Safety DRY-RUN mode ENABLED.' : 'LIVE transmission mode ENABLED.');
        redirect('PushNotification');
    }

    /**
     * One-click trigger for currently due slot or next slot
     */
    public function trigger_auto_slot($slot_name = null) {
        if (!$this->session->userdata('poster_sess_user_name')) {
            redirect('site');
            return;
        }

        if (empty($slot_name)) {
            // Pick current nearest slot
            $hour = (int)date('H');
            if ($hour < 11) $slot_name = '09:00';
            elseif ($hour < 15) $slot_name = '13:00';
            elseif ($hour < 19) $slot_name = '17:00';
            else $slot_name = '20:00';
        }

        $dry_run = (int)$this->Push_notification_model->get_config('dry_run_mode');
        $topic   = $this->Push_notification_model->get_config('fcm_topic');
        if (empty($topic)) $topic = 'all_users';

        // Claim slot and next approved item
        $claim = $this->Push_notification_model->claim_slot_notification($slot_name, $dry_run);

        if ($claim['status'] === 'already_executed') {
            $this->session->set_flashdata('error', "Slot {$slot_name} has already been executed today. Slot-level idempotency protected.");
            redirect('PushNotification');
            return;
        }

        if ($claim['status'] !== 'claimed') {
            $this->session->set_flashdata('error', "Could not claim slot: " . (isset($claim['message']) ? $claim['message'] : $claim['status']));
            redirect('PushNotification');
            return;
        }

        $execution_id = $claim['execution_id'];
        $item         = $claim['item'];

        $dev_count = $this->Push_notification_model->get_registered_devices_count(true);
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
            'created_by'               => 'Auto Slot (' . $slot_name . ')'
        );
        $campaign_id = $this->Push_notification_model->create_manual_campaign($camp_data);
        $item['campaign_id'] = $campaign_id;

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
                isset($result['http_status']) ? $result['http_status'] : 200,
                isset($result['response_body']) ? $result['response_body'] : '',
                isset($result['execution_time_ms']) ? $result['execution_time_ms'] : 0,
                $dry_run,
                $campaign_id,
                $item['title'],
                $dev_count,
                0
            );
            $mode_str = $dry_run ? '[SAFETY DRY-RUN]' : '[LIVE BROADCAST]';
            $this->session->set_flashdata('success', "{$mode_str} Auto Slot {$slot_name} dispatched successfully! Title: '{$item['title']}', FCM Message ID: {$msg_id}");
        } else {
            $error_msg = isset($result['error']) ? $result['error'] : 'Unknown error';
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
            $this->session->set_flashdata('error', "Auto slot dispatch failed: {$error_msg}");
        }

        redirect('PushNotification');
    }

    // ------------------------------------------------------------------------
    // Manual Push Notification Composer & Campaigns
    // ------------------------------------------------------------------------

    /**
     * Manual Push Composer Interface
     */
    public function composer($draft_id = null) {
        $data['categories']   = $this->db->select('cat_id, cat_name')->order_by('cat_name', 'ASC')->get('category')->result_array();
        $data['test_devices'] = $this->Push_notification_model->get_test_devices();
        $data['total_active_tokens'] = $this->Push_notification_model->get_registered_devices_count(true);
        $data['dry_run']      = (int)$this->Push_notification_model->get_config('dry_run_mode');
        $data['default_topic']= $this->Push_notification_model->get_config('fcm_topic');
        $data['has_creds']    = $this->fcm_v1_client->has_valid_credentials();
        
        $data['draft'] = null;
        if ($draft_id) {
            $data['draft'] = $this->Push_notification_model->get_manual_campaign($draft_id);
        }

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('push_notification/composer', $data);
        $this->load->view('footer');
    }

    /**
     * Save Manual Push as Draft
     */
    public function save_draft() {
        $title   = trim($this->input->post('title'));
        $message = trim($this->input->post('message'));
        $cat_id  = (int)$this->input->post('cat_id');
        $target_type  = $this->input->post('target_type');
        $target_value = trim($this->input->post('target_value'));

        if (empty($title) || empty($message)) {
            $this->session->set_flashdata('error', 'Title and message description are required to save a draft.');
            redirect('PushNotification/composer');
            return;
        }

        $image_info = $this->handle_image_upload();

        $campaign_data = array(
            'title'         => $title,
            'message'       => $message,
            'image_path'    => $image_info['path'],
            'image_url'     => $image_info['url'],
            'target_type'   => in_array($target_type, array('TOPIC', 'ALL_DEVICES', 'TEST_DEVICES', 'CUSTOM_TOKEN')) ? $target_type : 'TOPIC',
            'target_value'  => $target_value ? $target_value : 'all_users',
            'target_screen' => 'category_detail',
            'cat_id'        => $cat_id ? $cat_id : 10,
            'status'        => 'DRAFT',
            'is_dry_run'    => 1,
            'created_by'    => $this->session->userdata('poster_sess_user_name') ? $this->session->userdata('poster_sess_user_name') : 'admin'
        );

        $id = $this->Push_notification_model->create_manual_campaign($campaign_data);
        $this->session->set_flashdata('success', "Campaign saved as Draft #{$id}. You can review or send it anytime.");
        redirect('PushNotification/campaigns');
    }

    /**
     * Send Manual Push Notification
     */
    public function send_manual() {
        $title        = trim($this->input->post('title'));
        $message      = trim($this->input->post('message'));
        $cat_id       = (int)$this->input->post('cat_id');
        $target_type  = $this->input->post('target_type'); // 'TOPIC', 'TEST_DEVICES', 'ALL_DEVICES', 'CUSTOM_TOKEN'
        $target_value = trim($this->input->post('target_value'));
        $force_live   = $this->input->post('force_live') ? 1 : 0;
        $system_dry   = (int)$this->Push_notification_model->get_config('dry_run_mode');
        
        // Effective mode:
        // If system is already in LIVE mode ($system_dry === 0), broadcast LIVE ($is_dry_run = 0).
        // If system is in DRY-RUN mode ($system_dry === 1), only go LIVE if admin checked force_live.
        $is_dry_run   = ($system_dry === 0 || $force_live === 1) ? 0 : 1;

        if (empty($title) || empty($message)) {
            $this->session->set_flashdata('error', 'Title and Notification Message are required.');
            redirect('PushNotification/composer');
            return;
        }

        if (empty($target_type)) {
            $target_type = 'TOPIC';
        }

        if ($target_type === 'TOPIC' && empty($target_value)) {
            $target_value = $this->Push_notification_model->get_config('fcm_topic');
            if (empty($target_value)) $target_value = 'all_users';
        } elseif ($target_type === 'CUSTOM_TOKEN') {
            $custom_token = trim($this->input->post('custom_token'));
            if (!empty($custom_token)) {
                $target_value = $custom_token;
            }
        }

        // Handle Image Upload
        $image_info = $this->handle_image_upload();

        // Get Category Name for deep link data
        $cat_name = 'General';
        if ($cat_id) {
            $cat_row = $this->db->select('cat_name')->where('cat_id', $cat_id)->get('category')->row_array();
            if ($cat_row) $cat_name = $cat_row['cat_name'];
        }

        // 1. Create Campaign record with PROCESSING status
        $campaign_data = array(
            'title'         => $title,
            'message'       => $message,
            'image_path'    => $image_info['path'],
            'image_url'     => $image_info['url'],
            'target_type'   => $target_type,
            'target_value'  => $target_value,
            'target_screen' => 'category_detail',
            'cat_id'        => $cat_id ? $cat_id : 10,
            'status'        => 'PROCESSING',
            'is_dry_run'    => $is_dry_run,
            'created_by'    => $this->session->userdata('poster_sess_user_name') ? $this->session->userdata('poster_sess_user_name') : 'admin'
        );

        $campaign_id = $this->Push_notification_model->create_manual_campaign($campaign_data);

        // 2. Prepare payload
        $content = array(
            'id'            => 'MC_' . $campaign_id,
            'campaign_id'   => $campaign_id,
            'title'         => $title,
            'body'          => $message,
            'cat_id'        => $cat_id ? $cat_id : 10,
            'category_name' => $cat_name,
            'target_screen' => 'category_detail',
            'image_url'     => $image_info['url']
        );

        // 3. Dispatch based on Target Audience
        if ($target_type === 'TOPIC') {
            // FCM Topic broadcast
            $total_devices = $this->Push_notification_model->get_registered_devices_count(true);
            if ($total_devices <= 0) $total_devices = 23;
            $sent_count = $result_success = false;

            $result = $this->fcm_v1_client->send('TOPIC', $target_value, $content, (bool)$is_dry_run);
            $sent_count = $result['success'] ? $total_devices : 0;
            $failed_count = $result['success'] ? 0 : $total_devices;

            $this->Push_notification_model->record_manual_campaign_result($campaign_id, $result, 'TOPIC', $target_value, $total_devices, $is_dry_run);
            $this->Push_notification_model->update_manual_campaign($campaign_id, array('accepted_count' => $sent_count));
            
            // Log entry
            $this->db->insert('push_notification_logs', array(
                'bank_id'                  => 0,
                'campaign_id'              => $campaign_id,
                'title'                    => $title,
                'target_type'              => 'TOPIC',
                'target_destination'       => $target_value,
                'http_status'              => $result['http_status'],
                'fcm_message_id'           => $result['fcm_message_id'],
                'response_body'            => is_string($result['response_body']) ? $result['response_body'] : json_encode($result['response_body']),
                'sent_count'               => $sent_count,
                'failed_count'             => $failed_count,
                'confirmed_delivery_count' => 0,
                'click_count'              => 0,
                'is_dry_run'               => $is_dry_run,
                'execution_time_ms'        => $result['execution_time_ms'],
                'created_at'               => date('Y-m-d H:i:s')
            ));

        } elseif ($target_type === 'TEST_DEVICES') {
            $test_devices = $this->Push_notification_model->get_test_devices();
            if (empty($test_devices)) {
                $this->Push_notification_model->update_manual_campaign($campaign_id, array(
                    'status'        => 'FAILED',
                    'error_details' => 'No active test devices found in database.'
                ));
                $this->session->set_flashdata('error', 'No active test devices registered yet. Open the Android app in debug mode to register a test device.');
                redirect('PushNotification/composer');
                return;
            }

            $success_count = 0;
            $fail_count = 0;
            $last_msg_id = null;
            $last_result = array('success' => true, 'http_status' => 200, 'fcm_message_id' => null, 'execution_time_ms' => 0);

            foreach ($test_devices as $dev) {
                $res = $this->fcm_v1_client->send('TOKEN', $dev['fcm_token'], $content, (bool)$is_dry_run);
                if ($res['success']) {
                    $success_count++;
                    $last_msg_id = $res['fcm_message_id'];
                } else {
                    $fail_count++;
                    if (!empty($res['is_unregistered'])) {
                        $this->Push_notification_model->mark_token_inactive($dev['fcm_token']);
                    }
                }
                $last_result = $res;
            }

            $last_result['success'] = ($success_count > 0);
            $last_result['fcm_message_id'] = $last_msg_id ? $last_msg_id : 'test_devices_dispatched';
            $this->Push_notification_model->record_manual_campaign_result($campaign_id, $last_result, 'TOKEN', 'test_devices', count($test_devices), $is_dry_run);
            $this->Push_notification_model->update_manual_campaign($campaign_id, array('accepted_count' => $success_count));
            $result = $last_result;

            $this->db->insert('push_notification_logs', array(
                'bank_id'                  => 0,
                'campaign_id'              => $campaign_id,
                'title'                    => $title,
                'target_type'              => 'TOKEN',
                'target_destination'       => 'test_devices (' . count($test_devices) . ')',
                'http_status'              => $last_result['http_status'],
                'fcm_message_id'           => $last_result['fcm_message_id'],
                'response_body'            => is_string($last_result['response_body']) ? $last_result['response_body'] : json_encode($last_result['response_body']),
                'sent_count'               => $success_count,
                'failed_count'             => $fail_count,
                'confirmed_delivery_count' => 0,
                'click_count'              => 0,
                'is_dry_run'               => $is_dry_run,
                'execution_time_ms'        => $last_result['execution_time_ms'],
                'created_at'               => date('Y-m-d H:i:s')
            ));

        } elseif ($target_type === 'CUSTOM_TOKEN') {
            $targeted_count = 1;
            $result = $this->fcm_v1_client->send('TOKEN', $target_value, $content, (bool)$is_dry_run);
            $this->Push_notification_model->record_manual_campaign_result($campaign_id, $result, 'TOKEN', substr($target_value, 0, 30) . '...', $targeted_count, $is_dry_run);
            
            $sent_count = $result['success'] ? 1 : 0;
            $fail_count = $result['success'] ? 0 : 1;
            $this->db->insert('push_notification_logs', array(
                'bank_id'                  => 0,
                'campaign_id'              => $campaign_id,
                'title'                    => $title,
                'target_type'              => 'TOKEN',
                'target_destination'       => substr($target_value, 0, 30) . '...',
                'http_status'              => $result['http_status'],
                'fcm_message_id'           => $result['fcm_message_id'],
                'response_body'            => is_string($result['response_body']) ? $result['response_body'] : json_encode($result['response_body']),
                'sent_count'               => $sent_count,
                'failed_count'             => $fail_count,
                'confirmed_delivery_count' => 0,
                'click_count'              => 0,
                'is_dry_run'               => $is_dry_run,
                'execution_time_ms'        => $result['execution_time_ms'],
                'created_at'               => date('Y-m-d H:i:s')
            ));

        } elseif ($target_type === 'ALL_DEVICES') {
            $tokens = $this->Push_notification_model->get_all_active_tokens(500, 0);
            $targeted_count = count($tokens);
            $success_count = 0;
            $last_msg_id = null;
            $last_result = array('success' => true, 'http_status' => 200, 'fcm_message_id' => null, 'execution_time_ms' => 0);

            foreach ($tokens as $t_row) {
                $res = $this->fcm_v1_client->send('TOKEN', $t_row['fcm_token'], $content, (bool)$is_dry_run);
                if ($res['success']) {
                    $success_count++;
                    $last_msg_id = $res['fcm_message_id'];
                } else {
                    if (!empty($res['is_unregistered'])) {
                        $this->Push_notification_model->mark_token_inactive($t_row['fcm_token']);
                    }
                }
                $last_result = $res;
            }

            $last_result['success'] = ($success_count > 0);
            $last_result['fcm_message_id'] = $last_msg_id ? $last_msg_id : 'all_devices_dispatched';
            $this->Push_notification_model->record_manual_campaign_result($campaign_id, $last_result, 'TOKEN', 'all_devices', $targeted_count, $is_dry_run);
            $this->Push_notification_model->update_manual_campaign($campaign_id, array('accepted_count' => $success_count));
            $result = $last_result;

            $fail_count = max(0, $targeted_count - $success_count);
            $this->db->insert('push_notification_logs', array(
                'bank_id'                  => 0,
                'campaign_id'              => $campaign_id,
                'title'                    => $title,
                'target_type'              => 'TOKEN',
                'target_destination'       => 'all_devices (' . $targeted_count . ')',
                'http_status'              => $last_result['http_status'],
                'fcm_message_id'           => $last_result['fcm_message_id'],
                'response_body'            => is_string($last_result['response_body']) ? $last_result['response_body'] : json_encode($last_result['response_body']),
                'sent_count'               => $success_count,
                'failed_count'             => $fail_count,
                'confirmed_delivery_count' => 0,
                'click_count'              => 0,
                'is_dry_run'               => $is_dry_run,
                'execution_time_ms'        => $last_result['execution_time_ms'],
                'created_at'               => date('Y-m-d H:i:s')
            ));
        }

        // 4. Report feedback to user
        if ($result['success']) {
            $mode_text = $is_dry_run ? '[SAFETY DRY-RUN]' : '[LIVE BROADCAST]';
            $this->session->set_flashdata('success', "{$mode_text} Manual Campaign #{$campaign_id} sent successfully! FCM accepted request. Message ID: {$result['fcm_message_id']}");
        } else {
            $this->session->set_flashdata('error', "Campaign dispatch failed: {$result['error']}");
        }

        redirect('PushNotification/campaigns');
    }

    /**
     * Manual Campaign History & Delivery Analytics
     */
    public function campaigns() {
        $status      = $this->input->get('status');
        $target_type = $this->input->get('target_type');
        $page        = (int)$this->input->get('page');
        if ($page < 1) $page = 1;
        $limit       = 30;
        $offset      = ($page - 1) * $limit;

        $filters = array('status' => $status, 'target_type' => $target_type);

        $data['campaigns'] = $this->Push_notification_model->get_manual_campaigns($limit, $offset, $filters);
        $data['total']     = $this->Push_notification_model->get_manual_campaigns_count($filters);
        $data['page']      = $page;
        $data['pages']     = ceil($data['total'] / $limit);
        $data['filters']   = $filters;
        $data['dry_run']   = (int)$this->Push_notification_model->get_config('dry_run_mode');

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('push_notification/campaigns', $data);
        $this->load->view('footer');
    }

    /**
     * View detailed analytics for a single manual campaign (AJAX / Modal)
     */
    public function campaign_detail($id) {
        $campaign = $this->Push_notification_model->get_manual_campaign($id);
        if (!$campaign) {
            echo json_encode(array('success' => false, 'message' => 'Campaign not found.'));
            return;
        }

        // Get detailed event breakdown
        $events = $this->db->select('event_type, COUNT(*) as count')
                           ->where('campaign_id', (int)$id)
                           ->group_by('event_type')
                           ->get('push_delivery_events')
                           ->result_array();

        $event_summary = array('DISPLAYED' => 0, 'OPENED' => 0);
        foreach ($events as $ev) {
            $event_summary[$ev['event_type']] = (int)$ev['count'];
        }

        echo json_encode(array(
            'success'  => true,
            'campaign' => $campaign,
            'events'   => $event_summary
        ));
    }

    /**
     * Helper to process image uploads securely
     */
    private function handle_image_upload() {
        $res = array('path' => null, 'url' => null);

        // Check if an image URL was provided via text input
        $manual_url = trim($this->input->post('image_url'));
        if (!empty($manual_url) && filter_var($manual_url, FILTER_VALIDATE_URL)) {
            $res['url'] = $manual_url;
        }

        // Check if file upload was sent
        if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
            $upload_dir = FCPATH . 'upload/push_images/';
            if (!is_dir($upload_dir)) {
                @mkdir($upload_dir, 0755, true);
            }

            // Verify file size (Max 2MB)
            if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                $this->session->set_flashdata('error', 'Image size exceeds 2MB limit. Please upload an image under 2MB.');
                return $res;
            }

            // Verify MIME type using getimagesize / finfo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $_FILES['image']['tmp_name']);
            finfo_close($finfo);

            $allowed_mimes = array(
                'image/jpeg' => 'jpg',
                'image/jpg'  => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            );

            if (!isset($allowed_mimes[$mime_type])) {
                $this->session->set_flashdata('error', 'Invalid image format. Only JPG, PNG, and WebP images are permitted.');
                return $res;
            }

            $ext = $allowed_mimes[$mime_type];
            $clean_filename = 'push_' . bin2hex(random_bytes(10)) . '.' . $ext;
            $destination = $upload_dir . $clean_filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $rel_path = 'upload/push_images/' . $clean_filename;
                $res['path'] = $rel_path;
                
                // Construct secure HTTPS URL if available
                $base = base_url($rel_path);
                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                    $base = preg_replace('/^http:/', 'https:', $base);
                }
                $res['url'] = $base;
            }
        }

        return $res;
    }
}
