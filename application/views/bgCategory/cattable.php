<style>
/* Modern Sequence Manager Styles */
.cat-view-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    background: #f1f5f9;
    padding: 6px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    width: fit-content;
}
.cat-view-tab {
    padding: 9px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #475569;
    border: none;
    background: transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cat-view-tab:hover {
    color: #0f172a;
    background: rgba(255, 255, 255, 0.6);
}
.cat-view-tab.active {
    background: #ffffff;
    color: #2563eb;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}
.cat-view-tab .badge {
    background: #e2e8f0;
    color: #334155;
    font-size: 12px;
    padding: 2px 7px;
    border-radius: 20px;
}
.cat-view-tab.active .badge {
    background: #dbeafe;
    color: #1e40af;
}

/* Quick Sequence Manager Panel */
.seq-manager-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
    overflow: hidden;
}
.seq-manager-header {
    background: #ffffff;
    padding: 16px 20px;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 20;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.seq-search-box {
    position: relative;
    min-width: 280px;
}
.seq-search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 14px;
}
.seq-search-box input {
    padding-left: 36px;
    height: 40px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    font-size: 14px;
    width: 100%;
    transition: all 0.2s ease;
}
.seq-search-box input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    outline: none;
}
.seq-actions-group {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.btn-seq {
    padding: 9px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
}
.btn-seq-autorotate {
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    color: #ffffff;
    box-shadow: 0 3px 10px rgba(99, 102, 241, 0.35);
}
.btn-seq-autorotate:hover {
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.45);
}
.btn-seq-renumber {
    background: #f8fafc;
    color: #334155;
    border: 1px solid #cbd5e1;
}
.btn-seq-renumber:hover {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #94a3b8;
}
.btn-seq-resort {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}
.btn-seq-resort:hover {
    background: #dbeafe;
}
.btn-seq-save {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.35);
}
.btn-seq-save:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.45);
    color: #ffffff;
}

/* Sequence Table */
.seq-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.seq-table th {
    background: #f8fafc;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 2px solid #e2e8f0;
}
.seq-table td {
    padding: 10px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
    background: #ffffff;
    transition: background-color 0.25s ease;
}
.seq-table tr:hover td {
    background: #f8fafc;
}
.seq-table tr.seq-row-modified td {
    background: #fefce8 !important;
}
.seq-table tr.seq-row-saved td {
    background: #ecfdf5 !important;
}

/* Drag Handle */
.seq-drag-handle {
    cursor: grab;
    color: #94a3b8;
    font-size: 16px;
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s;
}
.seq-drag-handle:hover {
    color: #2563eb;
    background: #eff6ff;
}
.seq-drag-handle:active {
    cursor: grabbing;
}

/* Rank Badge */
.seq-rank-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}
.seq-rank-top {
    background: #fef3c7;
    color: #b45309;
    border-color: #fde68a;
}

/* Sequence Input */
.seq-input-field {
    width: 82px;
    height: 38px;
    text-align: center;
    font-weight: 700;
    font-size: 15px;
    color: #0f172a;
    border-radius: 8px;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    transition: all 0.2s ease;
}
.seq-input-field:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    outline: none;
    background: #eff6ff;
}

/* Quick Shift Buttons */
.seq-shift-btns {
    display: flex;
    gap: 4px;
}
.seq-shift-btn {
    width: 30px;
    height: 30px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.15s ease;
}
.seq-shift-btn:hover {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
}
.seq-shift-btn.btn-top:hover {
    background: #f59e0b;
    border-color: #f59e0b;
}

/* Floating Save Toast */
.seq-save-toast {
    position: fixed;
    bottom: 25px;
    right: 25px;
    background: #0f172a;
    color: #ffffff;
    padding: 14px 22px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    display: none;
    align-items: center;
    gap: 10px;
    z-index: 9999;
    animation: fadeInToast 0.3s ease;
}
@keyframes fadeInToast {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <i class="fa-solid fa-image"></i>
            </div>
            <h1>Background Categories <small><span class="cgreen"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Active</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li class="active">Background Categories</li>
        </ul>
        <div class="clear"></div>
    </div>

    <!-- View Mode Switcher Tabs -->
    <div class="cat-view-tabs">
        <button type="button" class="cat-view-tab" id="tabBtnTable" onclick="switchView('table')">
            <i class="fa-solid fa-table-list"></i> Standard Table View
        </button>
        <button type="button" class="cat-view-tab active" id="tabBtnSequence" onclick="switchView('sequence')">
            <i class="fa-solid fa-arrow-down-1-9"></i> ⚡ Quick Sequence Manager (All In 1 Page)
            <span class="badge" id="tabCountBadge"><?= count($data) ?></span>
        </button>
    </div>

    <!-- VIEW 1: QUICK SEQUENCE MANAGER (ALL IN ONE PAGE) -->
    <div id="viewSequenceManager">
        <div class="seq-manager-card">
            <!-- Sticky Action Header -->
            <div class="seq-manager-header">
                <div class="seq-search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="seqSearchInput" placeholder="Filter backgrounds by name or #ID...">
                </div>

                <div class="seq-actions-group">
                    <span id="unsavedChangesBadge" style="display: none; font-size: 13px; font-weight: 700; color: #b45309; background: #fef3c7; padding: 6px 12px; border-radius: 20px; border: 1px solid #fde68a;">
                        <i class="fa-solid fa-circle-exclamation"></i> <span id="unsavedCount">0</span> changes pending
                    </span>
                    <button type="button" class="btn-seq btn-seq-autorotate" id="btnAutoRotateNow" title="Automatically calculate today's date and arrange seasonal backgrounds">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> Auto-Rotate by Date
                    </button>
                    <button type="button" class="btn-seq btn-seq-renumber" id="btnAutoRenumber" title="Renumber visible categories from 0 to N sequentially">
                        <i class="fa-solid fa-list-ol"></i> Auto-Renumber (0, 1, 2...)
                    </button>
                    <button type="button" class="btn-seq btn-seq-resort" id="btnResortList" title="Re-order the rows visually on screen based on the typed numbers">
                        <i class="fa-solid fa-arrow-down-short-wide"></i> Re-Sort by Numbers
                    </button>
                    <button type="button" class="btn-seq btn-seq-save" id="btnSaveAllSeq">
                        <i class="fa-solid fa-floppy-disk"></i> <span>Save All Sequences</span>
                    </button>
                </div>
            </div>

            <!-- Sequence List Table (Zero Pagination - All Categories) -->
            <div style="max-height: 75vh; overflow-y: auto;">
                <table class="seq-table">
                    <thead>
                        <tr>
                            <th width="5%" style="text-align: center;">Drag</th>
                            <th width="8%" style="text-align: center;">Rank</th>
                            <th width="12%">Category ID</th>
                            <th width="40%">Background Category Name</th>
                            <th width="18%" style="text-align: center;">Sequence Number (App Order)</th>
                            <th width="17%" style="text-align: center;">Quick Position</th>
                        </tr>
                    </thead>
                    <tbody id="seqSortableList">
                        <?php if(!empty($data)){ 
                            $rank = 1;
                            foreach ($data as $cat) { 
                                $seq = isset($cat['sequence_number']) ? intval($cat['sequence_number']) : 0;
                        ?>
                        <tr class="seq-item-row" data-id="<?=$cat['bg_cat_id']?>" data-name="<?=strtolower($cat['name'])?>">
                            <td style="text-align: center;">
                                <span class="seq-drag-handle" title="Drag to reorder"><i class="fa-solid fa-grip-vertical"></i></span>
                            </td>
                            <td style="text-align: center;">
                                <span class="seq-rank-badge <?= ($rank <= 3) ? 'seq-rank-top' : '' ?>"><?= $rank ?></span>
                            </td>
                            <td>
                                <strong style="color: #64748b;">#<?=$cat['bg_cat_id']?></strong>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <strong class="cat-display-name" style="color: #0f172a; font-size: 15px; font-weight: 600;"><?= ucfirst($cat['name']); ?></strong>
                                    <a href="<?= base_url('site/showbgcateditform/'.$cat['bg_cat_id']) ?>" target="_blank" style="color: #94a3b8; font-size: 12px; margin-left: 8px;" title="Full Edit">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <input type="number" 
                                       class="seq-input-field bulk-seq-input" 
                                       data-id="<?=$cat['bg_cat_id']?>" 
                                       data-original="<?=$seq?>" 
                                       value="<?=$seq?>" 
                                       min="0">
                            </td>
                            <td style="text-align: center;">
                                <div class="seq-shift-btns" style="justify-content: center;">
                                    <button type="button" class="seq-shift-btn btn-top" title="Move to Top (#0)" onclick="moveRowToTop(this)">
                                        <i class="fa-solid fa-angles-up"></i>
                                    </button>
                                    <button type="button" class="seq-shift-btn" title="Move Up" onclick="moveRowUp(this)">
                                        <i class="fa-solid fa-angle-up"></i>
                                    </button>
                                    <button type="button" class="seq-shift-btn" title="Move Down" onclick="moveRowDown(this)">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </button>
                                    <button type="button" class="seq-shift-btn" title="Move to Bottom" onclick="moveRowToBottom(this)">
                                        <i class="fa-solid fa-angles-down"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php $rank++; } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- VIEW 2: STANDARD TABLE VIEW (CLASSIC DATA-TABLE) -->
    <div id="viewStandardTable" style="display: none;">
        <div class="row">
            <div class="col-md-12">
                <div class="block">
                    <div class="head">
                        <h2><i class="fa-solid fa-table-list"></i> Background Categories Listing</h2>
                        <ul class="buttons">
                            <li>
                                <a href="<?= base_url('site/showaddbgcatform') ?>" class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i> Add Category
                                </a>
                            </li>
                        </ul>                                                   
                    </div> 

                    <div id="dialog-confirm" title="Delete Background Category?" style="display: none;">
                        <p style="margin:10px; font-size: 15px; color: #ef4444;">
                            <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i> This Background Category will be permanently deleted. Are you sure?
                        </p>
                    </div>

                    <div class="data-fluid">
                        <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%"><input type="checkbox" class="checkall"/></th>
                                    <th width="10%">ID</th>
                                    <th width="45%">Category Name</th>
                                    <th width="20%" style="text-align: center;">Sequence Number</th>
                                    <th width="20%" class="TAC">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($data){ foreach ($data as $value) { 
                                    $sNum = isset($value['sequence_number']) ? intval($value['sequence_number']) : 0;
                                ?>
                                <tr id="<?php echo $value['bg_cat_id'] ?>" class="gradeX">
                                    <td><input type="checkbox" name="order[]" value="<?=$value['bg_cat_id']?>"/></td>
                                    <td><strong style="color: #64748b;">#<?=$value['bg_cat_id']?></strong></td>
                                    <td><strong style="color: #0f172a; font-size: 14px;"><?php echo ucfirst($value['name']); ?></strong></td>
                                    <td style="text-align: center;">
                                        <div style="display: inline-flex; align-items: center; gap: 6px;">
                                            <input type="number" 
                                                   class="form-control std-inline-seq" 
                                                   data-id="<?=$value['bg_cat_id']?>" 
                                                   value="<?=$sNum?>" 
                                                   style="width: 75px; text-align: center; font-weight: 700; height: 34px; border-radius: 6px;" 
                                                   title="Edit and click outside to save">
                                            <span class="inline-save-indicator" id="ind_<?=$value['bg_cat_id']?>"></span>
                                        </div>
                                    </td>
                                    <td class="TAC">
                                        <a href="<?php echo base_url()."site/showbgcateditform/".$value['bg_cat_id']; ?>" class="button green" title="Edit Category">
                                            <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                        </a>
                                        <a href="javascript:void(0);" id="<?php echo $value['bg_cat_id']; ?>" class="button red delete" title="Delete Category">
                                            <div class="icon"><i class="fa-solid fa-trash"></i></div>
                                        </a>                          
                                    </td>
                                </tr>
                                <?php } } ?>                          
                            </tbody>
                        </table>                    
                    </div> 
                </div>                       
            </div>
        </div>
    </div>
</div>

<!-- Floating Save Toast -->
<div class="seq-save-toast" id="saveToast">
    <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 18px;"></i>
    <span id="saveToastMsg">Saved successfully!</span>
</div>

<script type="text/javascript">
var pendingChanges = 0;

// View Mode Switcher
function switchView(mode) {
    if(mode === 'sequence') {
        $('#viewSequenceManager').show();
        $('#viewStandardTable').hide();
        $('#tabBtnSequence').addClass('active');
        $('#tabBtnTable').removeClass('active');
    } else {
        $('#viewSequenceManager').hide();
        $('#viewStandardTable').show();
        $('#tabBtnTable').addClass('active');
        $('#tabBtnSequence').removeClass('active');
    }
}

// Show Toast
function showToast(msg) {
    $('#saveToastMsg').text(msg);
    $('#saveToast').fadeIn(200).delay(2200).fadeOut(300);
}

// Update Unsaved Changes Count Badge
function updateUnsavedIndicator() {
    var modifiedRows = $('.seq-item-row.seq-row-modified').length;
    pendingChanges = modifiedRows;
    if(pendingChanges > 0) {
        $('#unsavedCount').text(pendingChanges);
        $('#unsavedChangesBadge').show();
        $('#btnSaveAllSeq span').text('Save All Sequences (' + pendingChanges + ' Changed)');
    } else {
        $('#unsavedChangesBadge').hide();
        $('#btnSaveAllSeq span').text('Save All Sequences');
    }
}

// Refresh visual Rank Numbers (#1, #2, #3...)
function refreshVisualRanks() {
    $('#seqSortableList .seq-item-row').each(function(index) {
        var rank = index + 1;
        var badge = $(this).find('.seq-rank-badge');
        badge.text(rank);
        if(rank <= 3) {
            badge.addClass('seq-rank-top');
        } else {
            badge.removeClass('seq-rank-top');
        }
    });
}

// Move Row Functions
function moveRowToTop(btn) {
    var row = $(btn).closest('tr');
    $('#seqSortableList').prepend(row);
    row.addClass('seq-row-modified');
    autoRenumberFromVisualOrder();
    refreshVisualRanks();
    updateUnsavedIndicator();
}

function moveRowToBottom(btn) {
    var row = $(btn).closest('tr');
    $('#seqSortableList').append(row);
    row.addClass('seq-row-modified');
    autoRenumberFromVisualOrder();
    refreshVisualRanks();
    updateUnsavedIndicator();
}

function moveRowUp(btn) {
    var row = $(btn).closest('tr');
    var prev = row.prev('.seq-item-row');
    if(prev.length > 0) {
        row.insertBefore(prev);
        row.addClass('seq-row-modified');
        prev.addClass('seq-row-modified');
        autoRenumberFromVisualOrder();
        refreshVisualRanks();
        updateUnsavedIndicator();
    }
}

function moveRowDown(btn) {
    var row = $(btn).closest('tr');
    var next = row.next('.seq-item-row');
    if(next.length > 0) {
        row.insertAfter(next);
        row.addClass('seq-row-modified');
        next.addClass('seq-row-modified');
        autoRenumberFromVisualOrder();
        refreshVisualRanks();
        updateUnsavedIndicator();
    }
}

// Auto-Renumber based on visual order
function autoRenumberFromVisualOrder() {
    $('#seqSortableList .seq-item-row').each(function(index) {
        var input = $(this).find('.bulk-seq-input');
        var oldVal = input.val();
        if(parseInt(oldVal) !== index) {
            input.val(index);
            $(this).addClass('seq-row-modified');
        }
    });
    refreshVisualRanks();
    updateUnsavedIndicator();
}

$(document).ready(function() {
    // 1. Delete Confirmation for Standard Table
    $(".table").on("click", ".delete", function() {
        var catID = $(this).attr('id');
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog( "close" );
                    $.ajax({
                        url: '<?php echo base_url(); ?>site/bgdeletecat',
                        dataType: 'json',
                        method: 'post',
                        data: {cat_id: catID},
                        success: function(data) {
                            alert(data);
                            location.reload();
                        }
                    });
                },
                "No": function() {
                    $(this).dialog( "close" );
                }
            }
        });
    });

    // 2. jQuery UI Sortable on the Full List
    if ($.fn.sortable) {
        $("#seqSortableList").sortable({
            handle: ".seq-drag-handle",
            cursor: "grabbing",
            opacity: 0.8,
            placeholder: "seq-sort-placeholder",
            update: function(event, ui) {
                ui.item.addClass('seq-row-modified');
                autoRenumberFromVisualOrder();
            }
        });
    }

    // 3. Instant Search Filter
    $("#seqSearchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase().trim();
        $("#seqSortableList .seq-item-row").filter(function() {
            var name = $(this).data("name") || "";
            var id = $(this).data("id") + "";
            var match = name.indexOf(value) > -1 || id.indexOf(value) > -1;
            $(this).toggle(match);
        });
    });

    // 4. Input value change event in Quick Sequence Manager
    $(document).on("input change", ".bulk-seq-input", function() {
        var row = $(this).closest('tr');
        var originalVal = $(this).data('original');
        var currentVal = $(this).val();

        if (parseInt(currentVal) !== parseInt(originalVal)) {
            row.addClass('seq-row-modified');
        } else {
            row.removeClass('seq-row-modified');
        }
        updateUnsavedIndicator();
    });

    // 5. Auto-Rotate by Date Button (AJAX)
    $("#btnAutoRotateNow").on("click", function() {
        var $btn = $(this);
        if (!confirm("Are you sure you want to Auto-Rotate all 18 background categories based on today's date? Seasonal backgrounds like Christmas / Valentine will move to the front.")) {
            return;
        }
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Auto-Rotating...');
        $.ajax({
            url: '<?php echo base_url(); ?>Site/auto_rotate_bg_categories',
            type: 'POST',
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa-solid fa-wand-magic-sparkles"></i> Auto-Rotate by Date');
                if (res.status === 'success') {
                    alert(res.message);
                    location.reload();
                } else {
                    alert(res.message || "Failed to auto-rotate categories");
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<i class="fa-solid fa-wand-magic-sparkles"></i> Auto-Rotate by Date');
                alert("Error connecting to server. Please try again.");
            }
        });
    });

    // 6. Auto-Renumber Button (0, 1, 2...)
    $("#btnAutoRenumber").on("click", function() {
        autoRenumberFromVisualOrder();
        showToast("Renumbered all visible background categories sequentially (0, 1, 2...)");
    });

    // 7. Re-Sort Rows Visually by Input Number
    $("#btnResortList").on("click", function() {
        var rows = $('#seqSortableList .seq-item-row').get();
        rows.sort(function(a, b) {
            var valA = parseInt($(a).find('.bulk-seq-input').val()) || 0;
            var valB = parseInt($(b).find('.bulk-seq-input').val()) || 0;
            return valA - valB;
        });
        $.each(rows, function(index, row) {
            $('#seqSortableList').append(row);
        });
        refreshVisualRanks();
        showToast("Sorted list visually by numbers!");
    });

    // 8. Save All Sequences (Batch AJAX)
    $("#btnSaveAllSeq").on("click", function() {
        var $btn = $(this);
        var sequences = [];

        $('#seqSortableList .seq-item-row').each(function() {
            var catId = $(this).data('id');
            var seqNum = $(this).find('.bulk-seq-input').val();
            sequences.push({
                cat_id: catId,
                sequence_number: seqNum
            });
        });

        if(sequences.length === 0) {
            alert("No categories found to save.");
            return;
        }

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '<?php echo base_url(); ?>Site/save_bg_category_sequences',
            type: 'POST',
            dataType: 'json',
            data: { sequences: sequences },
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> <span>Save All Sequences</span>');
                if (res.status === 'success') {
                    $('.seq-item-row').removeClass('seq-row-modified').addClass('seq-row-saved');
                    $('.bulk-seq-input').each(function() {
                        $(this).data('original', $(this).val());
                    });
                    setTimeout(function() {
                        $('.seq-item-row').removeClass('seq-row-saved');
                    }, 1800);

                    updateUnsavedIndicator();
                    showToast("✔ " + res.message);
                } else {
                    alert(res.message || "Failed to save sequences");
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> <span>Save All Sequences</span>');
                alert("Error connecting to server. Please try again.");
            }
        });
    });

    // 9. Standard Table Inline Auto-Save on Blur/Change
    $(document).on("change", ".std-inline-seq", function() {
        var $input = $(this);
        var catId = $input.data('id');
        var seqVal = $input.val();
        var $ind = $('#ind_' + catId);

        $ind.html('<i class="fa-solid fa-spinner fa-spin" style="color: #2563eb;"></i>');

        $.ajax({
            url: '<?php echo base_url(); ?>Site/save_bg_category_sequences',
            type: 'POST',
            dataType: 'json',
            data: { cat_id: catId, sequence_number: seqVal },
            success: function(res) {
                if (res.status === 'success') {
                    $ind.html('<i class="fa-solid fa-circle-check" style="color: #10b981;"></i>');
                    setTimeout(function() { $ind.empty(); }, 2000);
                    showToast("Sequence for #" + catId + " saved!");
                } else {
                    $ind.html('<i class="fa-solid fa-circle-xmark" style="color: #ef4444;"></i>');
                }
            },
            error: function() {
                $ind.html('<i class="fa-solid fa-circle-xmark" style="color: #ef4444;"></i>');
            }
        });
    });
});
</script>
