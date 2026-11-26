<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <h1>Edit Background Category <small>Update category information</small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>site/bgCategory">Background Categories</a></li>
            <li class="active">Edit Category</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">                
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-image"></i> Edit Background Category Details</h2>
                </div>
                <form id="validate" method="POST" action="<?php echo base_url();?>site/updatebgcat">
                    <div class="data-fluid">
                        <div class="row-form">
                            <div class="col-md-3"><strong>Category Name:</strong></div>
                            <div class="col-md-9">
                                <input type="text" class="form-control validate[required]" name='cat_name' value="<?php echo isset($data[0]['name']) ? $data[0]['name'] : ''; ?>" />
                                <span class="bottom" style="color: #64748b;">Required</span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-3"><strong>Sequence Number:</strong></div>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name='sequence_number' value="<?php echo isset($data[0]['sequence_number']) ? $data[0]['sequence_number'] : 0; ?>" />
                                <span class="bottom" style="color: #64748b;">Lower numbers appear first in the mobile app</span>
                            </div>
                        </div>

                        <input type="hidden" name="cat_id" value="<?php echo isset($data[0]['bg_cat_id']) ? $data[0]['bg_cat_id'] : ''; ?>">
                        <div class="toolbar bottom tar" style="padding: 16px 0 0; display: flex; justify-content: flex-end; gap: 10px;">
                            <button class="btn btn-secondary" type="button" onClick="$('#validate').validationEngine('hide');">
                                Hide Prompts
                            </button>
                            <button class="btn btn-primary" type="submit">
                                <i class="fa-solid fa-check"></i> Update Category
                            </button>
                        </div>
                    </div>                
                </form>
            </div>
        </div>
    </div>                 
</div>
