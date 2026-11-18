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
                        url:'<?php echo base_url(); ?>site/deleteuser',
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
                <i class="fa-solid fa-users"></i>
            </div>
            <h1>Users Management <small><span class="cgreen"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Active</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li class="active">Users</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-user-shield"></i> System Users & Privileges</h2>
                    <ul class="buttons">
                        <li>
                            <a href="<?= base_url('site/showadduserform')?>" class="btn btn-primary">
                                <i class="fa-solid fa-user-plus"></i> Add New User
                            </a>
                        </li>
                    </ul>                                                  
                </div>   

                <div id="dialog-confirm" title="Delete User?" style="display: none;">
                    <p style="margin:10px; font-size: 15px; color: #ef4444;">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i> This user will be permanently deleted. Are you sure?
                    </p>
                </div>

                <div class="data-fluid">
                    <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="20%">Username</th>
                                <th width="25%">Email Address</th>
                                <th width="20%">Password (Encrypted)</th>
                                <th width="25%">Assigned Permissions</th>
                                <th width="10%" class="TAC">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($data) {
                                foreach ($data as $value) { ?>
                            <tr>                                       
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 28px; height: 28px; border-radius: 50%; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">
                                            <?php echo strtoupper(substr($value['username'], 0, 1)); ?>
                                        </div>
                                        <strong style="color: #0f172a;"><?php echo $value['username']; ?></strong>
                                    </div>
                                </td>
                                <td><?php echo $value['email']; ?></td>
                                <td><code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 11px;"><?php echo substr($value['password'], 0, 16) . '...'; ?></code></td>
                                <td>
                                    <?php 
                                    $idarray = explode(',', $value['menu_id']);
                                    foreach ($idarray as $menuid) {
                                        if(!empty(trim($menuid))) {
                                            $res = $this->db->where('menu_id', $menuid)->get('user_menu')->result_array();
                                            if(!empty($res)) {
                                                echo '<span class="label label-info" style="margin: 2px 3px 2px 0;">' . $res[0]['menu_name'] . '</span>';
                                            }
                                        }
                                    }
                                    ?>
                                </td>
                                <td class="TAC">  
                                    <a href="javascript:void(0);" class="button green" title="User Active">
                                        <div class="icon"><i class="fa-solid fa-check"></i></div>
                                    </a>
                                    <button class="delete button red" id="<?= $value['user_id'] ?>" title="Delete User">
                                        <div class="icon"><i class="fa-solid fa-trash"></i></div>
                                    </button>
                                </td>
                            </tr>
                            <?php } 
                            } else { ?>
                            <tr>
                                <td colspan="5" class="TAC" style="padding: 24px;">No users found.</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>                    
                </div> 
            </div>                       
        </div>
    </div>  
</div>
