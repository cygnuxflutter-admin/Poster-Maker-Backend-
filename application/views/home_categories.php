<?php
if (!function_exists('hc_render_form_fields')) {
    function hc_render_form_fields($prefix, $categories, $bg_categories, $action_types, $default_sort = 1) {
        $is_edit = ($prefix === 'edit');
        ?>
        <div class="hc-live-preview-box" id="<?php echo $prefix; ?>_live_preview_wrap">
            <div class="hc-preview-label">Live Tile Preview</div>
            <div class="hc-tile-preview" id="<?php echo $prefix; ?>_tile_preview" style="background-color: #FFFBEB; border: 1px solid #FDE68A;">
                <div class="hc-tile-icon-wrap">
                    <img id="<?php echo $prefix; ?>_icon_preview_img" src="" alt="" style="display: none;">
                    <span id="<?php echo $prefix; ?>_icon_placeholder" class="hc-tile-fallback">F</span>
                </div>
                <div class="hc-tile-title" id="<?php echo $prefix; ?>_title_preview">Category Name</div>
            </div>
        </div>

        <div class="row-form">
            <div class="col-md-12"><strong>Display Name <span class="text-danger">*</span>:</strong></div>
            <div class="col-md-12">
                <input type="text" name="name" id="<?php echo $prefix; ?>_name" class="form-control hc-name-input" placeholder="e.g. Festival, Wedding, Sale" maxlength="100" required>
            </div>
        </div>

        <div class="row-form">
            <div class="col-md-12"><strong>Icon Image (Transparent PNG/WebP, 128&times;128px):</strong></div>
            <div class="col-md-12">
                <input type="file" name="icon_file" id="<?php echo $prefix; ?>_icon_file" class="hc-icon-file-input" accept="image/png,image/webp,image/jpeg,image/gif,image/svg+xml">
                <small class="text-muted" style="display:block; margin-top:4px;">
                    <?php echo $is_edit ? 'Leave blank to keep current icon.' : 'Saved under /upload/home_categories/'; ?>
                </small>
            </div>
        </div>

        <div class="row-form">
            <div class="col-md-6" style="padding-left:0; padding-right:6px;">
                <strong>Tile BG Color:</strong>
                <div class="hc-color-group">
                    <input type="color" id="<?php echo $prefix; ?>_bg_color_picker" class="hc-color-picker" value="#FFFBEB">
                    <input type="text" name="bg_color" id="<?php echo $prefix; ?>_bg_color" class="form-control hc-hex-input" value="#FFFBEB" placeholder="#FFFBEB" maxlength="20">
                </div>
            </div>
            <div class="col-md-6" style="padding-left:6px; padding-right:0;">
                <strong>Border Color (1dp):</strong>
                <div class="hc-color-group">
                    <input type="color" id="<?php echo $prefix; ?>_border_color_picker" class="hc-color-picker" value="#FDE68A">
                    <input type="text" name="border_color" id="<?php echo $prefix; ?>_border_color" class="form-control hc-hex-input" value="#FDE68A" placeholder="#FDE68A" maxlength="20">
                </div>
            </div>
        </div>

        <div class="row-form">
            <div class="col-md-12"><strong>Action Type <span class="text-danger">*</span>:</strong></div>
            <div class="col-md-12">
                <select name="action_type" id="<?php echo $prefix; ?>_action_type" class="form-control hc-action-type-select" required>
                    <?php foreach ($action_types as $key => $meta) { ?>
                        <option value="<?php echo html_escape($key); ?>"><?php echo html_escape($meta['label']); ?> (<?php echo html_escape($key); ?>)</option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <!-- Conditional Target: Template Category -->
        <div class="row-form hc-cond-section" id="<?php echo $prefix; ?>_cond_template_category">
            <div class="col-md-12"><strong>Target Template Category:</strong></div>
            <div class="col-md-12">
                <select id="<?php echo $prefix; ?>_tpl_cat_select" class="form-control hc-tpl-cat-select">
                    <option value="">-- Choose Template Category --</option>
                    <?php if (!empty($categories)) { foreach ($categories as $cat) { ?>
                        <option value="<?php echo html_escape($cat['cat_id']); ?>" data-name="<?php echo html_escape($cat['cat_name']); ?>">
                            #<?php echo html_escape($cat['cat_id']); ?> - <?php echo html_escape($cat['cat_name']); ?>
                        </option>
                    <?php } } ?>
                </select>
            </div>
        </div>

        <!-- Conditional Target: Background Category -->
        <div class="row-form hc-cond-section hc-hidden" id="<?php echo $prefix; ?>_cond_background_category">
            <div class="col-md-12"><strong>Target Background Category:</strong></div>
            <div class="col-md-12">
                <select id="<?php echo $prefix; ?>_bg_cat_select" class="form-control hc-bg-cat-select">
                    <option value="">-- Choose Background Category --</option>
                    <?php if (!empty($bg_categories)) { foreach ($bg_categories as $bg_cat) { ?>
                        <option value="<?php echo html_escape($bg_cat['bg_cat_id']); ?>" data-name="<?php echo html_escape($bg_cat['name']); ?>">
                            #<?php echo html_escape($bg_cat['bg_cat_id']); ?> - <?php echo html_escape($bg_cat['name']); ?>
                        </option>
                    <?php } } ?>
                </select>
            </div>
        </div>

        <!-- Auto-filled cat_id & cat_name (editable fallback) -->
        <div class="row-form hc-cond-section" id="<?php echo $prefix; ?>_cond_cat_fields">
            <div class="col-md-4" style="padding-left:0; padding-right:6px;">
                <strong>Target Cat ID:</strong>
                <input type="text" name="cat_id" id="<?php echo $prefix; ?>_cat_id" class="form-control" placeholder="e.g. 55" maxlength="50">
            </div>
            <div class="col-md-8" style="padding-left:6px; padding-right:0;">
                <strong>Target Cat Name (Fallback):</strong>
                <input type="text" name="cat_name" id="<?php echo $prefix; ?>_cat_name" class="form-control" placeholder="Auto-filled from dropdown" maxlength="100">
            </div>
        </div>

        <!-- Conditional Target: External URL -->
        <div class="row-form hc-cond-section hc-hidden" id="<?php echo $prefix; ?>_cond_url">
            <div class="col-md-12"><strong>Action URL:</strong></div>
            <div class="col-md-12">
                <input type="text" name="action_url" id="<?php echo $prefix; ?>_action_url" class="form-control" placeholder="https://example.com or category/53" maxlength="500">
            </div>
        </div>

        <!-- Conditional Info: Screen-only actions -->
        <div class="row-form hc-cond-section hc-hidden" id="<?php echo $prefix; ?>_cond_screen_info">
            <div class="col-md-12">
                <div class="hc-info-note" id="<?php echo $prefix; ?>_screen_info_text">
                    Opens the selected in-app screen directly. No category ID or URL required.
                </div>
            </div>
        </div>

        <div class="row-form">
            <div class="col-md-6" style="padding-left:0; padding-right:6px;">
                <strong>Sort Order:</strong>
                <input type="number" name="sort_order" id="<?php echo $prefix; ?>_sort_order" value="<?php echo (int)$default_sort; ?>" class="form-control" required>
            </div>
            <div class="col-md-6" style="padding-left:6px; padding-right:0;">
                <strong>Status:</strong>
                <div class="hc-status-control">
                    <input type="hidden" name="is_active" id="<?php echo $prefix; ?>_is_active" value="1">
                    <button type="button" class="hc-switch is-on" id="<?php echo $prefix; ?>_status_btn" role="switch" aria-checked="true">
                        <span class="hc-switch-knob"></span>
                    </button>
                    <span class="hc-status-label" id="<?php echo $prefix; ?>_status_label">Active</span>
                </div>
            </div>
        </div>
        <?php
    }
}
?>

<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <i class="fa-solid fa-table-cells-large"></i>
            </div>
            <h1>Home Categories <small><span class="cgreen"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Browse by Category</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li class="active">Home Categories</li>
        </ul>
        <div class="clear"></div>
    </div>

    <?php if ($this->session->flashdata('hc_success')) { ?>
        <div class="alert alert-success" style="border-radius: 10px; margin-bottom: 16px;">
            <i class="fa-solid fa-circle-check"></i> <?php echo html_escape($this->session->flashdata('hc_success')); ?>
        </div>
    <?php } ?>
    <?php if ($this->session->flashdata('hc_error')) { ?>
        <div class="alert alert-danger" style="border-radius: 10px; margin-bottom: 16px;">
            <i class="fa-solid fa-triangle-exclamation"></i> <?php echo html_escape($this->session->flashdata('hc_error')); ?>
        </div>
    <?php } ?>

    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <ul class="nav nav-tabs" style="margin-bottom: 0;">
                <li class="<?php echo (isset($app_id) && (int)$app_id === 2) ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('site/home_categories?app_id=2'); ?>">App 2 (Home Grid)</a>
                </li>
                <li class="<?php echo (isset($app_id) && (int)$app_id === 1) ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('site/home_categories?app_id=1'); ?>">App 1 (Default)</a>
                </li>
            </ul>
            <?php if (empty($data)) { ?>
                <a href="<?php echo base_url('site/seed_home_categories?app_id=' . (int)$app_id); ?>" class="btn btn-default" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Seed Default 8 Categories
                </a>
            <?php } ?>
        </div>
    </div>

    <div class="row">
        <!-- Create Form -->
        <div class="col-md-4">
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-plus-circle"></i> Add Home Category</h2>
                </div>
                <div class="data-fluid">
                    <form action="<?php echo base_url('site/insert_home_category'); ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="app_id" value="<?php echo isset($app_id) ? (int)$app_id : 2; ?>">
                        <?php hc_render_form_fields('add', $categories, $bg_categories, $action_types, !empty($data) ? (count($data) + 1) : 1); ?>
                        <div class="row-form" style="padding-top: 16px;">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width: 100%;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> Save Home Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div class="col-md-8">
            <div class="block">
                <div class="head" style="display:flex; align-items:center; justify-content:space-between;">
                    <h2><i class="fa-solid fa-grip-vertical"></i> Home Categories Order &amp; Tiles</h2>
                    <span class="text-muted" style="font-size: 12px; padding-right: 12px;">
                        <i class="fa-solid fa-arrows-up-down"></i> Drag rows to reorder
                    </span>
                </div>
                <div class="data-fluid">
                    <table class="table lcnp" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="10%" style="text-align: center;">Order</th>
                                <th width="22%">Tile &amp; Icon</th>
                                <th width="18%">Colors</th>
                                <th width="24%">Action &amp; Target</th>
                                <th width="12%" style="text-align: center;">Active</th>
                                <th width="14%" class="TAC">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="row_position">
                            <?php if (!empty($data)) { foreach ($data as $value) {
                                $bg = !empty($value['bg_color']) ? $value['bg_color'] : '#F8FAFC';
                                $border = !empty($value['border_color']) ? $value['border_color'] : '#E2E8F0';
                                $icon_abs = Home_category_model::resolve_icon_url($value['icon_url']);
                                $act_key = $value['action_type'];
                                $act_meta = isset($action_types[$act_key]) ? $action_types[$act_key] : array(
                                    'label' => $act_key,
                                    'badge_bg' => '#f1f5f9',
                                    'badge_color' => '#334155',
                                    'badge_border' => '#cbd5e1',
                                    'icon' => 'fa-bolt'
                                );
                                $is_on = ((int)$value['is_active'] === 1);
                            ?>
                            <tr id="<?php echo (int)$value['id']; ?>" class="hc-sortable-row">
                                <td class="seq_num" style="text-align: center; cursor: grab;">
                                    <span class="hc-order-pill">
                                        <i class="fa-solid fa-grip-vertical" style="color:#94a3b8; margin-right:4px;"></i>
                                        <span class="seq_val"><?php echo (int)$value['sort_order']; ?></span>
                                    </span>
                                </td>
                                <td>
                                    <div class="hc-row-tile-cell">
                                        <div class="hc-mini-tile" style="background-color: <?php echo html_escape($bg); ?>; border: 1px solid <?php echo html_escape($border); ?>;">
                                            <?php if ($icon_abs !== '') { ?>
                                                <img src="<?php echo html_escape($icon_abs); ?>" alt="<?php echo html_escape($value['name']); ?>">
                                            <?php } else { ?>
                                                <span><?php echo html_escape(strtoupper(substr($value['name'], 0, 1))); ?></span>
                                            <?php } ?>
                                        </div>
                                        <div>
                                            <strong style="color: #0f172a; font-size: 13.5px; display:block;"><?php echo html_escape($value['name']); ?></strong>
                                            <small style="color: #94a3b8;">ID #<?php echo (int)$value['id']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="hc-swatch-list">
                                        <span class="hc-swatch-item" title="Background: <?php echo html_escape($bg); ?>">
                                            <i class="hc-swatch-dot" style="background: <?php echo html_escape($bg); ?>; border-color: <?php echo html_escape($border); ?>;"></i>
                                            <code><?php echo html_escape(!empty($value['bg_color']) ? $value['bg_color'] : '—'); ?></code>
                                        </span>
                                        <span class="hc-swatch-item" title="Border: <?php echo html_escape($border); ?>">
                                            <i class="hc-swatch-dot" style="background: <?php echo html_escape($border); ?>;"></i>
                                            <code><?php echo html_escape(!empty($value['border_color']) ? $value['border_color'] : '—'); ?></code>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="hc-action-badge" style="background: <?php echo $act_meta['badge_bg']; ?>; color: <?php echo $act_meta['badge_color']; ?>; border: 1px solid <?php echo $act_meta['badge_border']; ?>;">
                                        <i class="fa-solid <?php echo $act_meta['icon']; ?>"></i>
                                        <?php echo html_escape($act_meta['label']); ?>
                                    </span>
                                    <div class="hc-target-sub">
                                        <?php if ($act_key === 'template_category' || $act_key === 'background_category') { ?>
                                            <?php if (!empty($value['cat_id']) || !empty($value['cat_name'])) { ?>
                                                <span class="hc-target-cat">#<?php echo html_escape($value['cat_id']); ?> <?php echo html_escape($value['cat_name']); ?></span>
                                            <?php } else { ?>
                                                <span class="text-muted">No target selected</span>
                                            <?php } ?>
                                        <?php } elseif ($act_key === 'url') { ?>
                                            <code style="font-size:11px; word-break:break-all;"><?php echo html_escape($value['action_url']); ?></code>
                                        <?php } else { ?>
                                            <span class="text-muted">In-app screen</span>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <button type="button"
                                        class="hc-switch hc-row-toggle <?php echo $is_on ? 'is-on' : ''; ?>"
                                        data-id="<?php echo (int)$value['id']; ?>"
                                        role="switch"
                                        aria-checked="<?php echo $is_on ? 'true' : 'false'; ?>"
                                        title="Toggle Active / Hidden">
                                        <span class="hc-switch-knob"></span>
                                    </button>
                                </td>
                                <td class="TAC">
                                    <?php
                                        $edit_payload = $value;
                                        $edit_payload['resolved_icon_url'] = $icon_abs;
                                    ?>
                                    <a href="javascript:void(0);" onclick='editHomeCategory(<?php echo htmlspecialchars(json_encode($edit_payload), ENT_QUOTES, "UTF-8"); ?>)' class="button green" title="Edit Home Category">
                                        <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                    </a>
                                    <a href="<?php echo base_url('site/delete_home_category/' . (int)$value['id'] . '?app_id=' . (int)$app_id); ?>" class="button red" onclick="return confirm('Delete this Home Category?');" title="Delete">
                                        <div class="icon"><i class="fa-solid fa-trash"></i></div>
                                    </a>
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 32px; color: #64748b;">
                                    No Home Categories configured for App <?php echo (int)$app_id; ?> yet. Use the form on the left or click "Seed Default 8 Categories".
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

<!-- Edit Home Category Modal -->
<div class="modal fade" id="editHomeCatModal" role="dialog" aria-labelledby="editHomeCatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo base_url('site/update_home_category'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="editHomeCatModalLabel">
                        <i class="fa-solid fa-pen-to-square" style="color: #4f46e5; margin-right: 8px;"></i> Edit Home Category
                    </h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="app_id" value="<?php echo isset($app_id) ? (int)$app_id : 2; ?>">
                    <input type="hidden" name="id" id="edit_hc_id">
                    <?php hc_render_form_fields('edit', $categories, $bg_categories, $action_types, 1); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Update Home Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type='text/javascript' src='<?php echo base_url(); ?>assets/js/jquery.sortable.js'></script>

<style>
.row-form.hc-hidden,
.hc-cond-section.hc-hidden {
    display: none !important;
}
.hc-live-preview-box {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.hc-preview-label {
    font-size: 11.5px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.hc-tile-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 96px;
    padding: 10px 12px;
    border-radius: 12px;
    transition: all 0.15s ease;
}
.hc-tile-icon-wrap {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 6px;
}
.hc-tile-icon-wrap img {
    max-width: 40px;
    max-height: 40px;
    object-fit: contain;
}
.hc-tile-fallback {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(15, 23, 42, 0.08);
    color: #0f172a;
    font-weight: 700;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.hc-tile-title {
    font-size: 12px;
    font-weight: 600;
    color: #0f172a;
    text-align: center;
}
.hc-color-group {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 4px;
}
.hc-color-picker {
    width: 38px !important;
    height: 36px !important;
    padding: 2px !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    background: #fff !important;
    cursor: pointer;
    flex-shrink: 0;
}
.hc-hex-input {
    font-family: monospace;
    font-size: 12.5px !important;
    text-transform: uppercase;
}
.hc-status-control {
    display: flex;
    align-items: center;
    gap: 10px;
    height: 38px;
}
.hc-status-label {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
}
.hc-switch {
    position: relative;
    width: 42px;
    height: 24px;
    border-radius: 999px;
    border: none;
    background: #cbd5e1;
    padding: 0;
    cursor: pointer;
    transition: background-color 0.2s ease;
    flex-shrink: 0;
    outline: none;
}
.hc-switch.is-on {
    background: #10b981;
}
.hc-switch-knob {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    transition: transform 0.2s ease;
}
.hc-switch.is-on .hc-switch-knob {
    transform: translateX(18px);
}
.hc-info-note {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 12.5px;
}
.hc-row-tile-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}
.hc-mini-tile {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
}
.hc-mini-tile img {
    max-width: 32px;
    max-height: 32px;
    object-fit: contain;
}
.hc-mini-tile span {
    font-weight: 700;
    font-size: 16px;
    color: #0f172a;
}
.hc-swatch-list {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.hc-swatch-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
}
.hc-swatch-dot {
    width: 14px;
    height: 14px;
    border-radius: 4px;
    border: 1px solid #cbd5e1;
    display: inline-block;
    flex-shrink: 0;
}
.hc-action-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 600;
}
.hc-target-sub {
    margin-top: 4px;
    font-size: 12px;
    color: #475569;
}
.hc-order-pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 8px;
    background: #f1f5f9;
    border-radius: 6px;
    font-weight: 700;
    color: #334155;
    font-size: 12.5px;
}
</style>

<script type="text/javascript">
(function($) {
    var screenHints = {
        all_templates: 'Opens the All Template Categories screen in the app.',
        all_backgrounds: 'Opens the All Background Categories screen in the app.',
        create_custom: 'Opens the Create Custom Poster screen in the app.',
        my_creations: 'Opens the user\'s My Creations screen in the app.'
    };

    function isValidHex(val) {
        return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test((val || '').trim());
    }

    function normalizeForPicker(val, fallback) {
        val = (val || '').trim();
        if (/^#[A-Fa-f0-9]{6}$/.test(val)) return val;
        if (/^#[A-Fa-f0-9]{3}$/.test(val)) {
            return '#' + val[1] + val[1] + val[2] + val[2] + val[3] + val[3];
        }
        return fallback;
    }

    function setFormSwitchState(prefix, active) {
        var isOn = parseInt(active, 10) === 1;
        $('#' + prefix + '_is_active').val(isOn ? 1 : 0);
        $('#' + prefix + '_status_btn')
            .toggleClass('is-on', isOn)
            .attr('aria-checked', isOn ? 'true' : 'false');
        $('#' + prefix + '_status_label').text(isOn ? 'Active' : 'Inactive');
    }

    function updatePreview(prefix) {
        var name = $('#' + prefix + '_name').val().trim() || 'Category Name';
        var bg = $('#' + prefix + '_bg_color').val().trim();
        var border = $('#' + prefix + '_border_color').val().trim();

        $('#' + prefix + '_title_preview').text(name);
        $('#' + prefix + '_icon_placeholder').text(name.charAt(0).toUpperCase() || 'C');
        $('#' + prefix + '_tile_preview').css({
            'background-color': isValidHex(bg) ? bg : '#F8FAFC',
            'border': '1px solid ' + (isValidHex(border) ? border : '#E2E8F0')
        });
    }

    function updateConditionalFields(prefix) {
        var action = $('#' + prefix + '_action_type').val();
        $('#' + prefix + '_cond_template_category').toggleClass('hc-hidden', action !== 'template_category');
        $('#' + prefix + '_cond_background_category').toggleClass('hc-hidden', action !== 'background_category');
        $('#' + prefix + '_cond_cat_fields').toggleClass('hc-hidden', !(action === 'template_category' || action === 'background_category'));
        $('#' + prefix + '_cond_url').toggleClass('hc-hidden', action !== 'url');

        var hasScreenHint = !!screenHints[action];
        $('#' + prefix + '_cond_screen_info').toggleClass('hc-hidden', !hasScreenHint);
        if (hasScreenHint) {
            $('#' + prefix + '_screen_info_text').text(screenHints[action]);
        }
    }

    function initHomeCategoryForm(prefix) {
        // Color picker <-> Hex text bidirectional sync
        $('#' + prefix + '_bg_color_picker').on('input change', function() {
            $('#' + prefix + '_bg_color').val($(this).val().toUpperCase());
            updatePreview(prefix);
        });
        $('#' + prefix + '_bg_color').on('input change', function() {
            var v = $(this).val().trim();
            if (isValidHex(v)) {
                $('#' + prefix + '_bg_color_picker').val(normalizeForPicker(v, '#FFFBEB'));
            }
            updatePreview(prefix);
        });

        $('#' + prefix + '_border_color_picker').on('input change', function() {
            $('#' + prefix + '_border_color').val($(this).val().toUpperCase());
            updatePreview(prefix);
        });
        $('#' + prefix + '_border_color').on('input change', function() {
            var v = $(this).val().trim();
            if (isValidHex(v)) {
                $('#' + prefix + '_border_color_picker').val(normalizeForPicker(v, '#FDE68A'));
            }
            updatePreview(prefix);
        });

        $('#' + prefix + '_name').on('input', function() {
            updatePreview(prefix);
        });

        // Icon file preview
        $('#' + prefix + '_icon_file').on('change', function() {
            var file = this.files && this.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + prefix + '_icon_preview_img').attr('src', e.target.result).show();
                $('#' + prefix + '_icon_placeholder').hide();
            };
            reader.readAsDataURL(file);
        });

        // Action type toggle
        $('#' + prefix + '_action_type').on('change', function() {
            updateConditionalFields(prefix);
        });

        // Template category dropdown auto-fill
        $('#' + prefix + '_tpl_cat_select').on('change', function() {
            var $opt = $(this).find('option:selected');
            var id = $(this).val();
            if (id) {
                $('#' + prefix + '_cat_id').val(id);
                $('#' + prefix + '_cat_name').val($opt.data('name') || '');
            }
        });

        // Background category dropdown auto-fill
        $('#' + prefix + '_bg_cat_select').on('change', function() {
            var $opt = $(this).find('option:selected');
            var id = $(this).val();
            if (id) {
                $('#' + prefix + '_cat_id').val(id);
                $('#' + prefix + '_cat_name').val($opt.data('name') || '');
            }
        });

        // Status switch
        $('#' + prefix + '_status_btn').on('click', function() {
            var current = parseInt($('#' + prefix + '_is_active').val(), 10);
            setFormSwitchState(prefix, current === 1 ? 0 : 1);
        });

        updateConditionalFields(prefix);
        updatePreview(prefix);
    }

    window.editHomeCategory = function(data) {
        $('#edit_hc_id').val(data.id);
        $('#edit_name').val(data.name || '');
        $('#edit_bg_color').val(data.bg_color || '');
        $('#edit_bg_color_picker').val(normalizeForPicker(data.bg_color, '#FFFBEB'));
        $('#edit_border_color').val(data.border_color || '');
        $('#edit_border_color_picker').val(normalizeForPicker(data.border_color, '#FDE68A'));
        $('#edit_action_type').val(data.action_type || 'template_category');
        $('#edit_cat_id').val(data.cat_id || '');
        $('#edit_cat_name').val(data.cat_name || '');
        $('#edit_action_url').val(data.action_url || '');
        $('#edit_sort_order').val(data.sort_order || 0);
        $('#edit_icon_file').val('');

        if (data.action_type === 'template_category') {
            $('#edit_tpl_cat_select').val(data.cat_id || '');
            $('#edit_bg_cat_select').val('');
        } else if (data.action_type === 'background_category') {
            $('#edit_bg_cat_select').val(data.cat_id || '');
            $('#edit_tpl_cat_select').val('');
        } else {
            $('#edit_tpl_cat_select').val('');
            $('#edit_bg_cat_select').val('');
        }

        if (data.resolved_icon_url) {
            $('#edit_icon_preview_img').attr('src', data.resolved_icon_url).show();
            $('#edit_icon_placeholder').hide();
        } else {
            $('#edit_icon_preview_img').attr('src', '').hide();
            $('#edit_icon_placeholder').show();
        }

        setFormSwitchState('edit', data.is_active);
        updateConditionalFields('edit');
        updatePreview('edit');
        $('#editHomeCatModal').modal('show');
    };

    $(document).ready(function() {
        initHomeCategoryForm('add');
        initHomeCategoryForm('edit');

        // Drag-and-drop sorting
        $('.row_position').sortable({
            delay: 150,
            stop: function() {
                var selectedData = [];
                $('.row_position > tr').each(function(index) {
                    $(this).find('.seq_val').text(index + 1);
                    selectedData.push($(this).attr('id'));
                });
                $.ajax({
                    url: '<?php echo base_url("site/home_category_sorting"); ?>',
                    type: 'POST',
                    data: { position: selectedData }
                });
            }
        });

        // AJAX Active/Inactive toggle
        $(document).on('click', '.hc-row-toggle', function() {
            var $btn = $(this);
            var id = $btn.data('id');
            $.ajax({
                url: '<?php echo base_url("site/toggle_home_category"); ?>/' + id,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    if (res && res.status === 'success') {
                        var isOn = parseInt(res.is_active, 10) === 1;
                        $btn.toggleClass('is-on', isOn).attr('aria-checked', isOn ? 'true' : 'false');
                    }
                }
            });
        });
    });
})(jQuery);
</script>
