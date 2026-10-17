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
                    url:'<?php echo base_url(); ?>site/deletecat',
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
            <h1>Category <small> <span class="cgreen">Active Now </span><span class="cgreen ico-circle" style="font-size: 8px;"></span></small></h1>
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
                    <li><a href="showaddcatform"><div class="icon"><span class="ico-add"></span></div></a></li>
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
                        <th width="">Category's Thumb</th>
                        <th>Sequence Number</th>
                        <th width="" class="TAC">Actions</th>
                    </tr>
                </thead>
                <tbody class="row_position">

                    <?php if($data){ foreach ($data as $value) { ?>
                    <tr  id="<?php echo $value['cat_id'] ?>" class="gradeX">
                        <td><input type="checkbox" name="order[]" value="528"/></td>
                        <td><?=$value['cat_id']?></td>
                        <td><?php echo ucfirst($value['cat_name']); ?></td>
                        <td><img src="<?= base_url($value['cat_thumb']) ?>" alt="" class="img img-thumbnail" width=50></td>
                        <td class="seq_num" style="text-align: center"><?php echo isset($value['sequence_number']) ? $value['sequence_number'] : 0; ?></td>
                        <td>
                            <a href="<?php echo base_url()."site/showcateditform/".$value['cat_id']; ?>" class="button green">
                                <div class="icon"><span class="ico-pencil"></span></div>
                            </a>
                            <!-- <button class="button red delete" id="<?php echo $value['app_id']; ?>"></button> -->
                            <a href="javascript:void(0);" id="<?php echo $value['cat_id']; ?>" class="button red delete">
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

<script type="text/javascript">
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
            url:'<?php echo base_url() ?>Site/category_sorting',
            type:'post',
            data:{position:data},
            success:function(res){
                console.log('Category order saved successfully');
            }
        })
    }
</script>

