<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <i class="fa-solid fa-panorama"></i>
            </div>
            <h1>Hero Banners (Top Slider) <small><span class="cgreen"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Active</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li class="active">Hero Banners</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="<?php echo (isset($app_id) && $app_id == 1) ? 'active' : ''; ?>"><a href="<?php echo base_url('site/hero_banners?app_id=1'); ?>">App 1 (Default)</a></li>
                <li class="<?php echo (isset($app_id) && $app_id == 2) ? 'active' : ''; ?>"><a href="<?php echo base_url('site/hero_banners?app_id=2'); ?>">App 2</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- Add New Form -->
        <div class="col-md-4">
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-plus-circle"></i> Add New Hero Banner</h2>
                </div>
                <div class="data-fluid">
                    <form action="<?php echo base_url('site/insert_hero'); ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="app_id" value="<?php echo isset($app_id) ? $app_id : 1; ?>">
                        <div class="row-form">
                            <div class="col-md-12"><strong>Upload Hero Poster (Wide Image):</strong></div>
                            <div class="col-md-12"><input type="file" name="banner_image" required accept="image/*"></div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-12"><strong>Select Category (Auto-fills Action URL):</strong></div>
                            <div class="col-md-12">
                                <select id="cat_select_add" style="display: none !important; position: absolute !important; opacity: 0 !important; width: 0 !important; height: 0 !important; pointer-events: none !important; visibility: hidden !important;" onchange="if(this.value){ $('#action_url_add').val('category/' + this.value); }">
                                    <option value="">-- Choose Category --</option>
                                    <?php if(isset($categories) && !empty($categories)) { foreach($categories as $cat) { ?>
                                        <option value="<?php echo $cat['cat_id']; ?>"><?php echo $cat['cat_id']; ?> - <?php echo $cat['cat_name']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-12"><strong>Action URL (Optional, link to open when clicked):</strong></div>
                            <div class="col-md-12">
                                <input type="text" name="action_url" id="action_url_add" placeholder="e.g. category/53" class="form-control">
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-12"><strong>Sequence Number:</strong></div>
                            <div class="col-md-12">
                                <input type="number" name="sequence_number" value="0" required class="form-control">
                            </div>
                        </div>

                        <div class="row-form" style="padding-top: 18px;">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width: 100%;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload & Save Banner
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="col-md-8">
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-images"></i> Active Hero Banners</h2>                                               
                </div> 
                <div class="data-fluid">
                    <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="15%" style="text-align: center;">Sequence</th>
                                <th width="40%">Hero Banner Image</th>
                                <th width="25%">Action URL</th>
                                <th width="20%" class="TAC">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="row_position">
                            <?php if($data) { foreach ($data as $value) { ?>
                            <tr id="<?php echo $value['id']; ?>">
                                <td class="seq_num" style="text-align: center;"><?php echo $value['sequence_number']; ?></td>
                                <td><img src="<?= base_url($value['banner_image']) ?>" alt="" class="img img-thumbnail" width="150" style="border-radius: 8px;"></td>
                                <td><code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;"><?php echo $value['action_url']; ?></code></td>
                                <td class="TAC">
                                    <a href="javascript:void(0);" onclick='editHero(<?php echo json_encode($value); ?>)' class="button green" title="Edit Hero Banner">
                                        <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                    </a>
                                    <a href="<?php echo base_url('site/delete_hero/'.$value['id']); ?>" class="button red" onclick="return confirm('Delete this Hero Banner?');" title="Delete">
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

<!-- Edit Hero Modal -->
<div class="modal fade" id="editHeroModal" role="dialog" aria-labelledby="editHeroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo base_url('site/update_hero'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="editHeroModalLabel"><i class="fa-solid fa-pen-to-square" style="color: #4f46e5; margin-right: 8px;"></i> Edit Hero Banner</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="app_id" value="<?php echo isset($app_id) ? $app_id : 1; ?>">
                    <input type="hidden" name="id" id="edit_hero_id">
                    
                    <div class="row-form" style="margin-bottom: 15px;">
                        <div class="col-md-12"><strong>Current Banner Image:</strong></div>
                        <div class="col-md-12">
                            <img id="edit_hero_preview" src="" class="img img-thumbnail" width="150" style="margin-bottom: 10px; display: block; border-radius: 8px;">
                            <input type="file" name="banner_image" accept="image/*">
                            <small class="text-muted">Leave blank to keep current image</small>
                        </div>
                    </div>

                    <div class="row-form" style="margin-bottom: 15px;">
                        <div class="col-md-12"><strong>Select Category (Auto-fills Action URL):</strong></div>
                        <div class="col-md-12">
                            <select id="cat_select_edit" style="display: none !important; position: absolute !important; opacity: 0 !important; width: 0 !important; height: 0 !important; pointer-events: none !important; visibility: hidden !important;" onchange="if(this.value){ $('#edit_action_url').val('category/' + this.value); }">
                                <option value="">-- Choose Category --</option>
                                <?php if(isset($categories) && !empty($categories)) { foreach($categories as $cat) { ?>
                                    <option value="<?php echo $cat['cat_id']; ?>"><?php echo $cat['cat_id']; ?> - <?php echo $cat['cat_name']; ?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row-form" style="margin-bottom: 15px;">
                        <div class="col-md-12"><strong>Action URL:</strong></div>
                        <div class="col-md-12">
                            <input type="text" name="action_url" id="edit_action_url" placeholder="e.g. category/53" class="form-control">
                        </div>
                    </div>

                    <div class="row-form" style="margin-bottom: 15px;">
                        <div class="col-md-12"><strong>Sequence Number:</strong></div>
                        <div class="col-md-12">
                            <input type="number" name="sequence_number" id="edit_sequence_number" required class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Update Banner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type='text/javascript' src='<?php echo base_url(); ?>assets/js/jquery.sortable.js'></script>

<style>
/* ==============================================================================
   Searchable Category Dropdown Modern Component
   ============================================================================== */
/* Ensure the original native select is completely hidden, overriding theme !important rules */
#cat_select_add,
#cat_select_edit {
    display: none !important;
    position: absolute !important;
    width: 0 !important;
    height: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    opacity: 0 !important;
    pointer-events: none !important;
    visibility: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
}

.cat-search-wrapper {
    position: relative;
    width: 100%;
    user-select: none;
    font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.cat-search-trigger {
    height: 42px;
    width: 100%;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 0 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease-in-out;
    outline: none;
}

.cat-search-trigger:hover {
    border-color: #94a3b8;
}

.cat-search-trigger:focus,
.cat-search-wrapper.is-open .cat-search-trigger {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18);
}

.cat-trigger-content {
    display: flex;
    align-items: center;
    gap: 8px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    flex: 1;
}

.cat-trigger-placeholder {
    color: #64748b;
    font-size: 13.5px;
    font-weight: 400;
}

.cat-selected-badge {
    background: #eef2ff;
    color: #4338ca;
    font-weight: 700;
    font-size: 11.5px;
    padding: 2px 7px;
    border-radius: 4px;
    border: 1px solid #c7d2fe;
    display: inline-flex;
    align-items: center;
    flex-shrink: 0;
    letter-spacing: 0.2px;
}

.cat-selected-name {
    color: #0f172a;
    font-size: 13.5px;
    font-weight: 600;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.cat-trigger-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-left: 8px;
}

.cat-clear-selection-btn {
    width: 20px;
    height: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #94a3b8;
    cursor: pointer;
    transition: all 0.15s ease;
    font-size: 11px;
}

.cat-clear-selection-btn:hover {
    background: #fee2e2;
    color: #ef4444;
}

.cat-trigger-arrow {
    color: #64748b;
    font-size: 12px;
    transition: transform 0.2s ease;
}

.cat-search-wrapper.is-open .cat-trigger-arrow {
    transform: rotate(180deg);
}

/* Dropdown Menu Panel */
.cat-dropdown-menu {
    position: absolute;
    top: calc(100% + 5px);
    left: 0;
    right: 0;
    z-index: 1060;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    box-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.15), 0 4px 10px -2px rgba(15, 23, 42, 0.08);
    display: none;
    overflow: hidden;
    animation: catFadeIn 0.15s ease;
}

@keyframes catFadeIn {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Search Header Box */
.cat-search-header {
    padding: 10px 12px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.cat-search-box {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.cat-search-icon {
    position: absolute;
    left: 11px;
    color: #94a3b8;
    font-size: 13px;
    pointer-events: none;
}

.cat-search-input {
    width: 100% !important;
    height: 36px !important;
    padding: 6px 32px 6px 32px !important;
    font-size: 13px !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    background: #ffffff !important;
    color: #0f172a !important;
    outline: none !important;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.04) !important;
    transition: all 0.15s ease !important;
}

.cat-search-input:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
}

.cat-search-clear {
    position: absolute;
    right: 10px;
    color: #94a3b8;
    font-size: 13px;
    cursor: pointer;
    transition: color 0.15s ease;
    padding: 2px;
}

.cat-search-clear:hover {
    color: #475569;
}

/* Meta Count / Info Bar */
.cat-search-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 12px;
    background: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
    font-size: 11.5px;
    color: #64748b;
    font-weight: 500;
}

.cat-meta-count {
    font-weight: 600;
    color: #475569;
}

.cat-meta-hint {
    color: #94a3b8;
    font-size: 11px;
}

/* Options Scrollable List */
.cat-options-list {
    max-height: 250px;
    overflow-y: auto;
    margin: 0;
    padding: 6px 0;
    list-style: none;
}

.cat-options-list::-webkit-scrollbar {
    width: 6px;
}

.cat-options-list::-webkit-scrollbar-track {
    background: #f8fafc;
}

.cat-options-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.cat-options-list::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Option Items */
.cat-option-item {
    padding: 8px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    font-size: 13px;
    color: #1e293b;
    transition: background-color 0.12s ease, color 0.12s ease;
}

.cat-option-item:hover,
.cat-option-item.is-focused {
    background: #f1f5f9;
    color: #312e81;
}

.cat-option-item.is-selected {
    background: #eff6ff;
    color: #2563eb;
    font-weight: 600;
}

.cat-option-left {
    display: flex;
    align-items: center;
    gap: 8px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex: 1;
}

.cat-option-badge {
    background: #f1f5f9;
    color: #475569;
    font-weight: 700;
    font-size: 11px;
    padding: 2px 7px;
    border-radius: 4px;
    border: 1px solid #e2e8f0;
    min-width: 32px;
    text-align: center;
    flex-shrink: 0;
}

.cat-option-item.is-selected .cat-option-badge {
    background: #dbeafe;
    color: #1e40af;
    border-color: #bfdbfe;
}

.cat-option-name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.cat-option-check {
    color: #2563eb;
    font-size: 13px;
    display: none;
    margin-left: 8px;
}

.cat-option-item.is-selected .cat-option-check {
    display: inline-block;
}

/* Default / Reset Option */
.cat-option-default {
    border-bottom: 1px dashed #e2e8f0;
    color: #64748b;
    font-style: italic;
    margin-bottom: 4px;
}

.cat-option-default:hover,
.cat-option-default.is-focused {
    background: #f8fafc;
    color: #334155;
}

/* Highlighted match */
.cat-hl {
    background: #fef08a !important;
    color: #854d0e !important;
    font-weight: 700 !important;
    padding: 0 1px !important;
    border-radius: 2px !important;
}

/* Empty State */
.cat-empty-state {
    padding: 24px 16px;
    text-align: center;
    color: #64748b;
}

.cat-empty-icon {
    font-size: 24px;
    color: #cbd5e1;
    margin-bottom: 8px;
}

.cat-empty-title {
    font-weight: 600;
    font-size: 13.5px;
    color: #334155;
    margin-bottom: 4px;
}

.cat-empty-desc {
    font-size: 12px;
    color: #94a3b8;
}

/* Modal compatibility */
#editHeroModal .modal-body,
#editHeroModal .modal-content {
    overflow: visible !important;
}
</style>

<script type="text/javascript">
    /**
     * Initializes a modern searchable category dropdown component
     * that syncs seamlessly with the native select element.
     */
    function initSearchableCategorySelect(selectSelector, targetUrlSelector) {
        var $select = $(selectSelector);
        if (!$select.length) return;

        // Prevent double initialization
        if ($select.data('cat-searchable-init')) return;
        $select.data('cat-searchable-init', true);

        // Hide original select completely
        $select.attr('style', 'display: none !important; position: absolute !important; opacity: 0 !important; width: 0 !important; height: 0 !important; pointer-events: none !important; visibility: hidden !important; border: 0 !important; padding: 0 !important; margin: 0 !important;').hide();

        // Parse options from select
        var categories = [];
        $select.find('option').each(function() {
            var val = $(this).val();
            var text = $(this).text();
            if (!val) {
                categories.push({ id: '', name: text, isDefault: true });
            } else {
                var parts = text.split(' - ');
                var name = parts.length > 1 ? parts.slice(1).join(' - ') : text;
                categories.push({ id: String(val).trim(), name: name.trim(), isDefault: false });
            }
        });

        var wrapperId = 'searchable_' + $select.attr('id');
        var $wrapper = $(
            '<div class="cat-search-wrapper" id="' + wrapperId + '">' +
                '<div class="cat-search-trigger" tabindex="0" role="button" aria-haspopup="listbox" aria-expanded="false">' +
                    '<div class="cat-trigger-content">' +
                        '<span class="cat-trigger-placeholder">-- Choose Category --</span>' +
                    '</div>' +
                    '<div class="cat-trigger-actions">' +
                        '<span class="cat-clear-selection-btn" title="Clear category" style="display:none;">' +
                            '<i class="fa-solid fa-xmark"></i>' +
                        '</span>' +
                        '<i class="fa-solid fa-chevron-down cat-trigger-arrow"></i>' +
                    '</div>' +
                '</div>' +
                '<div class="cat-dropdown-menu">' +
                    '<div class="cat-search-header">' +
                        '<div class="cat-search-box">' +
                            '<i class="fa-solid fa-magnifying-glass cat-search-icon"></i>' +
                            '<input type="text" class="cat-search-input" placeholder="Search by name or category ID..." autocomplete="off" spellcheck="false">' +
                            '<span class="cat-search-clear" title="Clear search" style="display:none;"><i class="fa-solid fa-circle-xmark"></i></span>' +
                        '</div>' +
                    '</div>' +
                    '<div class="cat-search-meta">' +
                        '<span class="cat-meta-count">Showing ' + (categories.length - 1) + ' categories</span>' +
                        '<span class="cat-meta-hint"><i class="fa-solid fa-keyboard" style="font-size:10px;"></i> Search Name or ID</span>' +
                    '</div>' +
                    '<ul class="cat-options-list" role="listbox"></ul>' +
                    '<div class="cat-empty-state" style="display:none;">' +
                        '<div class="cat-empty-icon"><i class="fa-solid fa-magnifying-glass"></i></div>' +
                        '<div class="cat-empty-title">No categories found</div>' +
                        '<div class="cat-empty-desc">No category matching "<span class="cat-empty-term"></span>"</div>' +
                    '</div>' +
                '</div>' +
            '</div>'
        );

        $select.after($wrapper);

        var $trigger = $wrapper.find('.cat-search-trigger');
        var $triggerContent = $wrapper.find('.cat-trigger-content');
        var $clearSelectionBtn = $wrapper.find('.cat-clear-selection-btn');
        var $dropdownMenu = $wrapper.find('.cat-dropdown-menu');
        var $searchInput = $wrapper.find('.cat-search-input');
        var $searchClearBtn = $wrapper.find('.cat-search-clear');
        var $optionsList = $wrapper.find('.cat-options-list');
        var $metaCount = $wrapper.find('.cat-meta-count');
        var $emptyState = $wrapper.find('.cat-empty-state');
        var $emptyTerm = $wrapper.find('.cat-empty-term');

        function escapeHtml(str) {
            return (str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function highlightText(text, tokens) {
            if (!tokens || !tokens.length) return escapeHtml(text);
            var escaped = escapeHtml(text);
            for (var i = 0; i < tokens.length; i++) {
                var token = tokens[i].replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                if (token) {
                    var regex = new RegExp('(' + token + ')', 'gi');
                    escaped = escaped.replace(regex, '<mark class="cat-hl">$1</mark>');
                }
            }
            return escaped;
        }

        function renderOptions(filterTokens) {
            $optionsList.empty();
            var matchCount = 0;
            var totalCats = Math.max(0, categories.length - 1);
            var currentVal = ($select.val() || '').toString();

            categories.forEach(function(cat) {
                if (cat.isDefault) {
                    if (filterTokens && filterTokens.length) return;
                    var $li = $('<li class="cat-option-item cat-option-default" data-id="" role="option">' +
                        '<div class="cat-option-left">' +
                            '<span class="cat-option-name">' + escapeHtml(cat.name) + '</span>' +
                        '</div>' +
                    '</li>');
                    if (currentVal === '') $li.addClass('is-selected');
                    $optionsList.append($li);
                    return;
                }

                var show = true;
                if (filterTokens && filterTokens.length) {
                    var idStr = cat.id.toLowerCase();
                    var nameStr = cat.name.toLowerCase();
                    var combined = idStr + ' ' + nameStr;

                    for (var i = 0; i < filterTokens.length; i++) {
                        var tok = filterTokens[i];
                        if (combined.indexOf(tok) === -1) {
                            show = false;
                            break;
                        }
                    }
                }

                if (show) {
                    matchCount++;
                    var isSelected = (currentVal === cat.id);
                    var idDisplay = highlightText(cat.id, filterTokens);
                    var nameDisplay = highlightText(cat.name, filterTokens);

                    var $li = $(
                        '<li class="cat-option-item' + (isSelected ? ' is-selected' : '') + '" data-id="' + cat.id + '" data-name="' + escapeHtml(cat.name) + '" role="option">' +
                            '<div class="cat-option-left">' +
                                '<span class="cat-option-badge">#' + idDisplay + '</span>' +
                                '<span class="cat-option-name">' + nameDisplay + '</span>' +
                            '</div>' +
                            '<i class="fa-solid fa-check cat-option-check"></i>' +
                        '</li>'
                    );
                    $optionsList.append($li);
                }
            });

            if (filterTokens && filterTokens.length) {
                $metaCount.text('Showing ' + matchCount + ' of ' + totalCats + ' categories');
                if (matchCount === 0) {
                    $emptyTerm.text(filterTokens.join(' '));
                    $emptyState.show();
                } else {
                    $emptyState.hide();
                    $optionsList.find('.cat-option-item:first').addClass('is-focused');
                }
            } else {
                $metaCount.text('Showing ' + totalCats + ' categories');
                $emptyState.hide();
            }
        }

        function updateTriggerUI() {
            var currentVal = ($select.val() || '').toString();
            var selectedCat = null;
            for (var i = 0; i < categories.length; i++) {
                if (categories[i].id === currentVal && !categories[i].isDefault) {
                    selectedCat = categories[i];
                    break;
                }
            }

            if (selectedCat) {
                $triggerContent.html(
                    '<span class="cat-selected-badge">#' + escapeHtml(selectedCat.id) + '</span>' +
                    '<span class="cat-selected-name">' + escapeHtml(selectedCat.name) + '</span>'
                );
                $clearSelectionBtn.show();
            } else {
                $triggerContent.html('<span class="cat-trigger-placeholder">-- Choose Category --</span>');
                $clearSelectionBtn.hide();
            }
        }

        function openDropdown() {
            $('.cat-search-wrapper').not($wrapper).removeClass('is-open').find('.cat-dropdown-menu').hide();

            $wrapper.addClass('is-open');
            $trigger.attr('aria-expanded', 'true');
            $dropdownMenu.fadeIn(120);

            $searchInput.val('');
            $searchClearBtn.hide();
            renderOptions([]);

            setTimeout(function() {
                $searchInput.focus();
                var $selected = $optionsList.find('.cat-option-item.is-selected');
                if ($selected.length && $selected[0].scrollIntoView) {
                    $selected[0].scrollIntoView({ block: 'nearest' });
                }
            }, 50);
        }

        function closeDropdown() {
            $wrapper.removeClass('is-open');
            $trigger.attr('aria-expanded', 'false');
            $dropdownMenu.fadeOut(100);
        }

        function selectCategory(id, name) {
            $select.val(id).trigger('change');
            if (targetUrlSelector && id) {
                $(targetUrlSelector).val('category/' + id);
            }
            updateTriggerUI();
            closeDropdown();
            $trigger.focus();
        }

        // Initial setup
        updateTriggerUI();

        // Trigger Click
        $trigger.on('click', function(e) {
            if ($(e.target).closest('.cat-clear-selection-btn').length) return;
            if ($wrapper.hasClass('is-open')) {
                closeDropdown();
            } else {
                openDropdown();
            }
        });

        // Clear Selection Button
        $clearSelectionBtn.on('click', function(e) {
            e.stopPropagation();
            $select.val('').trigger('change');
            if (targetUrlSelector && $(targetUrlSelector).val().indexOf('category/') === 0) {
                $(targetUrlSelector).val('');
            }
            updateTriggerUI();
            closeDropdown();
        });

        // Trigger Keyboard
        $trigger.on('keydown', function(e) {
            if (e.which === 13 || e.which === 32) { // Enter or Space
                e.preventDefault();
                if (!$wrapper.hasClass('is-open')) openDropdown();
            } else if (e.which === 27) { // Escape
                closeDropdown();
            }
        });

        // Search Input Filter
        $searchInput.on('input', function() {
            var raw = $(this).val() || '';
            if (raw.length > 0) {
                $searchClearBtn.show();
            } else {
                $searchClearBtn.hide();
            }

            var clean = raw.trim().toLowerCase().replace(/^category\//, '').replace(/^#/, '');
            var tokens = clean.split(/\s+/).filter(function(t) { return t.length > 0; });
            renderOptions(tokens);
        });

        // Clear Search Button
        $searchClearBtn.on('click', function() {
            $searchInput.val('').trigger('input').focus();
        });

        // Option Click
        $optionsList.on('click', '.cat-option-item', function() {
            var id = $(this).attr('data-id') || '';
            var name = $(this).attr('data-name') || '';
            selectCategory(id, name);
        });

        // Search Input Keyboard Navigation
        $searchInput.on('keydown', function(e) {
            var $visible = $optionsList.find('.cat-option-item:visible');
            if (!$visible.length) {
                if (e.which === 27) { closeDropdown(); $trigger.focus(); }
                return;
            }

            var $curr = $visible.filter('.is-focused');
            var idx = $visible.index($curr);

            if (e.which === 40) { // Arrow Down
                e.preventDefault();
                if (idx === -1 || idx === $visible.length - 1) {
                    idx = 0;
                } else {
                    idx++;
                }
                $visible.removeClass('is-focused');
                var $next = $visible.eq(idx).addClass('is-focused');
                if ($next.length && $next[0].scrollIntoView) $next[0].scrollIntoView({ block: 'nearest' });
            } else if (e.which === 38) { // Arrow Up
                e.preventDefault();
                if (idx <= 0) {
                    idx = $visible.length - 1;
                } else {
                    idx--;
                }
                $visible.removeClass('is-focused');
                var $prev = $visible.eq(idx).addClass('is-focused');
                if ($prev.length && $prev[0].scrollIntoView) $prev[0].scrollIntoView({ block: 'nearest' });
            } else if (e.which === 13) { // Enter
                e.preventDefault();
                if ($curr.length) {
                    $curr.trigger('click');
                } else if ($visible.length) {
                    $visible.first().trigger('click');
                }
            } else if (e.which === 27) { // Escape
                e.preventDefault();
                closeDropdown();
                $trigger.focus();
            }
        });

        // Synchronize with external changes to original select
        $select.on('change', function() {
            updateTriggerUI();
        });

        // Click outside closes dropdown
        $(document).on('click.catSearchOutside_' + wrapperId, function(e) {
            if (!$(e.target).closest($wrapper).length) {
                closeDropdown();
            }
        });
    }

    $(document).ready(function() {
        // Initialize searchable dropdowns for both Add and Edit
        initSearchableCategorySelect('#cat_select_add', '#action_url_add');
        initSearchableCategorySelect('#cat_select_edit', '#edit_action_url');
    });

    function editHero(data) {
        $('#edit_hero_id').val(data.id);
        $('#edit_sequence_number').val(data.sequence_number);
        $('#edit_action_url').val(data.action_url);
        $('#edit_hero_preview').attr('src', '<?php echo base_url(); ?>' + data.banner_image);
        
        // Try to pre-select category if action_url is like "category/53"
        if(data.action_url && data.action_url.indexOf('category/') === 0) {
            var catId = data.action_url.replace('category/', '').trim();
            $('#cat_select_edit').val(catId).trigger('change');
        } else {
            $('#cat_select_edit').val('').trigger('change');
        }

        $('#editHeroModal').modal('show');
    }

    $( ".row_position" ).sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $('.row_position>tr').each(function(index) {
                $(this).find('.seq_num').text(index);
                selectedData.push($(this).attr("id"));
            });
            updateOrder(selectedData);
        }
    });
    function updateOrder(data) {
        $.ajax({           
            url:'<?php echo base_url() ?>Site/hero_sorting',
            type:'post',
            data:{position:data},
            success:function(res){
                console.log('Order updated');
            }
        })
    }
</script>
