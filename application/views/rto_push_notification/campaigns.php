<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><span class="ico-bullhorn"></span></div>
            <h1>Broadcast Campaigns History <small><span class="cblue">Delivery & Open Telemetry</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>RtoPushNotification">RTO Push Notifications</a></li>
            <li class="active">Campaigns</li>
        </ul>
        <div class="clear"></div>
    </div>

    <!-- Navigation Tabs -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li><a href="<?php echo base_url('RtoPushNotification'); ?>">📊 Dashboard</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/bank'); ?>">🗂 RTO Content Bank (5,000+)</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/composer'); ?>">✍️ Manual Broadcast Composer</a></li>
                <li class="active"><a href="<?php echo base_url('RtoPushNotification/campaigns'); ?>">📢 Broadcast History</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-list"></span></div>
                    <h2>Broadcast Campaigns (Total: <?php echo number_format($total); ?>)</h2>
                </div>
                <div class="data-fluid">
                    <table class="table" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th>Campaign Title & Message</th>
                                <th style="width: 110px;">Target</th>
                                <th style="width: 100px;">Mode</th>
                                <th style="width: 90px;">Status</th>
                                <th style="width: 100px;">Confirmed Displays</th>
                                <th style="width: 90px;">Clicks</th>
                                <th style="width: 90px;">CTR%</th>
                                <th style="width: 140px;">Sent At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($campaigns)) { foreach ($campaigns as $camp) { 
                                $ctr = ($camp['confirmed_delivery_count'] > 0) ? round(($camp['open_count'] / $camp['confirmed_delivery_count']) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td>#<?php echo $camp['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($camp['title']); ?></strong>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($camp['message']); ?></small>
                                </td>
                                <td>
                                    <span class="label label-info">🎯 <?php echo htmlspecialchars($camp['target_screen']); ?></span>
                                </td>
                                <td>
                                    <?php if ($camp['is_dry_run']) { ?>
                                        <span class="label label-warning">SIMULATED</span>
                                    <?php } else { ?>
                                        <span class="label label-success">LIVE</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($camp['status'] === 'SENT') { ?>
                                        <span class="label label-primary">SENT</span>
                                    <?php } elseif ($camp['status'] === 'FAILED') { ?>
                                        <span class="label label-danger">FAILED</span>
                                    <?php } else { ?>
                                        <span class="label label-default"><?php echo $camp['status']; ?></span>
                                    <?php } ?>
                                </td>
                                <td><strong><?php echo number_format($camp['confirmed_delivery_count']); ?></strong></td>
                                <td><strong><?php echo number_format($camp['open_count']); ?></strong></td>
                                <td><span class="badge badge-success"><?php echo $ctr; ?>%</span></td>
                                <td><small><?php echo $camp['sent_at'] ? date('M d, Y H:i', strtotime($camp['sent_at'])) : '-'; ?></small></td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                                    No broadcast campaigns created yet. Use the Manual Composer or wait for automated slot dispatches.
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
