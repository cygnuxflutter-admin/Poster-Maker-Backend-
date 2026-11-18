<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><span class="ico-pencil"></span></div>
            <h1>Manual Broadcast Composer <small><span class="cblue">Instant RTO Push Dispatch</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>RtoPushNotification">RTO Push Notifications</a></li>
            <li class="active">Manual Composer</li>
        </ul>
        <div class="clear"></div>
    </div>

    <!-- Flash Messages -->
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
                <li class="active"><a href="<?php echo base_url('RtoPushNotification/composer'); ?>">✍️ Manual Broadcast Composer</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/campaigns'); ?>">📢 Broadcast History</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li><a href="<?php echo base_url('RtoPushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- Composer Form -->
        <div class="col-md-7">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-envelope"></span></div>
                    <h2>Craft Broadcast Notification</h2>
                </div>
                <div class="data-fluid" style="padding: 20px;">
                    <form action="<?php echo base_url('RtoPushNotification/do_compose'); ?>" method="POST" id="composerForm">

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Notification Title:</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="notif_title" class="form-control" placeholder="e.g. 🚨 Mama is watching! Pending Challan Alert" required maxlength="120" oninput="updatePreview()">
                            <small class="text-muted"><span id="title_chars">0</span> / 120 characters</small>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Notification Message / Body:</strong> <span class="text-danger">*</span></label>
                            <textarea name="message" id="notif_body" class="form-control" rows="4" placeholder="e.g. Check if your vehicle received an automated speed camera fine slip yesterday! Tap to check with photo proof." required maxlength="280" oninput="updatePreview()"></textarea>
                            <small class="text-muted"><span id="body_chars">0</span> / 280 characters</small>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Target App Module / Screen:</strong></label>
                            <select name="target_screen" id="target_screen" class="form-control" onchange="updatePreview()">
                                <option value="RC">🚗 RC & Vehicle Owner Search (Task_SearchVehicleDetailActivity)</option>
                                <option value="CHALLAN">🚨 Traffic Challan & Penalties (Task_ChallanSearchActivity)</option>
                                <option value="DL">🪪 Driving Licence Search & Status (DLActivity)</option>
                                <option value="EXAM" selected>📝 RTO Exam Mock Test (Task_LanguageSelectActivity)</option>
                                <option value="SYMBOL">🛑 Traffic Road Signs & Symbols (Task_SymbolActivity)</option>
                                <option value="RULES">⚖️ Traffic Rules & MV Act Penalties (Task_RulesActivity)</option>
                                <option value="FUEL">⛽ Daily Fuel Prices Tracker (Task_FuelCityActivity)</option>
                                <option value="RESALE">🏷️ Vehicle Resale Value Estimator (Task_ResaleEstimatorActivity)</option>
                                <option value="MILEAGE">🧮 Fuel Mileage & Road Trip Cost (Task_MileageCalculatorActivity)</option>
                                <option value="LOAN">💳 Car & Bike Loan EMI Calculator (Task_LoanEmiActivity)</option>
                                <option value="DOCUMENT">📄 Expiry Reminders (PUC & Insurance) (Task_ExpiryReminderActivity)</option>
                                <option value="CELEBRITY">🌟 Celebrity & VIP Vehicles (Task_CelebrityListActivity)</option>
                                <option value="OFFICE">🏛️ RTO Offices & Official Forms (Task_OficeActivity)</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Rich Banner Image URL (Optional):</strong></label>
                            <input type="url" name="image_url" id="notif_image" class="form-control" placeholder="https://yourserver.com/banner.jpg" oninput="updatePreview()">
                            <small class="text-muted">Displays as BigPictureStyle in Android notifications.</small>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label><strong>Target Audience:</strong></label>
                            <select name="target_type" id="target_type" class="form-control" onchange="toggleTargetValue()">
                                <option value="TOPIC">Broadcast to Global Topic: "<?php echo htmlspecialchars($default_topic); ?>" (All Users)</option>
                                <option value="CUSTOM_TOKEN">Single Test Device Token</option>
                            </select>
                        </div>

                        <div class="form-group" id="token_input_group" style="display: none; margin-bottom: 15px;">
                            <label><strong>Target Device Token:</strong></label>
                            <input type="text" name="target_value" id="target_value" class="form-control" placeholder="Paste FCM registration token...">
                        </div>

                        <?php if ($dry_run === 1) { ?>
                        <div class="checkbox" style="margin-bottom: 20px;">
                            <label style="color: #c0392b; font-weight: bold;">
                                <input type="checkbox" name="force_live" value="1"> 
                                ⚠️ System is in DRY-RUN mode. Check this box to FORCE LIVE send to real users.
                            </label>
                        </div>
                        <?php } ?>

                        <button type="submit" class="btn btn-primary btn-large" style="font-size: 15px; padding: 10px 25px;">
                            🚀 Send Broadcast Notification
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Live Android Device Mockup Preview -->
        <div class="col-md-5">
            <div class="block">
                <div class="head green">
                    <div class="icon"><span class="ico-mobile"></span></div>
                    <h2>Android System Tray Preview</h2>
                </div>
                <div class="data-fluid" style="padding: 20px; background: #eef2f5;">
                    <div style="background: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 16px; font-family: -apple-system, Roboto, sans-serif;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="background: #1e40af; color: #fff; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold;">🚗</span>
                                <span style="font-size: 12px; font-weight: 600; color: #374151;">RTO Vehicle Information</span>
                                <span style="font-size: 11px; color: #9ca3af;">• now</span>
                            </div>
                            <span class="label label-primary" id="preview_screen_badge" style="font-size: 10px;">EXAM</span>
                        </div>
                        <div id="preview_title" style="font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 4px;">
                            Notification Title Preview
                        </div>
                        <div id="preview_body" style="font-size: 13px; color: #4b5563; line-height: 1.4;">
                            Notification body message will appear here in the system tray.
                        </div>
                        <div id="preview_image_container" style="display: none; margin-top: 10px;">
                            <img id="preview_image" src="" alt="Banner Preview" style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    var t = $('#notif_title').val() || 'Notification Title Preview';
    var b = $('#notif_body').val() || 'Notification body message will appear here in the system tray.';
    var s = $('#target_screen').val() || 'EXAM';
    var img = $('#notif_image').val();

    $('#preview_title').text(t);
    $('#preview_body').text(b);
    $('#preview_screen_badge').text(s);
    $('#title_chars').text($('#notif_title').val().length);
    $('#body_chars').text($('#notif_body').val().length);

    if (img && img.trim().length > 10) {
        $('#preview_image').attr('src', img);
        $('#preview_image_container').show();
    } else {
        $('#preview_image_container').hide();
    }
}

function toggleTargetValue() {
    if ($('#target_type').val() === 'CUSTOM_TOKEN') {
        $('#token_input_group').show();
    } else {
        $('#token_input_group').hide();
    }
}
</script>
