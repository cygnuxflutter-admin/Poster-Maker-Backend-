<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<div class="modal fade" id="stickerinfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
  <div class="modal-dialog" style="width: 80%;">
    <div class="modal-content">                    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="stickerinfoModal">Sticker Info</h4>
      </div>
      <div class="modal-body col-md-12">
       <table class="table table-bordered" id="sticker_table">
        <caption><h3>Sticker Info</h3></caption>
        <thead>
          <th>Sticker Id</th>
          <th>X Position</th>
          <th>Y Position</th>
          <th>Width</th>
          <th>Height</th>
          <th>Rotation</th>
          <th>Image</th>
          <th>Resource Id</th>
          <th>Resource URI</th>
          <th>Order</th>
          
        </thead>
        <tbody class="tbody">

        </tbody>
      </table>
    </div>
    <div class="modal-footer">

      <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>            
    </div>
  </div>
</div>   
</div>

<div class="modal fade" id="textinfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
  <div class="modal-dialog" style="width: 80%;">
    <div class="modal-content">                    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="stickerinfoModal" >Text Info</h4>
      </div>
      <div class="modal-body col-md-12">
       <table class="table table-bordered" id="text_table">
        <caption><h3>Text Info</h3></caption>
        <thead>
          <th width="5%">Text Id</th>
          <th width="20%">Text</th>
          <th>Android Font</th>
          <th>iOS Font</th>
          <th>Colour</th>
          <th>X Position</th>
          <th>Y Position</th>
          <th>Width</th>
          <th>Height</th>
          <th>Rotation</th>
          <th>Order</th>
        </thead>
        <tbody class="tbody">

        </tbody>
      </table>
    </div>
    <div class="modal-footer">

      <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>            
    </div>
  </div>
</div>   
</div>



<div class="container">
  <div class="page-header">
    <div class="wrap-title">
      <div class="icon">
        <span class="ico-layout-7"></span>
      </div>
      <h1>Poster <small><?php echo ucfirst($this->session->userdata('poster_sess_user_name'));?>: <span class="cgreen">Active Now </span><span class="cgreen ico-circle" style="font-size: 8px;"></span></small></h1>
    </div>
    <ul class="breadcrumb">
      <li>
        <a href="/">Home</a>
      </li>
      <li class="active">Poster</li>
    </ul>
    <div class="clear"></div>
  </div>

  <div class="row">
    <div class="block">
      <div class="head blue">
        <div class="icon"><span class="ico-layout-9"></span></div>
        <h2>Poster</h2>
        <ul class="buttons">
          <li><button onclick="location.href='<?=base_url().'site/showaddposterform'?>'" style="background: none;border: none;"><div class="icon"><span class="ico-add"></span></div><h2>&nbsp;Add Poster</h2></button></li>
        </ul>                                                   
      </div> 

      <div id="dialog-confirm" title="Delete Models?" style="display: none;">
        <p style="margin:10px; font-size: 16px; color: red"><span class="ui-icon ui-icon-alert" style="float:left; margin:10px; font-size: 18px;"></span>This Apps will be permanently deleted. Are you sure?</p>
      </div>

      <div class="data-fluid">
        

        <table class="table lcnp" id="ddtable" cellpadding="0" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>Poster ID</th>
              <th>Poster Thumb</th>
              <th>Background Image</th>
              <th>Ratio</th>
              <th>Category</th>
              <th>Sticker Info</th>
              <th>Text Info</th>
              <th class="TAC">Actions</th>
            </tr>
          </thead>
          <tbody>

                                    
            </tbody>
        </table>   
      </div> 
    </div>                       
  </div>
</div>  
<script type="text/javascript">

 function delete_poster(postID)
 {
  swal({
    title: "Are you sure?",
    text: "Your will not be able to recover this Poster data!",
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
           // console.log(data[0].sticker_info);
           data[0].sticker_info = $.parseJSON(data[0].sticker_info);

           $.each(data[0].sticker_info, function (i,v)
           {
             var html_code = "<tr>";
             html_code +="<td class='sticker_id'>"+v.sticker_id+"</td>";
             html_code +="<td class='st_x_pos'>"+v.st_x_pos+"</td>";
             html_code +="<td class='st_y_pos'>"+v.st_y_pos+"</td>";
             html_code +="<td class='st_width'>"+v.st_width+"</td>";
             html_code +="<td class='st_height'>"+v.st_height+"</td>";
             html_code +="<td class='st_rotation'>"+v.st_rotation+"</td>";
             html_code +="<td class='st_image'><img src='<?=base_url();?>"+v.st_image+"' width=50></td>";
             html_code +="<td class='st_res_id'>"+v.st_res_id+"</td>";
             html_code +="<td class='st_res_uri'>"+v.st_res_uri+"</td>";
             html_code +="<td class='st_order'>"+v.st_order+"</td></tr>";
             $('.tbody').append(html_code);
           });
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
           // console.log(data[0].text_info);
           data[0].text_info = $.parseJSON(data[0].text_info);

           $.each(data[0].text_info, function (i,v)
           {
            console.log(v);
            var html_code = "<tr>";
            html_code+="<td class='text_id'>"+v.text_id+"</td>";
            html_code+="<td class='text'>"+v.text+"</td>";
            html_code+="<td class='font_family'>"+v.font_family+"</td>";
            html_code+="<td class='font_familyios'>"+v.font_familyiOS+"</td>";
            html_code+="<td class='txt_color'>"+v.txt_color+"</td>";
            html_code+="<td class='txt_x_pos'>"+v.txt_x_pos+"</td>";
            html_code+="<td class='txt_y_pos'>"+v.txt_y_pos+"</td>";
            html_code+="<td class='txt_width'>"+v.txt_width+"</td>";
            html_code+="<td class='txt_height'>"+v.txt_height+"</td>";
            html_code+="<td class='txt_rotation'>"+v.txt_rotation+"</td>";
            html_code+="<td class='txt_order'>"+v.txt_order+"</td></tr>";
            $('.tbody').append(html_code);
            });// $.each Function End

          } // Ajax Success function End

        }); // Ajax Call End

       }; // Text Info function End



       $(document).ready(function()
       {
        $("#ddtable").DataTable({
         
          "lengthMenu": [ [5,10, 25, 50], [5,10, 25, 50] ],
          
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
    });// Ready Function End

  </script>


