<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <i class="fa-solid fa-folder-plus"></i>
            </div>
            <h1>Add Category <small>Create a new poster category</small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>site/category">Categories</a></li>
            <li class="active">Add Category</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">                
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-layer-group"></i> Category Information</h2>
                </div>
                <form id="validate" method="POST" action="<?php echo base_url()."site/"?>insertcat" enctype="multipart/form-data">
                    <div class="data-fluid">
                        <div class="row-form">
                            <div class="col-md-3"><strong>Category Name:</strong></div>
                            <div class="col-md-9">
                                <input type="text" class="form-control validate[required]" placeholder="e.g. Birthday, Festival, Business" name='cat_name'/>
                                <span class="bottom" style="color: #64748b;">Required &bull; Visible in mobile app category list</span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-3"><strong>Category Thumbnail:</strong></div>
                            <div class="col-md-9">
                                <input type='file' class="validate[required]" name='cat_thumb' accept="image/*"/>
                                <span class="bottom" style="color: #64748b;">Required &bull; Recommended square aspect ratio (e.g. 512x512)</span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-3"><strong>Sequence Number:</strong></div>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name='sequence_number' value="0" placeholder="0" />
                                <span class="bottom" style="color: #64748b;">Lower numbers appear first in the app</span>
                            </div>
                        </div>

                        <div class="toolbar bottom tar" style="padding: 16px 0 0; display: flex; justify-content: flex-end; gap: 10px;">
                            <button class="btn btn-secondary" type="button" onClick="$('#validate').validationEngine('hide');">
                                Hide Prompts
                            </button>
                            <button class="btn btn-primary" type="submit">
                                <i class="fa-solid fa-check"></i> Save Category
                            </button>
                        </div>
                    </div>                
                </form>
            </div>
        </div>
    </div>                 
</div>
