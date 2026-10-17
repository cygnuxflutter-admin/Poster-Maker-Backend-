<style type="text/css">
.body .container .block .head.yellow{
    padding: 4px 4px 0px 10px;
    background-color: black !important;
}
</style>
<script type="text/javascript">


</script>   

<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <span class="ico-ok"></span>
            </div>
            <h1>ADD USERS <small>Add your Users</small></h1>
        </div>
        <ul class="breadcrumb">
            <li>
                <a href="index">Home</a>
            </li>
            <li class="active">Add Users</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">

        <div class="col-md-8 col-sm-offset-1">                

            <div class="block">
                <div id="error_text"><?php echo $this->session->flashdata('usrerr'); ?></div>
                <form method="POST" action="<?= base_url('site/adduser')?>" validate>
                    <div class="data-fluid">
                        <div class="row-form">
                            <div class="col-md-2 text-right">Username:</div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name='username' value="<?php echo set_value('username'); ?>" id="user_name" />
                                <span id="nameErMsg" class="bottom" style="color: red"></span>
                            </div>
                        </div> 
                        <div class="row-form">
                            <div class="col-md-2 text-right">Password:</div>
                            <div class="col-md-10">
                                <input type="password" class="form-control" name='password' id='user_password' />
                                <span id="passwordErMsg" class="bottom" style="color: red"></span>
                            </div>
                        </div> 
                        <div class="row-form">
                            <div class="col-md-2 text-right">Email:</div>
                            <div class="col-md-10">
                                <input type="email" class="form-control" name='email' id="user_email"/>
                                <span id="emailErMsg" class="bottom" style="color: red"></span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-10">
                                <div class="block">
                                    <div class="head yellow" >
                                        <h2>Menu Assign</h2>
                                    </div>
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

                                <div class="btn-group">
                                    <button class="btn btn-mini" id="ms_select" type="button">Select all</button>
                                    <button class="btn btn-mini" id="ms_deselect" type="button">Deselect all</button>
                                </div>
                                <span id="assignMenuErMsg" class="bottom" style="color: red"></span>
                            </div>
                        </div>
                    </div>
                    <div class="toolbar bottom tar">
                        <div class="btn-group">
                            <button class="btn" id="savebtn">Save</button>
                        </div>
                    </div>
                </div>                
            </form>

        </div>
    </div>
</div>                 
</div>
