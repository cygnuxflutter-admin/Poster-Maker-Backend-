<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><span class="ico-bullhorn"></span></div>
            <h1>Manual Campaign History <small><span class="cgreen">Delivery & Engagement Analytics</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>PushNotification">Push Notifications</a></li>
            <li class="active">Campaign History</li>
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
                <li><a href="<?php echo base_url('PushNotification'); ?>">📊 Dashboard</a></li>
                <li><a href="<?php echo base_url('PushNotification/bank'); ?>">🗂 Content Bank (2,000)</a></li>
                <li><a href="<?php echo base_url('PushNotification/composer'); ?>">✍️ Manual Push Composer</a></li>
                <li class="active"><a href="<?php echo base_url('PushNotification/campaigns'); ?>">📢 Campaign History</a></li>
                <li><a href="<?php echo base_url('PushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li><a href="<?php echo base_url('PushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li><a href="<?php echo base_url('PushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <!-- Analytics Explanation Banner (Fulfilling Requirement 4) -->
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12">
            <div style="background: #eef7fc; border-left: 4px solid #3498db; padding: 12px 18px; border-radius: 4px; font-size: 12px; color: #2c3e50;">
                <strong>ℹ️ Understanding Notification Delivery Metrics:</strong>
                <div style="margin-top: 4px; display: flex; gap: 20px; flex-wrap: wrap;">
                    <span><strong>1. Targeted:</strong> Devices eligible for dispatch (or Topic broadcast).</span>
                    <span><strong>2. FCM Accepted:</strong> Google FCM servers accepted the send request.</span>
                    <span><strong>3. Confirmed Delivery:</strong> Real devices that reported back receipt receipt (via app background callback).</span>
                    <span><strong>4. Opens:</strong> Users who actively tapped the notification banner to open the app.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaigns Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-list"></span></div>
                    <h2>Campaign Dispatches (Total: <?php echo number_format($total); ?>)</h2>
                    <ul class="buttons">
                        <li><a href="<?php echo base_url('PushNotification/composer'); ?>" class="btn btn-sm btn-primary" style="color: #fff;">+ New Push</a></li>
                    </ul>
                </div>
                <div class="data-fluid">
                    <table class="table" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th style="width: 70px;">Banner</th>
                                <th>Campaign Title & Message</th>
                                <th style="width: 140px;">Audience</th>
                                <th style="width: 120px;">Sent At</th>
                                <th style="width: 90px;">Status</th>
                                <th style="width: 200px;">Delivery & Opens</th>
                                <th style="width: 80px;" class="TAC">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($campaigns)) { foreach ($campaigns as $camp) { ?>
                            <tr>
                                <td>#<?php echo $camp['id']; ?></td>
                                <td>
                                    <?php if (!empty($camp['image_url'])) { ?>
                                        <a href="<?php echo htmlspecialchars($camp['image_url']); ?>" target="_blank">
                                            <img src="<?php echo htmlspecialchars($camp['image_url']); ?>" style="width: 55px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                        </a>
                                    <?php } else { ?>
                                        <span class="text-muted" style="font-size: 10px;">No Image</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <strong style="color: #2c3e50; font-size: 13.5px;"><?php echo htmlspecialchars($camp['title']); ?></strong>
                                    <div style="font-size: 12px; color: #666; margin-top: 2px;">
                                        <?php echo htmlspecialchars(substr($camp['message'], 0, 110) . (strlen($camp['message']) > 110 ? '...' : '')); ?>
                                    </div>
                                    <small class="text-muted">By: <?php echo htmlspecialchars($camp['created_by']); ?></small>
                                </td>
                                <td>
                                    <span class="label label-info"><?php echo $camp['target_type']; ?></span>
                                    <div style="font-size: 11px; color: #555; margin-top: 3px;">
                                        <code><?php echo htmlspecialchars(substr($camp['target_value'], 0, 18)); ?></code>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($camp['sent_at']) { ?>
                                        <small><?php echo date('M d, Y', strtotime($camp['sent_at'])); ?><br><?php echo date('h:i A', strtotime($camp['sent_at'])); ?></small>
                                    <?php } else { ?>
                                        <span class="text-muted"><small><?php echo date('M d, Y', strtotime($camp['created_at'])); ?></small></span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($camp['status'] === 'SENT') { ?>
                                        <span class="label label-success">SENT</span>
                                        <?php if ($camp['is_dry_run']) { ?>
                                            <div style="margin-top: 2px;"><span class="label label-warning" style="font-size: 9px;">DRY-RUN</span></div>
                                        <?php } ?>
                                    <?php } elseif ($camp['status'] === 'DRAFT') { ?>
                                        <span class="label label-default">DRAFT</span>
                                    <?php } elseif ($camp['status'] === 'PROCESSING') { ?>
                                        <span class="label label-warning">PROCESSING</span>
                                    <?php } else { ?>
                                        <span class="label label-danger">FAILED</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <div style="font-size: 11.5px; line-height: 1.6;">
                                        <?php if ($camp['target_type'] === 'TOPIC') { ?>
                                            <div>FCM Status: <span class="label label-success" style="font-size: 10px;">TOPIC BROADCAST</span></div>
                                            <div style="color: #2980b9; font-weight: 600; font-size: 11px; margin-top: 2px;">All Subscribed App Users</div>
                                        <?php } else { ?>
                                            <div>FCM Accepted: <strong><?php echo number_format($camp['accepted_count']); ?></strong></div>
                                        <?php } ?>
                                        <div style="margin-top: 3px;">Confirmed Devices: <strong class="text-success"><?php echo number_format($camp['confirmed_delivery_count']); ?></strong></div>
                                        <div>Notification Opens: <strong class="text-primary"><?php echo number_format($camp['open_count']); ?></strong></div>
                                    </div>
                                </td>
                                <td class="TAC">
                                    <?php if ($camp['status'] === 'DRAFT') { ?>
                                        <a href="<?php echo base_url('PushNotification/composer/' . $camp['id']); ?>" class="btn btn-xs btn-primary">Edit</a>
                                    <?php } else { ?>
                                        <button type="button" class="btn btn-xs btn-info" onclick="viewCampaignDetails(<?php echo $camp['id']; ?>)">
                                            Inspect
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted" style="padding: 40px;">
                                    No manual campaigns recorded yet. Click <strong>+ New Push</strong> above to create your first notification.
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
                    <li><a href="<?php echo base_url('PushNotification/campaigns?' . http_build_query(array_merge($filters, array('page' => $page - 1)))); ?>">&laquo; Prev</a></li>
                <?php } ?>
                <li class="active"><span>Page <?php echo $page; ?> of <?php echo $pages; ?></span></li>
                <?php if ($page < $pages) { ?>
                    <li><a href="<?php echo base_url('PushNotification/campaigns?' . http_build_query(array_merge($filters, array('page' => $page + 1)))); ?>">Next &raquo;</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

</div>

<!-- Campaign Details Modal -->
<div id="campaignDetailsModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header blue">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="ico-info-sign"></span> Campaign Delivery Telemetry</h4>
            </div>
            <div class="modal-body" style="padding: 20px;" id="modalDetailsBody">
                <div class="text-center text-muted" style="padding: 20px;">Loading telemetry...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewCampaignDetails(id) {
    $('#campaignDetailsModal').modal('show');
    $('#modalDetailsBody').html('<div class="text-center text-muted" style="padding: 20px;">Loading telemetry...</div>');

    $.ajax({
        url: '<?php echo base_url("PushNotification/campaign_detail/"); ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            if (resp.success) {
                var c = resp.campaign;
                var html = '<div class="row">' +
                    '<div class="col-md-7">' +
                        '<h4>' + escapeHtml(c.title) + '</h4>' +
                        '<p style="color: #555; font-size: 13px;">' + escapeHtml(c.message) + '</p>' +
                        (c.image_url ? '<div style="margin: 10px 0;"><img src="' + c.image_url + '" style="max-width: 100%; max-height: 180px; border-radius: 6px; border: 1px solid #eee;"></div>' : '') +
                        '<hr>' +
                        '<p><strong>FCM Message ID:</strong><br><code style="font-size: 11px;">' + (c.fcm_message_id ? c.fcm_message_id : 'N/A') + '</code></p>' +
                        (c.error_details ? '<p class="text-danger"><strong>Error:</strong> ' + escapeHtml(c.error_details) + '</p>' : '') +
                    '</div>' +
                    '<div class="col-md-5">' +
                        '<div style="background: #f8f9fa; padding: 15px; border-radius: 6px; border: 1px solid #e9ecef;">' +
                            '<h5 style="margin-top: 0; font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 8px;">Delivery Breakdown</h5>' +
                            '<p style="margin-bottom: 6px;">Target Type: <strong>' + c.target_type + '</strong></p>' +
                            (c.target_type === 'TOPIC' 
                                ? '<p style="margin-bottom: 6px;">FCM Status: <strong class="text-success">Broadcast Accepted (Topic: ' + escapeHtml(c.target_value) + ')</strong><br><small class="text-muted">FCM delivers to all subscribed devices asynchronously.</small></p>' 
                                : '<p style="margin-bottom: 6px;">FCM Requests Accepted: <strong class="text-success">' + c.accepted_count + '</strong></p>') +
                            '<p style="margin-bottom: 6px;">Confirmed Delivery Receipts: <strong class="text-info">' + c.confirmed_delivery_count + '</strong></p>' +
                            '<p style="margin-bottom: 6px;">Notification Opens / Clicks: <strong class="text-primary">' + c.open_count + '</strong></p>' +
                            '<p style="margin-bottom: 0;">Transmission Mode: ' + (c.is_dry_run == 1 ? '<span class="label label-warning">Safety Dry-Run</span>' : '<span class="label label-success">Live Broadcast</span>') + '</p>' +
                        '</div>' +
                    '</div>' +
                '</div>';
                $('#modalDetailsBody').html(html);
            } else {
                $('#modalDetailsBody').html('<div class="alert alert-danger">' + resp.message + '</div>');
            }
        },
        error: function() {
            $('#modalDetailsBody').html('<div class="alert alert-danger">Failed to load campaign telemetry.</div>');
        }
    });
}

function escapeHtml(str) {
    if (!str) return '';
    return $('<div>').text(str).html();
}
</script>
