<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <span class="ico-ok"></span>
            </div>
            <h1>EDIT APPLICATIONS</h1>
        </div>
        <ul class="breadcrumb">
            <li>
                <a href="index">Home</a>
            </li>
            <li class="active">EDIT Application</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-8 col-sm-offset-2">                
            <div class="block">
                <form id="validate" method="POST" action="<?php echo base_url();?>site/updatecat" enctype="multipart/form-data">
                    <div class="data-fluid">
                        <div class="row-form">
                            <div class="col-md-2">Category's Name:</div>
                            <div class="col-md-10">
                                <input type="text" class="form-control validate[required]" name='cat_name' value="<?php echo $data[0]['cat_name']; ?>" />
                                <span class="bottom">Required</span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-2">Category Thumb: </div>

                            <div class="col-md-8"> 
                                <img src="<?php echo base_url().$data[0]['cat_thumb'];?>" class="img img-thumbnail" alt="" width="50">            
                                <input type="file" name="cat_thumb" id="">                       
                          </div>
                      </div>

                      <input type="hidden" name="cat_id" value="<?php echo $data[0]['cat_id'] ?>">
                      <div class="toolbar bottom tar">
                        <div class="btn-group">
                            <button class="btn btn-info" type="button" onClick="$('#validate').validationEngine('hide');">Hide prompts</button>
                            <button class="btn" type="submit">Update</button>
                        </div>
                    </div>
                </div>                
            </form>
        </div>
    </div>
</div>                 
</div>
