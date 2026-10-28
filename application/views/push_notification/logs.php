<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><span class="ico-list"></span></div>
            <h1>Push Delivery Logs <small><span class="cgreen">FCM HTTP v1 Execution Telemetry</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>PushNotification">Push Notifications</a></li>
            <li class="active">Delivery Logs</li>
        </ul>
        <div class="clear"></div>
    </div>

    <!-- Navigation Tabs -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li><a href="<?php echo base_url('PushNotification'); ?>">📊 Dashboard</a></li>
                <li><a href="<?php echo base_url('PushNotification/bank'); ?>">🗂 Content Bank (2,000)</a></li>
                <li><a href="<?php echo base_url('PushNotification/composer'); ?>">✍️ Manual Push Composer</a></li>
                <li><a href="<?php echo base_url('PushNotification/campaigns'); ?>">📢 Campaign History</a></li>
                <li><a href="<?php echo base_url('PushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li class="active"><a href="<?php echo base_url('PushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li><a href="<?php echo base_url('PushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="head purple">
                    <div class="icon"><span class="ico-align-justify"></span></div>
                    <h2>Delivery History & Engagement Telemetry (Total: <?php echo number_format($total); ?>)</h2>
                </div>

                <!-- Aggregate Telemetry Stats Ribbon -->
                <?php if (isset($stats)) { ?>
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
                        <span class="label label-info" style="font-size: 11px; padding: 4px 8px;">
                            Active DB Tokens: <strong><?php echo number_format($stats['devices']); ?></strong>
                        </span>
                    </div>
                </div>
                <?php } ?>

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
                                <th style="width: 85px; text-align: center;">Mode</th>
                                <th>FCM Message ID / Response</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($logs)) { foreach ($logs as $l) { 
                                $sent = (int)$l['sent_count'];
                                $fail = (int)$l['failed_count'];
                                $clicks = (int)$l['click_count'];
                                $not_clicked = isset($l['not_clicked_count']) ? (int)$l['not_clicked_count'] : max(0, $sent - $clicks);
                                $ctr = isset($l['ctr']) ? $l['ctr'] : (($sent > 0) ? round(($clicks / $sent) * 100, 1) : 0);
                            ?>
                            <tr>
                                <td><small><?php echo $l['created_at']; ?></small></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($l['notification_title']); ?></strong>
                                    <?php if (!empty($l['category_name'])) { ?>
                                        <br><small class="text-muted"><span class="label label-default" style="font-size: 9.5px;"><?php echo htmlspecialchars($l['category_name']); ?></span></small>
                                    <?php } ?>
                                </td>
                                <td>
                                    <code><?php echo $l['target_type']; ?></code>: 
                                    <small><?php echo htmlspecialchars(substr($l['target_destination'], 0, 18) . '...'); ?></small>
                                </td>
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
                                    <div style="font-size: 9.5px; color: #888; margin-top: 2px;"><?php echo $l['execution_time_ms']; ?>ms</div>
                                </td>
                                <td>
                                    <?php if (!empty($l['fcm_message_id'])) { ?>
                                        <code style="font-size: 10.5px;"><?php echo htmlspecialchars(substr($l['fcm_message_id'], 0, 36) . '...'); ?></code>
                                    <?php } else { ?>
                                        <span class="text-danger" style="font-size: 10.5px;"><?php echo htmlspecialchars(substr($l['response_body'], 0, 80)); ?></span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted" style="padding: 30px;">
                                    No push notifications have been logged yet.
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($pages > 1) { ?>
    <div class="row">
        <div class="col-md-12 text-center">
            <ul class="pagination">
                <?php if ($page > 1) { ?>
                    <li><a href="<?php echo base_url('PushNotification/logs?page=' . ($page - 1)); ?>">&laquo; Prev</a></li>
                <?php } ?>
                <li class="active"><span>Page <?php echo $page; ?> of <?php echo $pages; ?></span></li>
                <?php if ($page < $pages) { ?>
                    <li><a href="<?php echo base_url('PushNotification/logs?page=' . ($page + 1)); ?>">Next &raquo;</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

</div>
