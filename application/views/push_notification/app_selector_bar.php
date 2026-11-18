<?php
$CI =& get_instance();
$active_app = !empty($active_app) ? $active_app : (($CI && isset($CI->session) && $CI->session->userdata('active_push_app')) ? $CI->session->userdata('active_push_app') : 'chirag');
$active_project_id = !empty($active_project_id) ? $active_project_id : (($CI && isset($CI->fcm_v1_client) && method_exists($CI->fcm_v1_client, 'get_project_id')) ? $CI->fcm_v1_client->get_project_id() : 'poster---text-art');

// Check credentials file existence
$chirag_has_file = isset($chirag_has_creds) ? $chirag_has_creds : (($CI && isset($CI->fcm_v1_client) && method_exists($CI->fcm_v1_client, 'has_valid_credentials')) ? $CI->fcm_v1_client->has_valid_credentials('chirag') : true);
$nitin_has_file  = isset($nitin_has_creds) ? $nitin_has_creds : (($CI && isset($CI->fcm_v1_client) && method_exists($CI->fcm_v1_client, 'has_valid_credentials')) ? $CI->fcm_v1_client->has_valid_credentials('nitin') : false);

// Check transmission mode for each app individually
$chirag_dry = ($CI && isset($CI->Push_notification_model)) ? (int)$CI->Push_notification_model->get_config('dry_run_mode') : 0;
$nitin_dry_val = ($CI && isset($CI->Push_notification_model)) ? $CI->Push_notification_model->get_config('nitin_dry_run_mode') : null;
$nitin_dry = ($nitin_dry_val !== null) ? (int)$nitin_dry_val : 1;
?>
<div class="row" style="margin-bottom: 18px;">
    <div class="col-md-12">
        <div style="background: linear-gradient(135deg, #1f3a60 0%, #294e80 100%); padding: 12px 18px; border-radius: 6px; color: #fff; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.12);">
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span style="font-size: 13.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                    <span class="ico-mobile" style="margin-right: 4px;"></span> Select App View:
                </span>
                <div class="btn-group" role="group">
                    <a href="<?php echo base_url('PushNotification/switch_app/chirag'); ?>" 
                       class="btn btn-sm <?php echo ($active_app === 'chirag') ? 'btn-success' : 'btn-default'; ?>" 
                       style="font-weight: 600; padding: 6px 14px; border-radius: 4px 0 0 4px;">
                        🔵 Chirag Sir App (Original)
                        <?php if (!$chirag_has_file): ?>
                            <span class="badge" style="background: #e74c3c; margin-left: 5px;">! Missing Key</span>
                        <?php elseif ($chirag_dry === 0): ?>
                            <span class="badge" style="background: #27ae60; margin-left: 5px;">● LIVE</span>
                        <?php else: ?>
                            <span class="badge" style="background: #e67e22; margin-left: 5px;">● DRY-RUN</span>
                        <?php endif; ?>
                    </a>
                    <a href="<?php echo base_url('PushNotification/switch_app/nitin'); ?>" 
                       class="btn btn-sm <?php echo ($active_app === 'nitin') ? 'btn-success' : 'btn-default'; ?>" 
                       style="font-weight: 600; padding: 6px 14px; border-radius: 0 4px 4px 0;">
                        🟢 Nitin Sir App (New)
                        <?php if (!$nitin_has_file): ?>
                            <span class="badge" style="background: #e74c3c; margin-left: 5px;">! Missing Key</span>
                        <?php elseif ($nitin_dry === 0): ?>
                            <span class="badge" style="background: #27ae60; margin-left: 5px;">● LIVE</span>
                        <?php else: ?>
                            <span class="badge" style="background: #e67e22; margin-left: 5px;">● DRY-RUN</span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
            <div style="font-size: 12px; display: flex; align-items: center; gap: 8px;">
                <span>Active Target:</span>
                <span class="label <?php echo ($active_app === 'nitin') ? 'label-warning' : 'label-info'; ?>" style="font-size: 11.5px; padding: 4px 9px;">
                    <?php echo ($active_app === 'nitin') ? 'Nitin Sir App' : 'Chirag Sir App'; ?>
                </span>
                <span style="font-family: monospace; background: rgba(0,0,0,0.25); padding: 3px 8px; border-radius: 3px;">
                    Key: <?php echo ($active_app === 'nitin') ? 'nitin_poster_service_account.json' : 'firebase_service_account.json'; ?>
                </span>
            </div>
        </div>
    </div>
</div>
