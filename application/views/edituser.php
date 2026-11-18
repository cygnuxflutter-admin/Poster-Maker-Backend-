<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <h1>Edit User <small>Update user information & permissions</small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>user">Users</a></li>
            <li class="active">Edit User</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">                
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-id-card"></i> User Credentials & Access</h2>
                </div>
                <div id="error_text" style="padding: 10px 20px; color: #ef4444; font-weight: 600;"><?php echo $this->session->flashdata('usrerr'); ?></div>
                <form method="POST" action="<?= base_url('site/adduser')?>" validate>
                    <div class="data-fluid">
                        <div class="row-form">
                            <div class="col-md-3"><strong>Username:</strong></div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name='username' value="<?php echo set_value('username'); ?>" id="user_name" placeholder="Enter username" />
                                <span id="nameErMsg" class="bottom" style="color: #ef4444; font-weight: 600;"></span>
                            </div>
                        </div> 
                        <div class="row-form">
                            <div class="col-md-3"><strong>Password:</strong></div>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name='password' id='user_password' placeholder="Enter secure password" />
                                <span id="passwordErMsg" class="bottom" style="color: #ef4444; font-weight: 600;"></span>
                            </div>
                        </div> 
                        <div class="row-form">
                            <div class="col-md-3"><strong>Email Address:</strong></div>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name='email' id="user_email" placeholder="e.g. admin@example.com" />
                                <span id="emailErMsg" class="bottom" style="color: #ef4444; font-weight: 600;"></span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-3"><strong>Assign Menu Access:</strong></div>
                            <div class="col-md-9">
                                <div class="block" style="border: 1px solid #e2e8f0; margin-bottom: 10px;">
                                    <div class="head">
                                        <h2><i class="fa-solid fa-list-check"></i> Module Permissions</h2>
                                    </div>
                                    <div style="padding: 14px;">
                                        <select name="menu_id[]" multiple="multiple" id="ms">
                                           <?php
                                           $menu = $this->db->get('user_menu')->result_array();
                                           foreach ($menu as $menuItem)
                                           { 
                                            ?>
                                            <option value="<?php echo $menuItem['menu_id']?>" name="opt"><?php echo $menuItem['menu_name'];?></option>
                                            <?php 
                                           } 
                                           ?>                                                                   
                                        </select>

                                        <div class="btn-group" style="margin-top: 10px;">
                                            <button class="btn btn-sm btn-secondary" id="ms_select" type="button">Select all</button>
                                            <button class="btn btn-sm btn-secondary" id="ms_deselect" type="button">Deselect all</button>
                                        </div>
                                        <span id="assignMenuErMsg" class="bottom" style="color: #ef4444; font-weight: 600; margin-top: 6px; display: block;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="toolbar bottom tar" style="padding: 16px 0 0; display: flex; justify-content: flex-end; gap: 10px;">
                            <a href="<?php echo base_url(); ?>user" class="btn btn-secondary">Cancel</a>
                            <button class="btn btn-primary" id="savebtn" type="submit">
                                <i class="fa-solid fa-check"></i> Save User
                            </button>
                        </div>
                    </div>                
                </form>
            </div>
        </div>
    </div>                 
</div>
