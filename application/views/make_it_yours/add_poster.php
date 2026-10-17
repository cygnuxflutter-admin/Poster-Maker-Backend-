<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <span class="ico-images"></span>
            </div>
            <h1><?php echo isset($edit_poster) ? 'Edit' : 'Add'; ?> MIY Poster</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="<?php echo base_url(); ?>MakeItYours/posters">MIY Posters</a></li>
            <li class="active"><?php echo isset($edit_poster) ? 'Edit' : 'Add'; ?> Poster</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="head">
                    <h2>Poster Details</h2>
                </div>
                <div class="data-fluid">
                    <form action="<?php echo base_url(); ?>MakeItYours/save_poster" method="POST" enctype="multipart/form-data">
                        
                        <?php if(isset($edit_poster)): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_poster['id']; ?>">
                        <?php endif; ?>

                        <div class="row-form">
                            <div class="col-md-3"><strong>Title:</strong></div>
                            <div class="col-md-9">
                                <input type="text" name="title" required class="form-control" value="<?php echo isset($edit_poster) ? $edit_poster['title'] : ''; ?>"/>
                            </div>
                        </div>
                        
                        <div class="row-form">
                            <div class="col-md-3"><strong>Category:</strong></div>
                            <div class="col-md-9">
                                <?php if(!isset($edit_poster)): ?>
                                    <small>You can select multiple categories (Hold Ctrl/Cmd to select multiple)</small>
                                <?php endif; ?>
                                <select name="<?php echo isset($edit_poster) ? 'category_id' : 'category_id[]'; ?>" required class="form-control" <?php echo !isset($edit_poster) ? 'multiple style="height: 150px;"' : ''; ?>>
                                    <?php if(isset($edit_poster)): ?>
                                        <option value="">Select Category</option>
                                    <?php endif; ?>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo (isset($edit_poster) && $edit_poster['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo $cat['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-3"><strong>Design ID (Grouping):</strong></div>
                            <div class="col-md-9">
                                <input type="text" name="design_id" class="form-control" value="<?php echo isset($edit_poster) && isset($edit_poster['design_id']) ? $edit_poster['design_id'] : ''; ?>" placeholder="e.g. diwali_01 (Leave blank if none)"/>
                                <small>Use this to group 1:1 and 9:16 posters of the same design together.</small>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-3"><strong>Poster Size (Ratio):</strong></div>
                            <div class="col-md-9">
                                <select name="ratio" required class="form-control">
                                    <option value="1:1" <?php echo (isset($edit_poster) && isset($edit_poster['ratio']) && $edit_poster['ratio'] == '1:1') ? 'selected' : ''; ?>>Square (1:1)</option>
                                    <option value="9:16" <?php echo (isset($edit_poster) && isset($edit_poster['ratio']) && $edit_poster['ratio'] == '9:16') ? 'selected' : ''; ?>>Portrait (9:16)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-3"><strong>Poster Image(s):</strong></div>
                            <div class="col-md-9">
                                <input type="file" name="poster_image[]" class="form-control" multiple <?php echo !isset($edit_poster) ? 'required' : ''; ?>/>
                                <small>You can select multiple high quality poster images at once.</small>
                                <div id="selected_files_preview" style="margin-top: 10px;"></div>
                                <?php if(isset($edit_poster) && !empty($edit_poster['poster_image'])): ?>
                                    <br>
                                    <img src="<?php echo base_url($edit_poster['poster_image']); ?>" width="100">
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row-form">
                            <div class="col-md-3"><strong>Thumbnail Image (Optional):</strong></div>
                            <div class="col-md-9">
                                <input type="file" name="thumbnail_image" class="form-control"/>
                                <small>If not provided, the poster image will be used as thumbnail.</small>
                                <?php if(isset($edit_poster) && !empty($edit_poster['thumbnail_image'])): ?>
                                    <br>
                                    <img id="edit_thumb_preview" src="<?php echo base_url($edit_poster['thumbnail_image']); ?>" width="100">
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Auto Business Personalization Section -->
                        <div class="row-form">
                            <div class="col-md-3"><strong>Enable Personalization:</strong></div>
                            <div class="col-md-9">
                                <input type="checkbox" name="personalization_enabled" id="personalization_enabled" value="1" <?php echo (isset($edit_poster) && $edit_poster['personalization_enabled'] == 1) ? 'checked' : ''; ?> onchange="togglePersonalization()"/>
                                <small>Check this to enable Auto Business Personalization (Slots & Theme) for this poster</small>
                            </div>
                        </div>

                        <div id="personalization_section" style="display: <?php echo (isset($edit_poster) && $edit_poster['personalization_enabled'] == 1) ? 'block' : 'none'; ?>; border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; background: #fbfbfb;">
                            <h4>Auto Business Personalization Configuration</h4>
                            
                            <!-- VISUAL EDITOR -->
                            <div id="visual_editor_mode">
                                <p class="text-muted">Upload a poster above first. Then enable and drag the boxes below to position the business fields.</p>
                                
                                <!-- Import JSON Layout -->
                                <div style="background: #eef; padding: 10px; margin-bottom: 15px; border: 1px solid #ccd;">
                                    <strong>Quick Import (Optional):</strong> Paste layout data here to auto-fill the boxes!
                                    <div style="display: flex; gap: 10px; margin-top: 5px;">
                                        <input type="text" id="import_json_data" class="form-control" placeholder='e.g. {"logo":{"x":50,"y":50,"w":80,"h":80}, "phone":{"x":50,"y":100,"w":100,"h":40}}' style="flex: 1;">
                                        <button type="button" class="btn btn-warning" onclick="importJsonToEditor()">Apply JSON</button>
                                    </div>
                                </div>
                            
                            <div class="row">
                                <div class="col-md-7" style="display:flex; flex-direction:column; align-items:center;">
                                    
                                    <div style="margin-bottom: 15px; width: 100%; text-align: center;">
                                        <button type="button" class="btn btn-primary" onclick="forceLoadImage()" style="font-weight:bold; font-size:16px; padding: 10px 20px;">Refresh Phone View</button>
                                    </div>
                                    
                                    <!-- Mobile Mockup -->
                                    <div style="width: 380px; max-width: 100%; height: 750px; background-color: #1a1a1a; border-radius: 40px; padding: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); position: relative; margin: 0 auto 10px auto;">
                                        
                                        <!-- Screen -->
                                        <div style="width: 100%; height: 100%; background-color: #f8f9fa; border-radius: 30px; overflow: hidden; display: flex; flex-direction: column; position: relative;">
                                            
                                            <!-- Top Status Bar -->
                                            <div style="height: 28px; background: #fff; display:flex; justify-content: space-between; padding: 0 20px; align-items: center; font-size: 11px; font-weight: bold; color: #444; z-index: 10; border-bottom: 1px solid #f0f0f0;">
                                                <span>4:35</span>
                                                <span style="letter-spacing: 2px;">📶 5G 🔋49%</span>
                                            </div>

                                            <!-- Mobile App Top Bar -->
                                            <div style="height: 46px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 16px; border-bottom: 1px solid #eee; z-index: 10;">
                                                <span style="font-size: 20px; color: #7c3aed; font-weight: bold; cursor: default;">&#x276E;</span>
                                                <div style="display: flex; gap: 16px; align-items: center;">
                                                    <span style="font-size: 18px; color: #a78bfa; cursor: pointer;" title="Undo">&#x21BA;</span>
                                                    <span style="font-size: 18px; color: #a78bfa; cursor: pointer;" title="Redo">&#x21BB;</span>
                                                    <span style="font-size: 16px; color: #a78bfa; cursor: pointer;" title="Bookmark">&#x1F516;</span>
                                                    <span style="background: #8b5cf6; color: #fff; padding: 5px 10px; border-radius: 6px; font-size: 14px; font-weight: bold; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(139,92,246,0.3);">&#x2B07;</span>
                                                </div>
                                            </div>

                                            <!-- Canvas Container (Phone Screen Area) -->
                                            <div id="canvas_container" style="flex: 1; display: flex; align-items: center; justify-content: center; background: #ffffff; position: relative; width: 100%; overflow: hidden;">
                                                <canvas id="miyu_poster_canvas_unique" width="356" height="356" style="background: #fff; box-shadow: 0px 4px 15px rgba(0,0,0,0.08);"></canvas>
                                            </div>
                                            
                                            <!-- Bottom Nav Bar -->
                                            <div style="height: 36px; background: #fff; display:flex; justify-content: space-around; align-items: center; color: #aaa; z-index: 10; border-top: 1px solid #f0f0f0;">
                                                <span style="font-size:16px;">|||</span>
                                                <span style="font-size:15px;">□</span>
                                                <span style="font-size:18px;">◁</span>
                                            </div>
                                        </div>

                                        <!-- Camera Notch -->
                                        <div style="position: absolute; top: 12px; left: 50%; transform: translateX(-50%); width: 110px; height: 18px; background: #1a1a1a; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; z-index: 20;"></div>
                                    </div>
                                    <div style="text-align: center; margin-bottom: 20px;">
                                        <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: normal; padding: 6px 12px; font-size: 12px; border-radius: 15px;">
                                            &#x2728; <strong>WYSIWYG Live:</strong> Text & Icons (&#x1F4DE;, &#x1F4CD;) stay aligned and scale in real mobile ratio
                                        </span>
                                    </div>

                                </div>
                                <div class="col-md-5">
                                    <h5>Fixed Slots Configuration</h5>
                                    <input type="hidden" name="slots" id="slots_data" value="<?php echo isset($edit_poster) ? htmlspecialchars($edit_poster['slots']) : '{}'; ?>">
                                    
                                    <div class="slot-controls" style="max-height: 400px; overflow-y: auto;">
                                        <?php 
                                        $fields = ['logo' => 'Logo', 'business_name' => 'Business Name', 'phone' => 'Phone Number', 'address' => 'Address'];
                                        foreach($fields as $key => $label): 
                                        ?>
                                        <div class="form-group" style="background:#fff; padding:10px; border:1px solid #eee; margin-bottom:10px;">
                                            <label><input type="checkbox" id="enable_<?php echo $key; ?>" onchange="toggleSlot('<?php echo $key; ?>', '<?php echo $label; ?>')"> <strong><?php echo $label; ?></strong></label>
                                            <div id="<?php echo $key; ?>_inputs" style="display:none; margin-top:5px;">
                                                <div style="display:flex; gap:5px; flex-wrap:wrap;">
                                                    <div><small>X:</small> <input type="number" id="<?php echo $key; ?>_x" class="form-control input-sm" oninput="updateBoxFromInput('<?php echo $key; ?>')" style="width:60px"></div>
                                                    <div><small>Y:</small> <input type="number" id="<?php echo $key; ?>_y" class="form-control input-sm" oninput="updateBoxFromInput('<?php echo $key; ?>')" style="width:60px"></div>
                                                    <div><small>W:</small> <input type="number" id="<?php echo $key; ?>_w" class="form-control input-sm" oninput="updateBoxFromInput('<?php echo $key; ?>')" style="width:60px"></div>
                                                    <div><small>H:</small> <input type="number" id="<?php echo $key; ?>_h" class="form-control input-sm" oninput="updateBoxFromInput('<?php echo $key; ?>')" style="width:60px"></div>
                                                    <?php if($key != 'logo'): ?>
                                                    <div><small>Color:</small> <input type="color" id="<?php echo $key; ?>_color" class="form-control input-sm" oninput="updateBoxFromInput('<?php echo $key; ?>')" value="#ffffff" style="padding:0; width:40px"></div>
                                                    <div><small>Size:</small> <input type="number" id="<?php echo $key; ?>_fontSize" class="form-control input-sm" oninput="updateBoxFromInput('<?php echo $key; ?>')" style="width:50px" placeholder="42"></div>
                                                    <div><small>Font:</small> <input type="text" id="<?php echo $key; ?>_fontFamily" class="form-control input-sm" oninput="updateBoxFromInput('<?php echo $key; ?>')" style="width:80px" placeholder="Poppins" value="Poppins"></div>
                                                    <div><small>Weight:</small> <select id="<?php echo $key; ?>_fontWeight" class="form-control input-sm" onchange="updateBoxFromInput('<?php echo $key; ?>')" style="width:65px; padding:2px;"><option value="400">400</option><option value="500">500</option><option value="600" selected>600</option><option value="700">700</option></select></div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if($key == 'phone' || $key == 'address'): ?>
                                                <div style="display:flex; gap:5px; flex-wrap:wrap; margin-top:5px; padding-top:5px; border-top:1px dashed #ccc;">
                                                    <strong>Icon:</strong>
                                                    <div><small>X:</small> <input type="number" id="<?php echo $key; ?>_icon_x" class="form-control input-sm" oninput="updateBoxFromInput('<?php echo $key; ?>')" style="width:60px"></div>
                                                    <div><small>Y:</small> <input type="number" id="<?php echo $key; ?>_icon_y" class="form-control input-sm" oninput="updateBoxFromInput('<?php echo $key; ?>')" style="width:60px"></div>
                                                    <div><small>W:</small> <input type="number" id="<?php echo $key; ?>_icon_w" class="form-control input-sm" oninput="updateBoxFromInput('<?php echo $key; ?>')" style="width:60px"></div>
                                                    <div><small>H:</small> <input type="number" id="<?php echo $key; ?>_icon_h" class="form-control input-sm" oninput="updateBoxFromInput('<?php echo $key; ?>')" style="width:60px"></div>
                                                    <div><small>Color:</small> <input type="color" id="<?php echo $key; ?>_icon_color" class="form-control input-sm" oninput="updateBoxFromInput('<?php echo $key; ?>')" value="#ffffff" style="padding:0; width:40px"></div>
                                                </div>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        
                                        <div class="form-group" style="background:#fff; padding:10px; border:1px solid #eee;">
                                            <label><strong>Theme Match (Optional)</strong></label>
                                            <input type="text" name="theme" id="theme" class="form-control" placeholder="e.g. blue, dark, festive" value="<?php echo isset($edit_poster) ? htmlspecialchars($edit_poster['theme']) : ''; ?>">
                                            <small>Mobile app will use this to match text colors and priority.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Personalization Section -->

                        <div class="row-form">
                            <div class="col-md-9 col-md-offset-3">
                                <?php if(isset($edit_poster)): ?>
                                    <button type="submit" name="submit_type" value="update_draft" class="btn btn-default" style="margin-right: 10px;">Save as Draft (Unpublish)</button>
                                    <button type="submit" name="submit_type" value="update_publish" class="btn btn-primary">Update & Publish</button>
                                <?php else: ?>
                                    <button type="submit" name="submit_type" value="draft" class="btn btn-default" style="margin-right: 10px;">Save as Draft (Not Public)</button>
                                    <button type="submit" name="submit_type" value="publish" class="btn btn-primary">Publish Poster(s)</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
/* ========== POSTER EDITOR v2.0 - CLEAN REWRITE ========== */

// Global variables
var canvas = null;
var slotsData = {};
var canvasBoxes = {};
var canvasIcons = {};
var imgScale = 1;

// Status indicator - shows on page to prove new code is running
(function() {
    var statusDiv = document.createElement('div');
    statusDiv.id = 'js_status';
    statusDiv.style.cssText = 'background:green; color:white; padding:5px 10px; font-size:12px; position:fixed; bottom:0; right:0; z-index:9999;';
    statusDiv.textContent = 'JS v2.0 OK | Fabric: ' + (typeof fabric !== 'undefined' ? 'YES' : 'NO');
    document.body.appendChild(statusDiv);
    
    // Auto-hide after 10 seconds
    setTimeout(function() { statusDiv.style.display = 'none'; }, 10000);
})();

// Global Bug Catcher
window.addEventListener('error', function(e) {
    alert("System Bug Found: " + e.message + "\nLine: " + e.lineno);
});

// Read initial slots data from hidden input
var initialSlots = document.getElementById('slots_data').value;
if(initialSlots && initialSlots !== '{}' && initialSlots !== '') {
    try { slotsData = JSON.parse(initialSlots); } catch(e) {}
}

// ===== CANVAS INIT =====
function initCanvas() {
    if(canvas) return; // Already initialized
    if(typeof fabric === 'undefined') {
        alert('ERROR: fabric.js library not loaded! Check internet connection.');
        document.getElementById('js_status').textContent = 'ERROR: fabric.js not loaded!';
        document.getElementById('js_status').style.background = 'red';
        return;
    }
    try {
        canvas = new fabric.Canvas('miyu_poster_canvas_unique');
    } catch(e) {
        alert('ERROR: Canvas init failed: ' + e.message);
        document.getElementById('js_status').textContent = 'ERROR: Canvas init failed: ' + e.message;
        document.getElementById('js_status').style.background = 'red';
    }
}

// ===== TOGGLE PERSONALIZATION =====
function togglePersonalization() {
    var isChecked = document.getElementById('personalization_enabled').checked;
    document.getElementById('personalization_section').style.display = isChecked ? 'block' : 'none';
    
    if(isChecked) {
        // Wait for section to become visible, then init canvas
        setTimeout(function() {
            initCanvas();
            // Auto-load first selected image
            var fileInput = document.querySelector('input[name="poster_image[]"]');
            if(fileInput && fileInput.files && fileInput.files.length > 0) {
                loadCanvasImage(fileInput.files[0]);
            }
        }, 200);
    }
}

// ===== LOAD IMAGE INTO CANVAS =====
function loadCanvasImage(file) {
    initCanvas();
    
    if(!canvas) {
        alert("Error: Canvas not ready. Fabric.js may not have loaded.");
        return;
    }
    
    var reader = new FileReader();
    reader.onload = function(f) {
        var dataUrl = f.target.result;
        
        var imgEl = new Image();
        imgEl.onload = function() {
            try {
                // Calculate dynamic width based on container (mockup screen)
                var screenContainer = document.getElementById('canvas_container');
                var maxW = screenContainer ? screenContainer.clientWidth : 400;
                if(maxW < 100) maxW = 400; // fallback
                
                imgScale = maxW / imgEl.width;
                var newHeight = imgEl.height * imgScale;
                
                canvas.setWidth(maxW);
                canvas.setHeight(newHeight);
                
                // --- BULLETPROOF HTML IMG TAG ---
                // Base64 strings can sometimes be too large for CSS url() in some browsers.
                // An actual <img> tag behind the canvas never fails.
                var wrapper = document.querySelector('.canvas-container');
                var targetParent = wrapper || document.getElementById('miyu_poster_canvas_unique').parentNode;
                
                var bgImg = document.getElementById('miyu_real_bg_img');
                if(!bgImg) {
                    bgImg = document.createElement('img');
                    bgImg.id = 'miyu_real_bg_img';
                    bgImg.style.position = 'absolute';
                    bgImg.style.left = '0';
                    bgImg.style.top = '0';
                    bgImg.style.zIndex = '0'; // Behind canvas
                    bgImg.style.pointerEvents = 'none'; // Don't block clicks
                    targetParent.insertBefore(bgImg, targetParent.firstChild);
                    targetParent.style.position = 'relative'; // Ensure absolute child is contained
                }
                bgImg.src = dataUrl;
                bgImg.style.width = maxW + 'px';
                bgImg.style.height = newHeight + 'px';
                
                // Force canvas layers to be fully transparent so the image shows through
                var allCanvases = targetParent.querySelectorAll('canvas');
                for(var c=0; c<allCanvases.length; c++) {
                    allCanvases[c].style.background = 'transparent';
                    allCanvases[c].style.backgroundColor = 'transparent';
                }
                
                // Clear any old fabric background image objects just in case
                var objs = canvas.getObjects();
                for(var i = objs.length - 1; i >= 0; i--) {
                    if(objs[i].id === 'poster_bg') canvas.remove(objs[i]);
                }
                canvas.backgroundImage = null;
                
                canvas.renderAll();
                
                // Only alert on error now, to prevent alert from blocking screen
                // Draw boxes on top
                renderExistingSlots();
            } catch(err) {
                alert("Error in setting image: " + err.message);
            }
        };
        imgEl.onerror = function() {
            alert("Error: Could not decode image data.");
        };
        imgEl.src = dataUrl;
    };
    reader.onerror = function() {
        alert("Error reading file from your device.");
    };
    reader.readAsDataURL(file);
}

// ===== FORCE LOAD (Button) =====
function forceLoadImage(silent) {
    if(typeof fabric === 'undefined') {
        alert("fabric.js library not loaded! Check internet.");
        return;
    }
    var fileInput = document.querySelector('input[name="poster_image[]"]');
    if(fileInput && fileInput.files && fileInput.files[0]) {
        loadCanvasImage(fileInput.files[0]);
    } else {
        if(!silent) alert("Select a Poster Image first!");
    }
}

// ===== IMPORT JSON =====
function importJsonToEditor() {
    initCanvas();
    var jsonStr = document.getElementById('import_json_data').value;
    if(!jsonStr || jsonStr.trim() === '') {
        alert("Paste JSON data first!");
        return;
    }
    try {
        var data = JSON.parse(jsonStr);
        if(Array.isArray(data)) data = data[0];
        
        // Clear existing
        for(var k in canvasBoxes) {
            if(canvas) canvas.remove(canvasBoxes[k]);
            document.getElementById('enable_' + k).checked = false;
            document.getElementById(k + '_inputs').style.display = 'none';
        }
        for(var k in canvasIcons) {
            if(canvas) canvas.remove(canvasIcons[k]);
        }
        canvasBoxes = {};
        canvasIcons = {};
        slotsData = {};
        
        // Apply
        for(var key in data) {
            if(document.getElementById('enable_' + key)) {
                document.getElementById('enable_' + key).checked = true;
                document.getElementById(key + '_inputs').style.display = 'block';
                document.getElementById(key + '_x').value = data[key].x || '';
                document.getElementById(key + '_y').value = data[key].y || '';
                document.getElementById(key + '_w').value = data[key].w || '';
                document.getElementById(key + '_h').value = data[key].h || '';
                var colInput = document.getElementById(key + '_color');
                if(colInput && data[key].color) colInput.value = data[key].color;
                
                if(document.getElementById(key + '_fontSize') && data[key].fontSize) document.getElementById(key + '_fontSize').value = data[key].fontSize;
                if(document.getElementById(key + '_fontFamily') && data[key].fontFamily) document.getElementById(key + '_fontFamily').value = data[key].fontFamily;
                if(document.getElementById(key + '_fontWeight') && data[key].fontWeight) document.getElementById(key + '_fontWeight').value = data[key].fontWeight;
                
                if((key === 'phone' || key === 'address') && data[key].icon) {
                    if(document.getElementById(key + '_icon_x') && data[key].icon.x !== undefined) document.getElementById(key + '_icon_x').value = data[key].icon.x;
                    if(document.getElementById(key + '_icon_y') && data[key].icon.y !== undefined) document.getElementById(key + '_icon_y').value = data[key].icon.y;
                    if(document.getElementById(key + '_icon_w') && data[key].icon.w !== undefined) document.getElementById(key + '_icon_w').value = data[key].icon.w;
                    if(document.getElementById(key + '_icon_h') && data[key].icon.h !== undefined) document.getElementById(key + '_icon_h').value = data[key].icon.h;
                    if(document.getElementById(key + '_icon_color') && data[key].icon.color !== undefined) document.getElementById(key + '_icon_color').value = data[key].icon.color;
                }
                
                drawBox(key, getLabelForKey(key), data[key].x, data[key].y, data[key].w, data[key].h);
            }
        }
        updateSlotsData();
        alert("Layout imported!");
    } catch(e) {
        alert("Invalid JSON! Error: " + e.message);
    }
}

// ===== FILE INPUT CHANGE → THUMBNAILS =====
document.querySelector('input[name="poster_image[]"]').addEventListener('change', function(e) {
    generateThumbnails(e.target);
});

function generateThumbnails(fileInput) {
    var files = fileInput.files;
    var previewDiv = document.getElementById('selected_files_preview');
    if(!files || files.length === 0) {
        previewDiv.innerHTML = '';
        return;
    }
    
    previewDiv.innerHTML = '<p style="margin-bottom:5px;"><strong>Selected (' + files.length + '):</strong> Click to preview, X to remove.</p><div id="thumbContainer" style="display:flex; flex-wrap:wrap; gap:10px;"></div>';
    var container = document.getElementById('thumbContainer');
    
    for(var i = 0; i < files.length; i++) {
        (function(file, index) {
            var reader = new FileReader();
            reader.onload = function(ev) {
                var box = document.createElement('div');
                box.style.cssText = 'position:relative; border:2px solid #ddd; padding:2px; cursor:pointer; width:60px; text-align:center;';
                
                var img = document.createElement('img');
                img.src = ev.target.result;
                img.style.cssText = 'width:100%; height:50px; object-fit:cover;';
                img.title = file.name;
                box.appendChild(img);
                
                var editThumb = document.getElementById('edit_thumb_preview');
                if(editThumb) editThumb.src = ev.target.result;
                
                var nameDiv = document.createElement('div');
                nameDiv.style.cssText = 'font-size:9px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;';
                nameDiv.textContent = file.name;
                box.appendChild(nameDiv);
                
                // X delete button
                var del = document.createElement('span');
                del.innerHTML = '&times;';
                del.style.cssText = 'position:absolute; top:-8px; right:-8px; background:red; color:white; border-radius:50%; width:18px; height:18px; line-height:16px; font-size:14px; text-align:center; cursor:pointer; font-weight:bold;';
                del.onclick = function(e) {
                    e.stopPropagation();
                    var dt = new DataTransfer();
                    for(var j = 0; j < fileInput.files.length; j++) {
                        if(j !== index) dt.items.add(fileInput.files[j]);
                    }
                    fileInput.files = dt.files;
                    generateThumbnails(fileInput);
                };
                box.appendChild(del);
                
                // Click to load in canvas
                box.onclick = function() {
                    var allBoxes = container.querySelectorAll('div');
                    for(var b = 0; b < allBoxes.length; b++) allBoxes[b].style.borderColor = '#ddd';
                    box.style.borderColor = '#007bff';
                    
                    if(document.getElementById('personalization_enabled').checked) {
                        loadCanvasImage(file);
                    } else {
                        alert('Check "Enable Personalization" first.');
                    }
                };
                
                container.appendChild(box);
                
                // Auto-load first image
                if(index === 0) {
                    box.style.borderColor = '#007bff';
                    if(document.getElementById('personalization_enabled').checked) {
                        loadCanvasImage(file);
                    }
                }
            };
            reader.readAsDataURL(file);
        })(files[i], i);
    }
}

// ===== EDIT MODE: Load existing poster =====
<?php if(isset($edit_poster) && !empty($edit_poster['poster_image'])): ?>
    setTimeout(function() {
        initCanvas();
        if(!canvas) return;
        var editImg = document.createElement('img');
        
        editImg.onload = function() {
            try {
                imgScale = 400 / editImg.width;
                canvas.setWidth(400);
                canvas.setHeight(editImg.height * imgScale);
                
                var dataUrl = editImg.src;
                
                var wrapper = document.querySelector('.canvas-container');
                var targetParent = wrapper || document.getElementById('miyu_poster_canvas_unique').parentNode;
                
                var bgImg = document.getElementById('miyu_real_bg_img');
                if(!bgImg) {
                    bgImg = document.createElement('img');
                    bgImg.id = 'miyu_real_bg_img';
                    bgImg.style.position = 'absolute';
                    bgImg.style.left = '0';
                    bgImg.style.top = '0';
                    bgImg.style.zIndex = '0';
                    bgImg.style.pointerEvents = 'none';
                    targetParent.insertBefore(bgImg, targetParent.firstChild);
                    targetParent.style.position = 'relative';
                }
                bgImg.src = dataUrl;
                bgImg.style.width = '400px';
                bgImg.style.height = (editImg.height * imgScale) + 'px';
                
                var allCanvases = targetParent.querySelectorAll('canvas');
                for(var c=0; c<allCanvases.length; c++) {
                    allCanvases[c].style.background = 'transparent';
                    allCanvases[c].style.backgroundColor = 'transparent';
                }
                
                // Clear any old fabric background image objects
                var objs = canvas.getObjects();
                for(var i = objs.length - 1; i >= 0; i--) {
                    if(objs[i].id === 'poster_bg') canvas.remove(objs[i]);
                }
                canvas.backgroundImage = null;
                
                canvas.renderAll();
                renderExistingSlots();
            } catch(e) {
                alert("Fabric error in edit mode: " + e.message);
            }
        };
        editImg.onerror = function() {
            alert("Error loading existing poster image for edit. Path: <?php echo base_url($edit_poster['poster_image']); ?>");
        };
        editImg.src = '<?php echo base_url($edit_poster['poster_image']); ?>';
    }, 500);
<?php endif; ?>

// ===== SLOT FUNCTIONS =====
function renderExistingSlots() {
    for(var key in slotsData) {
        var el = document.getElementById('enable_' + key);
        if(el) {
            el.checked = true;
            document.getElementById(key + '_inputs').style.display = 'block';
            document.getElementById(key + '_x').value = slotsData[key].x || '';
            document.getElementById(key + '_y').value = slotsData[key].y || '';
            document.getElementById(key + '_w').value = slotsData[key].w || '';
            document.getElementById(key + '_h').value = slotsData[key].h || '';
            var colInput = document.getElementById(key + '_color');
            if(colInput && slotsData[key].color) colInput.value = slotsData[key].color;
            
            if(document.getElementById(key + '_fontSize') && slotsData[key].fontSize) document.getElementById(key + '_fontSize').value = slotsData[key].fontSize;
            if(document.getElementById(key + '_fontFamily') && slotsData[key].fontFamily) document.getElementById(key + '_fontFamily').value = slotsData[key].fontFamily;
            if(document.getElementById(key + '_fontWeight') && slotsData[key].fontWeight) document.getElementById(key + '_fontWeight').value = slotsData[key].fontWeight;
            
            if((key === 'phone' || key === 'address') && slotsData[key].icon) {
                if(document.getElementById(key + '_icon_x') && slotsData[key].icon.x !== undefined) document.getElementById(key + '_icon_x').value = slotsData[key].icon.x;
                if(document.getElementById(key + '_icon_y') && slotsData[key].icon.y !== undefined) document.getElementById(key + '_icon_y').value = slotsData[key].icon.y;
                if(document.getElementById(key + '_icon_w') && slotsData[key].icon.w !== undefined) document.getElementById(key + '_icon_w').value = slotsData[key].icon.w;
                if(document.getElementById(key + '_icon_h') && slotsData[key].icon.h !== undefined) document.getElementById(key + '_icon_h').value = slotsData[key].icon.h;
                if(document.getElementById(key + '_icon_color') && slotsData[key].icon.color !== undefined) document.getElementById(key + '_icon_color').value = slotsData[key].icon.color;
            }
            
            drawBox(key, getLabelForKey(key), slotsData[key].x, slotsData[key].y, slotsData[key].w, slotsData[key].h);
        }
    }
}

function getLabelForKey(key) {
    var labels = {'logo':'Logo', 'business_name':'Biz Name', 'phone':'Phone', 'address':'Address'};
    return labels[key] || key;
}

function toggleSlot(key, label) {
    var isEnabled = document.getElementById('enable_' + key).checked;
    document.getElementById(key + '_inputs').style.display = isEnabled ? 'block' : 'none';
    
    if(isEnabled) {
        initCanvas();
        var defX = 100, defY = 850, defW = 300, defH = 40;
        if(key === 'logo') { defX = 80; defY = 840; defW = 120; defH = 120; }
        if(key === 'business_name') { defX = 220; defY = 840; defW = 500; defH = 50; }
        if(key === 'phone') { defX = 260; defY = 910; defW = 280; defH = 35; }
        if(key === 'address') { defX = 580; defY = 910; defW = 400; defH = 35; }
        
        document.getElementById(key + '_x').value = defX;
        document.getElementById(key + '_y').value = defY;
        document.getElementById(key + '_w').value = defW;
        document.getElementById(key + '_h').value = defH;
        
        if(key === 'phone' || key === 'address') {
            var iconW = 28, iconH = 28;
            var iconXInput = document.getElementById(key + '_icon_x');
            var iconYInput = document.getElementById(key + '_icon_y');
            var iconWInput = document.getElementById(key + '_icon_w');
            var iconHInput = document.getElementById(key + '_icon_h');
            var iconColInput = document.getElementById(key + '_icon_color');
            
            if(iconWInput) iconWInput.value = iconW;
            if(iconHInput) iconHInput.value = iconH;
            if(iconXInput && (!iconXInput.value || isNaN(parseInt(iconXInput.value)))) iconXInput.value = defX - iconW - 10;
            if(iconYInput && (!iconYInput.value || isNaN(parseInt(iconYInput.value)))) iconYInput.value = defY + Math.round((defH - iconH) / 2);
            if(iconColInput && !iconColInput.value) iconColInput.value = '#ffffff';
        }
        
        drawBox(key, label, defX, defY, defW, defH);
        updateSlotsData();
    } else {
        if(canvasBoxes[key] && canvas) {
            canvas.remove(canvasBoxes[key]);
            delete canvasBoxes[key];
        }
        if(canvasIcons[key] && canvas) {
            canvas.remove(canvasIcons[key]);
            delete canvasIcons[key];
        }
        delete slotsData[key];
        updateSlotsData();
        if(canvas) canvas.renderAll();
    }
}

function getDummyText(key, label) {
    if (key === 'business_name') return "Shree Fashion";
    if (key === 'phone') return "8160626089";
    if (key === 'address') return "67, Rio Business Hub, Surat";
    return label;
}

function drawIconObject(key, iconX, iconY, iconW, iconH, iconColor) {
    if(!canvas) return;
    if(canvasIcons[key]) {
        canvas.remove(canvasIcons[key]);
        delete canvasIcons[key];
    }
    
    // SVG Paths for Phone and Location Pin
    var pathStr = (key === 'address')
        ? "M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"
        : "M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z";
    
    try {
        var pathObj = new fabric.Path(pathStr, {
            fill: iconColor || '#ffffff',
            originX: 'center',
            originY: 'center'
        });
        
        var naturalDim = Math.max(pathObj.width || 24, pathObj.height || 24);
        var targetDim = iconW * imgScale;
        pathObj.scale(targetDim / naturalDim);
        
        var hitRect = new fabric.Rect({
            width: iconW * imgScale,
            height: iconH * imgScale,
            fill: 'transparent',
            stroke: 'rgba(255, 152, 0, 0.6)',
            strokeWidth: 1,
            strokeDashArray: [3, 3],
            originX: 'center',
            originY: 'center'
        });
        
        var iconGroup = new fabric.Group([hitRect, pathObj], {
            left: iconX * imgScale,
            top: iconY * imgScale,
            originX: 'left',
            originY: 'top',
            cornerColor: '#ff9800',
            cornerSize: 6,
            transparentCorners: false,
            hasRotatingPoint: false,
            id: key + '_icon'
        });
        
        iconGroup.on('moving', function() {
            var currX = Math.round(iconGroup.left / imgScale);
            var currY = Math.round(iconGroup.top / imgScale);
            var iconXInput = document.getElementById(key + '_icon_x');
            var iconYInput = document.getElementById(key + '_icon_y');
            if(iconXInput) iconXInput.value = currX;
            if(iconYInput) iconYInput.value = currY;
            updateSlotsData();
        });
        
        canvas.add(iconGroup);
        canvasIcons[key] = iconGroup;
    } catch(err) {
        console.error("Error drawing icon on canvas: ", err);
    }
}

function drawBox(key, label, x, y, w, h) {
    if(!canvas) { initCanvas(); if(!canvas) return; }
    
    if(canvasBoxes[key]) canvas.remove(canvasBoxes[key]);
    
    var groupItems = [];
    
    // Transparent box with dashed border to show boundaries
    var rect = new fabric.Rect({
        width: w * imgScale, height: h * imgScale,
        fill: 'transparent', stroke: 'rgba(255,0,0,0.6)', strokeWidth: 1, strokeDashArray: [4, 4],
        originX: 'left', originY: 'top'
    });
    groupItems.push(rect);
    
    var tColor = '#000000';
    if (key === 'logo') {
        var logoBg = new fabric.Rect({
            width: w * imgScale, height: h * imgScale,
            fill: 'rgba(200,200,200,0.5)', originX: 'left', originY: 'top'
        });
        groupItems.unshift(logoBg);
        var text = new fabric.Text("LOGO", {
            fontSize: 18 * imgScale, fill: '#333', originX: 'center', originY: 'center',
            top: (h * imgScale) / 2, left: (w * imgScale) / 2, fontWeight: 'bold'
        });
        groupItems.push(text);
    } else {
        // Fetch Live Style
        var colInput = document.getElementById(key + '_color');
        var fSizeInput = document.getElementById(key + '_fontSize');
        var fFamilyInput = document.getElementById(key + '_fontFamily');
        var fWeightInput = document.getElementById(key + '_fontWeight');
        
        tColor = (colInput && colInput.value) ? colInput.value : '#000000';
        var tSize = (fSizeInput && fSizeInput.value && !isNaN(parseInt(fSizeInput.value))) ? parseInt(fSizeInput.value) : 24;
        var tFam = (fFamilyInput && fFamilyInput.value) ? fFamilyInput.value : 'Poppins';
        var tWeight = (fWeightInput && fWeightInput.value) ? fWeightInput.value : '600';
        
        var dummy = getDummyText(key, label);
        
        var text = new fabric.Text(dummy, {
            fontSize: tSize * imgScale, 
            fill: tColor, 
            fontFamily: tFam,
            fontWeight: tWeight,
            originX: 'center', originY: 'center',
            top: (h * imgScale) / 2, left: (w * imgScale) / 2
        });
        groupItems.push(text);
    }
    
    var group = new fabric.Group(groupItems, {
        left: x * imgScale, top: y * imgScale,
        cornerColor: 'blue', transparentCorners: false, id: key
    });
    
    group._lastBoxX = x;
    group._lastBoxY = y;
    
    group.on('moving', function() { updateInputsFromBox(key, group); });
    group.on('scaling', function() { updateInputsFromBox(key, group); });
    
    canvas.add(group);
    canvasBoxes[key] = group;
    
    // Draw associated icon on canvas for phone & address!
    if(key === 'phone' || key === 'address') {
        var iconWInput = document.getElementById(key + '_icon_w');
        var iconHInput = document.getElementById(key + '_icon_h');
        var iconXInput = document.getElementById(key + '_icon_x');
        var iconYInput = document.getElementById(key + '_icon_y');
        var iconColInput = document.getElementById(key + '_icon_color');
        
        var iW = parseInt(iconWInput ? iconWInput.value : 28) || 28;
        var iH = parseInt(iconHInput ? iconHInput.value : 28) || 28;
        var iCol = (iconColInput && iconColInput.value) ? iconColInput.value : (tColor || '#ffffff');
        
        var iX = parseInt(iconXInput ? iconXInput.value : NaN);
        if(isNaN(iX)) {
            iX = x - iW - 8;
            if(iconXInput) iconXInput.value = iX;
        }
        var iY = parseInt(iconYInput ? iconYInput.value : NaN);
        if(isNaN(iY)) {
            iY = y + Math.round((h - iH) / 2);
            if(iconYInput) iconYInput.value = iY;
        }
        if(iconWInput) iconWInput.value = iW;
        if(iconHInput) iconHInput.value = iH;
        if(iconColInput) iconColInput.value = iCol;
        
        drawIconObject(key, iX, iY, iW, iH, iCol);
    }
    
    canvas.renderAll();
}

function updateInputsFromBox(key, group) {
    var newX = Math.round(group.left / imgScale);
    var newY = Math.round(group.top / imgScale);
    var newW = Math.round((group.width * group.scaleX) / imgScale);
    var newH = Math.round((group.height * group.scaleY) / imgScale);
    
    var lastX = group._lastBoxX !== undefined ? group._lastBoxX : newX;
    var lastY = group._lastBoxY !== undefined ? group._lastBoxY : newY;
    var dx = newX - lastX;
    var dy = newY - lastY;
    
    document.getElementById(key + '_x').value = newX;
    document.getElementById(key + '_y').value = newY;
    document.getElementById(key + '_w').value = newW;
    document.getElementById(key + '_h').value = newH;
    
    // Automatically move associated icon in sync!
    if((key === 'phone' || key === 'address') && canvasIcons[key]) {
        var iconXInput = document.getElementById(key + '_icon_x');
        var iconYInput = document.getElementById(key + '_icon_y');
        if(iconXInput && iconYInput) {
            var oldIconX = parseInt(iconXInput.value) || (newX - 35);
            var oldIconY = parseInt(iconYInput.value) || newY;
            var newIconX = oldIconX + dx;
            var newIconY = oldIconY + dy;
            
            iconXInput.value = newIconX;
            iconYInput.value = newIconY;
            
            canvasIcons[key].set({
                left: newIconX * imgScale,
                top: newIconY * imgScale
            });
            canvasIcons[key].setCoords();
        }
    }
    
    group._lastBoxX = newX;
    group._lastBoxY = newY;
    
    updateSlotsData();
    if(canvas) canvas.renderAll();
}

function updateBoxFromInput(key) {
    var x = parseInt(document.getElementById(key + '_x').value) || 0;
    var y = parseInt(document.getElementById(key + '_y').value) || 0;
    var w = parseInt(document.getElementById(key + '_w').value) || 100;
    var h = parseInt(document.getElementById(key + '_h').value) || 40;
    
    drawBox(key, getLabelForKey(key), x, y, w, h);
    updateSlotsData();
}

function updateSlotsData() {
    slotsData = {};
    for(var key in canvasBoxes) {
        var boxData = {
            x: parseInt(document.getElementById(key + '_x').value) || 0,
            y: parseInt(document.getElementById(key + '_y').value) || 0,
            w: parseInt(document.getElementById(key + '_w').value) || 0,
            h: parseInt(document.getElementById(key + '_h').value) || 0
        };
        if(key !== 'logo') {
            var colInput = document.getElementById(key + '_color');
            if(colInput) boxData.color = colInput.value;
            
            var fSizeInput = document.getElementById(key + '_fontSize');
            if(fSizeInput && fSizeInput.value) boxData.fontSize = parseInt(fSizeInput.value);
            
            var fFamilyInput = document.getElementById(key + '_fontFamily');
            if(fFamilyInput && fFamilyInput.value) boxData.fontFamily = fFamilyInput.value;
            
            var fWeightInput = document.getElementById(key + '_fontWeight');
            if(fWeightInput && fWeightInput.value) boxData.fontWeight = parseInt(fWeightInput.value);
        }
        
        if(key === 'phone' || key === 'address') {
            var icon_x = parseInt(document.getElementById(key + '_icon_x').value);
            var icon_y = parseInt(document.getElementById(key + '_icon_y').value);
            var icon_w = parseInt(document.getElementById(key + '_icon_w').value) || 28;
            var icon_h = parseInt(document.getElementById(key + '_icon_h').value) || 28;
            var icon_color = document.getElementById(key + '_icon_color').value || '#ffffff';
            
            if(!isNaN(icon_x) && !isNaN(icon_y)) {
                boxData.icon = {
                    type: key === 'address' ? "location" : "phone",
                    x: icon_x,
                    y: icon_y,
                    w: icon_w,
                    h: icon_h,
                    color: icon_color
                };
            }
        }
        slotsData[key] = boxData;
    }
    document.getElementById('slots_data').value = JSON.stringify(slotsData);
}

</script>

