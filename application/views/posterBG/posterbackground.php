<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<div class="container">
  <div class="page-header">
    <div class="wrap-title">
      <div class="icon">
        <span class="ico-layout-7"></span>
      </div>
      <h1>Background <small><?php echo ucfirst($this->session->userdata('poster_sess_user_name'));?>: <span class="cgreen">Active Now </span><span class="cgreen ico-circle" style="font-size: 8px;"></span></small></h1>
    </div>
    <ul class="breadcrumb">
      <li>
        <a href="/">Home</a>
      </li>
      <li class="active">Background</li>
    </ul>
    <div class="clear"></div>
  </div>

  <div class="row">
    <div class="block">
      <div class="head blue">
        <div class="icon"><span class="ico-layout-9"></span></div>
        <h2>Background</h2>
        <ul class="buttons">
          <li><button onclick="location.href='<?=base_url().'site/showaddposterform'?>'" style="background: none;border: none;"><div class="icon"><span class="ico-add"></span></div><h2>&nbsp;Add Background</h2></button></li>
        </ul>                                                   
      </div> 

      <div id="dialog-confirm" title="Delete Models?" style="display: none;">
        <p style="margin:10px; font-size: 16px; color: red"><span class="ui-icon ui-icon-alert" style="float:left; margin:10px; font-size: 18px;"></span>This Background will be permanently deleted. Are you sure?</p>
      </div>

      <div class="data-fluid">
        <table class="table" cellpadding="0" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>Background Thumb</th>
              <th>Category</th>
              <th class="TAC">Actions</th>
            </tr>
          </thead>
          <tbody>

            <?php if($data)
            { 
              foreach ($data as $value) 
                { ?>
                  <tr>
                    <td><a href="<?php echo base_url().$value['post_thumb'];?>"><?php echo $value['post_id']; ?></a></td>
                    <td><a href="<?php echo base_url().$value['post_thumb'];?>"><img class="img img-thumbnail" src="<?php echo base_url().$value['post_thumb'];?>" alt="<?php echo $value['post_thumb']; ?>" width=110 style="height: 77.5px;"></a></td>
      

                    <td><?php $this->load->view('role/updel_btn',array('value'=>array('data_id'=>$value['post_id'],'path'=>'showaddposterform'),"wh"=>'true')); ?></td>
                  </tr>
                  <?php 
                  } 
              } 
              ?>                       

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
    text: "Your will not be able to recover this Background data!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Yes, delete it!",
    closeOnConfirm: false
  },
  function()
  {
    $.post("<?= base_url('site/delete_posterbg'); ?>",{post_id:postID} ,function(data){
     swal("Deleted!", data, "success");
     setTimeout(function(){
       window.location.reload(1);
     }, 1000)
   });
  });

}
</script>


