<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><i class="fa-solid fa-car-side"></i></div>
            <h1>RTO Vehicle App <small><span class="cblue">FCM Push Notification Hub</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li class="active">RTO Push Notifications</li>
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

    <!-- Master Control Status Header -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <div class="well" style="background: #fff; border-left: 4px solid #3498db; margin-bottom: 0; display: flex; justify-content: space-between; align-items: center; padding: 15px 20px;">
                <div>
                    <span style="font-size: 15px; font-weight: 600; margin-right: 15px;">
                        Campaign Status: 
                        <?php if ($stats['is_paused']) { ?>
                            <span class="label label-danger" style="font-size: 13px;">PAUSED</span>
                        <?php } else { ?>
                            <span class="label label-success" style="font-size: 13px;">ACTIVE (4x Daily Slots)</span>
                        <?php } ?>
                    </span>
                    <span style="font-size: 15px; font-weight: 600;">
                        Transmission Mode: 
                        <?php if ($stats['dry_run']) { ?>
                            <span class="label label-warning" style="font-size: 13px;">SAFETY DRY-RUN (Simulate Only)</span>
                        <?php } else { ?>
                            <span class="label label-danger" style="font-size: 13px;">LIVE BROADCAST (Real FCM Users)</span>
                        <?php } ?>
                    </span>
                </div>
                <div>
                    <a href="<?php echo base_url('RtoPushNotification/toggle_pause'); ?>" class="btn <?php echo $stats['is_paused'] ? 'btn-success' : 'btn-danger'; ?>" style="margin-right: 5px;">
                        <?php echo $stats['is_paused'] ? '▶ Resume Campaign' : '⏸ Pause Campaign'; ?>
                    </a>
                    <a href="<?php echo base_url('RtoPushNotification/toggle_dry_run'); ?>" class="btn <?php echo $stats['dry_run'] ? 'btn-danger' : 'btn-warning'; ?>">
                        <?php echo $stats['dry_run'] ? '🚀 Switch to LIVE' : '🛡️ Enable Safety Dry-Run'; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Tabs -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?php echo base_url('RtoPushNotification'); ?>">📊 Dashboard</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/bank'); ?>">🗂 RTO Content Bank (5,000+)</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/composer'); ?>">✍️ Manual Broadcast Composer</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/campaigns'); ?>">📢 Broadcast History</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <!-- Stat Counters -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-3">
            <div class="block" style="border-left: 4px solid #3498db; padding: 15px; background: #fff;">
                <div style="font-size: 12px; color: #7f8c8d; text-transform: uppercase;">Total Bank Content</div>
                <div style="font-size: 28px; font-weight: bold; color: #2c3e50;"><?php echo number_format($stats['total']); ?></div>
                <div style="font-size: 12px; color: #95a5a6;"><?php echo number_format($stats['draft']); ?> Drafts available</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="block" style="border-left: 4px solid #2ecc71; padding: 15px; background: #fff;">
                <div style="font-size: 12px; color: #7f8c8d; text-transform: uppercase;">Approved & Ready</div>
                <div style="font-size: 28px; font-weight: bold; color: #27ae60;"><?php echo number_format($stats['approved']); ?></div>
                <div style="font-size: 12px; color: #95a5a6;">Next in line for automated dispatch</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="block" style="border-left: 4px solid #9b59b6; padding: 15px; background: #fff;">
                <div style="font-size: 12px; color: #7f8c8d; text-transform: uppercase;">Total Sent to Devices</div>
                <div style="font-size: 28px; font-weight: bold; color: #8e44ad;"><?php echo number_format($stats['total_devices_sent']); ?></div>
                <div style="font-size: 12px; color: #95a5a6;"><?php echo $stats['sent']; ?> slot broadcasts (<?php echo $stats['today_sent']; ?> sent today)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="block" style="border-left: 4px solid #e74c3c; padding: 15px; background: #fff;">
                <div style="font-size: 12px; color: #7f8c8d; text-transform: uppercase;">Total Active App Users</div>
                <div style="font-size: 28px; font-weight: bold; color: #c0392b;"><?php echo number_format($stats['devices']); ?></div>
                <div style="font-size: 12px; color: #95a5a6;"><?php echo number_format($stats['test_devices']); ?> test devices | 100% active tokens</div>
            </div>
        </div>
    </div>

    <!-- Telemetry Aggregate Row -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-4">
            <div class="block" style="border-left: 4px solid #16a085; padding: 15px; background: #fff;">
                <div style="font-size: 12px; color: #7f8c8d; text-transform: uppercase;">Confirmed App Displays</div>
                <div style="font-size: 24px; font-weight: bold; color: #16a085;"><?php echo number_format($stats['total_confirmed_delivery']); ?></div>
                <div style="font-size: 12px; color: #95a5a6;">System tray notifications shown</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block" style="border-left: 4px solid #f39c12; padding: 15px; background: #fff;">
                <div style="font-size: 12px; color: #7f8c8d; text-transform: uppercase;">Notification Clicks (Opens)</div>
                <div style="font-size: 24px; font-weight: bold; color: #d35400;"><?php echo number_format($stats['total_clicks']); ?></div>
                <div style="font-size: 12px; color: #95a5a6;">App opened via notification</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block" style="border-left: 4px solid #2980b9; padding: 15px; background: #fff;">
                <div style="font-size: 12px; color: #7f8c8d; text-transform: uppercase;">Engagement CTR%</div>
                <div style="font-size: 24px; font-weight: bold; color: #2980b9;"><?php echo $stats['overall_ctr']; ?>%</div>
                <div style="font-size: 12px; color: #95a5a6;">Clicks per 100 sent notifications</div>
            </div>
        </div>
    </div>

    <!-- Today's Schedule Status -->
    <div class="row">
        <div class="col-md-6">
            <div class="block">
                <div class="head blue" style="display: flex; justify-content: space-between; align-items: center; padding-right: 15px;">
                    <div>
                        <div class="icon"><span class="ico-clock"></span></div>
                        <h2>Today's Schedule (<?php echo isset($stats['today_day']) ? $stats['today_day'] : date('l'); ?> - Asia/Kolkata)</h2>
                    </div>
                    <div>
                        <a href="<?php echo base_url('RtoPushNotification/trigger_auto_slot'); ?>" class="btn btn-xs btn-primary" onclick="return confirm('Immediately dispatch the next automated RTO notification from the bank?')" style="font-weight: 600; padding: 4px 10px;">
                            ⚡ Run Next Slot Now
                        </a>
                    </div>
                </div>
                <div class="data-fluid">
                    <table class="table" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th>Slot Time</th>
                                <th>Status</th>
                                <th>Execution Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $executed_slots = array();
                            foreach ($stats['today_slots'] as $s) {
                                $executed_slots[$s['slot_name']] = $s;
                            }
                            foreach ($stats['daily_slots'] as $slot) {
                                $is_done = isset($executed_slots[$slot]);
                                $slot_info = $is_done ? $executed_slots[$slot] : null;
                            ?>
                            <tr>
                                <td><strong>⏰ <?php echo $slot; ?></strong></td>
                                <td>
                                    <?php if ($is_done) { 
                                        if ($slot_info['status'] === 'COMPLETED') { ?>
                                            <span class="label label-success">COMPLETED</span>
                                        <?php } elseif ($slot_info['status'] === 'SKIPPED') { ?>
                                            <span class="label label-warning">SKIPPED</span>
                                        <?php } else { ?>
                                            <span class="label label-danger"><?php echo $slot_info['status']; ?></span>
                                        <?php }
                                    } else { ?>
                                        <span class="label label-default">PENDING</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($is_done) {
                                        echo htmlspecialchars($slot_info['notes']);
                                    } else {
                                        echo "<span class='text-muted'>Scheduled for " . $slot . " IST</span>";
                                    } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Firebase Credentials Card -->
        <div class="col-md-6">
            <div class="block">
                <div class="head <?php echo $has_creds ? 'green' : 'orange'; ?>">
                    <div class="icon"><span class="ico-shield"></span></div>
                    <h2>Firebase FCM HTTP v1 (RTO App)</h2>
                </div>
                <div class="data-fluid" style="padding: 15px;">
                    <p><strong>Firebase Project:</strong> <code>rto-app-4365b</code></p>
                    <p><strong>Package Name:</strong> <code>com.vehicle.information.trending.rtoexam.rto</code></p>
                    <p><strong>Service Account Key:</strong> 
                        <?php if ($has_creds) { ?>
                            <span class="label label-success">VALID KEY CONFIGURED</span>
                        <?php } else { ?>
                            <span class="label label-warning">DEFAULT KEY (PLACE rto_firebase_service_account.json)</span>
                        <?php } ?>
                    </p>
                    <p><small style="color: #7f8c8d;">Path: <?php echo htmlspecialchars($creds_path); ?></small></p>
                    <hr style="margin: 10px 0;">
                    <p style="font-size: 13px; color: #555;">
                        Broadcasts reach all RTO app users subscribed to topic: <code>all_rto_users</code>.
                        Active registered devices in database: <strong><?php echo number_format($stats['devices']); ?></strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Logs with Device Send, Fail, Click & Not-Clicked Telemetry -->
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="block">
                <div class="head purple">
                    <div class="icon"><span class="ico-list"></span></div>
                    <h2>Recent Dispatch Logs & Telemetry</h2>
                    <ul class="buttons">
                        <li><a href="<?php echo base_url('RtoPushNotification/logs'); ?>">View All Logs</a></li>
                    </ul>
                </div>
                
                <!-- Aggregate Telemetry Stats Ribbon -->
                <div style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; padding: 14px 18px; display: flex; flex-wrap: wrap; gap: 20px; align-items: center; justify-content: space-between;">
                    <div style="display: flex; gap: 22px; flex-wrap: wrap; align-items: center;">
                        <span style="font-size: 13px; color: #2c3e50;">
                            <strong style="color: #27ae60; font-size: 17px;"><span class="ico-mobile"></span> <?php echo number_format($stats['total_devices_sent']); ?></strong>
                            <span style="color: #555; margin-left: 4px;">Total Devices Sent</span>
                        </span>
                        <span style="border-left: 1px solid #cbd5e1; height: 22px;"></span>
                        <span style="font-size: 13px; color: #2c3e50;">
                            <strong style="color: #e74c3c; font-size: 17px;"><span class="ico-remove"></span> <?php echo number_format($stats['total_devices_failed']); ?></strong>
                            <span style="color: #555; margin-left: 4px;">Total Failed</span>
                        </span>
                        <span style="border-left: 1px solid #cbd5e1; height: 22px;"></span>
                        <span style="font-size: 13px; color: #2c3e50;">
                            <strong style="color: #2980b9; font-size: 17px;"><span class="ico-hand-up"></span> <?php echo number_format($stats['total_clicks']); ?></strong>
                            <span style="color: #555; margin-left: 4px;">User Clicks (Opens)</span>
                        </span>
                        <span style="border-left: 1px solid #cbd5e1; height: 22px;"></span>
                        <span style="font-size: 13px; color: #2c3e50;">
                            <strong style="color: #64748b; font-size: 17px;"><span class="ico-eye-open"></span> <?php echo number_format($stats['total_not_clicked']); ?></strong>
                            <span style="color: #555; margin-left: 4px;">Not Clicked</span>
                            <small class="text-muted">(CTR: <strong style="color:#2980b9;"><?php echo $stats['overall_ctr']; ?>%</strong>)</small>
                        </span>
                    </div>
                    <div>
                        <span class="label label-info" style="font-size: 12px; padding: 5px 10px; font-weight: 600;">
                            Total Registered Users: <strong><?php echo number_format($stats['devices']); ?></strong>
                        </span>
                    </div>
                </div>

                <div class="data-fluid">
                    <table class="table" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th style="width: 140px;">Timestamp</th>
                                <th>Notification Title</th>
                                <th style="width: 140px;">Target</th>
                                <th style="width: 95px; text-align: center;">Sent</th>
                                <th style="width: 80px; text-align: center;">Failed</th>
                                <th style="width: 120px; text-align: center;">User Clicks</th>
                                <th style="width: 105px; text-align: center;">Not Clicked</th>
                                <th style="width: 80px; text-align: center;">HTTP</th>
                                <th style="width: 90px; text-align: center;">Mode</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_logs)) { foreach ($recent_logs as $l) { 
                                $sent = (int)$l['sent_count'];
                                $fail = (int)$l['failed_count'];
                                $clicks = (int)$l['click_count'];
                                $not_clicked = isset($l['not_clicked_count']) ? (int)$l['not_clicked_count'] : max(0, $sent - $clicks);
                                $ctr = isset($l['ctr']) ? $l['ctr'] : (($sent > 0) ? round(($clicks / $sent) * 100, 1) : 0);
                            ?>
                            <tr>
                                <td><small><?php echo date('M d, H:i:s', strtotime($l['created_at'])); ?></small></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($l['notification_title']); ?></strong>
                                    <?php if (!empty($l['category_name'])) { ?>
                                        <br><small class="text-muted"><span class="label label-default" style="font-size: 9.5px;"><?php echo htmlspecialchars($l['category_name']); ?></span></small>
                                    <?php } ?>
                                </td>
                                <td><code><?php echo $l['target_type']; ?>: <?php echo htmlspecialchars($l['target_destination']); ?></code></td>
                                <td style="text-align: center;">
                                    <?php if ($sent > 0) { ?>
                                        <span class="label label-success" style="font-size: 11.5px; font-weight: 600; padding: 3px 7px;">
                                            <span class="ico-mobile"></span> <?php echo number_format($sent); ?>
                                        </span>
                                    <?php } else { ?>
                                        <span class="text-muted">0</span>
                                    <?php } ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($fail > 0) { ?>
                                        <span class="label label-danger" style="font-size: 11.5px; font-weight: 600; padding: 3px 7px;">
                                            <span class="ico-remove"></span> <?php echo number_format($fail); ?>
                                        </span>
                                    <?php } else { ?>
                                        <span class="text-muted" style="font-size: 11px;">0</span>
                                    <?php } ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($clicks > 0) { ?>
                                        <span class="label label-info" style="font-size: 11.5px; font-weight: 600; padding: 3px 7px; background: #2980b9;">
                                            <span class="ico-hand-up"></span> <?php echo number_format($clicks); ?>
                                        </span>
                                        <div style="font-size: 10px; color: #2980b9; margin-top: 2px; font-weight: 600;"><?php echo $ctr; ?>% CTR</div>
                                    <?php } else { ?>
                                        <span class="text-muted" style="font-size: 11px;">0 (0%)</span>
                                    <?php } ?>
                                </td>
                                <td style="text-align: center;">
                                    <span class="label label-default" style="font-size: 11px; background: #e2e8f0; color: #475569; padding: 2px 6px;">
                                        <?php echo number_format($not_clicked); ?>
                                    </span>
                                    <div style="font-size: 9.5px; color: #777; margin-top: 2px;"><?php echo (100 - $ctr); ?>%</div>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($l['http_status'] == 200) { ?>
                                        <span class="label label-success">200 OK</span>
                                    <?php } else { ?>
                                        <span class="label label-danger"><?php echo $l['http_status']; ?></span>
                                    <?php } ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php echo $l['is_dry_run'] ? '<span class="label label-warning">DRY-RUN</span>' : '<span class="label label-primary">LIVE</span>'; ?>
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted" style="padding: 25px;">
                                    No dispatch logs recorded yet.
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
