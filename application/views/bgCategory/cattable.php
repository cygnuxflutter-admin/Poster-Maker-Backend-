<script type="text/javascript">
    $(document).ready(function(){
       $(".table").on("click", ".delete", function()
        {
        var catID= $(this).attr('id');
        $( "#dialog-confirm" ).dialog({
          resizable: false,
          height: "auto",
          width: 400,
          modal: true,
          buttons: {
            "Yes": function() {

                $( this ).dialog( "close" );
                $.ajax({
                    url:'<?php echo base_url(); ?>site/bgdeletecat',
                    dataType: 'json',
                    method:'post',
                    data:{cat_id:catID},
                    success:function(data){
                        alert(data);
                        location.reload();
                    }
                });
            },
            No: function() {
              $(this).dialog( "close" );
          }
      }
  });


    });
    });
        
</script>
<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <span class="ico-layout-7"></span>
            </div>
            <h1>Category <small><?php echo ucfirst($this->session->userdata('username'));?>: <span class="cgreen">Active Now </span><span class="cgreen ico-circle" style="font-size: 8px;"></span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li>
                <a href="/">Home</a>
            </li>
            <li class="active">Category</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="block">
            <div class="head blue">
                <div class="icon"><span class="ico-layout-9"></span></div>
                <h2>Category</h2>
                <ul class="buttons">
                    <li><a href="showaddbgcatform"><div class="icon"><span class="ico-add"></span></div><h4 style="display: inline-block; ">Add Category</h4></a></li>
                </ul>                                                   
            </div> 

            <div id="dialog-confirm" title="Delete Models?" style="display: none;">
              <p style="margin:10px; font-size: 16px; color: red"><span class="ui-icon ui-icon-alert" style="float:left; margin:10px; font-size: 18px;"></span>This Category will be permanently deleted. Are you sure?</p>
          </div>

          <div class="data-fluid">
            <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="checkall"/></th>
                        <th>ID</th>
                        <th width="">Category's Name</th>
                        <th width="" class="TAC">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if($data){ foreach ($data as $value) { ?>
                    <tr>
                        <td><input type="checkbox" name="order[]" value="528"/></td>
                        <td><?=$value['bg_cat_id']?></td>
                        <td><?php echo ucfirst($value['name']); ?></td>
                        <td>
                            <a href="<?php echo base_url()."site/showbgcateditform/".$value['bg_cat_id']; ?>" class="button green">
                                <div class="icon"><span class="ico-pencil"></span></div>
                            </a>
                            <!-- <button class="button red delete" id="<?php echo $value['app_id']; ?>"></button> -->
                            <a href="javascript:void(0);" id="<?php echo $value['bg_cat_id']; ?>" class="button red delete">
                                                          <div class="icon"><span class="ico-remove"></span></div></a>                          
                        </td>
                    </tr>
                    <?php } } ?>                          
                </tbody>
            </table>                    
        </div> 
    </div>                       
</div>
</div>  



