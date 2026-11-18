<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * RTO Vehicle App - FCM HTTP v1 Client for CodeIgniter 3
 * Self-contained OAuth2 RS256 JWT Token Generator + Google FCM HTTP v1 API Client.
 * Project: rto-app-4365b
 */
class Rto_fcm_v1_client {

    protected $CI;
    protected $service_account_path;
    protected $project_id;
    protected $access_token = null;
    protected $token_expires_at = 0;
    protected $cache_file;

    public function __construct($config = array()) {
        $this->CI =& get_instance();
        
        $default_path = APPPATH . 'config/secrets/rto_firebase_service_account.json';
        if (!file_exists($default_path)) {
            // Fallback to general service account if rto-specific is not uploaded yet
            $fallback_path = APPPATH . 'config/secrets/firebase_service_account.json';
            if (file_exists($fallback_path)) {
                $default_path = $fallback_path;
            }
        }

        $this->service_account_path = isset($config['service_account_path']) ? $config['service_account_path'] : $default_path;
        $this->project_id           = isset($config['project_id']) ? $config['project_id'] : 'rto-app-4365b';
        $this->cache_file           = APPPATH . 'cache/rto_fcm_oauth_token.json';
    }

    public function configure($config = array()) {
        if (isset($config['service_account_path'])) {
            $this->service_account_path = $config['service_account_path'];
            $this->access_token = null;
            $this->token_expires_at = 0;
        }
        if (isset($config['project_id'])) {
            $this->project_id = $config['project_id'];
        }
        if (isset($config['cache_file'])) {
            $this->cache_file = $config['cache_file'];
        }
        return $this;
    }

    public function has_valid_credentials() {
        if (!file_exists($this->service_account_path)) {
            return false;
        }
        $content = file_get_contents($this->service_account_path);
        $json = json_decode($content, true);
        return is_array($json) && !empty($json['client_email']) && !empty($json['private_key']);
    }

    public function get_credentials_path() {
        return $this->service_account_path;
    }

    public function get_project_id() {
        return $this->project_id;
    }

    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function get_access_token($force_refresh = false) {
        $now = time();

        if (!$force_refresh && $this->access_token && ($this->token_expires_at - 120) > $now) {
            return $this->access_token;
        }

        if (!$force_refresh && file_exists($this->cache_file)) {
            $cache_raw = file_get_contents($this->cache_file);
            $cache = json_decode($cache_raw, true);
            if (is_array($cache) && !empty($cache['token']) && ($cache['expires_at'] - 120) > $now) {
                $this->access_token = $cache['token'];
                $this->token_expires_at = $cache['expires_at'];
                return $this->access_token;
            }
        }

        if (!$this->has_valid_credentials()) {
            throw new Exception("RTO Firebase Service Account credentials missing or invalid at: " . $this->service_account_path);
        }

        $sa = json_decode(file_get_contents($this->service_account_path), true);
        $client_email = $sa['client_email'];
        $private_key  = $sa['private_key'];
        if (!empty($sa['project_id'])) {
            $this->project_id = $sa['project_id'];
        }

        $header = array(
            'alg' => 'RS256',
            'typ' => 'JWT'
        );

        $claims = array(
            'iss'   => $client_email,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'exp'   => $now + 3600,
            'iat'   => $now
        );

        $header_enc = $this->base64url_encode(json_encode($header));
        $claims_enc = $this->base64url_encode(json_encode($claims));
        $sig_input  = $header_enc . '.' . $claims_enc;

        $binary_signature = '';
        $algo = OPENSSL_ALGO_SHA256;
        $sign_ok = openssl_sign($sig_input, $binary_signature, $private_key, $algo);

        if (!$sign_ok) {
            throw new Exception("OpenSSL RS256 signing failed. Ensure openssl extension is loaded.");
        }

        $jwt_assertion = $sig_input . '.' . $this->base64url_encode($binary_signature);

        $token_endpoint = 'https://oauth2.googleapis.com/token';
        $post_fields = http_build_query(array(
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt_assertion
        ));

        $ch = curl_init($token_endpoint);
        $curl_opts = array(
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $post_fields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_HTTPHEADER     => array('Content-Type: application/x-www-form-urlencoded')
        );
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $curl_opts[CURLOPT_SSL_VERIFYPEER] = false;
        }
        curl_setopt_array($ch, $curl_opts);

        $response_raw = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            throw new Exception("cURL error during OAuth token exchange: " . $curl_error);
        }

        $resp = json_decode($response_raw, true);
        if ($http_code !== 200 || empty($resp['access_token'])) {
            $err_msg = isset($resp['error_description']) ? $resp['error_description'] : (isset($resp['error']) ? $resp['error'] : 'Unknown error');
            throw new Exception("Google OAuth failed with HTTP {$http_code}: " . $err_msg);
        }

        $this->access_token = $resp['access_token'];
        $this->token_expires_at = $now + (isset($resp['expires_in']) ? (int)$resp['expires_in'] : 3600);

        @file_put_contents($this->cache_file, json_encode(array(
            'token'      => $this->access_token,
            'expires_at' => $this->token_expires_at
        )));

        return $this->access_token;
    }

    public function send($target_type, $destination, $content, $is_dry_run = false) {
        $start_time = microtime(true);

        $title         = !empty($content['title']) ? $content['title'] : 'RTO Vehicle App';
        $body          = !empty($content['body']) ? $content['body'] : '';
        $cat_name      = !empty($content['category_name']) ? (string)$content['category_name'] : 'General';
        $target_screen = !empty($content['target_screen']) ? (string)$content['target_screen'] : 'EXAM';
        $notif_id      = !empty($content['id']) ? (string)$content['id'] : (string)time();

        $message = array(
            'notification' => array(
                'title' => $title,
                'body'  => $body
            ),
            'data' => array(
                'notification_id' => $notif_id,
                'title'           => $title,
                'body'            => $body,
                'category_name'   => $cat_name,
                'target_screen'   => $target_screen
            ),
            'android' => array(
                'priority' => 'HIGH',
                'notification' => array(
                    'sound'                   => 'default',
                    'default_sound'           => true,
                    'default_vibrate_timings' => true,
                    'notification_priority'   => 'PRIORITY_HIGH'
                )
            )
        );

        if (!empty($content['campaign_id'])) {
            $message['data']['campaign_id'] = (string)$content['campaign_id'];
        }

        if (!empty($content['image_url'])) {
            $message['notification']['image'] = $content['image_url'];
            $message['data']['image_url']     = $content['image_url'];
            $message['data']['big_picture']   = $content['image_url'];
            $message['android']['notification']['image'] = $content['image_url'];
        }

        if (strtoupper($target_type) === 'TOPIC') {
            $clean_topic = preg_replace('/^\/topics\//', '', trim($destination));
            $message['topic'] = $clean_topic;
        } else {
            $message['token'] = trim($destination);
        }

        if ($is_dry_run) {
            $exec_time = round((microtime(true) - $start_time) * 1000);
            $simulated_msg_id = 'projects/' . $this->project_id . '/messages/dryrun_' . bin2hex(random_bytes(10));
            return array(
                'success'           => true,
                'is_dry_run'        => true,
                'http_status'       => 200,
                'fcm_message_id'    => $simulated_msg_id,
                'response_body'     => json_encode(array('name' => $simulated_msg_id, 'mode' => 'SIMULATED_DRY_RUN')),
                'execution_time_ms' => $exec_time,
                'is_retryable'      => false,
                'payload_preview'   => array('message' => $message)
            );
        }

        try {
            $token = $this->get_access_token();
        } catch (Exception $e) {
            $exec_time = round((microtime(true) - $start_time) * 1000);
            return array(
                'success'           => false,
                'is_dry_run'        => false,
                'http_status'       => 401,
                'fcm_message_id'    => null,
                'error'             => 'Authentication Error: ' . $e->getMessage(),
                'response_body'     => json_encode(array('error' => $e->getMessage())),
                'execution_time_ms' => $exec_time,
                'is_retryable'      => false
            );
        }

        $endpoint = 'https://fcm.googleapis.com/v1/projects/' . $this->project_id . '/messages:send';
        $post_fields = json_encode(array('message' => $message));

        $ch = curl_init($endpoint);
        $send_opts = array(
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $post_fields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_HTTPHEADER     => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json; UTF-8'
            )
        );
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $send_opts[CURLOPT_SSL_VERIFYPEER] = false;
        }
        curl_setopt_array($ch, $send_opts);

        $response_raw = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        $exec_time = round((microtime(true) - $start_time) * 1000);

        if ($curl_error) {
            return array(
                'success'           => false,
                'is_dry_run'        => false,
                'http_status'       => 0,
                'fcm_message_id'    => null,
                'error'             => 'Network cURL error: ' . $curl_error,
                'response_body'     => null,
                'execution_time_ms' => $exec_time,
                'is_retryable'      => true
            );
        }

        $resp = json_decode($response_raw, true);

        if ($http_code === 200 && !empty($resp['name'])) {
            return array(
                'success'           => true,
                'is_dry_run'        => false,
                'http_status'       => 200,
                'fcm_message_id'    => $resp['name'],
                'response_body'     => $response_raw,
                'execution_time_ms' => $exec_time,
                'is_retryable'      => false
            );
        }

        $err_msg = 'Unknown FCM Error (HTTP ' . $http_code . ')';
        if (!empty($resp['error']['message'])) {
            $err_msg = $resp['error']['message'];
        }

        $is_retryable = ($http_code >= 500 || $http_code === 429);

        return array(
            'success'           => false,
            'is_dry_run'        => false,
            'http_status'       => $http_code,
            'fcm_message_id'    => null,
            'error'             => $err_msg,
            'response_body'     => $response_raw,
            'execution_time_ms' => $exec_time,
            'is_retryable'      => $is_retryable
        );
    }
}
