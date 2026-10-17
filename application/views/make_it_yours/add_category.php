<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <span class="ico-layout-7"></span>
            </div>
            <h1><?php echo isset($edit_cat) ? 'Edit' : 'Add'; ?> MIY Category</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="<?php echo base_url(); ?>MakeItYours/categories">MIY Categories</a></li>
            <li class="active"><?php echo isset($edit_cat) ? 'Edit' : 'Add'; ?> Category</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="head">
                    <h2>Category Details</h2>
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
                                    <small>You can add multiple categories at once by separating them with a comma (,)</small>
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
                                <input type="file" name="image" class="form-control"/>
                                <?php if(isset($edit_cat) && !empty($edit_cat['image'])): ?>
                                    <br>
                                    <img src="<?php echo base_url($edit_cat['image']); ?>" width="100">
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-3"><strong>Is Active:</strong></div>
                            <div class="col-md-9">
                                <input type="checkbox" name="is_active" value="1" <?php echo (!isset($edit_cat) || $edit_cat['is_active'] == 1) ? 'checked' : ''; ?>/>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">Save Category</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
