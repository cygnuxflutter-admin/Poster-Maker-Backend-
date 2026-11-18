<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><span class="ico-fighter-jet"></span></div>
            <h1>Test Push Notification <small><span class="cblue">Target Specific Device or Topic</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>RtoPushNotification">RTO Push Notifications</a></li>
            <li class="active">Test Send</li>
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
                <li><a href="<?php echo base_url('RtoPushNotification/bank'); ?>">🗂 RTO Content Bank (5,000+)</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/composer'); ?>">✍️ Manual Broadcast Composer</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/campaigns'); ?>">📢 Broadcast History</a></li>
                <li class="active"><a href="<?php echo base_url('RtoPushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-send"></span></div>
                    <h2>Dispatch Test Notification</h2>
                </div>
                <div class="data-fluid" style="padding: 20px;">
                    <form action="<?php echo base_url('RtoPushNotification/do_test_send'); ?>" method="POST">
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Target Type:</strong></label>
                            <select name="target_type" id="test_target_type" class="form-control" onchange="toggleTestDestination()">
                                <option value="TOPIC">FCM Topic (Broadcast)</option>
                                <option value="TOKEN" selected>Single Device FCM Token</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Destination (FCM Token or Topic Name):</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="destination" id="test_destination" class="form-control" placeholder="Paste FCM Device Token here..." required>
                            <small class="text-muted" id="test_dest_hint">Paste the registration token from Android Logcat or pick from registered test devices below.</small>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Notification Title:</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="🚗 RTO App Test Notification" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Notification Body:</strong> <span class="text-danger">*</span></label>
                            <textarea name="body" class="form-control" rows="3" required>Test push notification dispatched successfully from the RTO backend!</textarea>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Target Screen / Action:</strong></label>
                            <select name="target_screen" class="form-control">
                                <option value="EXAM">📝 RTO Exam Practice (EXAM)</option>
                                <option value="CHALLAN">🚨 Challan Check (CHALLAN)</option>
                                <option value="RC">🚗 RC Owner Search (RC)</option>
                                <option value="DL">🪪 Driving Licence (DL)</option>
                                <option value="SYMBOL">🛑 Traffic Signs (SYMBOL)</option>
                                <option value="RULES">⚖️ Traffic Rules (RULES)</option>
                                <option value="FUEL">⛽ Fuel Tracker (FUEL)</option>
                                <option value="RESALE">🏷️ Resale Calculator (RESALE)</option>
                                <option value="MILEAGE">🧮 Mileage & Trip Cost (MILEAGE)</option>
                                <option value="LOAN">💳 Loan EMI Calculator (LOAN)</option>
                                <option value="DOCUMENT">📄 Expiry Reminders (DOCUMENT)</option>
                                <option value="CELEBRITY">🌟 Celebrity Vehicles (CELEBRITY)</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Image URL (Optional):</strong></label>
                            <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                        </div>

                        <?php if ($dry_run === 1) { ?>
                        <div class="checkbox" style="margin-bottom: 20px;">
                            <label style="color: #c0392b; font-weight: bold;">
                                <input type="checkbox" name="force_live" value="1" checked> 
                                Force LIVE Network Send (Even though system is in Dry-Run Mode)
                            </label>
                        </div>
                        <?php } ?>

                        <button type="submit" class="btn btn-success btn-large" style="padding: 10px 25px; font-size: 15px;">
                            🚀 Send Test Push Now
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Registered Test Devices List -->
        <div class="col-md-5">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-tablet"></span></div>
                    <h2>Registered Test Devices (<?php echo count($test_devices); ?>)</h2>
                </div>
                <div class="data-fluid">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Device ID</th>
                                <th>Version</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($test_devices)) { foreach ($test_devices as $td) { ?>
                            <tr>
                                <td>
                                    <code><?php echo htmlspecialchars(substr($td['device_id'], 0, 16)); ?>...</code>
                                    <br><small class="text-muted"><?php echo date('M d, H:i', strtotime($td['last_active_at'])); ?></small>
                                </td>
                                <td>v<?php echo htmlspecialchars($td['app_version'] ?: '1.0'); ?></td>
                                <td>
                                    <button class="btn btn-xs btn-primary" onclick="useToken('<?php echo htmlspecialchars($td['fcm_token']); ?>')">
                                        Use Token
                                    </button>
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted" style="padding: 20px;">
                                    No test devices registered yet. Open the RTO Android app with test flag enabled to register your phone.
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

<script>
function toggleTestDestination() {
    if ($('#test_target_type').val() === 'TOPIC') {
        $('#test_destination').val('<?php echo htmlspecialchars($default_topic); ?>');
        $('#test_dest_hint').text('Targeting FCM Topic broadcast.');
    } else {
        $('#test_destination').val('');
        $('#test_dest_hint').text('Paste the registration token from Android Logcat or pick from registered test devices.');
    }
}

function useToken(token) {
    $('#test_target_type').val('TOKEN');
    $('#test_destination').val(token);
    toggleTestDestination();
    $('#test_destination').focus();
}
</script>
