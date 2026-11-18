<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RtoPushNotification extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');

        // Verify Admin Login Session
        if (!$this->session->userdata('poster_sess_user_name')) {
            redirect('admin');
        }

        $this->load->model('Rto_push_notification_model');
        $this->load->library('Rto_fcm_v1_client');

        if (isset($this->db)) {
            $this->db->simple_query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
        }
    }

    /**
     * Dashboard Overview
     */
    public function index() {
        // Virtual cron fallback: if a slot is due and pending, dispatch it
        $this->Rto_push_notification_model->check_and_dispatch_due_slot();

        $data['stats']       = $this->Rto_push_notification_model->get_stats();
        $data['recent_logs'] = $this->Rto_push_notification_model->get_logs(10, 0);
        $data['has_creds']   = $this->rto_fcm_v1_client->has_valid_credentials();
        $data['creds_path']  = $this->rto_fcm_v1_client->get_credentials_path();

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('rto_push_notification/dashboard', $data);
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

        $data['items']   = $this->Rto_push_notification_model->get_bank_items($filters, $limit, $offset);
        $data['total']   = $this->Rto_push_notification_model->get_bank_count($filters);
        $data['page']    = $page;
        $data['pages']   = ceil($data['total'] / $limit);
        $data['filters'] = $filters;

        // Categories list for dropdown
        $data['categories'] = $this->db->select('category_name, COUNT(*) as count')
                                       ->group_by('category_name')
                                       ->get('rto_push_notification_bank')
                                       ->result_array();

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('rto_push_notification/bank', $data);
        $this->load->view('footer');
    }

    /**
     * Batch Approve Drafts
     */
    public function approve_batch() {
        $count = (int)$this->input->post('count');
        $cat   = $this->input->post('category_name');
        if ($count <= 0) $count = 100;

        $approved = $this->Rto_push_notification_model->approve_next_batch($count, $cat);
        $this->session->set_flashdata('success', "Successfully approved {$approved} RTO drafts for automated scheduling.");
        redirect('RtoPushNotification/bank?status=APPROVED');
    }

    /**
     * One-click approval for all drafts in the bank
     */
    public function approve_all_drafts() {
        $this->db->where('status !=', 'APPROVED')->update('rto_push_notification_bank', array('status' => 'APPROVED'));
        $count = $this->db->affected_rows();
        $this->session->set_flashdata('success', "All {$count} drafts in RTO Content Bank have been APPROVED!");
        redirect('RtoPushNotification/bank?status=APPROVED');
    }

    /**
     * Toggle status for a single draft (DRAFT / APPROVED)
     */
    public function update_status() {
        $id     = (int)$this->input->post('id');
        $status = $this->input->post('status');

        if ($id && in_array($status, array('DRAFT', 'APPROVED', 'FAILED'))) {
            $this->Rto_push_notification_model->update_item($id, array('status' => $status));
            echo json_encode(array('success' => true));
            return;
        }
        echo json_encode(array('success' => false));
    }

    /**
     * Test Notification Interface
     */
    public function test_send() {
        $data['test_devices']  = $this->Rto_push_notification_model->get_test_devices();
        $data['dry_run']       = (int)$this->Rto_push_notification_model->get_config('dry_run_mode');
        $data['has_creds']     = $this->rto_fcm_v1_client->has_valid_credentials();
        $data['default_topic'] = $this->Rto_push_notification_model->get_config('fcm_topic') ?: 'all_rto_users';

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('rto_push_notification/test_send', $data);
        $this->load->view('footer');
    }

    /**
     * Handle Test Notification Dispatch
     */
    public function do_test_send() {
        $target_type   = $this->input->post('target_type'); // 'TOKEN' or 'TOPIC'
        $destination   = trim($this->input->post('destination'));
        $title         = trim($this->input->post('title'));
        $body          = trim($this->input->post('body'));
        $target_screen = trim($this->input->post('target_screen') ?: 'EXAM');
        $image_url     = trim($this->input->post('image_url'));
        $force_live    = $this->input->post('force_live') ? 1 : 0;
        $system_dry    = (int)$this->Rto_push_notification_model->get_config('dry_run_mode');
        $is_dry_send   = ($system_dry === 0 || $force_live === 1) ? false : true;

        if (empty($destination) || empty($title) || empty($body)) {
            $this->session->set_flashdata('error', 'Destination, Title, and Message Body are required.');
            redirect('RtoPushNotification/test_send');
            return;
        }

        $content = array(
            'id'            => 'RTO_TEST_' . time(),
            'title'         => $title,
            'body'          => $body,
            'category_name' => 'RTO Test Notification',
            'target_screen' => $target_screen,
            'image_url'     => !empty($image_url) ? $image_url : null
        );

        $result = $this->rto_fcm_v1_client->send($target_type, $destination, $content, $is_dry_send);

        // Record log
        $this->Rto_push_notification_model->log_dispatch(
            0,
            $target_type,
            $destination,
            $result,
            $is_dry_send
        );

        if ($result['success']) {
            $mode_str = $is_dry_send ? '[SAFETY DRY-RUN MODE: Simulated]' : '[LIVE MODE: Sent to Google FCM]';
            $msg_id   = $result['fcm_message_id'] ? " (FCM ID: {$result['fcm_message_id']})" : '';
            $this->session->set_flashdata('success', "{$mode_str} Test push sent successfully!{$msg_id} in {$result['execution_time_ms']}ms");
        } else {
            $err = isset($result['error']) ? $result['error'] : 'Unknown FCM failure';
            $this->session->set_flashdata('error', "Push dispatch failed: {$err}");
        }

        redirect('RtoPushNotification/test_send');
    }

    /**
     * Manual Campaign Composer
     */
    public function composer() {
        $data['dry_run']       = (int)$this->Rto_push_notification_model->get_config('dry_run_mode');
        $data['default_topic'] = $this->Rto_push_notification_model->get_config('fcm_topic') ?: 'all_rto_users';
        $data['test_devices']  = $this->Rto_push_notification_model->get_test_devices();

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('rto_push_notification/composer', $data);
        $this->load->view('footer');
    }

    /**
     * Save & Dispatch Manual Broadcast Campaign
     */
    public function do_compose() {
        $title         = trim($this->input->post('title'));
        $message       = trim($this->input->post('message'));
        $target_type   = $this->input->post('target_type') ?: 'TOPIC';
        $target_value  = trim($this->input->post('target_value') ?: 'all_rto_users');
        $target_screen = trim($this->input->post('target_screen') ?: 'EXAM');
        $image_url     = trim($this->input->post('image_url'));
        $force_live    = $this->input->post('force_live') ? 1 : 0;
        $system_dry    = (int)$this->Rto_push_notification_model->get_config('dry_run_mode');
        $is_dry        = ($system_dry === 0 || $force_live === 1) ? 0 : 1;

        if (empty($title) || empty($message)) {
            $this->session->set_flashdata('error', 'Title and Message are required.');
            redirect('RtoPushNotification/composer');
            return;
        }

        $campaign_uid = hash('sha256', $title . '|' . $message . '|' . microtime(true));

        $campaign_data = array(
            'campaign_uid'   => $campaign_uid,
            'title'          => $title,
            'message'        => $message,
            'image_url'      => !empty($image_url) ? $image_url : null,
            'target_type'    => $target_type,
            'target_value'   => $target_value,
            'target_screen'  => $target_screen,
            'status'         => 'PROCESSING',
            'is_dry_run'     => $is_dry,
            'created_by'     => $this->session->userdata('poster_sess_user_name') ?: 'admin'
        );

        $campaign_id = $this->Rto_push_notification_model->create_campaign($campaign_data);
        $result      = $this->Rto_push_notification_model->dispatch_manual_campaign($campaign_id);

        if ($result && $result['success']) {
            $mode_str = ($is_dry === 1) ? '[SIMULATED DRY-RUN]' : '[LIVE BROADCAST]';
            $this->session->set_flashdata('success', "{$mode_str} RTO broadcast campaign dispatched successfully!");
        } else {
            $err = isset($result['error']) ? $result['error'] : 'Failed to dispatch';
            $this->session->set_flashdata('error', "Campaign dispatch error: {$err}");
        }

        redirect('RtoPushNotification/campaigns');
    }

    /**
     * Manual Broadcast Campaigns History & Telemetry
     */
    public function campaigns() {
        $page   = (int)$this->input->get('page');
        if ($page < 1) $page = 1;
        $limit  = 30;
        $offset = ($page - 1) * $limit;

        $data['campaigns'] = $this->Rto_push_notification_model->get_campaigns($limit, $offset);
        $data['total']     = $this->Rto_push_notification_model->get_campaigns_count();
        $data['page']      = $page;
        $data['pages']     = ceil($data['total'] / $limit);

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('rto_push_notification/campaigns', $data);
        $this->load->view('footer');
    }

    /**
     * Delivery Logs & Telemetry
     */
    public function logs() {
        $page   = (int)$this->input->get('page');
        if ($page < 1) $page = 1;
        $limit  = 50;
        $offset = ($page - 1) * $limit;

        $data['stats'] = $this->Rto_push_notification_model->get_stats();
        $data['logs']  = $this->Rto_push_notification_model->get_logs($limit, $offset);
        $data['total'] = $this->Rto_push_notification_model->get_logs_count();
        $data['page']  = $page;
        $data['pages'] = ceil($data['total'] / $limit);

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('rto_push_notification/logs', $data);
        $this->load->view('footer');
    }

    /**
     * Settings Management
     */
    public function settings() {
        $data['configs']         = $this->Rto_push_notification_model->get_all_configs();
        $data['weekly_schedule'] = $this->Rto_push_notification_model->get_weekly_schedule();
        $data['has_creds']       = $this->rto_fcm_v1_client->has_valid_credentials();
        $data['creds_path']      = $this->rto_fcm_v1_client->get_credentials_path();

        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('rto_push_notification/settings', $data);
        $this->load->view('footer');
    }

    /**
     * Save Settings
     */
    public function save_settings() {
        $topic        = trim($this->input->post('fcm_topic'));
        $project_id   = trim($this->input->post('fcm_project_id') ?: 'rto-app-4365b');
        $sa_file      = trim($this->input->post('service_account_file') ?: 'rto_firebase_service_account.json');
        $dry_run      = $this->input->post('dry_run_mode') ? '1' : '0';
        $is_paused    = $this->input->post('is_campaign_paused') ? '1' : '0';
        $max_retries  = (int)$this->input->post('max_retries');

        $days = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
        $weekly = array();
        foreach ($days as $d) {
            $w_val = trim((string)$this->input->post('weekly_' . $d));
            if (!empty($w_val)) {
                $parts = array_map('trim', explode(',', $w_val));
                $parts = array_values(array_filter($parts));
                $weekly[$d] = implode(',', $parts);
            } else {
                $s1 = trim((string)$this->input->post('slot_' . $d . '_1'));
                $s2 = trim((string)$this->input->post('slot_' . $d . '_2'));
                $s3 = trim((string)$this->input->post('slot_' . $d . '_3'));
                $s4 = trim((string)$this->input->post('slot_' . $d . '_4'));
                
                $slot_parts = array_values(array_filter(array($s1, $s2, $s3, $s4)));
                if (!empty($slot_parts)) {
                    $weekly[$d] = implode(',', $slot_parts);
                } else {
                    $weekly[$d] = '09:00,13:00,17:00,20:00';
                }
            }
        }

        $configs = array(
            'daily_slots'          => !empty($weekly['monday']) ? $weekly['monday'] : '09:00,13:00,17:00,20:00',
            'weekly_schedule'      => json_encode($weekly),
            'fcm_topic'            => $topic ?: 'all_rto_users',
            'fcm_project_id'       => $project_id,
            'service_account_file' => $sa_file,
            'dry_run_mode'         => $dry_run,
            'is_campaign_paused'   => $is_paused,
            'max_retries'          => $max_retries > 0 ? (string)$max_retries : '3'
        );

        $this->Rto_push_notification_model->set_multiple_configs($configs);
        $this->session->set_flashdata('success', 'RTO Notification Settings updated successfully.');
        redirect('RtoPushNotification/settings');
    }

    /**
     * One-click toggle for Emergency Pause
     */
    public function toggle_pause() {
        $current = (int)$this->Rto_push_notification_model->get_config('is_campaign_paused');
        $new_val = ($current === 1) ? '0' : '1';
        $this->Rto_push_notification_model->set_config('is_campaign_paused', $new_val);
        $this->session->set_flashdata('success', ($new_val === '1') ? 'RTO campaign has been PAUSED.' : 'RTO campaign has been RESUMED.');
        redirect('RtoPushNotification');
    }

    /**
     * One-click toggle for Dry-Run Mode
     */
    public function toggle_dry_run() {
        $current = (int)$this->Rto_push_notification_model->get_config('dry_run_mode');
        $new_val = ($current === 1) ? '0' : '1';
        $this->Rto_push_notification_model->set_config('dry_run_mode', $new_val);
        $this->session->set_flashdata('success', ($new_val === '1') ? 'RTO Safety DRY-RUN mode ENABLED.' : 'RTO LIVE transmission mode ENABLED.');
        redirect('RtoPushNotification');
    }

    /**
     * Trigger a slot manually from Dashboard
     */
    public function trigger_auto_slot($slot_name = null) {
        if (empty($slot_name)) {
            $hour = (int)date('H');
            if ($hour < 11) $slot_name = '09:00';
            elseif ($hour < 15) $slot_name = '13:00';
            elseif ($hour < 19) $slot_name = '17:00';
            else $slot_name = '20:00';
        }

        $res = $this->Rto_push_notification_model->dispatch_slot($slot_name);
        if ($res['success']) {
            $this->session->set_flashdata('success', "Slot {$slot_name} triggered successfully!");
        } else {
            $this->session->set_flashdata('error', "Could not dispatch slot {$slot_name}: " . ($res['message'] ?? ($res['error'] ?? 'Unknown')));
        }
        redirect('RtoPushNotification');
    }
}
