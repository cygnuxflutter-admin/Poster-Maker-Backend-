<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><span class="ico-folder-open"></span></div>
            <h1>Bulk JSON Notification Import <small><span class="cgreen">Upload &amp; Schedule via JSON</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>PushNotification">Push Notifications</a></li>
            <li class="active">Bulk JSON Import</li>
        </ul>
        <div class="clear"></div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')) { ?>
        <div class="alert alert-success" style="font-size: 14px; padding: 14px 18px;">
            <strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php } ?>
    <?php if ($this->session->flashdata('error')) { ?>
        <div class="alert alert-danger" style="font-size: 14px; padding: 14px 18px;">
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
                <li class="active"><a href="<?php echo base_url('PushNotification/import_json'); ?>">📥 Bulk JSON Import</a></li>
                <li><a href="<?php echo base_url('PushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li><a href="<?php echo base_url('PushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li><a href="<?php echo base_url('PushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <!-- Recently Imported Notifications Table (If any) -->
    <?php if (!empty($recent_imported)) { ?>
    <div class="row" style="margin-bottom: 25px;">
        <div class="col-md-12">
            <div class="block" style="border: 2px solid #27ae60; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(39, 174, 96, 0.15);">
                <div class="head green" style="background: linear-gradient(135deg, #27ae60, #2ecc71); padding: 10px 18px; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div class="icon"><span class="ico-check"></span></div>
                        <h2 style="color: #fff; font-size: 15px; margin: 0;">
                            🎉 Just Imported (<?php echo count($recent_imported); ?> Notifications Added) - Upload Images, Edit, Pause or Delete Below
                        </h2>
                    </div>
                    <a href="<?php echo base_url('PushNotification/clear_imported_list'); ?>" class="btn btn-xs btn-default" style="color: #333; font-weight: 600; margin-left: auto;" title="Dismiss this temporary list">
                        ✕ Dismiss List
                    </a>
                </div>
                <div class="data-fluid">
                    <div style="background: #eafaf1; padding: 10px 16px; font-size: 12.5px; color: #196f3d; border-bottom: 1px solid #d4efdf;">
                        💡 <strong>ઓપ્શન્સ માર્ગદર્શિકા:</strong> ઈમેજ ઉમેરવા <strong>"✏️ Edit &amp; Image"</strong>, આપોઆપ મોકલાતી અટકાવવા <strong>"⏸️ Pause"</strong>, તરત મોકલવા <strong>"🚀 Send"</strong>, અને કાઢી નાખવા <strong>"🗑️ Delete"</strong> વાપરો.
                    </div>
                    <table class="table table-striped" style="margin-bottom: 0;">
                        <thead>
                            <tr style="background: #f8fafc;">
                                <th style="width: 55px;">ID</th>
                                <th style="width: 75px;">Banner</th>
                                <th>Title &amp; Message</th>
                                <th style="width: 140px;">Scheduled Time</th>
                                <th style="width: 100px;">Target App</th>
                                <th style="width: 105px;">Status</th>
                                <th style="width: 250px;" class="TAC">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_imported as $item) { ?>
                            <tr>
                                <td><strong>#<?php echo $item['id']; ?></strong></td>
                                <td>
                                    <?php if (!empty($item['image_url'])) { ?>
                                        <a href="<?php echo htmlspecialchars($item['image_url']); ?>" target="_blank">
                                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" style="width: 55px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc;">
                                        </a>
                                    <?php } else { ?>
                                        <span class="label label-default" style="font-size: 10px;">No Image</span>
                                        <div style="margin-top: 3px;">
                                            <a href="<?php echo base_url('PushNotification/edit/' . $item['id']); ?>" style="font-size: 10px; color: #0284c7; font-weight: 700;">+ Add</a>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td>
                                    <strong style="color: #2c3e50; font-size: 13.5px;"><?php echo htmlspecialchars($item['title']); ?></strong>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                                        <?php echo htmlspecialchars($item['message']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($item['scheduled_at'])) { ?>
                                        <strong style="color: #0284c7; font-size: 12px;">📅 <?php echo date('d M Y', strtotime($item['scheduled_at'])); ?></strong>
                                        <div style="font-size: 11px; color: #0369a1; font-weight: 600;">⏱️ <?php echo date('h:i A', strtotime($item['scheduled_at'])); ?> IST</div>
                                    <?php } else { ?>
                                        <span class="text-muted">-</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php 
                                    $appCode = !empty($item['app_code']) ? $item['app_code'] : 'chirag';
                                    if ($appCode === 'nitin') {
                                        echo '<span class="label label-warning">Nitin Sir App</span>';
                                    } elseif ($appCode === 'both') {
                                        echo '<span class="label label-primary">Both Apps</span>';
                                    } else {
                                        echo '<span class="label label-info">Chirag Sir App</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($item['status'] === 'PAUSED') { ?>
                                        <span class="label label-warning" style="background: #e67e22; font-weight: bold; font-size: 10.5px; padding: 3px 7px;">⏸️ PAUSED</span>
                                    <?php } elseif ($item['status'] === 'SENT') { ?>
                                        <span class="label label-success" style="font-weight: bold; font-size: 10.5px; padding: 3px 7px;">SENT</span>
                                    <?php } else { ?>
                                        <span class="label label-info" style="background: #0284c7; font-weight: bold; font-size: 10.5px; padding: 3px 7px;">⏰ SCHEDULED</span>
                                    <?php } ?>
                                </td>
                                <td class="TAC">
                                    <div style="display: flex; gap: 4px; flex-wrap: wrap; justify-content: center; align-items: center;">
                                        <a href="<?php echo base_url('PushNotification/edit/' . $item['id']); ?>" class="btn btn-xs btn-primary" style="font-weight: 700;" title="Upload banner image or edit title/time">
                                            ✏️ Edit &amp; Image
                                        </a>
                                        <a href="<?php echo base_url('PushNotification/send_scheduled_now/' . $item['id']); ?>" class="btn btn-xs btn-success" onclick="return confirm('Send notification #<?php echo $item['id']; ?> immediately right now?');" title="Dispatch now" style="font-weight: 600;">
                                            🚀 Send
                                        </a>
                                        <?php if ($item['status'] === 'PAUSED'): ?>
                                            <a href="<?php echo base_url('PushNotification/resume_campaign/' . $item['id']); ?>" class="btn btn-xs btn-info" title="Resume notification" style="font-weight: 700; background: #0284c7; border-color: #0284c7; color: #fff;">
                                                ▶️ Resume
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo base_url('PushNotification/pause_campaign/' . $item['id']); ?>" class="btn btn-xs btn-warning" onclick="return confirm('Pause notification #<?php echo $item['id']; ?>? It will NOT be sent automatically until resumed.');" title="Pause notification" style="font-weight: 700; background: #f39c12; border-color: #e67e22; color: #fff;">
                                                ⏸️ Pause
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php echo base_url('PushNotification/delete_campaign/' . $item['id']); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Permanently delete notification #<?php echo $item['id']; ?>? This cannot be undone.');" title="Delete notification" style="font-weight: 700; background: #e74c3c; border-color: #c0392b; color: #fff;">
                                            🗑️ Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <!-- Main Import Grid -->
    <div class="row">
        <!-- Import Form (Left Column) -->
        <div class="col-md-7">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-cloud-upload"></span></div>
                    <h2>Upload Notifications via JSON</h2>
                </div>
                <div class="data-fluid" style="padding: 20px;">
                    <form action="<?php echo base_url('PushNotification/do_import_json'); ?>" method="POST" enctype="multipart/form-data" id="jsonImportForm">
                        
                        <!-- Target Application Selection -->
                        <div class="form-group" style="margin-bottom: 20px; background: #eef4f8; padding: 14px 18px; border-radius: 6px; border: 1px solid #b8d4e8;">
                            <label style="font-size: 13.5px; color: #1e3c72; margin-bottom: 6px; display: block;">
                                <strong><span class="ico-mobile"></span> Default Target Application:</strong> <span class="text-danger">*</span>
                            </label>
                            <select name="default_app" id="defaultAppSelect" class="form-control" style="font-weight: 600; font-size: 13.5px; height: 38px;">
                                <option value="chirag" <?php echo ($active_app === 'chirag') ? 'selected' : ''; ?>>
                                    🔵 Chirag Sir App Only (firebase_service_account.json)
                                </option>
                                <option value="nitin" <?php echo ($active_app === 'nitin') ? 'selected' : ''; ?>>
                                    🟢 Nitin Sir App Only (nitin_poster_service_account.json)
                                </option>
                                <option value="both">
                                    📢 Both Apps Separately (Dispatch to Chirag Sir &amp; Nitin Sir)
                                </option>
                            </select>
                            <span class="help-block text-muted" style="font-size: 11px; margin-top: 5px; margin-bottom: 0;">
                                If individual items in JSON do not specify <code>"app"</code>, this target application is used.
                            </span>
                        </div>

                        <!-- Upload Method Selector (Tabs) -->
                        <div class="form-group" style="margin-bottom: 18px;">
                            <label style="font-size: 13.5px;"><strong>Choose Input Method:</strong></label>
                            <div style="display: flex; gap: 15px; margin-top: 5px;">
                                <label style="cursor: pointer; font-size: 13px; font-weight: 600;">
                                    <input type="radio" name="input_mode" value="file" checked onchange="toggleInputMode('file')" style="margin-right: 6px;">
                                    📁 Upload .json File
                                </label>
                                <label style="cursor: pointer; font-size: 13px; font-weight: 600;">
                                    <input type="radio" name="input_mode" value="text" onchange="toggleInputMode('text')" style="margin-right: 6px;">
                                    📝 Paste JSON Text Directly
                                </label>
                            </div>
                        </div>

                        <!-- Mode 1: File Upload -->
                        <div id="fileUploadContainer" style="margin-bottom: 20px; background: #fdfefe; border: 2px dashed #3498db; padding: 22px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 32px; color: #3498db; margin-bottom: 8px;">
                                <span class="ico-file"></span>
                            </div>
                            <label style="font-size: 14px; font-weight: 700; color: #2c3e50; cursor: pointer; display: block; margin-bottom: 8px;">
                                Choose a .json file from your computer
                            </label>
                            <input type="file" name="json_file" id="jsonFileInput" accept=".json,application/json" class="form-control" style="max-width: 320px; margin: 0 auto;" onchange="handleFileSelected(this)">
                            <div style="font-size: 11.5px; color: #7f8c8d; margin-top: 8px;">
                                Standard UTF-8 JSON file containing an array of notifications.
                            </div>
                        </div>

                        <!-- Mode 2: Direct JSON Textarea -->
                        <div id="textUploadContainer" style="margin-bottom: 20px; display: none;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                <label style="margin: 0;"><strong>Paste JSON Content:</strong></label>
                                <div>
                                    <button type="button" class="btn btn-xs btn-info" onclick="insertSampleJSON()">📋 Load Sample JSON</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="clearJSONText()">🧹 Clear</button>
                                </div>
                            </div>
                            <textarea name="json_content" id="jsonContentArea" rows="11" class="form-control" 
                                      placeholder='[&#10;  {&#10;    "title": "🎉 Special Poster Offer",&#10;    "description": "Create amazing festive posters for your social media &amp; business now!",&#10;    "time": "2026-11-05 10:30:00"&#10;  }&#10;]'
                                      style="font-family: 'Consolas', 'Courier New', monospace; font-size: 12.5px; line-height: 1.45;"></textarea>
                            <span class="help-block text-muted" style="font-size: 11px; margin-top: 4px;">
                                Supports keys: <code>title</code> (or <code>titel</code>), <code>description</code> (or <code>descipatin</code> / <code>message</code>), and <code>time</code> (scheduled date/time).
                            </span>
                        </div>

                        <!-- Optional Default Category -->
                        <div class="form-group" style="margin-bottom: 18px;">
                            <label><strong>Optional Default App Category Deep Link:</strong></label>
                            <select name="default_cat_id" id="defaultCatId" class="form-control">
                                <option value="10">-- Poster Special (Default ID: 10) --</option>
                                <?php foreach ($categories as $cat) { ?>
                                    <option value="<?php echo $cat['cat_id']; ?>">
                                        Category: <?php echo htmlspecialchars($cat['cat_name']); ?> (ID: <?php echo $cat['cat_id']; ?>)
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="help-block text-muted" style="font-size: 11px;">
                                When users tap on the notification in the app, this category opens.
                            </span>
                        </div>

                        <!-- Target Audience -->
                        <div class="form-group" style="margin-bottom: 18px; background: #f8fafc; padding: 12px 16px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <label style="margin-bottom: 8px;"><strong>Target Audience for Imported Notifications:</strong></label>
                            <div>
                                <label class="radio-inline" style="font-weight: 600; margin-right: 15px;">
                                    <input type="radio" name="target_type" value="TOPIC" checked>
                                    All Users Topic (<code><?php echo htmlspecialchars($default_topic); ?></code>)
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="target_type" value="TEST_DEVICES">
                                    Test Devices Only
                                </label>
                            </div>
                        </div>

                        <!-- Safety Transmission Toggle -->
                        <div class="form-group" style="margin-bottom: 25px; background: <?php echo ($dry_run == 0) ? '#e8f8f5' : '#fff8e1'; ?>; padding: 12px 16px; border-radius: 6px; border: 1px solid <?php echo ($dry_run == 0) ? '#2ecc71' : '#ffe082'; ?>;">
                            <label style="cursor: pointer; font-weight: bold; color: <?php echo ($dry_run == 0) ? '#27ae60' : '#b78103'; ?>; margin-bottom: 0; font-size: 13px;">
                                <input type="checkbox" name="force_live" id="forceLiveCheckbox" value="1" <?php echo ($dry_run == 0) ? 'checked' : ''; ?> style="margin-right: 8px;"> 
                                <?php if ($dry_run == 0): ?>
                                    <span class="label label-success" style="font-size: 10px; margin-right: 5px;">LIVE BROADCAST</span> Auto-dispatches live to real devices when scheduled time is reached
                                <?php else: ?>
                                    <span class="label label-warning" style="font-size: 10px; margin-right: 5px;">DRY-RUN DEFAULT</span> Check box to dispatch live to real devices at scheduled time
                                <?php endif; ?>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                            <button type="button" class="btn btn-info" onclick="previewJSON()" style="padding: 10px 18px; font-weight: 600;">
                                👁 Validate &amp; Preview JSON
                            </button>
                            <button type="submit" class="btn btn-success" id="btnSubmitImport" style="padding: 10px 24px; font-weight: 700; font-size: 14px; box-shadow: 0 4px 10px rgba(46, 204, 113, 0.25);">
                                📥 Import &amp; Schedule Notifications
                            </button>
                            <a href="<?php echo base_url('PushNotification/campaigns'); ?>" class="btn btn-default" style="padding: 10px 18px;">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Help & JSON Format Specification (Right Column) -->
        <div class="col-md-5">
            <div class="block">
                <div class="head green">
                    <div class="icon"><span class="ico-info-sign"></span></div>
                    <h2>JSON Format Guide (નિયમો અને ઉદાહરણ)</h2>
                </div>
                <div class="data-fluid" style="padding: 18px;">
                    <div style="background: #eef7fc; border-left: 4px solid #3498db; padding: 12px 14px; border-radius: 4px; font-size: 12.5px; color: #2c3e50; margin-bottom: 15px; line-height: 1.5;">
                        <strong>💡 How it works:</strong><br>
                        1. JSON માં <strong>title</strong>, <strong>description</strong> અને <strong>time</strong> આપો.<br>
                        2. JSON અપલોડ કરતાં જ બધી નોટિફિકેશન આપમેળે <strong>SCHEDULED</strong> થઈ જશે.<br>
                        3. ત્યારબાદ જો કોઈ નોટિફિકેશનમાં <strong>Image (ફોટો) અપલોડ કરવો હોય</strong>, તો તેની સામે <strong>"✏️ Edit"</strong> પર ક્લિક કરીને ગમે ત્યારે ઈમેજ અપલોડ કરી શકાશે.
                    </div>

                    <h4 style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">
                        📌 Supported JSON Keys:
                    </h4>
                    <table class="table table-bordered" style="font-size: 12px; margin-bottom: 15px;">
                        <thead>
                            <tr style="background: #f1f5f9;">
                                <th>Field</th>
                                <th>Accepted Keys</th>
                                <th>Description / Format</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Title</strong> <span class="text-danger">*</span></td>
                                <td><code>title</code>, <code>titel</code></td>
                                <td>Headline of the push notification (max 150 chars).</td>
                            </tr>
                            <tr>
                                <td><strong>Description</strong> <span class="text-danger">*</span></td>
                                <td><code>description</code>, <code>descipatin</code>, <code>message</code></td>
                                <td>Main body text of the notification.</td>
                            </tr>
                            <tr>
                                <td><strong>Time</strong></td>
                                <td><code>time</code>, <code>datetime</code>, <code>scheduled_at</code></td>
                                <td>
                                    Scheduled date &amp; time:<br>
                                    • <code>"2026-11-05 10:30:00"</code><br>
                                    • <code>"2026-11-05 10:30"</code><br>
                                    • <code>"10:30 AM"</code> (Today or tomorrow)
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Image URL</strong> (Optional)</td>
                                <td><code>image</code>, <code>image_url</code></td>
                                <td>Direct HTTPS image URL (optional, can also upload via Edit).</td>
                            </tr>
                            <tr>
                                <td><strong>App</strong> (Optional)</td>
                                <td><code>app</code>, <code>target_app</code></td>
                                <td><code>"chirag"</code>, <code>"nitin"</code>, or <code>"both"</code>.</td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <h4 style="font-size: 13px; font-weight: 700; color: #334155; margin: 0;">
                            📋 Sample JSON Template:
                        </h4>
                        <button type="button" class="btn btn-xs btn-default" onclick="copySampleJSON()">Copy JSON</button>
                    </div>
                    <pre id="sampleJsonBlock" style="background: #1e293b; color: #38bdf8; padding: 12px; border-radius: 6px; font-size: 11.5px; font-family: monospace; max-height: 230px; overflow-y: auto;">[
  {
    "title": "🎉 Festive Posters Ready!",
    "description": "Explore 500+ trending festival poster designs for your social media.",
    "time": "<?php echo date('Y-m-d', strtotime('+1 day')); ?> 09:30:00"
  },
  {
    "title": "✨ Good Morning Status",
    "description": "Send positive thoughts and beautiful morning wishes to all your contacts.",
    "time": "<?php echo date('Y-m-d', strtotime('+1 day')); ?> 14:00:00"
  },
  {
    "title": "🔥 Business Promotion Flyer",
    "description": "Promote your business with professional banners in just 2 minutes.",
    "time": "<?php echo date('Y-m-d', strtotime('+2 days')); ?> 18:30:00"
  }
]</pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Live JSON Client-Side Preview Card (Dynamic) -->
    <div class="row" id="previewTableContainer" style="display: none; margin-top: 10px; margin-bottom: 30px;">
        <div class="col-md-12">
            <div class="block" style="border: 1px solid #38bdf8; border-radius: 6px; overflow: hidden;">
                <div class="head blue" style="background: #0284c7; padding: 10px 16px;">
                    <div class="icon"><span class="ico-eye-open"></span></div>
                    <h2 style="color: #fff; font-size: 15px; margin: 0;">
                        Live JSON Parse Preview: <span id="previewCountBadge" class="label label-warning" style="font-size: 12px; margin-left: 8px;">0 items</span>
                    </h2>
                </div>
                <div class="data-fluid" style="padding: 15px; max-height: 420px; overflow-y: auto;">
                    <div id="previewValidationStatus" style="margin-bottom: 10px; font-weight: 600;"></div>
                    <table class="table table-bordered table-striped" style="margin-bottom: 0;">
                        <thead>
                            <tr style="background: #f8fafc;">
                                <th style="width: 40px;">#</th>
                                <th style="width: 250px;">Title</th>
                                <th>Description / Message</th>
                                <th style="width: 170px;">Scheduled Date &amp; Time</th>
                                <th style="width: 110px;">Target App</th>
                                <th style="width: 90px;">Image?</th>
                            </tr>
                        </thead>
                        <tbody id="previewTableBody">
                            <!-- Populated via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
function toggleInputMode(mode) {
    if (mode === 'text') {
        $('#fileUploadContainer').hide();
        $('#textUploadContainer').slideDown(200);
    } else {
        $('#textUploadContainer').hide();
        $('#fileUploadContainer').slideDown(200);
    }
}

function insertSampleJSON() {
    var sample = $('#sampleJsonBlock').text();
    $('#jsonContentArea').val(sample);
    previewJSON();
}

function clearJSONText() {
    $('#jsonContentArea').val('');
    $('#previewTableContainer').slideUp(200);
}

function copySampleJSON() {
    var text = $('#sampleJsonBlock').text();
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Sample JSON copied to clipboard!');
        });
    } else {
        var dummy = document.createElement('textarea');
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);
        alert('Sample JSON copied to clipboard!');
    }
}

function handleFileSelected(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            var content = e.target.result;
            // Also put it in the textarea so preview can read it
            $('#jsonContentArea').val(content);
            previewJSON(content);
        };
        reader.readAsText(file);
    }
}

function previewJSON(explicitContent) {
    var raw = explicitContent || $('#jsonContentArea').val();
    if (!raw || !raw.trim()) {
        alert('Please paste JSON text or select a .json file first.');
        return;
    }

    try {
        // Strip BOM if present
        raw = raw.replace(/^\uFEFF/, '').trim();
        var data = JSON.parse(raw);

        // Unwrap if root is an object containing an array
        if (data && !Array.isArray(data)) {
            if (Array.isArray(data.notifications)) data = data.notifications;
            else if (Array.isArray(data.data)) data = data.data;
            else if (Array.isArray(data.items)) data = data.items;
            else if (data.title || data.titel) data = [data];
        }

        if (!Array.isArray(data) || data.length === 0) {
            alert('JSON parsed successfully, but no notification array was found. Expected a JSON array of objects.');
            return;
        }

        var defaultApp = $('#defaultAppSelect').val();
        var validCount = 0;
        var invalidCount = 0;
        var tbody = '';

        data.forEach(function(row, idx) {
            var title = row.title || row.titel || row.heading || row.name || '';
            var desc  = row.description || row.descipatin || row.desc || row.message || row.body || row.msg || row.text || '';
            var time  = row.time || row.scheduled_at || row.scheduled_time || row.datetime || row.date_time || '';
            if (row.date && time && !time.includes('-')) {
                time = row.date + ' ' + time;
            } else if (row.date && !time) {
                time = row.date + ' 09:00:00';
            }
            var image = row.image || row.image_url || row.banner || '';
            var app   = row.app || row.target_app || row.app_code || defaultApp;

            var isValid = (title.trim().length > 0 && desc.trim().length > 0);
            if (isValid) validCount++; else invalidCount++;

            tbody += '<tr style="' + (isValid ? '' : 'background: #fff5f5; color: #c0392b;') + '">';
            tbody += '<td>' + (idx + 1) + '</td>';
            tbody += '<td><strong>' + escapeHtml(title) + '</strong>' + (!isValid ? ' <span class="label label-danger">Missing Title/Desc</span>' : '') + '</td>';
            tbody += '<td style="font-size: 12px;">' + escapeHtml(desc) + '</td>';
            tbody += '<td><code style="color: #0284c7;">' + (time ? escapeHtml(time) : 'Default (+2 hours)') + '</code></td>';
            tbody += '<td><span class="label label-info">' + escapeHtml(app) + '</span></td>';
            tbody += '<td>' + (image ? '🖼️ Yes' : '<span class="text-muted">No Image</span>') + '</td>';
            tbody += '</tr>';
        });

        $('#previewTableBody').html(tbody);
        $('#previewCountBadge').text(data.length + ' item(s) found (' + validCount + ' valid)');
        
        var statusHtml = '<span class="text-success">✓ ' + validCount + ' valid notification(s) ready to import.</span>';
        if (invalidCount > 0) {
            statusHtml += ' <span class="text-danger">⚠️ ' + invalidCount + ' item(s) missing title or description.</span>';
        }
        $('#previewValidationStatus').html(statusHtml);

        $('#previewTableContainer').slideDown(250);
        $('html, body').animate({
            scrollTop: $('#previewTableContainer').offset().top - 70
        }, 300);

    } catch (e) {
        alert('Invalid JSON syntax: ' + e.message);
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return text.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
</script>
