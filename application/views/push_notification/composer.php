<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><span class="ico-pencil"></span></div>
            <h1>Manual Push Composer <small><span class="cgreen">Rich Image Notifications</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>PushNotification">Push Notifications</a></li>
            <li class="active">Composer</li>
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
                <li class="active"><a href="<?php echo base_url('PushNotification/composer'); ?>">✍️ Manual Push Composer</a></li>
                <li><a href="<?php echo base_url('PushNotification/campaigns'); ?>">📢 Campaign History</a></li>
                <li><a href="<?php echo base_url('PushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li><a href="<?php echo base_url('PushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li><a href="<?php echo base_url('PushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- Composer Form -->
        <div class="col-md-7">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-envelope"></span></div>
                    <h2>Create Push Notification</h2>
                </div>
                <div class="data-fluid" style="padding: 20px;">
                    <form action="<?php echo base_url('PushNotification/send_manual'); ?>" method="POST" enctype="multipart/form-data" id="composerForm">
                        
                        <!-- Notification Title -->
                        <div class="form-group" style="margin-bottom: 18px;">
                            <label><strong>Notification Title:</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="notifTitle" class="form-control" maxlength="150" 
                                   placeholder="e.g. 🌟 Flash Sale! 50% Off On All Festival Flyers" 
                                   value="<?php echo $draft ? htmlspecialchars($draft['title']) : ''; ?>" 
                                   required oninput="updateLivePreview()">
                            <span class="help-block text-muted" style="font-size: 11px;">Keep title punchy and under 60 characters for best display on mobile lockscreens.</span>
                        </div>

                        <!-- Notification Description -->
                        <div class="form-group" style="margin-bottom: 18px;">
                            <label><strong>Notification Description / Message:</strong> <span class="text-danger">*</span></label>
                            <textarea name="message" id="notifBody" class="form-control" rows="3" maxlength="500" 
                                      placeholder="e.g. Design stunning posters for your business today. Tap to explore fresh trending templates." 
                                      required oninput="updateLivePreview()"><?php echo $draft ? htmlspecialchars($draft['message']) : ''; ?></textarea>
                            <span class="help-block text-muted" style="font-size: 11px;">Max 500 characters. Supports emojis.</span>
                        </div>

                        <!-- Image Upload & URL -->
                        <div class="form-group" style="margin-bottom: 18px; background: #fdfefe; border: 1px dashed #3498db; padding: 15px; border-radius: 6px;">
                            <label><strong>Rich Notification Banner Image:</strong> (Optional)</label>
                            <div style="margin-bottom: 10px;">
                                <input type="file" name="image" id="notifImageFile" accept="image/jpeg,image/png,image/webp" class="form-control" onchange="handleImageFileSelect(this)">
                            </div>
                            <div class="text-center text-muted" style="margin: 5px 0; font-size: 11px;">— OR ENTER DIRECT HTTPS IMAGE URL —</div>
                            <input type="url" name="image_url" id="notifImageUrl" class="form-control" 
                                   placeholder="https://example.com/images/banner.jpg" 
                                   value="<?php echo $draft ? htmlspecialchars($draft['image_url']) : ''; ?>" 
                                   oninput="handleImageUrlInput(this.value)">
                            <span class="help-block text-muted" style="font-size: 11px; margin-top: 5px;">
                                Supported formats: JPG, PNG, WebP (Max 2MB). Recommended ratio: 2:1 (e.g. 1024x512px).
                            </span>
                        </div>

                        <!-- Deep Link App Screen -->
                        <div class="form-group" style="margin-bottom: 18px;">
                            <label><strong>Optional App Screen / Deep Link Target:</strong></label>
                            <select name="cat_id" id="notifCatId" class="form-control" onchange="updateLivePreview()">
                                <option value="">-- Open App Home Screen --</option>
                                <?php foreach ($categories as $cat) { ?>
                                    <option value="<?php echo $cat['cat_id']; ?>" <?php echo ($draft && $draft['cat_id'] == $cat['cat_id']) ? 'selected' : ''; ?>>
                                        Category: <?php echo htmlspecialchars($cat['cat_name']); ?> (ID: <?php echo $cat['cat_id']; ?>)
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="help-block text-muted" style="font-size: 11px;">
                                Clicking the notification directly opens this category inside Postermaker.
                            </span>
                        </div>

                        <!-- Target Audience -->
                        <div class="form-group" style="margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 6px; border: 1px solid #e9ecef;">
                            <label><strong>Target Audience:</strong> <span class="text-danger">*</span></label>
                            <div style="margin-bottom: 10px;">
                                <label class="radio-inline" style="margin-right: 15px; font-weight: 600;">
                                    <input type="radio" name="target_type" value="TOPIC" checked onchange="handleAudienceChange('TOPIC')">
                                    All Subscribed App Users (Topic: <code><?php echo htmlspecialchars($default_topic); ?></code>)
                                </label>
                                <br>
                                <label class="radio-inline" style="margin-right: 15px;">
                                    <input type="radio" name="target_type" value="TEST_DEVICES" onchange="handleAudienceChange('TEST_DEVICES')">
                                    Selected Registered Test Devices (<?php echo count($test_devices); ?> available)
                                </label>
                                <br>
                                <label class="radio-inline">
                                    <input type="radio" name="target_type" value="CUSTOM_TOKEN" onchange="handleAudienceChange('CUSTOM_TOKEN')">
                                    Specific Device FCM Token (Single Device)
                                </label>
                            </div>

                            <!-- Topic input field -->
                            <div id="topicField" style="margin-top: 10px;">
                                <label style="font-size: 12px;">Topic Identifier:</label>
                                <input type="text" name="target_value" id="targetValueTopic" class="form-control" value="<?php echo htmlspecialchars($default_topic); ?>" placeholder="all_users">
                                <span class="help-block text-muted" style="font-size: 11px;">
                                    Estimated Audience: <strong>All installed Android devices subscribed to this topic</strong>.
                                </span>
                            </div>

                            <!-- Custom Token input field -->
                            <div id="tokenField" style="margin-top: 10px; display: none;">
                                <label style="font-size: 12px;">Device Registration Token:</label>
                                <textarea name="custom_token" id="targetValueToken" class="form-control" rows="2" placeholder="Paste single device FCM registration token..."></textarea>
                            </div>
                        </div>

                        <!-- Safety Transmission Toggle -->
                        <div class="form-group" style="margin-bottom: 25px; background: <?php echo ($dry_run == 0) ? '#e8f8f5' : '#fff8e1'; ?>; padding: 14px 16px; border-radius: 6px; border: 1px solid <?php echo ($dry_run == 0) ? '#2ecc71' : '#ffe082'; ?>;">
                            <label style="cursor: pointer; font-weight: bold; color: <?php echo ($dry_run == 0) ? '#27ae60' : '#b78103'; ?>; margin-bottom: 0; font-size: 13.5px;">
                                <input type="checkbox" name="force_live" id="forceLiveCheckbox" value="1" <?php echo ($dry_run == 0) ? 'checked' : ''; ?> style="margin-right: 8px;"> 
                                <?php if ($dry_run == 0): ?>
                                    <span class="label label-success" style="font-size: 11px; margin-right: 5px;">LIVE MODE ACTIVE</span> Send as LIVE BROADCAST to real devices
                                <?php else: ?>
                                    <span class="label label-warning" style="font-size: 11px; margin-right: 5px;">DRY-RUN DEFAULT</span> Check to Send LIVE Broadcast to real devices
                                <?php endif; ?>
                            </label>
                            <div style="font-size: 12px; color: <?php echo ($dry_run == 0) ? '#1e8449' : '#795548'; ?>; margin-top: 6px;">
                                <?php if ($dry_run == 0): ?>
                                    System is currently in <strong>LIVE Mode</strong>. This message will be delivered directly to Android devices. (Uncheck if you want a safety dry-run test).
                                <?php else: ?>
                                    System is currently in <strong>Safety Dry-Run Mode</strong>. Leave unchecked to safely validate without sending to users. Check box to bypass and send live.
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button type="button" class="btn btn-primary" onclick="initiateSend()" style="padding: 10px 20px; font-size: 14px; font-weight: 600;">
                                🚀 Send Now
                            </button>
                            <button type="button" class="btn btn-default" onclick="saveAsDraft()" style="padding: 10px 20px; font-size: 14px;">
                                💾 Save as Draft
                            </button>
                            <button type="button" class="btn btn-info" onclick="updateLivePreview()" style="padding: 10px 18px; font-size: 14px;">
                                👁 Preview Notification
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Phone Lockscreen Mockup Preview -->
        <div class="col-md-5">
            <div class="block">
                <div class="head green">
                    <div class="icon"><span class="ico-mobile"></span></div>
                    <h2>Android Notification Preview</h2>
                </div>
                <div class="data-fluid" style="padding: 30px 20px; background: #1a252f; min-height: 520px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    
                    <div style="color: #ecf0f1; font-size: 13px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;">
                        Lockscreen Appearance
                    </div>

                    <!-- Android 13/14 System Tray Card -->
                    <div style="background: #ffffff; width: 100%; max-width: 360px; border-radius: 18px; padding: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
                        
                        <!-- Header -->
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 22px; height: 22px; background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 11px; font-weight: bold;">
                                    P
                                </div>
                                <span style="font-size: 12px; font-weight: 600; color: #495057;">POSTER MAKER</span>
                                <span style="font-size: 11px; color: #adb5bd;">•</span>
                                <span id="previewCategoryBadge" style="font-size: 11px; color: #3498db; font-weight: 500;">General</span>
                            </div>
                            <span style="font-size: 11px; color: #6c757d;">now</span>
                        </div>

                        <!-- Title -->
                        <div id="cardPreviewTitle" style="font-size: 14.5px; font-weight: 700; color: #212529; margin-bottom: 5px; line-height: 1.3;">
                            Notification Title Preview
                        </div>

                        <!-- Body -->
                        <div id="cardPreviewBody" style="font-size: 13px; color: #495057; line-height: 1.45; margin-bottom: 8px;">
                            Your notification message will appear here in clean typography.
                        </div>

                        <!-- BigPicture Banner -->
                        <div id="cardPreviewImageWrapper" style="display: none; margin-top: 10px; border-radius: 12px; overflow: hidden; background: #e9ecef; border: 1px solid #dee2e6;">
                            <img id="cardPreviewImage" src="" style="width: 100%; height: auto; max-height: 170px; object-fit: cover; display: block;">
                        </div>

                        <!-- Action Bar -->
                        <div style="margin-top: 12px; padding-top: 8px; border-top: 1px solid #f1f3f5; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 11px; color: #868e96;">Tap to open template</span>
                            <span style="font-size: 11px; font-weight: 600; color: #2980b9;">OPEN APP &rsaquo;</span>
                        </div>

                    </div>

                    <div style="color: #95a5a6; font-size: 11px; margin-top: 20px; text-align: center; max-width: 320px;">
                        * Android automatically renders expanded BigPicture image style when user pulls down on notification shade.
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Broadcast Confirmation Modal -->
<div id="confirmBroadcastModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #e74c3c; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.8;">&times;</button>
                <h4 class="modal-title"><span class="ico-warning-sign"></span> Confirm Live Broadcast</h4>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <p style="font-size: 15px; font-weight: 600; color: #2c3e50;">
                    You are about to send a LIVE notification to real users:
                </p>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                    <p style="margin-bottom: 5px;"><strong>Target Audience:</strong> <span id="modalAudience" class="label label-danger">All Subscribed Users</span></p>
                    <p style="margin-bottom: 5px;"><strong>Title:</strong> <span id="modalTitle"></span></p>
                    <p style="margin-bottom: 0;"><strong>Message:</strong> <span id="modalBody"></span></p>
                </div>
                <div class="alert alert-warning" style="margin-bottom: 0;">
                    <small>This notification will be dispatched via Firebase Cloud Messaging HTTP v1 immediately. This action cannot be undone.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="submitFinalSend()">Yes, Confirm & Send Broadcast</button>
            </div>
        </div>
    </div>
</div>

<script>
function handleAudienceChange(type) {
    if (type === 'TOPIC') {
        $('#topicField').show();
        $('#tokenField').hide();
    } else if (type === 'CUSTOM_TOKEN') {
        $('#topicField').hide();
        $('#tokenField').show();
    } else {
        $('#topicField').hide();
        $('#tokenField').hide();
    }
}

function handleImageFileSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        if (file.size > 2 * 1024 * 1024) {
            alert('File size exceeds 2MB limit. Please choose a smaller image.');
            input.value = '';
            $('#cardPreviewImageWrapper').hide();
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#cardPreviewImage').attr('src', e.target.result);
            $('#cardPreviewImageWrapper').show();
        };
        reader.readAsDataURL(file);
    }
}

function handleImageUrlInput(url) {
    if (url && url.startsWith('http')) {
        $('#cardPreviewImage').attr('src', url);
        $('#cardPreviewImageWrapper').show();
    } else if (!$('#notifImageFile').val()) {
        $('#cardPreviewImageWrapper').hide();
    }
}

function updateLivePreview() {
    var title = $('#notifTitle').val();
    var body  = $('#notifBody').val();
    var catText = $('#notifCatId option:selected').text();

    $('#cardPreviewTitle').text(title ? title : 'Notification Title Preview');
    $('#cardPreviewBody').text(body ? body : 'Your notification message will appear here in clean typography.');
    
    if (catText && !catText.startsWith('--')) {
        $('#previewCategoryBadge').text(catText.split('(')[0].replace('Category:', '').trim());
    } else {
        $('#previewCategoryBadge').text('General');
    }
}

function initiateSend() {
    var title = $('#notifTitle').val().trim();
    var body  = $('#notifBody').val().trim();
    if (!title || !body) {
        alert('Please provide both Title and Notification Message before sending.');
        return;
    }

    var isLive = $('#forceLiveCheckbox').is(':checked');
    var targetType = $('input[name="target_type"]:checked').val();

    // If sending to ALL users as LIVE broadcast, require modal confirmation
    if (isLive && targetType === 'TOPIC') {
        $('#modalTitle').text(title);
        $('#modalBody').text(body);
        $('#modalAudience').text('Topic: ' + $('#targetValueTopic').val());
        $('#confirmBroadcastModal').modal('show');
    } else if (isLive) {
        // Targeted live send to Test Devices or Custom Token
        var targetLabel = (targetType === 'TEST_DEVICES') ? 'Selected Test Devices' : 'Custom Token';
        if (confirm("🚀 Confirm LIVE Send:\n\nYou are about to send this notification LIVE to " + targetLabel + ".\n\nDeliver now?")) {
            submitFinalSend();
        }
    } else {
        // Explicit warning for Dry Run
        if (confirm("⚠️ SAFETY DRY-RUN WARNING:\n\nThe 'Send as LIVE BROADCAST' box is unchecked.\n\nThis notification will ONLY validate the payload with Firebase. NO DEVICES will receive it.\n\nProceed with Dry-Run validation?")) {
            submitFinalSend();
        }
    }
}

function submitFinalSend() {
    $('#confirmBroadcastModal').modal('hide');
    $('#composerForm').submit();
}

function saveAsDraft() {
    var title = $('#notifTitle').val().trim();
    var body  = $('#notifBody').val().trim();
    if (!title || !body) {
        alert('Please enter at least a title and message to save as draft.');
        return;
    }
    $('#composerForm').attr('action', '<?php echo base_url("PushNotification/save_draft"); ?>');
    $('#composerForm').submit();
}

// Initial preview update
$(document).ready(function() {
    updateLivePreview();
    <?php if ($draft && !empty($draft['image_url'])) { ?>
        handleImageUrlInput('<?php echo htmlspecialchars($draft['image_url']); ?>');
    <?php } ?>
});
</script>
