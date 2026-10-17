<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <span class="ico-ok"></span>
            </div>
            <h1>ADD CATEGORY</h1>
        </div>
        <ul class="breadcrumb">
            <li>
                <a href="index">Home</a>
            </li>
            <li class="active">Add Category</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-8 col-sm-offset-2">                
            <div class="block">
                <form id="validate" method="POST" action="<?php echo base_url()."site/"?>insertcat" enctype="multipart/form-data">
                    <div class="data-fluid">
                        <div class="row-form">
                            <div class="col-md-2">Category Name:</div>
                            <div class="col-md-10">
                                <input type="text" class="form-control validate[required]" name='cat_name'/>
                                <span class="bottom">Required</span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-2">Category Thumb:</div>
                            <div class="col-md-10">
                                <input type='file' class="validate[required]" name='cat_thumb'/>
                                <span class="bottom">Required</span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-2">Sequence Number:</div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name='sequence_number' value="" />
                              <!--   <span class="bottom">Required</span> -->
                            </div>
                        </div>

                        <div class="toolbar bottom tar">
                            <div class="btn-group">
                                <button class="btn btn-info" type="button" onClick="$('#validate').validationEngine('hide');">Hide prompts</button>
                                <button class="btn" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>                
                </form>
            </div>
        </div>
    </div>                 
</div>
