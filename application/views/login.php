<!DOCTYPE html>
<html lang="en">
    <head>        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />    
        <!--[if gt IE 8]>
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />        
        <![endif]-->                
        <title>Login</title>
        <!-- <link rel="icon" type="image/ico" href="favicon.ico"/> -->

        <link href="<?php echo base_url(); ?>assets/css/stylesheets.css" rel="stylesheet" type="text/css" />
        <!--[if lt IE 10]>
            <link href="css/ie.css" rel="stylesheet" type="text/css" />
        <![endif]-->           
        <!--[if lte IE 7]>
            <script type='text/javascript' src='js/plugins/other/lte-ie7.js'></script>
        <![endif]-->    
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/jquery/jquery-1.10.2.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/jquery/jquery-ui-1.10.3.custom.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/jquery/jquery-migrate-1.2.1.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/jquery/globalize.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/other/excanvas.js'></script>

        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/other/jquery.mousewheel.min.js'></script>

        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/bootstrap/bootstrap.min.js'></script>            

        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/cookies/jquery.cookies.2.2.0.min.js'></script>    

        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>    

        <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/plugins/uniform/jquery.uniform.min.js"></script>
        <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/plugins/select/select2.min.js"></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/tagsinput/jquery.tagsinput.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/maskedinput/jquery.maskedinput-1.3.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/multiselect/jquery.multi-select.min.js'></script>

        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/validationEngine/languages/jquery.validationEngine-en.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/validationEngine/jquery.validationEngine.js'></script>    

        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/shbrush/XRegExp.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/shbrush/shCore.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/shbrush/shBrushXml.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/shbrush/shBrushJScript.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/shbrush/shBrushCss.js'></script>    

        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/charts.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/actions.js'></script>

    </head>
    <body>    
        <div id="loader"><img src="<?php echo base_url(); ?>assets/img/loader.gif"/></div>
        <?php if($this->session->userdata('poster_sess_user_name') !=null){ redirect('admin/showAdmin') ;  }
        else{
        ?>
    
        <div class="login">

            <div class="page-header">
                <div class="icon">
                    <span class="ico-arrow-right"></span>
                </div>
                <h1>Login <small>Wallpaper Admin</small></h1>
            </div>
           <div id="error_text"><?php echo $this->session->flashdata('error'); ?></div>

             <form id="validate" method="POST" action="<?php echo base_url()."admin/authenticate" ?>">
            <div style="float: left;">
                <div class="row-form">
                    <div class="col-md-12">
                        <input type="text" class="form-control validate[required]" placeholder="username" name="username" />
                       
                    </div>
                </div>
                <div class="row-form">
                    <div class="col-md-12">
                        <input class="form-control validate[required]" type="password" name="password" placeholder="password"/>
                    </div>            
                </div>
               <!--  <div class="row-form">
                   <div class="col-md-12">
                       <input type="checkbox"/> Keep me signed in
                   </div>
               </div> -->
                <div class="row-form">
                    <div class="col-md-12">
                        <button class="btn" name="submit">Sign in <span class="icon-arrow-next icon-white"></span></button>
                    </div>                
                </div>
            </div>
        </form>
        </div>
        <?php }?>
    </body>
</html>
