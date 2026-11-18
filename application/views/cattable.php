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
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <h1>Poster Categories <small><span class="cgreen"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Active</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li class="active">Categories</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-table-list"></i> Category Listing & Sequence</h2>
                    <ul class="buttons">
                        <li>
                            <a href="<?= base_url('site/showaddcatform') ?>" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Add Category
                            </a>
                        </li>
                    </ul>                                                   
                </div> 

                <div id="dialog-confirm" title="Delete Category?" style="display: none;">
                    <p style="margin:10px; font-size: 15px; color: #ef4444;">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i> This Category will be permanently deleted. Are you sure?
                    </p>
                </div>

                <div class="data-fluid">
                    <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox" class="checkall"/></th>
                                <th width="8%">Category ID</th>
                                <th width="35%">Category Name</th>
                                <th width="20%">Thumbnail</th>
                                <th width="18%" style="text-align: center;">Sequence Number</th>
                                <th width="15%" class="TAC">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="row_position">
                            <?php if($data){ foreach ($data as $value) { ?>
                            <tr id="<?php echo $value['cat_id'] ?>" class="gradeX" style="cursor: move;">
                                <td><input type="checkbox" name="order[]" value="528"/></td>
                                <td><strong style="color: #64748b;">#<?=$value['cat_id']?></strong></td>
                                <td><strong style="color: #0f172a; font-size: 14px;"><?php echo ucfirst($value['cat_name']); ?></strong></td>
                                <td><img src="<?= base_url($value['cat_thumb']) ?>" alt="" class="img img-thumbnail" width="55" height="55" style="object-fit: cover;"></td>
                                <td class="seq_num" style="text-align: center;"><?php echo isset($value['sequence_number']) ? $value['sequence_number'] : 0; ?></td>
                                <td class="TAC">
                                    <a href="<?php echo base_url()."site/showcateditform/".$value['cat_id']; ?>" class="button green" title="Edit Category">
                                        <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                    </a>
                                    <a href="javascript:void(0);" id="<?php echo $value['cat_id']; ?>" class="button red delete" title="Delete Category">
                                        <div class="icon"><i class="fa-solid fa-trash"></i></div>
                                    </a>                          
                                </td>
                            </tr>
                            <?php } } ?>                          
                        </tbody>
                    </table>                    
                </div> 
            </div>                       
        </div>
    </div>  
</div>

<script type='text/javascript' src='<?php echo base_url(); ?>assets/js/jquery.sortable.js'></script>
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
