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

        // Initialize active app profile from session
        $this->_get_active_app();
    }

    /**
     * Helper to get and enforce active app profile ('chirag' or 'nitin')
     */
    private function _get_active_app() {
        $active = $this->session->userdata('active_push_app');
        if (!$active || !in_array($active, array('chirag', 'nitin'))) {
            $active = 'chirag';
            $this->session->set_userdata('active_push_app', 'chirag');
        }
        if (isset($this->fcm_v1_client) && method_exists($this->fcm_v1_client, 'set_profile')) {
            $this->fcm_v1_client->set_profile($active);
        }
        return $active;
    }

    /**
     * Switch Active App Workspace (Chirag Sir App <-> Nitin Sir App)
     */
    public function switch_app($app = 'chirag') {
        $app = strtolower(trim((string)$app));
        if (!in_array($app, array('chirag', 'nitin'))) {
            $app = 'chirag';
        }
        $this->session->set_userdata('active_push_app', $app);
        if (isset($this->fcm_v1_client) && method_exists($this->fcm_v1_client, 'set_profile')) {
            $this->fcm_v1_client->set_profile($app);
        }
        $label = ($app === 'nitin') ? 'Nitin Sir App' : 'Chirag Sir App';
        $this->session->set_flashdata('success', "Active workspace switched to: {$label}");

        $ref = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('PushNotification');
        redirect($ref);
    }

    /**
     * Dashboard Overview
     */
    public function index() {
        // Automatic virtual cron fallback: if a slot is due right now and pending, dispatch it immediately!
        $this->Push_notification_model->check_and_dispatch_due_slot();
        $this->Push_notification_model->check_and_dispatch_scheduled_campaigns();

        $data['active_app']        = $this->_get_active_app();
        $data['active_project_id'] = (isset($this->fcm_v1_client) && method_exists($this->fcm_v1_client, 'get_project_id')) ? $this->fcm_v1_client->get_project_id() : 'poster---text-art';
        $data['stats']             = $this->Push_notification_model->get_stats($data['active_app']);
        $data['recent_logs']       = $this->Push_notification_model->get_logs(10, 0, $data['active_app']);
        $data['has_creds']         = $this->fcm_v1_client->has_valid_credentials();
        $data['creds_path']        = $this->fcm_v1_client->get_credentials_path();
        $data['chirag_has_creds']  = method_exists($this->fcm_v1_client, 'has_valid_credentials') ? $this->fcm_v1_client->has_valid_credentials('chirag') : true;
        $data['nitin_has_creds']   = method_exists($this->fcm_v1_client, 'has_valid_credentials') ? $this->fcm_v1_client->has_valid_credentials('nitin') : false;

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

        $data['active_app']        = $this->_get_active_app();
        $data['active_project_id'] = $this->fcm_v1_client->get_project_id();
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
        $data['active_app']        = $this->_get_active_app();
        $data['active_project_id'] = $this->fcm_v1_client->get_project_id();
        $data['test_devices']      = $this->Push_notification_model->get_test_devices($data['active_app']);
        
        if ($data['active_app'] === 'nitin') {
            $n_dry = $this->Push_notification_model->get_config('nitin_dry_run_mode');
            $data['dry_run'] = ($n_dry !== null) ? (int)$n_dry : 1;
        } else {
            $data['dry_run'] = (int)$this->Push_notification_model->get_config('dry_run_mode');
        }

        $data['has_creds']         = $this->fcm_v1_client->has_valid_credentials();
        $default_topic             = $this->Push_notification_model->get_config('fcm_topic');
        $data['default_topic']     = !empty($default_topic) ? $default_topic : 'all_users';

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('push_notification/test_send', $data);
        $this->load->view('footer');
    }

    /**
     * Handle Test Notification Dispatch
     */
    public function do_test_send() {
        $target_app  = $this->input->post('target_app');
        if (!empty($target_app) && in_array($target_app, array('chirag', 'nitin'))) {
            $this->fcm_v1_client->set_profile($target_app);
        } else {
            $target_app = $this->_get_active_app();
        }

        $target_type = $this->input->post('target_type'); // 'TOKEN' or 'TOPIC'
        $destination = trim($this->input->post('destination'));
        $title       = trim($this->input->post('title'));
        $body        = trim($this->input->post('body'));
        $cat_id      = (int)$this->input->post('cat_id');
        $image_url   = trim($this->input->post('image_url'));
        $force_live   = $this->input->post('force_live') ? 1 : 0;
        
        if ($target_app === 'nitin') {
            $n_dry = $this->Push_notification_model->get_config('nitin_dry_run_mode');
            $system_dry = ($n_dry !== null) ? (int)$n_dry : 1;
        } else {
            $system_dry = (int)$this->Push_notification_model->get_config('dry_run_mode');
        }
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

        $data['active_app']        = $this->_get_active_app();
        $data['active_project_id'] = $this->fcm_v1_client->get_project_id();
        $data['stats'] = $this->Push_notification_model->get_stats($data['active_app']);
        $data['logs']  = $this->Push_notification_model->get_logs($limit, $offset, $data['active_app']);
        $data['total'] = $this->Push_notification_model->get_logs_count($data['active_app']);
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
        $optimal_7_slots = '08:00,10:30,12:30,15:00,17:30,19:30,21:30';
        $current_slots = $this->Push_notification_model->get_config('daily_slots');
        if (empty($current_slots) || $current_slots === '09:00,13:00,17:00,20:00') {
            $days = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
            $weekly = array();
            foreach ($days as $d) {
                $weekly[$d] = $optimal_7_slots;
            }
            $this->Push_notification_model->set_config('daily_slots', $optimal_7_slots);
            $this->Push_notification_model->set_config('weekly_schedule', json_encode($weekly));
        }

        $configs = $this->Push_notification_model->get_config();
        $data['active_app']        = $this->_get_active_app();
        $data['active_project_id'] = $this->fcm_v1_client->get_project_id();
        $data['configs'] = array_merge(array(
            'daily_slots'        => $optimal_7_slots,
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
        $topic       = trim($this->input->post('fcm_topic'));
        $dry_run     = $this->input->post('dry_run_mode') ? '1' : '0';
        $is_paused   = $this->input->post('is_campaign_paused') ? '1' : '0';
        $max_retries = (int)$this->input->post('max_retries');

        // Parse Sunday to Saturday schedule (RTO-style comma separated or dynamic array)
        $days = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
        $weekly = array();
        foreach ($days as $d) {
            $w_val = trim((string)$this->input->post('weekly_' . $d));
            if (!empty($w_val)) {
                $parts = array_map('trim', explode(',', $w_val));
                $clean_p = array();
                foreach ($parts as $p) {
                    $p = trim($p);
                    if (preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $p)) {
                        $sp = explode(':', $p);
                        $clean_p[] = sprintf('%02d:%02d', (int)$sp[0], (int)$sp[1]);
                    }
                }
                $clean_p = array_values(array_unique($clean_p));
                sort($clean_p);
                $weekly[$d] = !empty($clean_p) ? implode(',', $clean_p) : '09:00,11:00,13:00,15:00,17:00,19:00,21:00';
            } else {
                $day_slots = $this->input->post('slots_' . $d);
                if (is_array($day_slots)) {
                    $clean_day = array();
                    foreach ($day_slots as $st) {
                        $st = trim((string)$st);
                        if (preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $st)) {
                            $sp = explode(':', $st);
                            $clean_day[] = sprintf('%02d:%02d', (int)$sp[0], (int)$sp[1]);
                        }
                    }
                    $clean_day = array_values(array_unique($clean_day));
                    sort($clean_day);
                    $weekly[$d] = !empty($clean_day) ? implode(',', $clean_day) : '09:00,11:00,13:00,15:00,17:00,19:00,21:00';
                } else {
                    $weekly[$d] = '09:00,11:00,13:00,15:00,17:00,19:00,21:00';
                }
            }
        }

        $daily_slots_val = !empty($weekly['monday']) ? $weekly['monday'] : '09:00,11:00,13:00,15:00,17:00,19:00,21:00';

        $configs = array(
            'daily_slots'        => $daily_slots_val,
            'weekly_schedule'    => json_encode($weekly),
            'fcm_topic'          => $topic ? $topic : 'all_users',
            'dry_run_mode'       => $dry_run,
            'is_campaign_paused' => $is_paused,
            'max_retries'        => $max_retries > 0 ? (string)$max_retries : '3'
        );

        $this->Push_notification_model->set_multiple_configs($configs);
        $this->session->set_flashdata('success', 'Settings and weekly schedule updated successfully.');
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
     * One-click toggle for Emergency Pause (App-Specific)
     */
    public function toggle_pause() {
        $active_app = $this->_get_active_app();
        $config_key = ($active_app === 'nitin') ? 'nitin_is_campaign_paused' : 'is_campaign_paused';
        $current_val = $this->Push_notification_model->get_config($config_key);
        $current = ($current_val !== null) ? (int)$current_val : 0;
        $new_val = ($current === 1) ? '0' : '1';
        $this->Push_notification_model->set_config($config_key, $new_val);
        $app_name = ($active_app === 'nitin') ? 'Nitin Sir App' : 'Chirag Sir App';
        $this->session->set_flashdata('success', ($new_val === '1') ? "Automated campaign PAUSED for {$app_name}." : "Automated campaign RESUMED for {$app_name}.");
        redirect('PushNotification');
    }

    /**
     * One-click toggle for Dry-Run Mode (App-Specific)
     */
    public function toggle_dry_run() {
        $active_app = $this->_get_active_app();
        $config_key = ($active_app === 'nitin') ? 'nitin_dry_run_mode' : 'dry_run_mode';
        $current_val = $this->Push_notification_model->get_config($config_key);
        if ($current_val === null) {
            $current = ($active_app === 'nitin') ? 1 : 0;
        } else {
            $current = (int)$current_val;
        }
        $new_val = ($current === 1) ? '0' : '1';
        $this->Push_notification_model->set_config($config_key, $new_val);
        $app_name = ($active_app === 'nitin') ? 'Nitin Sir App' : 'Chirag Sir App';
        $this->session->set_flashdata('success', ($new_val === '1') ? "Safety DRY-RUN mode ENABLED for {$app_name}." : "LIVE transmission mode ENABLED for {$app_name}.");
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

        $active_app = $this->_get_active_app();

        if (empty($slot_name)) {
            // Pick current nearest slot
            $hour = (int)date('H');
            if ($hour < 11) $slot_name = '09:00';
            elseif ($hour < 15) $slot_name = '13:00';
            elseif ($hour < 19) $slot_name = '17:00';
            else $slot_name = '20:00';
        }

        if ($active_app === 'nitin') {
            $n_dry = $this->Push_notification_model->get_config('nitin_dry_run_mode');
            $dry_run = ($n_dry !== null) ? (int)$n_dry : 1;
        } else {
            $dry_run = (int)$this->Push_notification_model->get_config('dry_run_mode');
        }
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
        if ($draft_id) {
            redirect('PushNotification/edit/' . (int)$draft_id);
            return;
        }

        $data['active_app']        = $this->_get_active_app();
        $data['active_project_id'] = $this->fcm_v1_client->get_project_id();
        $data['categories']   = $this->db->select('cat_id, cat_name')->order_by('cat_name', 'ASC')->get('category')->result_array();
        $data['test_devices'] = $this->Push_notification_model->get_test_devices($data['active_app']);
        $data['total_active_tokens'] = $this->Push_notification_model->get_registered_devices_count(true);
        if ($data['active_app'] === 'nitin') {
            $n_dry = $this->Push_notification_model->get_config('nitin_dry_run_mode');
            $data['dry_run'] = ($n_dry !== null) ? (int)$n_dry : 1;
        } else {
            $data['dry_run'] = (int)$this->Push_notification_model->get_config('dry_run_mode');
        }
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
        if ($this->db->field_exists('app_code', 'push_manual_campaigns')) {
            $campaign_data['app_code'] = $this->_get_active_app();
        }

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
        $target_app   = $this->input->post('target_app');
        if (empty($target_app) || !in_array($target_app, array('chirag', 'nitin', 'both'))) {
            $target_app = $this->_get_active_app();
        }
        $force_live   = $this->input->post('force_live') ? 1 : 0;
        if ($target_app === 'nitin') {
            $n_dry = $this->Push_notification_model->get_config('nitin_dry_run_mode');
            $system_dry = ($n_dry !== null) ? (int)$n_dry : 1;
        } else {
            $system_dry = (int)$this->Push_notification_model->get_config('dry_run_mode');
        }
        
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

        // Handle Scheduled Date & Time Delivery
        $delivery_mode = $this->input->post('delivery_mode');
        if ($delivery_mode === 'SCHEDULE') {
            $sched_date = trim($this->input->post('scheduled_date'));
            $sched_time = trim($this->input->post('scheduled_time'));
            if (empty($sched_date) || empty($sched_time)) {
                $this->session->set_flashdata('error', 'Please select both Date and Time to schedule the notification.');
                redirect('PushNotification/composer');
                return;
            }

            $sched_ts = strtotime("{$sched_date} {$sched_time}:00");
            if (!$sched_ts) {
                $this->session->set_flashdata('error', 'Invalid scheduled date or time.');
                redirect('PushNotification/composer');
                return;
            }

            if ($sched_ts < (time() - 120)) {
                $this->session->set_flashdata('error', 'Scheduled date and time must be in the future (Asia/Kolkata).');
                redirect('PushNotification/composer');
                return;
            }

            $scheduled_at = date('Y-m-d H:i:s', $sched_ts);

            $campaign_data = array(
                'title'                    => $title,
                'message'                  => $message,
                'image_path'               => $image_info['path'],
                'image_url'                => $image_info['url'],
                'target_type'              => $target_type,
                'target_value'             => $target_value,
                'target_screen'            => 'category_detail',
                'cat_id'                   => $cat_id ? $cat_id : 10,
                'status'                   => 'SCHEDULED',
                'scheduled_at'             => $scheduled_at,
                'is_dry_run'               => $is_dry_run,
                'targeted_count'           => 0,
                'accepted_count'           => 0,
                'confirmed_delivery_count' => 0,
                'open_count'               => 0,
                'created_by'               => $this->session->userdata('poster_sess_user_name') ? $this->session->userdata('poster_sess_user_name') : 'admin',
                'app_code'                 => $target_app
            );

            $campaign_id = $this->Push_notification_model->create_manual_campaign($campaign_data);
            $formatted_time = date('d M Y, h:i A', $sched_ts);
            $this->session->set_flashdata('success', "✓ Notification successfully SCHEDULED for {$formatted_time} (Campaign #{$campaign_id})! It will be automatically dispatched at that exact time.");
            redirect('PushNotification/campaigns');
            return;
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
        if ($this->db->field_exists('app_code', 'push_manual_campaigns')) {
            $campaign_data['app_code'] = $target_app;
        }

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

            $apps_to_dispatch = ($target_app === 'both') ? array('chirag', 'nitin') : array($target_app);
            $result = array('success' => false, 'http_status' => 500, 'fcm_message_id' => null, 'execution_time_ms' => 0, 'error' => 'No apps dispatched');

            foreach ($apps_to_dispatch as $app_code) {
                $this->fcm_v1_client->set_profile($app_code);
                $app_name_tag = ($app_code === 'nitin') ? '[Nitin Sir App]' : '[Chirag Sir App]';

                $app_sys_dry = ($app_code === 'nitin')
                    ? (($this->Push_notification_model->get_config('nitin_dry_run_mode') !== null) ? (int)$this->Push_notification_model->get_config('nitin_dry_run_mode') : 1)
                    : (int)$this->Push_notification_model->get_config('dry_run_mode');
                $app_dry_run = ($app_sys_dry === 0 || $force_live === 1) ? 0 : 1;

                $app_devices = $this->Push_notification_model->get_registered_devices_count(true, $app_code);
                if ($app_devices <= 0) {
                    $app_devices = ($app_code === 'nitin') ? 1 : 23;
                }

                $res = $this->fcm_v1_client->send('TOPIC', $target_value, $content, (bool)$app_dry_run);
                $sent_count   = $res['success'] ? $app_devices : 0;
                $failed_count = $res['success'] ? 0 : $app_devices;

                $this->Push_notification_model->record_manual_campaign_result($campaign_id, $res, 'TOPIC', $target_value . " ({$app_code})", $app_devices, $app_dry_run);
                
                // Log entry
                $this->db->insert('push_notification_logs', array(
                    'bank_id'                  => 0,
                    'campaign_id'              => $campaign_id,
                    'title'                    => "{$app_name_tag} {$title}",
                    'target_type'              => 'TOPIC',
                    'target_destination'       => "{$target_value} ({$app_code})",
                    'http_status'              => $res['http_status'],
                    'fcm_message_id'           => $res['fcm_message_id'],
                    'response_body'            => is_string($res['response_body']) ? $res['response_body'] : json_encode($res['response_body']),
                    'sent_count'               => $sent_count,
                    'failed_count'             => $failed_count,
                    'confirmed_delivery_count' => 0,
                    'click_count'              => 0,
                    'is_dry_run'               => $app_dry_run,
                    'execution_time_ms'        => $res['execution_time_ms'],
                    'created_at'               => date('Y-m-d H:i:s')
                ));

                if ($res['success']) {
                    $result = $res;
                } else if (!$result['success']) {
                    $result = $res;
                }
            }
            $this->Push_notification_model->update_manual_campaign($campaign_id, array('accepted_count' => $sent_count));

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

        $data['active_app']        = $this->_get_active_app();
        $data['active_project_id'] = $this->fcm_v1_client->get_project_id();
        $data['campaigns'] = $this->Push_notification_model->get_manual_campaigns($limit, $offset, $filters, $data['active_app']);
        $data['total']     = $this->Push_notification_model->get_manual_campaigns_count($filters, $data['active_app']);
        $data['page']      = $page;
        $data['pages']     = ceil($data['total'] / $limit);
        $data['filters']   = $filters;
        if ($data['active_app'] === 'nitin') {
            $n_dry = $this->Push_notification_model->get_config('nitin_dry_run_mode');
            $data['dry_run'] = ($n_dry !== null) ? (int)$n_dry : 1;
        } else {
            $data['dry_run'] = (int)$this->Push_notification_model->get_config('dry_run_mode');
        }

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

    /**
     * Instantly dispatches a SCHEDULED campaign
     */
    public function send_scheduled_now($id) {
        $camp = $this->Push_notification_model->get_manual_campaign($id);
        if (!$camp || $camp['status'] !== 'SCHEDULED') {
            $this->session->set_flashdata('error', 'Campaign not found or not in SCHEDULED status.');
            redirect('PushNotification/campaigns');
            return;
        }

        $this->db->where('id', (int)$id)->update('push_manual_campaigns', array('scheduled_at' => date('Y-m-d H:i:s')));
        $this->Push_notification_model->check_and_dispatch_scheduled_campaigns();

        $this->session->set_flashdata('success', "Scheduled Notification #{$id} has been dispatched immediately!");
        redirect('PushNotification/campaigns');
    }

    /**
     * Cancel a SCHEDULED campaign
     */
    public function cancel_scheduled($id) {
        $camp = $this->Push_notification_model->get_manual_campaign($id);
        if (!$camp || $camp['status'] !== 'SCHEDULED') {
            $this->session->set_flashdata('error', 'Campaign not found or not in SCHEDULED status.');
            redirect('PushNotification/campaigns');
            return;
        }

        $this->Push_notification_model->update_manual_campaign($id, array('status' => 'CANCELLED'));
        $this->session->set_flashdata('success', "Scheduled Notification #{$id} has been CANCELLED.");
        redirect('PushNotification/campaigns');
    }

    /**
     * Delete a campaign (Draft, Cancelled, Scheduled, or Paused)
     */
    public function delete_campaign($id) {
        $id = (int)$id;
        $campaign = $this->Push_notification_model->get_manual_campaign($id);
        if (!$campaign) {
            $this->session->set_flashdata('error', 'Campaign not found.');
            $ref = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('PushNotification/campaigns');
            redirect($ref);
            return;
        }

        $this->db->where('id', $id)->delete('push_manual_campaigns');

        // Also clean up from session if present
        $recent = $this->session->userdata('last_imported_ids');
        if (is_array($recent)) {
            $recent = array_values(array_diff($recent, array($id)));
            $this->session->set_userdata('last_imported_ids', $recent);
        }

        $this->session->set_flashdata('success', "Notification #{$id} has been permanently deleted.");
        $ref = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('PushNotification/campaigns');
        redirect($ref);
    }

    /**
     * Pause a SCHEDULED campaign so it is not dispatched
     */
    public function pause_campaign($id) {
        $id = (int)$id;
        $camp = $this->Push_notification_model->get_manual_campaign($id);
        if (!$camp) {
            $this->session->set_flashdata('error', 'Campaign not found.');
            $ref = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('PushNotification/campaigns');
            redirect($ref);
            return;
        }

        $this->Push_notification_model->update_manual_campaign($id, array('status' => 'PAUSED'));
        $this->session->set_flashdata('success', "⏸️ Notification #{$id} is now PAUSED (will not be dispatched automatically).");
        $ref = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('PushNotification/campaigns');
        redirect($ref);
    }

    /**
     * Resume a PAUSED campaign back to SCHEDULED
     */
    public function resume_campaign($id) {
        $id = (int)$id;
        $camp = $this->Push_notification_model->get_manual_campaign($id);
        if (!$camp) {
            $this->session->set_flashdata('error', 'Campaign not found.');
            $ref = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('PushNotification/campaigns');
            redirect($ref);
            return;
        }

        $this->Push_notification_model->update_manual_campaign($id, array('status' => 'SCHEDULED'));
        $this->session->set_flashdata('success', "▶️ Notification #{$id} is now RESUMED and SCHEDULED for dispatch.");
        $ref = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('PushNotification/campaigns');
        redirect($ref);
    }

    /**
     * Clear the temporary 'Just Imported' box from session
     */
    public function clear_imported_list() {
        $this->session->unset_userdata('last_imported_ids');
        redirect('PushNotification/import_json');
    }

    /**
     * Bulk JSON Notification Import View
     */
    public function import_json() {
        $data['active_app']        = $this->_get_active_app();
        $data['active_project_id'] = $this->fcm_v1_client->get_project_id();
        $data['categories']        = $this->db->select('cat_id, cat_name')->order_by('cat_name', 'ASC')->get('category')->result_array();
        $data['test_devices']      = $this->Push_notification_model->get_test_devices($data['active_app']);
        $data['default_topic']     = $this->Push_notification_model->get_config('fcm_topic') ?: 'all_users';

        if ($data['active_app'] === 'nitin') {
            $n_dry = $this->Push_notification_model->get_config('nitin_dry_run_mode');
            $data['dry_run'] = ($n_dry !== null) ? (int)$n_dry : 1;
        } else {
            $data['dry_run'] = (int)$this->Push_notification_model->get_config('dry_run_mode');
        }

        // Fetch recently imported notifications (persist across requests via session)
        $flash_ids = $this->session->flashdata('recent_imported_ids');
        if (!empty($flash_ids) && is_array($flash_ids)) {
            $this->session->set_userdata('last_imported_ids', $flash_ids);
        }
        $imported_ids = $this->session->userdata('last_imported_ids');

        $data['recent_imported'] = array();
        if (!empty($imported_ids) && is_array($imported_ids)) {
            $data['recent_imported'] = $this->db->where_in('id', $imported_ids)
                                                ->order_by('id', 'DESC')
                                                ->get('push_manual_campaigns')
                                                ->result_array();
        }

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('push_notification/import_json', $data);
        $this->load->view('footer');
    }

    /**
     * Process JSON Upload / Direct Input and bulk insert scheduled notifications
     */
    public function do_import_json() {
        $json_raw = '';

        // 1. Check if a .json file was uploaded
        if (!empty($_FILES['json_file']['tmp_name']) && is_uploaded_file($_FILES['json_file']['tmp_name'])) {
            $json_raw = file_get_contents($_FILES['json_file']['tmp_name']);
        } elseif ($this->input->post('json_content')) {
            $json_raw = trim($this->input->post('json_content'));
        }

        if (empty($json_raw)) {
            $this->session->set_flashdata('error', 'Please upload a .json file or paste your JSON content.');
            redirect('PushNotification/import_json');
            return;
        }

        // Clean UTF-8 BOM if present
        $json_raw = preg_replace('/^\xEF\xBB\xBF/', '', $json_raw);
        $items = json_decode($json_raw, true);

        if ($items === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->session->set_flashdata('error', 'Invalid JSON syntax: ' . json_last_error_msg() . '. Please verify your JSON format.');
            redirect('PushNotification/import_json');
            return;
        }

        // Handle case where root object wraps items: {"notifications": [...]}, {"data": [...]}, {"items": [...]}
        if (is_array($items) && !isset($items[0])) {
            foreach (array('notifications', 'data', 'items', 'campaigns') as $wrapper_key) {
                if (isset($items[$wrapper_key]) && is_array($items[$wrapper_key])) {
                    $items = $items[$wrapper_key];
                    break;
                }
            }
            // If it's a single notification object
            if (is_array($items) && !isset($items[0]) && (isset($items['title']) || isset($items['titel']))) {
                $items = array($items);
            }
        }

        if (!is_array($items) || empty($items)) {
            $this->session->set_flashdata('error', 'No notification items found in JSON. Format must be an array of notification objects.');
            redirect('PushNotification/import_json');
            return;
        }

        $default_app    = $this->input->post('default_app') ?: $this->_get_active_app();
        $default_cat_id = (int)$this->input->post('default_cat_id') ?: 10;
        $default_target = $this->input->post('target_type') ?: 'TOPIC';
        $default_topic  = $this->input->post('target_value') ?: ($this->Push_notification_model->get_config('fcm_topic') ?: 'all_users');
        $force_live     = $this->input->post('force_live') ? 1 : 0;
        $system_dry     = (int)$this->Push_notification_model->get_config('dry_run_mode');
        $is_dry_run     = ($system_dry === 0 || $force_live === 1) ? 0 : 1;
        $admin_user     = $this->session->userdata('poster_sess_user_name') ?: 'admin';

        $imported_count = 0;
        $imported_ids   = array();
        $skipped_count  = 0;
        $errors         = array();

        $this->Push_notification_model->ensure_scheduled_campaign_support();

        foreach ($items as $idx => $row) {
            if (!is_array($row)) {
                $skipped_count++;
                continue;
            }

            // Extract Title (flexible keys: title, titel, heading, name)
            $title = '';
            foreach (array('title', 'titel', 'heading', 'name') as $tk) {
                if (!empty($row[$tk])) {
                    $title = trim((string)$row[$tk]);
                    break;
                }
            }

            // Extract Description (flexible keys: description, descipatin, desc, message, body, msg, text)
            $message = '';
            foreach (array('description', 'descipatin', 'desc', 'message', 'body', 'msg', 'details', 'text') as $mk) {
                if (!empty($row[$mk])) {
                    $message = trim((string)$row[$mk]);
                    break;
                }
            }

            if (empty($title) || empty($message)) {
                $skipped_count++;
                $errors[] = "Item #" . ($idx + 1) . " skipped: Title and Description are required.";
                continue;
            }

            // Extract Time / Scheduled At (flexible keys: time, scheduled_at, scheduled_time, datetime, date_time, slot)
            $time_raw = '';
            foreach (array('time', 'scheduled_at', 'scheduled_time', 'datetime', 'date_time', 'slot') as $tk) {
                if (!empty($row[$tk])) {
                    $time_raw = trim((string)$row[$tk]);
                    break;
                }
            }

            // If date is provided in separate field: "date": "2026-11-05", "time": "10:00"
            $date_raw = !empty($row['date']) ? trim((string)$row['date']) : '';
            if (!empty($date_raw) && !empty($time_raw) && !strpos($time_raw, '-')) {
                $time_raw = "{$date_raw} {$time_raw}";
            } elseif (!empty($date_raw) && empty($time_raw)) {
                $time_raw = "{$date_raw} 09:00:00";
            }

            $scheduled_at = null;

            if (!empty($time_raw)) {
                // If only time is provided (e.g. "10:00", "10:30 AM", "18:45")
                if (preg_match('/^\d{1,2}:\d{2}(:\d{2})?(\s*(AM|PM|am|pm))?$/', $time_raw)) {
                    $today_ts = strtotime(date('Y-m-d') . ' ' . $time_raw);
                    // If this time today has passed, schedule for tomorrow
                    if ($today_ts < time()) {
                        $scheduled_at = date('Y-m-d H:i:s', strtotime('+1 day', $today_ts));
                    } else {
                        $scheduled_at = date('Y-m-d H:i:s', $today_ts);
                    }
                } elseif (is_numeric($time_raw) && (int)$time_raw > 1500000000) {
                    // Unix timestamp
                    $scheduled_at = date('Y-m-d H:i:s', (int)$time_raw);
                } else {
                    $parsed_ts = strtotime($time_raw);
                    if ($parsed_ts) {
                        $scheduled_at = date('Y-m-d H:i:s', $parsed_ts);
                    } else {
                        $scheduled_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
                    }
                }
            } else {
                $scheduled_at = date('Y-m-d H:i:s', strtotime('+2 hours'));
            }

            // Optional image URL
            $image_url = null;
            foreach (array('image', 'image_url', 'banner', 'photo', 'img') as $ik) {
                if (!empty($row[$ik])) {
                    $image_url = trim((string)$row[$ik]);
                    break;
                }
            }

            // Optional target app
            $item_app = $default_app;
            foreach (array('target_app', 'app', 'app_code') as $ak) {
                if (!empty($row[$ak]) && in_array(strtolower($row[$ak]), array('chirag', 'nitin', 'both'))) {
                    $item_app = strtolower($row[$ak]);
                    break;
                }
            }

            // Optional cat_id
            $item_cat_id = $default_cat_id;
            if (!empty($row['cat_id']) || !empty($row['category_id'])) {
                $item_cat_id = (int)(!empty($row['cat_id']) ? $row['cat_id'] : $row['category_id']);
            }

            $campaign_data = array(
                'title'                    => $title,
                'message'                  => $message,
                'image_path'               => null,
                'image_url'                => $image_url,
                'target_type'              => $default_target,
                'target_value'             => $default_topic,
                'target_screen'            => 'category_detail',
                'cat_id'                   => $item_cat_id,
                'status'                   => 'SCHEDULED',
                'scheduled_at'             => $scheduled_at,
                'is_dry_run'               => $is_dry_run,
                'targeted_count'           => 0,
                'accepted_count'           => 0,
                'confirmed_delivery_count' => 0,
                'open_count'               => 0,
                'created_by'               => $admin_user,
                'app_code'                 => $item_app
            );

            $new_id = $this->Push_notification_model->create_manual_campaign($campaign_data);
            if ($new_id) {
                $imported_count++;
                $imported_ids[] = $new_id;
            }
        }

        if ($imported_count > 0) {
            $msg = "✓ Successfully imported and scheduled {$imported_count} notification(s)!";
            if ($skipped_count > 0) {
                $msg .= " ({$skipped_count} item(s) skipped due to missing fields).";
            }
            $this->session->set_flashdata('success', $msg);
            $this->session->set_flashdata('recent_imported_ids', $imported_ids);
            $this->session->set_userdata('last_imported_ids', $imported_ids);
        } else {
            $this->session->set_flashdata('error', 'No notifications were imported. ' . implode(' ', $errors));
        }

        redirect('PushNotification/import_json');
    }

    /**
     * Edit Scheduled Notification / Draft with Image Upload Option
     */
    public function edit($id) {
        $id = (int)$id;
        $campaign = $this->Push_notification_model->get_manual_campaign($id);
        if (!$campaign) {
            $this->session->set_flashdata('error', "Notification #{$id} not found.");
            redirect('PushNotification/campaigns');
            return;
        }

        $data['active_app']        = $this->_get_active_app();
        $data['active_project_id'] = $this->fcm_v1_client->get_project_id();
        $data['campaign']          = $campaign;
        $data['categories']        = $this->db->select('cat_id, cat_name')->order_by('cat_name', 'ASC')->get('category')->result_array();
        $data['test_devices']      = $this->Push_notification_model->get_test_devices($data['active_app']);
        $data['total_active_tokens']= $this->Push_notification_model->get_registered_devices_count(true);
        $data['default_topic']     = $this->Push_notification_model->get_config('fcm_topic') ?: 'all_users';

        if ($data['active_app'] === 'nitin') {
            $n_dry = $this->Push_notification_model->get_config('nitin_dry_run_mode');
            $data['dry_run'] = ($n_dry !== null) ? (int)$n_dry : 1;
        } else {
            $data['dry_run'] = (int)$this->Push_notification_model->get_config('dry_run_mode');
        }

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('push_notification/edit', $data);
        $this->load->view('footer');
    }

    /**
     * Update Scheduled Notification or Draft (Title, Message, Image Upload, Scheduled Time)
     */
    public function update_campaign($id) {
        $id = (int)$id;
        $campaign = $this->Push_notification_model->get_manual_campaign($id);
        if (!$campaign) {
            $this->session->set_flashdata('error', "Notification #{$id} not found.");
            redirect('PushNotification/campaigns');
            return;
        }

        $title   = trim($this->input->post('title'));
        $message = trim($this->input->post('message'));
        $cat_id  = (int)$this->input->post('cat_id');
        $target_app = $this->input->post('target_app') ?: $this->_get_active_app();

        if (empty($title) || empty($message)) {
            $this->session->set_flashdata('error', 'Notification Title and Description are required.');
            redirect('PushNotification/edit/' . $id);
            return;
        }

        // Image Handling
        $remove_image = $this->input->post('remove_image') ? 1 : 0;
        $current_image_path = $campaign['image_path'];
        $current_image_url  = $campaign['image_url'];

        if ($remove_image) {
            $current_image_path = null;
            $current_image_url  = null;
        } else {
            // Check if new file was uploaded or direct image URL entered
            $image_info = $this->handle_image_upload();
            if (!empty($image_info['url'])) {
                $current_image_url  = $image_info['url'];
                $current_image_path = $image_info['path'];
            }
        }

        $target_type  = $this->input->post('target_type') ?: 'TOPIC';
        $target_value = trim($this->input->post('target_value')) ?: 'all_users';
        if ($target_type === 'CUSTOM_TOKEN') {
            $c_tok = trim($this->input->post('custom_token'));
            if (!empty($c_tok)) $target_value = $c_tok;
        }

        $delivery_mode = $this->input->post('delivery_mode');
        $status        = $campaign['status'];
        $scheduled_at  = $campaign['scheduled_at'];

        if ($delivery_mode === 'SCHEDULE') {
            $s_date = trim($this->input->post('scheduled_date'));
            $s_time = trim($this->input->post('scheduled_time'));
            if (!empty($s_date) && !empty($s_time)) {
                $ts = strtotime("{$s_date} {$s_time}:00");
                if ($ts) {
                    $scheduled_at = date('Y-m-d H:i:s', $ts);
                    $status = 'SCHEDULED';
                }
            }
        } elseif ($delivery_mode === 'PAUSED') {
            $status = 'PAUSED';
            $s_date = trim($this->input->post('scheduled_date'));
            $s_time = trim($this->input->post('scheduled_time'));
            if (!empty($s_date) && !empty($s_time)) {
                $ts = strtotime("{$s_date} {$s_time}:00");
                if ($ts) {
                    $scheduled_at = date('Y-m-d H:i:s', $ts);
                }
            }
        } elseif ($delivery_mode === 'DRAFT') {
            $status = 'DRAFT';
        }

        $force_live = $this->input->post('force_live') ? 1 : 0;
        $system_dry = (int)$this->Push_notification_model->get_config('dry_run_mode');
        $is_dry_run = ($system_dry === 0 || $force_live === 1) ? 0 : 1;

        $update_data = array(
            'title'         => $title,
            'message'       => $message,
            'image_path'    => $current_image_path,
            'image_url'     => $current_image_url,
            'target_type'   => $target_type,
            'target_value'  => $target_value,
            'cat_id'        => $cat_id ?: 10,
            'status'        => $status,
            'scheduled_at'  => $scheduled_at,
            'is_dry_run'    => $is_dry_run,
            'app_code'      => $target_app
        );

        $this->Push_notification_model->update_manual_campaign($id, $update_data);

        // If user clicked "Send Immediately Now"
        if ($delivery_mode === 'NOW') {
            $this->session->set_flashdata('success', "Notification #{$id} updated. Dispatching now...");
            redirect('PushNotification/send_scheduled_now/' . $id);
            return;
        }

        $this->session->set_flashdata('success', "✓ Notification #{$id} has been successfully updated!");
        $redirect_to = $this->input->post('redirect_ref') ? $this->input->post('redirect_ref') : base_url('PushNotification/campaigns');
        redirect($redirect_to);
    }
}