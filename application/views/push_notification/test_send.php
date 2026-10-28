<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><span class="ico-share"></span></div>
            <h1>Test Push Notification <small><span class="cgreen">Live & Dry-Run Validation</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>PushNotification">Push Notifications</a></li>
            <li class="active">Send Test Push</li>
        </ul>
        <div class="clear"></div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')) { ?>
        <div class="alert alert-success">
            <strong>Result:</strong> <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php } ?>
    <?php if ($this->session->flashdata('error')) { ?>
        <div class="alert alert-danger">
            <strong>Error:</strong> <?php echo $this->session->flashdata('error'); ?>
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
                <li class="active"><a href="<?php echo base_url('PushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li><a href="<?php echo base_url('PushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li><a href="<?php echo base_url('PushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- Form -->
        <div class="col-md-7">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-send"></span></div>
                    <h2>Dispatch Test Notification</h2>
                </div>
                <div class="data-fluid" style="padding: 20px;">
                    <form action="<?php echo base_url('PushNotification/do_test_send'); ?>" method="POST" id="testPushForm">
                        
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Target Type:</strong></label>
                            <div>
                                <label class="radio-inline" style="margin-right: 20px;">
                                    <input type="radio" name="target_type" value="TOKEN" checked onchange="toggleTarget(this.value)"> 
                                    Single Device Token (Recommended for Testing)
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="target_type" value="TOPIC" onchange="toggleTarget(this.value)"> 
                                    FCM Topic Broadcast (All App Users)
                                </label>
                            </div>
                        </div>

                        <!-- Device Token Selection -->
                        <div class="form-group" id="tokenGroup" style="margin-bottom: 15px;">
                            <label><strong>Destination Device Token:</strong></label>
                            <?php if (!empty($test_devices)) { ?>
                                <select class="form-control" style="margin-bottom: 8px;" onchange="$('#destinationInput').val(this.value)">
                                    <option value="">-- Or choose from registered test devices --</option>
                                    <?php foreach ($test_devices as $dev) { ?>
                                        <option value="<?php echo htmlspecialchars($dev['fcm_token']); ?>">
                                            Device: <?php echo htmlspecialchars($dev['device_id']); ?> (v<?php echo $dev['app_version']; ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                            <textarea name="destination" id="destinationInput" class="form-control" rows="2" placeholder="Paste FCM registration token from Android app..." required></textarea>
                            <span class="help-block text-muted" style="font-size: 11px;">
                                Copy the token from Android Studio Logcat tag <code>FCM_TOKEN</code> or select a registered device above.
                            </span>
                        </div>

                        <!-- Topic Input (Hidden by default) -->
                        <div class="form-group" id="topicGroup" style="margin-bottom: 15px; display: none;">
                            <label><strong>Topic Name:</strong></label>
                            <input type="text" id="topicInput" class="form-control" value="<?php echo htmlspecialchars($default_topic); ?>" placeholder="e.g. all_users or postermaker_en" oninput="$('#destinationInput').val(this.value)">
                            <span class="help-block text-muted" style="font-size: 11px;">
                                Warning: Topic notifications reach ALL app users subscribed to this topic.
                            </span>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Notification Title:</strong></label>
                            <input type="text" name="title" id="titleInput" class="form-control" maxlength="100" placeholder="e.g. 🎉 New Festival Templates Ready!" required oninput="updatePreview()">
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Notification Message Body:</strong></label>
                            <textarea name="body" id="bodyInput" class="form-control" rows="3" maxlength="250" placeholder="e.g. Create personalized greeting posters for your loved ones in seconds." required oninput="updatePreview()"></textarea>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Category ID (Deep Link):</strong></label>
                            <select name="cat_id" class="form-control">
                                <option value="10">Sales (10)</option>
                                <option value="47" selected>Birthday (47)</option>
                                <option value="18">Anniversary (18)</option>
                                <option value="55">Festival / Diwali (55)</option>
                                <option value="11">Business (11)</option>
                                <option value="76">Art & Design (76)</option>
                                <option value="79">Motivation (79)</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label><strong>Optional Banner Image URL:</strong></label>
                            <input type="url" name="image_url" id="imageInput" class="form-control" placeholder="https://example.com/banner.jpg" oninput="updatePreview()">
                        </div>

                        <div class="form-group" style="margin-bottom: 20px; background: <?php echo ($dry_run == 0) ? '#e8f8f5' : '#f9f9f9'; ?>; padding: 12px; border-radius: 4px; border: 1px solid <?php echo ($dry_run == 0) ? '#2ecc71' : '#e1e8ed'; ?>;">
                            <label style="cursor: pointer; font-weight: 600; color: <?php echo ($dry_run == 0) ? '#27ae60' : '#c0392b'; ?>;">
                                <input type="checkbox" name="force_live" value="1" <?php echo ($dry_run == 0) ? 'checked' : ''; ?> style="margin-right: 6px;"> 
                                <?php if ($dry_run == 0): ?>
                                    <span class="label label-success" style="font-size: 10px; margin-right: 4px;">LIVE SYSTEM</span> Send as LIVE Dispatch (Delivers to real device)
                                <?php else: ?>
                                    Force LIVE Dispatch (Send to real FCM network now)
                                <?php endif; ?>
                            </label>
                            <div style="font-size: 11px; color: <?php echo ($dry_run == 0) ? '#1e8449' : '#7f8c8d'; ?>; margin-top: 4px;">
                                <?php if ($dry_run == 0): ?>
                                    System is currently in <strong>LIVE Mode</strong>. Notification will be delivered to the device immediately. Uncheck to run dry-run validation.
                                <?php else: ?>
                                    If left unchecked, the notification will be tested in <strong>SAFETY DRY-RUN</strong> mode (validates schema without sending to device).
                                <?php endif; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="padding: 10px; font-size: 15px;">
                            🚀 Execute Test Notification
                        </button>

                    </form>
                </div>
            </div>
        </div>

        <!-- Phone Mockup Preview -->
        <div class="col-md-5">
            <div class="block">
                <div class="head green">
                    <div class="icon"><span class="ico-mobile"></span></div>
                    <h2>Device Lockscreen Preview</h2>
                </div>
                <div class="data-fluid" style="padding: 25px; background: #2c3e50; min-height: 400px; display: flex; justify-content: center; align-items: center;">
                    
                    <!-- Android Notification Card -->
                    <div style="background: #ffffff; width: 100%; max-width: 340px; border-radius: 16px; padding: 14px 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 20px; height: 20px; background: #e74c3c; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 10px; font-weight: bold;">P</div>
                                <span style="font-size: 12px; font-weight: 600; color: #555;">POSTER MAKER</span>
                            </div>
                            <span style="font-size: 11px; color: #888;">Just now</span>
                        </div>
                        <div id="previewTitle" style="font-size: 14px; font-weight: bold; color: #111; margin-bottom: 4px;">
                            🎉 New Festival Templates Ready!
                        </div>
                        <div id="previewBody" style="font-size: 13px; color: #444; line-height: 1.4;">
                            Create personalized greeting posters for your loved ones in seconds.
                        </div>
                        <div id="previewImageContainer" style="display: none; margin-top: 10px; border-radius: 8px; overflow: hidden;">
                            <img id="previewImage" src="" style="width: 100%; max-height: 140px; object-fit: cover;">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleTarget(val) {
    if (val === 'TOPIC') {
        $('#tokenGroup').hide();
        $('#topicGroup').show();
        $('#destinationInput').val($('#topicInput').val());
    } else {
        $('#topicGroup').hide();
        $('#tokenGroup').show();
        $('#destinationInput').val('');
    }
}

function updatePreview() {
    var title = $('#titleInput').val();
    var body  = $('#bodyInput').val();
    var img   = $('#imageInput').val();

    $('#previewTitle').text(title ? title : 'Notification Title');
    $('#previewBody').text(body ? body : 'Notification message body will appear here...');

    if (img && img.startsWith('http')) {
        $('#previewImage').attr('src', img);
        $('#previewImageContainer').show();
    } else {
        $('#previewImageContainer').hide();
    }
}
</script>
