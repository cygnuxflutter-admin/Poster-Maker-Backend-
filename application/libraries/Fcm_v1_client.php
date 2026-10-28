<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * FCM HTTP v1 Client for CodeIgniter 3
 * Self-contained OAuth2 RS256 JWT Token Generator + Google FCM HTTP v1 API Client.
 * Zero external Composer dependencies required (uses native OpenSSL and cURL).
 */
class Fcm_v1_client {

    protected $CI;
    protected $service_account_path;
    protected $project_id;
    protected $access_token = null;
    protected $token_expires_at = 0;
    protected $cache_file;

    public function __construct($config = array()) {
        $this->CI =& get_instance();
        
        $default_path = APPPATH . 'config/secrets/firebase_service_account.json';
        $this->service_account_path = isset($config['service_account_path']) ? $config['service_account_path'] : $default_path;
        $this->project_id = isset($config['project_id']) ? $config['project_id'] : 'poster---text-art';
        $this->cache_file = APPPATH . 'cache/fcm_oauth_token.json';
    }

    /**
     * Check if service account credentials file exists and is valid.
     */
    public function has_valid_credentials() {
        if (!file_exists($this->service_account_path)) {
            return false;
        }
        $content = file_get_contents($this->service_account_path);
        $json = json_decode($content, true);
        return is_array($json) && !empty($json['client_email']) && !empty($json['private_key']);
    }

    /**
     * Get path to service account file.
     */
    public function get_credentials_path() {
        return $this->service_account_path;
    }

    /**
     * Base64URL encode helper.
     */
    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Obtains or refreshes a Google OAuth2 access token using RS256 signed JWT assertion.
     */
    public function get_access_token($force_refresh = false) {
        $now = time();

        // Check in-memory token
        if (!$force_refresh && $this->access_token && ($this->token_expires_at - 120) > $now) {
            return $this->access_token;
        }

        // Check cached token file
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
            throw new Exception("Firebase Service Account credentials missing or invalid at: " . $this->service_account_path);
        }

        $sa = json_decode(file_get_contents($this->service_account_path), true);
        $client_email = $sa['client_email'];
        $private_key  = $sa['private_key'];
        if (!empty($sa['project_id'])) {
            $this->project_id = $sa['project_id'];
        }

        // 1. Build JWT Header
        $header = array(
            'alg' => 'RS256',
            'typ' => 'JWT'
        );

        // 2. Build JWT Claims
        $claims = array(
            'iss'   => $client_email,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'exp'   => $now + 3600,
            'iat'   => $now
        );

        $payload = $this->base64url_encode(json_encode($header)) . '.' . $this->base64url_encode(json_encode($claims));

        // 3. Sign using OpenSSL SHA256
        $signature = '';
        $binary_key = openssl_pkey_get_private($private_key);
        if (!$binary_key) {
            throw new Exception("Unable to parse private key from service account JSON: " . openssl_error_string());
        }

        $signed = openssl_sign($payload, $signature, $binary_key, OPENSSL_ALGO_SHA256);
        if (!$signed) {
            throw new Exception("Failed to sign JWT with OpenSSL: " . openssl_error_string());
        }

        $jwt = $payload . '.' . $this->base64url_encode($signature);

        // 4. Request OAuth2 access token
        $ch = curl_init('https://oauth2.googleapis.com/token');
        $curl_opts = array(
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query(array(
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt
            )),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_HTTPHEADER     => array('Content-Type: application/x-www-form-urlencoded')
        );
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $curl_opts[CURLOPT_SSL_VERIFYPEER] = false;
        }
        curl_setopt_array($ch, $curl_opts);

        $res = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_err = curl_error($ch);
        curl_close($ch);

        if ($curl_err) {
            throw new Exception("OAuth2 cURL error: " . $curl_err);
        }

        $json = json_decode($res, true);
        if ($http_code !== 200 || empty($json['access_token'])) {
            $err_desc = isset($json['error_description']) ? $json['error_description'] : (isset($json['error']) ? $json['error'] : $res);
            throw new Exception("OAuth2 Token Exchange failed (HTTP {$http_code}): " . $err_desc);
        }

        $this->access_token = $json['access_token'];
        $this->token_expires_at = $now + (isset($json['expires_in']) ? (int)$json['expires_in'] : 3600);

        // Persist token in cache
        @file_put_contents($this->cache_file, json_encode(array(
            'token'      => $this->access_token,
            'expires_at' => $this->token_expires_at
        )));

        return $this->access_token;
    }

    /**
     * Send Push Notification via FCM HTTP v1.
     *
     * @param string $target_type 'TOPIC' or 'TOKEN'
     * @param string $destination Topic name (e.g. 'all_users') or Device Registration Token
     * @param array $content Array with 'title', 'body', 'cat_id', 'category_name', 'target_screen', 'image_url'
     * @param bool $is_dry_run If true, simulates transmission safely
     * @return array
     */
    public function send($target_type, $destination, $content, $is_dry_run = false) {
        $start_time = microtime(true);

        $title = !empty($content['title']) ? $content['title'] : 'Poster Maker';
        $body  = !empty($content['body']) ? $content['body'] : '';
        $cat_id = !empty($content['cat_id']) ? (string)$content['cat_id'] : '10';
        $cat_name = !empty($content['category_name']) ? (string)$content['category_name'] : 'General';
        $target_screen = !empty($content['target_screen']) ? (string)$content['target_screen'] : 'category_detail';
        $notif_id = !empty($content['id']) ? (string)$content['id'] : (string)time();

        // Build FCM HTTP v1 Message Object
        $message = array(
            'notification' => array(
                'title' => $title,
                'body'  => $body
            ),
            'data' => array(
                'notification_id' => $notif_id,
                'title'           => $title,
                'body'            => $body,
                'cat_id'          => $cat_id,
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

        // Set Target
        if (strtoupper($target_type) === 'TOPIC') {
            // Remove leading /topics/ if present
            $clean_topic = preg_replace('/^\/topics\//', '', trim($destination));
            $message['topic'] = $clean_topic;
        } else {
            $message['token'] = trim($destination);
        }

        // Handle DRY-RUN mode safely
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

        // LIVE SEND
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
                'response_body'     => $curl_error,
                'execution_time_ms' => $exec_time,
                'is_retryable'      => true  // Network drops are retryable
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

        // Determine error classifications
        $error_status = isset($resp['error']['status']) ? $resp['error']['status'] : '';
        $error_msg    = isset($resp['error']['message']) ? $resp['error']['message'] : $response_raw;

        $is_retryable = in_array($http_code, array(429, 500, 502, 503, 504)) || 
                        in_array($error_status, array('RESOURCE_EXHAUSTED', 'UNAVAILABLE', 'INTERNAL'));

        $is_unregistered = ($error_status === 'UNREGISTERED' || $error_status === 'NOT_FOUND');

        return array(
            'success'           => false,
            'is_dry_run'        => false,
            'http_status'       => $http_code,
            'fcm_message_id'    => null,
            'error'             => "FCM Error ({$http_code}/{$error_status}): {$error_msg}",
            'response_body'     => $response_raw,
            'execution_time_ms' => $exec_time,
            'is_retryable'      => $is_retryable,
            'is_unregistered'   => $is_unregistered
        );
    }
}
