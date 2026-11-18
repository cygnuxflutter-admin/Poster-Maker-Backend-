<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><span class="ico-cog"></span></div>
            <h1>Push Settings <small><span class="cblue">Scheduler & Firebase Parameters</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>RtoPushNotification">RTO Push Notifications</a></li>
            <li class="active">Settings</li>
        </ul>
        <div class="clear"></div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')) { ?>
        <div class="alert alert-success">
            <strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php } ?>
    <?php if ($this->session->flashdata('error')) { ?>
        <div class="alert alert-danger">
            <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php } ?>

    <!-- Navigation Tabs -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li><a href="<?php echo base_url('RtoPushNotification'); ?>">📊 Dashboard</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/bank'); ?>">🗂 Content Bank (5,000+)</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/composer'); ?>">✍️ Manual Push Composer</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/campaigns'); ?>">📢 Campaign History</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li class="active"><a href="<?php echo base_url('RtoPushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- Settings Form (Left Column) -->
        <div class="col-md-7">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-cogs"></span></div>
                    <h2>Scheduler & Delivery Controls</h2>
                </div>
                <div class="data-fluid" style="padding: 20px;">
                    <form action="<?php echo base_url('RtoPushNotification/save_settings'); ?>" method="POST">
                        
                        <!-- Weekly Schedule (Sunday to Saturday Times) with Copy Monday Button -->
                        <div class="panel panel-default" style="border: 1px solid #d2d6de; margin-bottom: 25px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <div class="panel-heading" style="background: #f8f9fa; display: flex; justify-content: space-between; align-items: center; padding: 10px 15px;">
                                <strong style="color: #2c3e50; font-size: 13.5px;"><span class="ico-calendar"></span> Weekly Schedule (Sunday to Saturday Times)</strong>
                                <button type="button" class="btn btn-sm btn-info" onclick="copyMondayToAll()" style="font-weight: 600;">
                                    <span class="ico-copy"></span> Copy Monday to All Days
                                </button>
                            </div>
                            <div class="panel-body" style="padding: 12px 15px; overflow-x: auto;">
                                <p class="text-muted" style="font-size: 11.5px; margin-bottom: 12px;">
                                    Customize auto notification dispatch times (HH:MM in <span class="cgreen">Asia/Kolkata</span>) for each specific day of the week. Enter comma-separated times for multiple daily slots.
                                </p>
                                
                                <?php 
                                $days_meta = array(
                                    'sunday'    => array('label' => 'Sunday', 'icon' => '🟠', 'theme' => 'Weekend Special'),
                                    'monday'    => array('label' => 'Monday', 'icon' => '💼', 'theme' => 'Week Start Motivation'),
                                    'tuesday'   => array('label' => 'Tuesday', 'icon' => '💼', 'theme' => 'Design & Tips'),
                                    'wednesday' => array('label' => 'Wednesday', 'icon' => '💼', 'theme' => 'Mid-Week Business'),
                                    'thursday'  => array('label' => 'Thursday', 'icon' => '💼', 'theme' => 'Feature Hacks'),
                                    'friday'    => array('label' => 'Friday', 'icon' => '🎉', 'theme' => 'Weekend Offers'),
                                    'saturday'  => array('label' => 'Saturday', 'icon' => '🎉', 'theme' => 'Festive & Celebration')
                                );
                                $today_lower = strtolower(date('l'));
                                ?>

                                <table class="table table-bordered table-striped" style="margin-bottom: 5px;">
                                    <thead>
                                        <tr style="background: #f1f3f5; font-size: 12px;">
                                            <th style="width: 25%;">Day of Week</th>
                                            <th style="width: 50%;">Notification Times (Asia/Kolkata)</th>
                                            <th style="width: 25%;">Focus / Theme</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($days_meta as $key => $meta): 
                                            $val = isset($weekly_schedule[$key]) ? $weekly_schedule[$key] : '09:00,11:00,13:00,15:00,17:00,19:00,21:00';
                                            $is_today = ($key === $today_lower);
                                        ?>
                                        <tr style="<?php echo $is_today ? 'background: #e8f8f5;' : ''; ?>">
                                            <td style="vertical-align: middle;">
                                                <strong><?php echo $meta['icon'] . ' ' . $meta['label']; ?></strong>
                                                <?php if ($is_today): ?>
                                                    <span class="label label-success" style="font-size: 9px; margin-left: 2px;">TODAY</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <input type="text" name="weekly_<?php echo $key; ?>" id="weekly_<?php echo $key; ?>" class="form-control input-sm weekly-input" value="<?php echo htmlspecialchars($val); ?>" placeholder="09:00,13:00,17:00,20:00" required>
                                            </td>
                                            <td style="vertical-align: middle; color: #7f8c8d; font-size: 12px;">
                                                <?php echo htmlspecialchars($meta['theme']); ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- FCM Parameters & Topic -->
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label><strong>Default Broadcast Topic:</strong></label>
                            <input type="text" name="fcm_topic" class="form-control" value="<?php echo htmlspecialchars($configs['fcm_topic'] ?? 'all_rto_users'); ?>" placeholder="all_rto_users" required>
                            <span class="help-block text-muted" style="font-size: 11px;">
                                Android app subscribes to this topic via <code>FirebaseMessaging.getInstance().subscribeToTopic("<?php echo htmlspecialchars($configs['fcm_topic'] ?? 'all_rto_users'); ?>")</code>.
                            </span>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label><strong>Firebase Project ID:</strong></label>
                            <input type="text" name="fcm_project_id" class="form-control" value="<?php echo htmlspecialchars($configs['fcm_project_id'] ?? 'rto-app-4365b'); ?>" required>
                            <span class="help-block text-muted" style="font-size: 11px;">
                                From your RTO Google Services configuration (e.g. <code>rto-app-4365b</code>).
                            </span>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label><strong>Service Account Key Filename:</strong></label>
                            <input type="text" name="service_account_file" class="form-control" value="<?php echo htmlspecialchars($configs['service_account_file'] ?? 'rto_firebase_service_account.json'); ?>" required>
                            <span class="help-block text-muted" style="font-size: 11px;">
                                Expected at <code>application/config/secrets/rto_firebase_service_account.json</code>
                            </span>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label><strong>Max Retries for Transient Errors:</strong></label>
                            <input type="number" name="max_retries" class="form-control" value="<?php echo htmlspecialchars($configs['max_retries'] ?? '3'); ?>" min="1" max="10" required>
                            <span class="help-block text-muted" style="font-size: 11px;">
                                Bounded exponential backoff attempts for HTTP 429/503 errors.
                            </span>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px; background: #fff8e1; padding: 15px; border-radius: 4px; border: 1px solid #ffe082;">
                            <label style="cursor: pointer; font-weight: bold; color: #b78103;">
                                <input type="checkbox" name="dry_run_mode" value="1" <?php echo (($configs['dry_run_mode'] ?? '1') == '1') ? 'checked' : ''; ?>>
                                Enable Safety Dry-Run Mode
                            </label>
                            <div style="font-size: 12px; color: #795548; margin-top: 5px;">
                                When enabled, cron workers simulate FCM dispatches and record logs without sending actual push messages to user devices. Keep enabled until you are ready for live broadcasts.
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 25px; background: #ffebee; padding: 15px; border-radius: 4px; border: 1px solid #ffcdd2;">
                            <label style="cursor: pointer; font-weight: bold; color: #c62828;">
                                <input type="checkbox" name="is_campaign_paused" value="1" <?php echo (($configs['is_campaign_paused'] ?? '0') == '1') ? 'checked' : ''; ?>>
                                Emergency Pause (Halt All Automated Sends)
                            </label>
                            <div style="font-size: 12px; color: #b71c1c; margin-top: 5px;">
                                Master kill-switch. When checked, all daily slots are skipped immediately upon cron invocation.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="padding: 8px 25px; font-weight: bold;">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Documentation & Crontab Box (Right Column) -->
        <div class="col-md-5">
            <div class="block">
                <div class="head green">
                    <div class="icon"><span class="ico-info-sign"></span></div>
                    <h2>Hostinger Server Cron Setup</h2>
                </div>
                <div class="data-fluid" style="padding: 20px;">
                    <p style="font-size: 13px;">To automate the daily notifications, configure a 5-minute recurring cron job in Hostinger hPanel or via SSH <code>crontab -e</code>:</p>
                    
                    <div style="background: #272822; color: #f8f8f2; padding: 12px; border-radius: 4px; font-family: monospace; font-size: 12px; margin-bottom: 15px; word-break: break-all; line-height: 1.6;">
                        */5 * * * * php <?php echo FCPATH; ?>index.php RtoPushCron run >> <?php echo APPPATH; ?>logs/rto_cron.log 2>&1
                    </div>

                    <p style="font-size: 12px; color: #555; line-height: 1.6;">
                        <strong>How it works:</strong> The cron job awakens every 5 minutes, checks the current time in <span class="cgreen">asia/kolkata</span>, verifies if configured slot times are due, safely claims the next approved bank item using atomic database locking, and dispatches via FCM HTTP v1.
                    </p>

                    <hr>

                    <h4>Firebase Credentials</h4>
                    <p>
                        Status: 
                        <?php if ($has_creds) { ?>
                            <span class="label label-success" style="font-size: 12px; padding: 3px 8px;">✓ Installed & Valid</span>
                        <?php } else { ?>
                            <span class="label label-danger" style="font-size: 12px; padding: 3px 8px;">Missing Credentials</span>
                        <?php } ?>
                    </p>
                    <p><small class="text-muted" style="word-break: break-all;">Expected at: <code><?php echo htmlspecialchars($creds_path); ?></code></small></p>

                    <hr>

                    <h4>Manual Slot Trigger (Testing)</h4>
                    <p style="font-size: 12px; color: #666;">You can instantly test-fire a slot right now to verify live dispatching:</p>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <a href="<?php echo base_url('RtoPushNotification/trigger_auto_slot/09:00'); ?>" class="btn btn-default btn-sm" onclick="return confirm('Trigger slot 09:00 now?');">
                            ▶ Test 09:00
                        </a>
                        <a href="<?php echo base_url('RtoPushNotification/trigger_auto_slot/13:00'); ?>" class="btn btn-default btn-sm" onclick="return confirm('Trigger slot 13:00 now?');">
                            ▶ Test 13:00
                        </a>
                        <a href="<?php echo base_url('RtoPushNotification/trigger_auto_slot/17:00'); ?>" class="btn btn-default btn-sm" onclick="return confirm('Trigger slot 17:00 now?');">
                            ▶ Test 17:00
                        </a>
                        <a href="<?php echo base_url('RtoPushNotification/trigger_auto_slot/20:00'); ?>" class="btn btn-default btn-sm" onclick="return confirm('Trigger slot 20:00 now?');">
                            ▶ Test 20:00
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyMondayToAll() {
    var monVal = $('#weekly_monday').val();
    if (!monVal) {
        alert('Please enter notification times for Monday first.');
        return;
    }

    var days = ['sunday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    for (var i = 0; i < days.length; i++) {
        $('#weekly_' + days[i]).val(monVal);
    }

    alert('Monday times copied to all 7 days!\n\nTimes: ' + monVal + '\n\nPlease click "Save Settings" below to save changes.');
}
</script>
