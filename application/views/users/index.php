<script type="text/javascript">
    $(document).ready(function()
    {
        $("#dialog-confirm").hide();

        $(".delete").on('click', function()
        {
            var userID= $(this).attr('id');
            $( "#dialog-confirm" ).dialog({
              resizable: false,
              height: "auto",
              width: 400,
              modal: true,
              buttons: {
                "Yes": function() {
                    $( this ).dialog( "close" );
                    $.ajax({
                        url:'<?php echo base_url(); ?>user/deleteUser',
                        dataType: 'json',
                        method:'post',
                        data:{user_id:userID},
                        success:function(data){
                            alert(data);
                            location.reload();
                        }
                    });
                },
                No: function() {
                  $( this ).dialog( "close" );
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
            <h1>Users Management<small>Manage your Users</small></h1>
        </div>
        <ul class="breadcrumb">
            <li>
                <a href="/">Home</a>
            </li>
            <li class="active">Users Tables</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="block">
            <div class="head dblue">
                <div class="icon"><span class="ico-layout-9"></span></div>
                <h2>Users Details</h2>
                <?php $this->load->view('role/insertbtn',array('title'=>'Add User','path'=>'user/adduser')); ?>                                                        
            </div>   

            <div id="dialog-confirm" title="Delete Models?" style="display: none;">
              <p style="margin:10px; font-size: 16px; color: red"><span class="ui-icon ui-icon-alert" style="float:left; margin:10px; font-size: 18px;"></span>This User will be permanently deleted. Are you sure?</p>
          </div>

          <div class="data-fluid">
            <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="15%">Username</th>
                        
                        <th width="15%">Email</th>
            
                        <th width="10%">Assigned Menu</th>
                        <th width="10%">Assigned Role</th>
                        <th width="10%" class="TAC">Actions</th>
                    </tr>
                </thead>
                <tbody>

                 <?php if($data)
                 {
                    foreach ($data as $value) {  ?>
                        <tr>                                       
                            <td><?php echo $value['username']; ?></td>

                            <td><?php echo $value['email']; ?></td>
                           
                            <td><?php 
                            $idarray = explode(',', $value['menu_id']);
                                 
                            foreach ($idarray as $menuid) {
                                $res= $this->db->where('menu_id',$menuid)->get('user_menu')->result_array();

                                echo $res[0]['menu_name'].', ';                                     
                            }
                            ?>
                        </td>
                        <td><?php echo $value['user_role']; ?></td>
                        <td><?php $this->load->view('role/updel_btn',array('value'=>array('data_id'=>$value['user_id'],'path'=>'user/updateUser'),"wh"=>'true')); ?></td>
                    </tr>
                    <?php } 
                }
                ?>
            </tbody>
        </table>                    
    </div> 
</div>                       
</div>
</div>  
</div>

