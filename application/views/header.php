<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />                   
    <title>Poster Maker - Admin Panel</title>
    
    <!-- Modern Typography & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Stylesheets -->
    <link href="<?php echo base_url(); ?>assets/css/stylesheets.css" rel="stylesheet" type="text/css" /> 
    <link href="<?php echo base_url(); ?>assets/css/modern-theme.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/jquery/jquery-1.10.2.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/jquery/jquery-ui-1.10.3.custom.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/jquery/jquery-migrate-1.2.1.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/other/excanvas.js'></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/plugins/swal/swal.css">
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/other/jquery.mousewheel.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/bootstrap/bootstrap.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/cookies/jquery.cookies.2.2.0.min.js'></script>    
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/multiselect/jquery.multi-select.min.js'></script>

    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/plugins/select/select2.min.js"></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/tagsinput/jquery.tagsinput.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/maskedinput/jquery.maskedinput-1.3.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/validationEngine/languages/jquery.validationEngine-en.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/validationEngine/jquery.validationEngine.js'></script>  

    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/jflot/jquery.flot.js'></script>    
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/jflot/jquery.flot.stack.js'></script>    
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/jflot/jquery.flot.resize.js'></script>  
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/sparklines/jquery.sparkline.min.js'></script>        
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/datatables/jquery.dataTables.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/plugins/uniform/jquery.uniform.min.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>assets/js/plugins/swal/sweetalert.js"></script>
    
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/shbrush/XRegExp.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/shbrush/shCore.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/shbrush/shBrushXml.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/shbrush/shBrushJScript.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/shbrush/shBrushCss.js'></script>    

    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/user.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/charts.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/actions.js'></script>
</head>
<?php if($this->session->userdata('poster_sess_user_name')==null){ redirect('admin');}
?>
<body>    
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    <div class="wrapper">
