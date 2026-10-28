<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class DeviceToken extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Push_notification_model');
        header('Content-Type: application/json; charset=utf-8');
    }

    /**
     * POST /API/V1/DeviceToken/register
     * Registers or updates an Android device FCM token.
     */
    public function register() {
        // Read POST or php://input JSON
        $device_id   = $this->input->post('device_id');
        $fcm_token   = $this->input->post('fcm_token');
        $app_version = $this->input->post('app_version');
        $language    = $this->input->post('language') ? $this->input->post('language') : 'en';
        $is_test     = (int)$this->input->post('is_test_device');

        if (empty($device_id) || empty($fcm_token)) {
            $raw_input = json_decode(file_get_contents('php://input'), true);
            if (is_array($raw_input)) {
                $device_id   = isset($raw_input['device_id']) ? $raw_input['device_id'] : null;
                $fcm_token   = isset($raw_input['fcm_token']) ? $raw_input['fcm_token'] : null;
                $app_version = isset($raw_input['app_version']) ? $raw_input['app_version'] : null;
                $language    = isset($raw_input['language']) ? $raw_input['language'] : 'en';
                $is_test     = isset($raw_input['is_test_device']) ? (int)$raw_input['is_test_device'] : 0;
            }
        }

        if (empty($device_id) || empty($fcm_token)) {
            echo json_encode(array(
                'status'  => 'error',
                'code'    => 400,
                'message' => 'device_id and fcm_token are required parameters.'
            ));
            return;
        }

        $res = $this->Push_notification_model->register_token($device_id, $fcm_token, $app_version, $language, $is_test);

        // Virtual cron trigger: check if an automated push slot is due
        $this->Push_notification_model->check_and_dispatch_due_slot();

        if ($res) {
            echo json_encode(array(
                'status'  => 'success',
                'code'    => 200,
                'message' => 'FCM Token registered successfully.',
                'data'    => array(
                    'device_id'   => $device_id,
                    'is_test'     => $is_test,
                    'registered'  => true
                )
            ));
        } else {
            echo json_encode(array(
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Failed to save token to database.'
            ));
        }
    }

    /**
     * POST /API/V1/DeviceToken/track_notification
     * Receives device delivery receipt (DISPLAYED) or click event (OPENED).
     */
    public function track_notification() {
        $campaign_id = $this->input->post('campaign_id');
        $device_id   = $this->input->post('device_id');
        $event_type  = $this->input->post('event_type');

        if (empty($campaign_id) || empty($device_id) || empty($event_type)) {
            $raw_input = json_decode(file_get_contents('php://input'), true);
            if (is_array($raw_input)) {
                $campaign_id = isset($raw_input['campaign_id']) ? $raw_input['campaign_id'] : null;
                $device_id   = isset($raw_input['device_id']) ? $raw_input['device_id'] : null;
                $event_type  = isset($raw_input['event_type']) ? $raw_input['event_type'] : null;
            }
        }

        if (empty($campaign_id) || empty($device_id) || empty($event_type)) {
            echo json_encode(array(
                'status'  => 'error',
                'code'    => 400,
                'message' => 'campaign_id, device_id, and event_type are required.'
            ));
            return;
        }

        $res = $this->Push_notification_model->record_delivery_event($campaign_id, $device_id, $event_type);

        echo json_encode(array(
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Event tracked successfully.',
            'data'    => array(
                'campaign_id' => (int)$campaign_id,
                'event_type'  => strtoupper($event_type),
                'recorded'    => $res
            )
        ));
    }
}
