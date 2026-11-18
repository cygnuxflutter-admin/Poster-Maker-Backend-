<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<!-- Sticker Info Modal -->
<div class="modal fade" id="stickerinfoModal" tabindex="-1" role="dialog" aria-labelledby="stickerinfoModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 85%; max-width: 1100px;">
    <div class="modal-content">                    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="font-size: 24px;">&times;</button>
        <h4 class="modal-title" id="stickerinfoModalLabel"><i class="fa-solid fa-shapes" style="color: #4f46e5; margin-right: 8px;"></i> Poster Sticker Information</h4>
      </div>
      <div class="modal-body" style="padding: 16px;">
        <table class="table table-bordered" id="sticker_table">
          <thead>
            <tr>
              <th>Sticker ID</th>
              <th>X Position</th>
              <th>Y Position</th>
              <th>Width</th>
              <th>Height</th>
              <th>Rotation</th>
              <th>Sticker Image</th>
              <th>Resource ID</th>
              <th>Resource URI</th>
              <th>Render Order</th>
            </tr>
          </thead>
          <tbody class="tbody">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Close</button>            
      </div>
    </div>
  </div> 
</div>

<!-- Text Info Modal -->
<div class="modal fade" id="textinfoModal" tabindex="-1" role="dialog" aria-labelledby="textinfoModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 85%; max-width: 1100px;">
    <div class="modal-content">                    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="font-size: 24px;">&times;</button>
        <h4 class="modal-title" id="textinfoModalLabel"><i class="fa-solid fa-font" style="color: #10b981; margin-right: 8px;"></i> Poster Text Information</h4>
      </div>
      <div class="modal-body" style="padding: 16px;">
        <table class="table table-bordered" id="text_table">
          <thead>
            <tr>
              <th width="8%">Text ID</th>
              <th width="22%">Default Text</th>
              <th>Android Font</th>
              <th>iOS Font</th>
              <th>Colour</th>
              <th>X Pos</th>
              <th>Y Pos</th>
              <th>Width</th>
              <th>Height</th>
              <th>Rotation</th>
              <th>Order</th>
            </tr>
          </thead>
          <tbody class="tbody">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Close</button>            
      </div>
    </div>
  </div> 
</div>

<div class="container">
  <div class="page-header">
    <div class="wrap-title">
      <div class="icon">
        <i class="fa-solid fa-images"></i>
      </div>
      <h1>Poster Templates <small><span class="cgreen"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Active</span></small></h1>
    </div>
    <ul class="breadcrumb">
      <li><a href="<?php echo base_url(); ?>site">Home</a></li>
      <li class="active">Poster Templates</li>
    </ul>
    <div class="clear"></div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="block">
        <div class="head">
          <h2><i class="fa-solid fa-layer-group"></i> All Poster Templates</h2>
          <ul class="buttons">
            <li>
              <a href="<?=base_url().'site/showaddposterform'?>" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Add Poster
              </a>
            </li>
          </ul>                                                   
        </div> 

        <div id="dialog-confirm" title="Delete Model?" style="display: none;">
          <p style="margin:10px; font-size: 15px; color: #ef4444;">
            <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i> This Poster template will be permanently deleted. Are you sure?
          </p>
        </div>

        <div class="data-fluid">
          <table class="table lcnp" id="ddtable" cellpadding="0" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th width="8%">Poster ID</th>
                <th width="14%">Poster Thumb</th>
                <th width="14%">Background Image</th>
                <th width="8%">Ratio</th>
                <th width="20%">Category</th>
                <th width="10%">Sticker Info</th>
                <th width="10%">Text Info</th>
                <th width="16%" class="TAC">Actions</th>
              </tr>
            </thead>
            <tbody>                                    
            </tbody>
          </table>   
        </div> 
      </div>                       
    </div>
  </div>
</div>  

<script type="text/javascript">
  function delete_poster(postID)
  {
    swal({
      title: "Are you sure?",
      text: "You will not be able to recover this Poster data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false
    },
    function()
    {
      $.post("<?= base_url('site/delete_poster'); ?>",{post_id:postID} ,function(data){
        swal("Deleted!", data, "success");
        setTimeout(function(){
          window.location.reload(1);
        }, 1000)
      });
    });
  }

  function getSticker(id){
    $('.tbody').html("");
    var stickerID = id;

    $.ajax({
      url:'<?= base_url('site/getsticker_info')?>',
      type:'post',
      data:{sticker_id:stickerID},
      dataType:'json',
      success:function(data){
        if(data && data[0] && data[0].sticker_info) {
          var stickerInfo = (typeof data[0].sticker_info === 'string') ? $.parseJSON(data[0].sticker_info) : data[0].sticker_info;
          $.each(stickerInfo, function (i,v)
          {
            var html_code = "<tr>";
            html_code +="<td class='sticker_id'><strong>#"+v.sticker_id+"</strong></td>";
            html_code +="<td class='st_x_pos'>"+v.st_x_pos+"</td>";
            html_code +="<td class='st_y_pos'>"+v.st_y_pos+"</td>";
            html_code +="<td class='st_width'>"+v.st_width+"</td>";
            html_code +="<td class='st_height'>"+v.st_height+"</td>";
            html_code +="<td class='st_rotation'>"+v.st_rotation+"</td>";
            html_code +="<td class='st_image'><img src='<?=base_url();?>"+v.st_image+"' width='50' style='border-radius: 6px;'></td>";
            html_code +="<td class='st_res_id'>"+v.st_res_id+"</td>";
            html_code +="<td class='st_res_uri'>"+v.st_res_uri+"</td>";
            html_code +="<td class='st_order'><span class='seq_num'>"+v.st_order+"</span></td></tr>";
            $('.tbody').append(html_code);
          });
        }
      }
    });
  }

  function getText(id)
  {
    $('.tbody').html("");
    var stickerID = id;

    $.ajax({
      url:'<?= base_url('site/getsticker_info')?>',
      type:'post',
      data:{sticker_id:stickerID},
      dataType:'json',
      success:function(data){
        if(data && data[0] && data[0].text_info) {
          var textInfo = (typeof data[0].text_info === 'string') ? $.parseJSON(data[0].text_info) : data[0].text_info;
          $.each(textInfo, function (i,v)
          {
            var html_code = "<tr>";
            html_code+="<td class='text_id'><strong>#"+v.text_id+"</strong></td>";
            html_code+="<td class='text'><strong>"+v.text+"</strong></td>";
            html_code+="<td class='font_family'>"+v.font_family+"</td>";
            html_code+="<td class='font_familyios'>"+v.font_familyiOS+"</td>";
            html_code+="<td class='txt_color'><span style='display:inline-block;width:12px;height:12px;border-radius:2px;background:"+v.txt_color+";margin-right:4px;'></span>"+v.txt_color+"</td>";
            html_code+="<td class='txt_x_pos'>"+v.txt_x_pos+"</td>";
            html_code+="<td class='txt_y_pos'>"+v.txt_y_pos+"</td>";
            html_code+="<td class='txt_width'>"+v.txt_width+"</td>";
            html_code+="<td class='txt_height'>"+v.txt_height+"</td>";
            html_code+="<td class='txt_rotation'>"+v.txt_rotation+"</td>";
            html_code+="<td class='txt_order'><span class='seq_num'>"+v.txt_order+"</span></td></tr>";
            $('.tbody').append(html_code);
          });
        }
      }
    });
  }

  $(document).ready(function()
  {
    $("#ddtable").DataTable({
      "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],
      "processing": true,
      "serverSide": true,
      "ajax":{
        url : "<?php echo base_url("site/fetchdata") ?>",
        type : 'post',
      },
    });

    $(document).on("click", ".sticker_info", function () {
      getSticker($(this).attr('id'));
    });
    $(document).on("click", ".text_info", function () {
      getText($(this).attr('id'));
    });
  });
</script>
