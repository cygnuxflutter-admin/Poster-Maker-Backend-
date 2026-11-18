<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <i class="fa-solid fa-folder-plus"></i>
            </div>
            <h1><?php echo isset($edit_cat) ? 'Edit' : 'Add'; ?> MIY Category <small><span class="cgreen"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Customization Pack</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>MakeItYours/categories">MIY Categories</a></li>
            <li class="active"><?php echo isset($edit_cat) ? 'Edit' : 'Add'; ?> Category</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-palette"></i> Category Details</h2>
                </div>
                <div class="data-fluid">
                    <form action="<?php echo base_url(); ?>MakeItYours/save_category" method="POST" enctype="multipart/form-data">
                        
                        <?php if(isset($edit_cat)): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_cat['id']; ?>">
                        <?php endif; ?>

                        <div class="row-form">
                            <div class="col-md-3"><strong>Category Name:</strong></div>
                            <div class="col-md-9">
                                <input type="text" name="name" required class="form-control" value="<?php echo isset($edit_cat) ? $edit_cat['name'] : ''; ?>" placeholder="e.g. Diwali, Navratri, Business"/>
                                <?php if(!isset($edit_cat)): ?>
                                    <small class="text-muted" style="margin-top: 4px; display: block;">You can add multiple categories at once by separating them with a comma (,)</small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-3"><strong>Sort Order:</strong></div>
                            <div class="col-md-9">
                                <input type="number" name="sort_order" class="form-control" value="<?php echo isset($edit_cat) ? $edit_cat['sort_order'] : '0'; ?>"/>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-3"><strong>Thumbnail Image:</strong></div>
                            <div class="col-md-9">
                                <input type="file" name="image" class="form-control" accept="image/*"/>
                                <?php if(isset($edit_cat) && !empty($edit_cat['image'])): ?>
                                    <div style="margin-top: 8px;">
                                        <img src="<?php echo base_url($edit_cat['image']); ?>" width="80" style="border-radius: 8px; border: 1px solid #e2e8f0;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-3"><strong>Status:</strong></div>
                            <div class="col-md-9">
                                <label style="display: flex; align-items: center; gap: 8px; font-weight: 500; cursor: pointer;">
                                    <input type="checkbox" name="is_active" value="1" <?php echo (!isset($edit_cat) || $edit_cat['is_active'] == 1) ? 'checked' : ''; ?>/>
                                    <span>Active (Visible in App)</span>
                                </label>
                            </div>
                        </div>

                        <div class="row-form" style="padding-top: 18px; border-bottom: none;">
                            <div class="col-md-9 col-md-offset-3" style="display: flex; gap: 10px;">
                                <a href="<?php echo base_url(); ?>MakeItYours/categories" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-check"></i> Save Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
