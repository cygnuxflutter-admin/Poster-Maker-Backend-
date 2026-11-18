<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <h1>Edit Category <small>Update category information</small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>site/category">Categories</a></li>
            <li class="active">Edit Category</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">                
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-layer-group"></i> Edit Category Details</h2>
                </div>
                <form id="validate" method="POST" action="<?php echo base_url();?>site/updatecat" enctype="multipart/form-data">
                    <div class="data-fluid">
                        <div class="row-form">
                            <div class="col-md-3"><strong>Category Name:</strong></div>
                            <div class="col-md-9">
                                <input type="text" class="form-control validate[required]" name='cat_name' value="<?php echo $data[0]['cat_name']; ?>" />
                                <span class="bottom" style="color: #64748b;">Required</span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-3"><strong>Current Thumbnail:</strong></div>
                            <div class="col-md-9"> 
                                <img src="<?php echo base_url().$data[0]['cat_thumb'];?>" class="img img-thumbnail" alt="" width="70" style="margin-bottom: 8px; display: block; border-radius: 8px;">            
                                <input type="file" name="cat_thumb" accept="image/*">
                                <span class="bottom" style="color: #64748b;">Leave empty to keep existing image</span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-3"><strong>Sequence Number:</strong></div>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name='sequence_number' value="<?php echo $data[0]['sequence_number']; ?>" />
                            </div>
                        </div>

                        <input type="hidden" name="cat_id" value="<?php echo $data[0]['cat_id'] ?>">
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
