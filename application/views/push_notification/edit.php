<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><span class="ico-pencil"></span></div>
            <h1>
                Edit Notification #<?php echo $campaign['id']; ?> 
                <small>
                    <?php if ($campaign['status'] === 'SCHEDULED') { ?>
                        <span class="label label-info" style="font-size: 11px; vertical-align: middle;">⏰ SCHEDULED</span>
                    <?php } elseif ($campaign['status'] === 'DRAFT') { ?>
                        <span class="label label-default" style="font-size: 11px; vertical-align: middle;">DRAFT</span>
                    <?php } elseif ($campaign['status'] === 'SENT') { ?>
                        <span class="label label-success" style="font-size: 11px; vertical-align: middle;">SENT</span>
                    <?php } else { ?>
                        <span class="label label-warning" style="font-size: 11px; vertical-align: middle;"><?php echo $campaign['status']; ?></span>
                    <?php } ?>
                </small>
            </h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>PushNotification">Push Notifications</a></li>
            <li><a href="<?php echo base_url(); ?>PushNotification/campaigns">Campaigns</a></li>
            <li class="active">Edit #<?php echo $campaign['id']; ?></li>
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

    <!-- App Workspace Selector Bar -->
    <?php if (file_exists(APPPATH . 'views/push_notification/app_selector_bar.php')) {
        $this->load->view('push_notification/app_selector_bar');
    } ?>

    <!-- Navigation Tabs -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li><a href="<?php echo base_url('PushNotification'); ?>">📊 Dashboard</a></li>
                <li><a href="<?php echo base_url('PushNotification/bank'); ?>">🗂 Content Bank (2,000)</a></li>
                <li><a href="<?php echo base_url('PushNotification/composer'); ?>">✍️ Manual Push Composer</a></li>
                <li><a href="<?php echo base_url('PushNotification/campaigns'); ?>">📢 Campaign History</a></li>
                <li><a href="<?php echo base_url('PushNotification/import_json'); ?>">📥 Bulk JSON Import</a></li>
                <li class="active"><a href="#">✏️ Edit Notification #<?php echo $campaign['id']; ?></a></li>
                <li><a href="<?php echo base_url('PushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li><a href="<?php echo base_url('PushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li><a href="<?php echo base_url('PushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <?php 
    $scheduled_date = !empty($campaign['scheduled_at']) ? date('Y-m-d', strtotime($campaign['scheduled_at'])) : date('Y-m-d');
    $scheduled_time = !empty($campaign['scheduled_at']) ? date('H:i', strtotime($campaign['scheduled_at'])) : date('H:i', strtotime('+15 minutes'));
    $current_app    = !empty($campaign['app_code']) ? $campaign['app_code'] : $active_app;
    ?>

    <div class="row">
        <!-- Edit Form (Left Column) -->
        <div class="col-md-7">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-edit"></span></div>
                    <h2>Edit Notification Details &amp; Upload Banner</h2>
                </div>
                <div class="data-fluid" style="padding: 20px;">
                    <form action="<?php echo base_url('PushNotification/update_campaign/' . $campaign['id']); ?>" method="POST" enctype="multipart/form-data" id="editForm">
                        
                        <!-- Target Application Selection Dropdown -->
                        <div class="form-group" style="margin-bottom: 20px; background: #eef4f8; padding: 14px 18px; border-radius: 6px; border: 1px solid #b8d4e8;">
                            <label style="font-size: 13.5px; color: #1e3c72; margin-bottom: 8px;">
                                <strong><span class="ico-mobile"></span> Target Application:</strong> <span class="text-danger">*</span>
                            </label>
                            <select name="target_app" id="targetAppSelect" class="form-control" style="font-weight: 600; font-size: 13.5px; height: 38px;">
                                <option value="chirag" <?php echo ($current_app === 'chirag') ? 'selected' : ''; ?>>
                                    🔵 Chirag Sir App Only (firebase_service_account.json)
                                </option>
                                <option value="nitin" <?php echo ($current_app === 'nitin') ? 'selected' : ''; ?>>
                                    🟢 Nitin Sir App Only (nitin_poster_service_account.json)
                                </option>
                                <option value="both" <?php echo ($current_app === 'both') ? 'selected' : ''; ?>>
                                    📢 Both Apps Separately (Dispatch to Chirag Sir &amp; Nitin Sir)
                                </option>
                            </select>
                        </div>

                        <!-- Notification Title -->
                        <div class="form-group" style="margin-bottom: 18px;">
                            <label><strong>Notification Title:</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="notifTitle" class="form-control" maxlength="150" 
                                   value="<?php echo htmlspecialchars($campaign['title'], ENT_QUOTES, 'UTF-8'); ?>" 
                                   required oninput="updateLivePreview()">
                            <span class="help-block text-muted" style="font-size: 11px;">Keep title punchy and under 60 characters for best display on mobile lockscreens.</span>
                        </div>

                        <!-- Notification Description -->
                        <div class="form-group" style="margin-bottom: 18px;">
                            <label><strong>Notification Description / Message:</strong> <span class="text-danger">*</span></label>
                            <textarea name="message" id="notifBody" class="form-control" rows="3" maxlength="500" 
                                      required oninput="updateLivePreview()"><?php echo htmlspecialchars($campaign['message'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <span class="help-block text-muted" style="font-size: 11px;">Max 500 characters. Supports emojis.</span>
                        </div>

                        <!-- Rich Notification Banner Image Upload & URL (Primary Edit Requirement) -->
                        <div class="form-group" style="margin-bottom: 22px; background: #fdfefe; border: 2px dashed #0284c7; padding: 18px; border-radius: 8px;">
                            <label style="font-size: 14px; color: #0369a1; display: block; margin-bottom: 10px;">
                                <strong>🖼️ Rich Notification Banner Image (ઈમેજ અપલોડ કરો):</strong>
                            </label>

                            <!-- Current Image Display if available -->
                            <?php if (!empty($campaign['image_url'])) { ?>
                                <div id="currentImageContainer" style="background: #f1f5f9; padding: 12px; border-radius: 6px; margin-bottom: 14px; border: 1px solid #cbd5e1;">
                                    <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">
                                        Current Image:
                                    </div>
                                    <div style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
                                        <a href="<?php echo htmlspecialchars($campaign['image_url']); ?>" target="_blank">
                                            <img src="<?php echo htmlspecialchars($campaign['image_url']); ?>" id="currentImageThumb" style="max-width: 140px; max-height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid #94a3b8; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                        </a>
                                        <div>
                                            <label style="cursor: pointer; color: #dc2626; font-size: 12.5px; font-weight: 600;">
                                                <input type="checkbox" name="remove_image" id="removeImageCheckbox" value="1" onchange="toggleRemoveImage(this)" style="margin-right: 6px;">
                                                🗑️ Remove current banner image
                                            </label>
                                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                                Check this box if you want this notification to be text-only without any banner.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <!-- Upload New Image File -->
                            <div style="margin-bottom: 12px;">
                                <label style="font-size: 12.5px; color: #334155; font-weight: 600;">
                                    <span class="ico-upload"></span> <strong>Upload New Image File (કોમ્પ્યુટરમાંથી નવી ઈમેજ પસંદ કરો):</strong>
                                </label>
                                <input type="file" name="image" id="notifImageFile" accept="image/jpeg,image/png,image/webp" class="form-control" onchange="handleImageFileSelect(this)">
                                <span class="help-block text-muted" style="font-size: 11px; margin-top: 4px;">
                                    Supported formats: JPG, PNG, WebP (Max 2MB). Recommended ratio: 2:1 (1024x512px).
                                </span>
                            </div>

                            <!-- Or Direct Image URL -->
                            <div class="text-center text-muted" style="margin: 8px 0; font-size: 11px; font-weight: 700;">— OR ENTER DIRECT HTTPS IMAGE URL —</div>
                            <input type="url" name="image_url" id="notifImageUrl" class="form-control" 
                                   placeholder="https://example.com/images/banner.jpg" 
                                   value="<?php echo htmlspecialchars($campaign['image_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                   oninput="handleImageUrlInput(this.value)">
                        </div>

                        <!-- Delivery Timing & Status -->
                        <div class="form-group" style="margin-bottom: 22px; background: #f0f9ff; padding: 16px; border-radius: 8px; border: 1px solid #bae6fd;">
                            <label style="font-size: 13.5px; color: #0369a1; margin-bottom: 10px; display: block;">
                                <strong><span class="ico-time"></span> Delivery Timing (ક્યારે મોકલવી છે?):</strong> <span class="text-danger">*</span>
                            </label>
                            
                            <div style="display: flex; gap: 20px; margin-bottom: 12px; flex-wrap: wrap;">
                                <label style="cursor: pointer; font-weight: 700; font-size: 13px; color: #0284c7;">
                                    <input type="radio" name="delivery_mode" value="SCHEDULE" <?php echo ($campaign['status'] === 'SCHEDULED' || (!in_array($campaign['status'], array('PAUSED', 'DRAFT')) && !empty($campaign['scheduled_at']))) ? 'checked' : ''; ?> onchange="toggleDeliverySchedule(true)" style="margin-right: 6px;">
                                    ⏰ Schedule for Date &amp; Time (શેડ્યૂલ કરેલી રાખો)
                                </label>
                                <label style="cursor: pointer; font-weight: 700; font-size: 13px; color: #d97706;">
                                    <input type="radio" name="delivery_mode" value="PAUSED" <?php echo ($campaign['status'] === 'PAUSED') ? 'checked' : ''; ?> onchange="toggleDeliverySchedule(false)" style="margin-right: 6px;">
                                    ⏸️ Pause Notification (ડિસ્પેચ અટકાવો)
                                </label>
                                <label style="cursor: pointer; font-weight: 600; font-size: 13px; color: #1e293b;">
                                    <input type="radio" name="delivery_mode" value="NOW" onchange="toggleDeliverySchedule(false)" style="margin-right: 6px;">
                                    🚀 Send Immediately Right Now (હમણાં જ મોકલો)
                                </label>
                                <label style="cursor: pointer; font-weight: 600; font-size: 13px; color: #64748b;">
                                    <input type="radio" name="delivery_mode" value="DRAFT" <?php echo ($campaign['status'] === 'DRAFT') ? 'checked' : ''; ?> onchange="toggleDeliverySchedule(false)" style="margin-right: 6px;">
                                    💾 Save as Inactive Draft
                                </label>
                            </div>

                            <!-- Date & Time Pickers Container -->
                            <div id="scheduleInputsContainer" style="<?php echo ($campaign['status'] === 'SCHEDULED' || !empty($campaign['scheduled_at'])) ? '' : 'display: none;'; ?> background: #ffffff; padding: 14px 16px; border-radius: 6px; border: 1px solid #7dd3fc; margin-top: 10px;">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6" style="margin-bottom: 10px;">
                                        <label style="font-size: 12px; color: #334155; font-weight: 700;">
                                            📅 Scheduled Date (તારીખ): <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="scheduled_date" id="scheduledDate" class="form-control" value="<?php echo $scheduled_date; ?>" style="font-weight: 600; font-size: 14px; border-color: #38bdf8;">
                                    </div>
                                    <div class="col-xs-12 col-sm-6" style="margin-bottom: 10px;">
                                        <label style="font-size: 12px; color: #334155; font-weight: 700;">
                                            ⏱️ Scheduled Time (સમય - Asia/Kolkata): <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" name="scheduled_time" id="scheduledTime" class="form-control" value="<?php echo $scheduled_time; ?>" style="font-weight: 600; font-size: 14px; border-color: #38bdf8;">
                                    </div>
                                </div>
                                <div style="font-size: 11.5px; color: #0284c7; margin-top: 4px;">
                                    💡 Cron Job will automatically dispatch this notification at the exact chosen date and time.
                                </div>
                            </div>
                        </div>

                        <!-- Deep Link App Screen -->
                        <div class="form-group" style="margin-bottom: 18px;">
                            <label><strong>App Screen / Deep Link Target Category:</strong></label>
                            <select name="cat_id" id="notifCatId" class="form-control" onchange="updateLivePreview()">
                                <option value="10">-- Open App (Default Category: 10) --</option>
                                <?php foreach ($categories as $cat) { ?>
                                    <option value="<?php echo $cat['cat_id']; ?>" <?php echo ($campaign['cat_id'] == $cat['cat_id']) ? 'selected' : ''; ?>>
                                        Category: <?php echo htmlspecialchars($cat['cat_name']); ?> (ID: <?php echo $cat['cat_id']; ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Target Audience -->
                        <div class="form-group" style="margin-bottom: 20px; background: #f8f9fa; padding: 14px 16px; border-radius: 6px; border: 1px solid #e9ecef;">
                            <label><strong>Target Audience:</strong></label>
                            <div style="margin-bottom: 10px;">
                                <label class="radio-inline" style="margin-right: 15px; font-weight: 600;">
                                    <input type="radio" name="target_type" value="TOPIC" <?php echo ($campaign['target_type'] === 'TOPIC') ? 'checked' : ''; ?> onchange="handleAudienceChange('TOPIC')">
                                    All Users Topic (<code><?php echo htmlspecialchars($default_topic); ?></code>)
                                </label>
                                <label class="radio-inline" style="margin-right: 15px;">
                                    <input type="radio" name="target_type" value="TEST_DEVICES" <?php echo ($campaign['target_type'] === 'TEST_DEVICES') ? 'checked' : ''; ?> onchange="handleAudienceChange('TEST_DEVICES')">
                                    Selected Test Devices (<?php echo count($test_devices); ?>)
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="target_type" value="CUSTOM_TOKEN" <?php echo ($campaign['target_type'] === 'CUSTOM_TOKEN') ? 'checked' : ''; ?> onchange="handleAudienceChange('CUSTOM_TOKEN')">
                                    Single Token
                                </label>
                            </div>

                            <div id="topicField" style="margin-top: 10px; <?php echo ($campaign['target_type'] === 'TOPIC') ? '' : 'display: none;'; ?>">
                                <input type="text" name="target_value" id="targetValueTopic" class="form-control" value="<?php echo htmlspecialchars($campaign['target_value'] ?: $default_topic); ?>" placeholder="all_users">
                            </div>

                            <div id="tokenField" style="margin-top: 10px; <?php echo ($campaign['target_type'] === 'CUSTOM_TOKEN') ? '' : 'display: none;'; ?>">
                                <textarea name="custom_token" id="targetValueToken" class="form-control" rows="2" placeholder="Paste FCM registration token..."><?php echo ($campaign['target_type'] === 'CUSTOM_TOKEN') ? htmlspecialchars($campaign['target_value']) : ''; ?></textarea>
                            </div>
                        </div>

                        <!-- Safety Transmission Toggle -->
                        <div class="form-group" style="margin-bottom: 25px; background: <?php echo ($campaign['is_dry_run'] == 0) ? '#e8f8f5' : '#fff8e1'; ?>; padding: 12px 16px; border-radius: 6px; border: 1px solid <?php echo ($campaign['is_dry_run'] == 0) ? '#2ecc71' : '#ffe082'; ?>;">
                            <label style="cursor: pointer; font-weight: bold; color: <?php echo ($campaign['is_dry_run'] == 0) ? '#27ae60' : '#b78103'; ?>; margin-bottom: 0; font-size: 13px;">
                                <input type="checkbox" name="force_live" id="forceLiveCheckbox" value="1" <?php echo ($campaign['is_dry_run'] == 0) ? 'checked' : ''; ?> style="margin-right: 8px;"> 
                                <?php if ($campaign['is_dry_run'] == 0): ?>
                                    <span class="label label-success" style="font-size: 10.5px; margin-right: 5px;">LIVE MODE</span> Send as LIVE broadcast to real mobile devices
                                <?php else: ?>
                                    <span class="label label-warning" style="font-size: 10.5px; margin-right: 5px;">DRY-RUN DEFAULT</span> Check to send LIVE to real mobile devices
                                <?php endif; ?>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                            <button type="submit" class="btn btn-primary" id="btnUpdateSubmit" style="padding: 10px 22px; font-size: 13.5px; font-weight: 700; box-shadow: 0 4px 10px rgba(52, 152, 219, 0.25);">
                                💾 Save Changes &amp; Update
                            </button>
                            <?php if ($campaign['status'] === 'PAUSED'): ?>
                                <a href="<?php echo base_url('PushNotification/resume_campaign/' . $campaign['id']); ?>" class="btn btn-info" style="padding: 10px 16px; font-size: 13.5px; font-weight: 700; background: #0284c7; color: #fff;">
                                    ▶️ Resume
                                </a>
                            <?php else: ?>
                                <a href="<?php echo base_url('PushNotification/pause_campaign/' . $campaign['id']); ?>" class="btn btn-warning" onclick="return confirm('Pause notification #<?php echo $campaign['id']; ?>? It will NOT be sent until resumed.');" style="padding: 10px 16px; font-size: 13.5px; font-weight: 700; background: #f39c12; border-color: #e67e22; color: #fff;">
                                    ⏸️ Pause
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo base_url('PushNotification/delete_campaign/' . $campaign['id']); ?>" class="btn btn-danger" onclick="return confirm('Permanently delete notification #<?php echo $campaign['id']; ?>? This cannot be undone.');" style="padding: 10px 16px; font-size: 13.5px; font-weight: 700; background: #e74c3c; border-color: #c0392b;">
                                🗑️ Delete
                            </a>
                            <a href="<?php echo base_url('PushNotification/campaigns'); ?>" class="btn btn-default" style="padding: 10px 18px; font-size: 13.5px;">
                                Cancel / Back
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Android Lockscreen Live Preview (Right Column) -->
        <div class="col-md-5">
            <div class="block">
                <div class="head green">
                    <div class="icon"><span class="ico-mobile"></span></div>
                    <h2>Android Live Notification Preview</h2>
                </div>
                <div class="data-fluid" style="padding: 30px 20px; background: #1a252f; min-height: 520px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    
                    <!-- Phone Shell Frame -->
                    <div style="width: 320px; background: #2c3e50; border-radius: 28px; padding: 14px 12px 20px 12px; box-shadow: 0 16px 36px rgba(0,0,0,0.5); border: 2px solid #34495e;">
                        
                        <!-- Phone Notch / Status Bar -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 8px 12px 8px; color: #bdc3c7; font-size: 11px; font-weight: 600;">
                            <span><?php echo date('H:i'); ?></span>
                            <div style="width: 50px; height: 4px; background: #4a6278; border-radius: 4px;"></div>
                            <span><span class="ico-signal"></span> <span class="ico-tint"></span> 98%</span>
                        </div>

                        <!-- Lockscreen Notification Card Container -->
                        <div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); border-radius: 16px; padding: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.4);">
                            
                            <!-- App Branding Header inside Banner -->
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <div style="width: 18px; height: 18px; background: linear-gradient(135deg, #3498db, #9b59b6); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 10px; font-weight: bold;">
                                        P
                                    </div>
                                    <span style="font-size: 11.5px; font-weight: 700; color: #34495e; text-transform: uppercase; letter-spacing: 0.5px;">POSTER MAKER</span>
                                    <span style="font-size: 10px; color: #7f8c8d;">• just now</span>
                                </div>
                                <span class="badge badge-info" id="previewCategoryBadge" style="font-size: 9px; padding: 2px 6px; background: #3498db; text-transform: uppercase;">Category</span>
                            </div>

                            <!-- Notification Title -->
                            <div id="cardPreviewTitle" style="font-size: 13.5px; font-weight: 700; color: #1e272e; margin-bottom: 4px; line-height: 1.35; word-break: break-word;">
                                <?php echo htmlspecialchars($campaign['title'], ENT_QUOTES, 'UTF-8'); ?>
                            </div>

                            <!-- Notification Body -->
                            <div id="cardPreviewBody" style="font-size: 12px; color: #485460; line-height: 1.4; margin-bottom: 10px; word-break: break-word;">
                                <?php echo htmlspecialchars($campaign['message'], ENT_QUOTES, 'UTF-8'); ?>
                            </div>

                            <!-- Big Rich Banner Image Preview -->
                            <div id="cardPreviewImageWrapper" style="<?php echo !empty($campaign['image_url']) ? '' : 'display: none;'; ?> margin-top: 8px; border-radius: 10px; overflow: hidden; background: #ecf0f1; border: 1px solid #dcdde1; max-height: 150px;">
                                <img id="cardPreviewImage" src="<?php echo htmlspecialchars($campaign['image_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; height: 150px; object-fit: cover; display: block;" alt="Notification Banner">
                            </div>

                            <!-- Notification Quick Action Pill -->
                            <div style="display: flex; gap: 8px; margin-top: 10px; padding-top: 8px; border-top: 1px solid #eceff1;">
                                <span style="font-size: 11px; font-weight: 600; color: #2980b9; cursor: pointer;">EXPLORE TEMPLATES &gt;</span>
                            </div>

                        </div>

                        <!-- Home Bar -->
                        <div style="width: 90px; height: 3px; background: #7f8c8d; border-radius: 2px; margin: 18px auto 0 auto;"></div>

                    </div>

                    <div style="color: #95a5a6; font-size: 12px; text-align: center; margin-top: 15px;">
                        Live interactive preview updates instantly as you edit text or pick an image.
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
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
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#cardPreviewImage').attr('src', e.target.result);
            $('#cardPreviewImageWrapper').show();
            // Uncheck remove checkbox if checked
            $('#removeImageCheckbox').prop('checked', false);
        };
        reader.readAsDataURL(file);
    }
}

function handleImageUrlInput(url) {
    if (url && url.startsWith('http')) {
        $('#cardPreviewImage').attr('src', url);
        $('#cardPreviewImageWrapper').show();
        $('#removeImageCheckbox').prop('checked', false);
    } else if (!$('#notifImageFile').val() && !$('#removeImageCheckbox').is(':checked')) {
        // Keep current or hide
    }
}

function toggleRemoveImage(checkbox) {
    if ($(checkbox).is(':checked')) {
        $('#cardPreviewImageWrapper').hide();
        $('#notifImageFile').val('');
        $('#notifImageUrl').val('');
    } else {
        var existingUrl = '<?php echo htmlspecialchars($campaign['image_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>';
        if (existingUrl) {
            $('#cardPreviewImage').attr('src', existingUrl);
            $('#cardPreviewImageWrapper').show();
            $('#notifImageUrl').val(existingUrl);
        }
    }
}

function updateLivePreview() {
    var title = $('#notifTitle').val();
    var body  = $('#notifBody').val();
    var catText = $('#notifCatId option:selected').text();

    $('#cardPreviewTitle').text(title ? title : 'Notification Title');
    $('#cardPreviewBody').text(body ? body : 'Your notification message description will appear here.');
    
    if (catText && !catText.startsWith('--')) {
        $('#previewCategoryBadge').text(catText.split('(')[0].replace('Category:', '').trim());
    } else {
        $('#previewCategoryBadge').text('General');
    }
}

function toggleDeliverySchedule(isSchedule) {
    if (isSchedule) {
        $('#scheduleInputsContainer').slideDown(200);
        $('#btnUpdateSubmit').html('💾 Save Changes &amp; Keep Scheduled').removeClass('btn-success').addClass('btn-primary');
    } else {
        var mode = $('input[name="delivery_mode"]:checked').val();
        $('#scheduleInputsContainer').slideUp(200);
        if (mode === 'NOW') {
            $('#btnUpdateSubmit').html('🚀 Save &amp; Dispatch Immediately Now').removeClass('btn-primary').addClass('btn-success');
        } else {
            $('#btnUpdateSubmit').html('💾 Save as Draft').removeClass('btn-success').addClass('btn-primary');
        }
    }
}

$(document).ready(function() {
    updateLivePreview();
});
</script>
