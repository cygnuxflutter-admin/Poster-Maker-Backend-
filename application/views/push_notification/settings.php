<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><span class="ico-cog"></span></div>
            <h1>Push Settings <small><span class="cgreen">Scheduler & Firebase Parameters</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>PushNotification">Push Notifications</a></li>
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

    <!-- Navigation Tabs -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li><a href="<?php echo base_url('PushNotification'); ?>">📊 Dashboard</a></li>
                <li><a href="<?php echo base_url('PushNotification/bank'); ?>">🗂 Content Bank (2,000)</a></li>
                <li><a href="<?php echo base_url('PushNotification/composer'); ?>">✍️ Manual Push Composer</a></li>
                <li><a href="<?php echo base_url('PushNotification/campaigns'); ?>">📢 Campaign History</a></li>
                <li><a href="<?php echo base_url('PushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li><a href="<?php echo base_url('PushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li class="active"><a href="<?php echo base_url('PushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- Settings Form -->
        <div class="col-md-7">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-cogs"></span></div>
                    <h2>Scheduler & Delivery Controls</h2>
                </div>
                <div class="data-fluid" style="padding: 20px;">
                    <form action="<?php echo base_url('PushNotification/save_settings'); ?>" method="POST">
                        
                        <!-- 4 Daily Notification Time Pickers (Master Controls) -->
                        <div class="panel panel-info" style="border: 1px solid #bce8f1; margin-bottom: 25px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <div class="panel-heading" style="background: #d9edf7; color: #31708f; padding: 12px 15px;">
                                <strong style="font-size: 14px;"><span class="ico-time"></span> 4 Daily Auto Notification Timers (Set Master Times)</strong>
                            </div>
                            <div class="panel-body" style="padding: 15px;">
                                <p style="font-size: 12px; color: #555; margin-bottom: 15px;">
                                    તમે દરરોજ ઓટોમેટિક મોકલવાની ૪ નોટિફિકેશનનો ચોક્કસ ટાઈમ (Time Picker) અહીંથી બદલી શકો છો (દા.ત. 09:00 ની જગ્યાએ 08:00 AM કરવું હોય તો):
                                </p>

                                <?php
                                $def_slots = explode(',', isset($configs['daily_slots']) ? $configs['daily_slots'] : '09:00,13:00,17:00,20:00');
                                $m_s1 = isset($def_slots[0]) && strlen(trim($def_slots[0])) > 0 ? trim($def_slots[0]) : '09:00';
                                $m_s2 = isset($def_slots[1]) && strlen(trim($def_slots[1])) > 0 ? trim($def_slots[1]) : '13:00';
                                $m_s3 = isset($def_slots[2]) && strlen(trim($def_slots[2])) > 0 ? trim($def_slots[2]) : '17:00';
                                $m_s4 = isset($def_slots[3]) && strlen(trim($def_slots[3])) > 0 ? trim($def_slots[3]) : '20:00';
                                ?>

                                <div class="row" style="margin-bottom: 15px;">
                                    <div class="col-xs-6 col-sm-3" style="margin-bottom: 10px;">
                                        <label style="font-size: 12px; color: #2c3e50; font-weight: bold;">🌅 Slot 1 (Morning / સવાર):</label>
                                        <input type="time" id="master_slot_1" class="form-control" value="<?php echo $m_s1; ?>" style="font-size: 15px; font-weight: bold; border-color: #3498db;">
                                        <small class="text-muted" style="font-size: 11px;">e.g. 08:00 AM / 09:00 AM</small>
                                    </div>
                                    <div class="col-xs-6 col-sm-3" style="margin-bottom: 10px;">
                                        <label style="font-size: 12px; color: #2c3e50; font-weight: bold;">☀️ Slot 2 (Noon / બપોર):</label>
                                        <input type="time" id="master_slot_2" class="form-control" value="<?php echo $m_s2; ?>" style="font-size: 15px; font-weight: bold; border-color: #f39c12;">
                                        <small class="text-muted" style="font-size: 11px;">e.g. 01:00 PM (13:00)</small>
                                    </div>
                                    <div class="col-xs-6 col-sm-3" style="margin-bottom: 10px;">
                                        <label style="font-size: 12px; color: #2c3e50; font-weight: bold;">🌆 Slot 3 (Evening / સાંજ):</label>
                                        <input type="time" id="master_slot_3" class="form-control" value="<?php echo $m_s3; ?>" style="font-size: 15px; font-weight: bold; border-color: #e67e22;">
                                        <small class="text-muted" style="font-size: 11px;">e.g. 05:00 PM (17:00)</small>
                                    </div>
                                    <div class="col-xs-6 col-sm-3" style="margin-bottom: 10px;">
                                        <label style="font-size: 12px; color: #2c3e50; font-weight: bold;">🌙 Slot 4 (Night / રાત):</label>
                                        <input type="time" id="master_slot_4" class="form-control" value="<?php echo $m_s4; ?>" style="font-size: 15px; font-weight: bold; border-color: #9b59b6;">
                                        <small class="text-muted" style="font-size: 11px;">e.g. 08:00 PM (20:00)</small>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center; background: #eef7fa; padding: 10px 14px; border-radius: 4px; border: 1px solid #d9edf7;">
                                    <button type="button" class="btn btn-sm btn-info" onclick="applyMasterTimesToAll()" style="font-weight: 600;">
                                        ⚡ Apply This Time Schedule to All Days (Sunday - Saturday)
                                    </button>
                                    <span style="font-size: 11.5px; color: #31708f;">(બધા વારમાં આ ટાઈમ આપોઆપ સેટ થઈ જશે)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sunday to Saturday Weekly Table with 4 Separate Time Inputs Per Day -->
                        <div class="panel panel-default" style="border: 1px solid #d2d6de; margin-bottom: 25px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <div class="panel-heading" style="background: #f8f9fa; display: flex; justify-content: space-between; align-items: center; padding: 10px 15px;">
                                <strong style="color: #2c3e50; font-size: 13.5px;"><span class="ico-calendar"></span> Day-by-Day Customized Time Pickers (Sunday to Saturday)</strong>
                                <span class="label label-default" style="font-size: 11px;">4 Slots Per Day</span>
                            </div>
                            <div class="panel-body" style="padding: 12px 15px; overflow-x: auto;">
                                <p style="font-size: 11.5px; color: #7f8c8d; margin-bottom: 12px;">
                                    દરેક વાર માટે ૪ અલગ અલગ ટાઈમ બોક્સ આપેલા છે. તમે કોઈ પણ દિવસનો ટાઈમ સીધો જ ક્લિક કરીને બદલી શકો છો:
                                </p>
                                
                                <?php 
                                $days_meta = array(
                                    'sunday'    => array('label' => 'Sunday', 'icon' => '☀️'),
                                    'monday'    => array('label' => 'Monday', 'icon' => '💼'),
                                    'tuesday'   => array('label' => 'Tuesday', 'icon' => '💼'),
                                    'wednesday' => array('label' => 'Wednesday', 'icon' => '💼'),
                                    'thursday'  => array('label' => 'Thursday', 'icon' => '💼'),
                                    'friday'    => array('label' => 'Friday', 'icon' => '🎉'),
                                    'saturday'  => array('label' => 'Saturday', 'icon' => '🎉')
                                );
                                $today_lower = strtolower(date('l'));
                                ?>

                                <table class="table table-bordered" style="margin-bottom: 5px; min-width: 580px;">
                                    <thead>
                                        <tr style="background: #f1f3f5; font-size: 12px;">
                                            <th style="width: 20%;">Day of Week</th>
                                            <th style="width: 20%; color: #2980b9;">🌅 Slot 1 (Morning)</th>
                                            <th style="width: 20%; color: #d35400;">☀️ Slot 2 (Noon)</th>
                                            <th style="width: 20%; color: #c0392b;">🌆 Slot 3 (Evening)</th>
                                            <th style="width: 20%; color: #8e44ad;">🌙 Slot 4 (Night)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($days_meta as $key => $meta): 
                                            $val = isset($weekly_schedule[$key]) ? $weekly_schedule[$key] : '09:00,13:00,17:00,20:00';
                                            $parts = explode(',', $val);
                                            $s1 = isset($parts[0]) && strlen(trim($parts[0])) > 0 ? trim($parts[0]) : '09:00';
                                            $s2 = isset($parts[1]) && strlen(trim($parts[1])) > 0 ? trim($parts[1]) : '13:00';
                                            $s3 = isset($parts[2]) && strlen(trim($parts[2])) > 0 ? trim($parts[2]) : '17:00';
                                            $s4 = isset($parts[3]) && strlen(trim($parts[3])) > 0 ? trim($parts[3]) : '20:00';
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
                                                <input type="time" name="slot_<?php echo $key; ?>_1" id="slot_<?php echo $key; ?>_1" class="form-control input-sm slot-input-1" value="<?php echo htmlspecialchars($s1); ?>" required>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <input type="time" name="slot_<?php echo $key; ?>_2" id="slot_<?php echo $key; ?>_2" class="form-control input-sm slot-input-2" value="<?php echo htmlspecialchars($s2); ?>" required>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <input type="time" name="slot_<?php echo $key; ?>_3" id="slot_<?php echo $key; ?>_3" class="form-control input-sm slot-input-3" value="<?php echo htmlspecialchars($s3); ?>" required>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <input type="time" name="slot_<?php echo $key; ?>_4" id="slot_<?php echo $key; ?>_4" class="form-control input-sm slot-input-4" value="<?php echo htmlspecialchars($s4); ?>" required>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label><strong>Default Broadcast Topic:</strong></label>
                            <input type="text" name="fcm_topic" class="form-control" value="<?php echo htmlspecialchars($configs['fcm_topic']); ?>" placeholder="all_users" required>
                            <span class="help-block text-muted" style="font-size: 11px;">
                                Android app subscribes to this topic via <code>FirebaseMessaging.getInstance().subscribeToTopic("<?php echo htmlspecialchars($configs['fcm_topic']); ?>")</code>.
                            </span>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label><strong>Max Retries for Transient Errors:</strong></label>
                            <input type="number" name="max_retries" class="form-control" value="<?php echo htmlspecialchars($configs['max_retries']); ?>" min="1" max="10" required>
                            <span class="help-block text-muted" style="font-size: 11px;">
                                Bounded exponential backoff attempts for HTTP 429/503 errors.
                            </span>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px; background: #fff8e1; padding: 15px; border-radius: 4px; border: 1px solid #ffe082;">
                            <label style="cursor: pointer; font-weight: bold; color: #b78103;">
                                <input type="checkbox" name="dry_run_mode" value="1" <?php echo ($configs['dry_run_mode'] == '1') ? 'checked' : ''; ?>>
                                Enable Safety Dry-Run Mode
                            </label>
                            <div style="font-size: 12px; color: #795548; margin-top: 5px;">
                                When enabled, cron workers simulate FCM dispatches and record logs without sending actual push messages to user devices. Keep enabled until you are ready for live broadcasts.
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 25px; background: #ffebee; padding: 15px; border-radius: 4px; border: 1px solid #ffcdd2;">
                            <label style="cursor: pointer; font-weight: bold; color: #c62828;">
                                <input type="checkbox" name="is_campaign_paused" value="1" <?php echo ($configs['is_campaign_paused'] == '1') ? 'checked' : ''; ?>>
                                Emergency Pause (Halt All Automated Sends)
                            </label>
                            <div style="font-size: 12px; color: #b71c1c; margin-top: 5px;">
                                Master kill-switch. When checked, all 4 daily slots are skipped immediately upon cron invocation.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Documentation & Crontab Box -->
        <div class="col-md-5">
            <div class="block">
                <div class="head green">
                    <div class="icon"><span class="ico-info-sign"></span></div>
                    <h2>Hostinger Server Cron Setup</h2>
                </div>
                <div class="data-fluid" style="padding: 20px;">
                    <p>To automate the 4 daily notifications, configure a 5-minute recurring cron job in Hostinger hPanel or via SSH <code>crontab -e</code>:</p>
                    
                    <div style="background: #272822; color: #f8f8f2; padding: 12px; border-radius: 4px; font-family: monospace; font-size: 12px; margin-bottom: 15px; word-break: break-all;">
                        */5 * * * * php <?php echo FCPATH; ?>index.php PushCron run >> <?php echo APPPATH; ?>logs/push_cron.log 2>&1
                    </div>

                    <p style="font-size: 12px; color: #555;">
                        <strong>How it works:</strong> The cron job awakens every 5 minutes, checks the current time in <code>Asia/Kolkata</code>, verifies if 09:00, 13:00, 17:00, or 20:00 is due, safely claims the next approved bank item using atomic database locking, and dispatches via FCM HTTP v1.
                    </p>

                    <hr>

                    <h4>Firebase Credentials</h4>
                    <p>
                        Status: 
                        <?php if ($has_creds) { ?>
                            <span class="label label-success">✓ Installed & Valid</span>
                        <?php } else { ?>
                            <span class="label label-danger">Missing Credentials</span>
                        <?php } ?>
                    </p>
                    <p><small class="text-muted">Expected at: <code><?php echo htmlspecialchars($creds_path); ?></code></small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function applyMasterTimesToAll() {
    var s1 = $('#master_slot_1').val();
    var s2 = $('#master_slot_2').val();
    var s3 = $('#master_slot_3').val();
    var s4 = $('#master_slot_4').val();

    if (!s1 || !s2 || !s3 || !s4) {
        alert('Please make sure all 4 slot times are filled.');
        return;
    }

    $('.slot-input-1').val(s1);
    $('.slot-input-2').val(s2);
    $('.slot-input-3').val(s3);
    $('.slot-input-4').val(s4);

    alert('Schedule applied to all 7 days!\n\nSlot 1 (Morning): ' + s1 + '\nSlot 2 (Noon): ' + s2 + '\nSlot 3 (Evening): ' + s3 + '\nSlot 4 (Night): ' + s4 + '\n\nPlease click "Save Settings" below to save changes.');
}
</script>
