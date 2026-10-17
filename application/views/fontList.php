<style>
    pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
    .string { color: green; }
    .number { color: darkorange; }
    .boolean { color: blue; }
    .null { color: magenta; }
    .key { color: red; }
    .results{
        height: 450px;
    }

</style>
<div class="container">
  <div class="page-header">
    <div class="wrap-title">
      <div class="icon">
        <span class="ico-layout-7"></span>
    </div>
    <h1>Font List <small><?php echo ucfirst($this->session->userdata('poster_sess_user_name'));?>: <span class="cgreen">Active Now </span><span class="cgreen ico-circle" style="font-size: 8px;"></span></small></h1>
</div>
<ul class="breadcrumb">
  <li>
    <a href="/">Home</a>
</li>
<li class="active">Font List</li>
</ul>
<div class="clear"></div>
</div>

<div class="row">
    <div class="block">
      <div class="head blue">
        <div class="icon"><span class="ico-layout-9"></span></div>
        <h2>Font List</h2>
  </div> 

  <div id="dialog-confirm" title="Delete Models?" style="display: none;">
    <p style="margin:10px; font-size: 16px; color: red"><span class="ui-icon ui-icon-alert" style="float:left; margin:10px; font-size: 18px;"></span>This Apps will be permanently deleted. Are you sure?</p>
</div>
</div>                       
</div>
<div class="row">

    <form method="post" action="<?php echo base_url() ?>/download/swiperCatDownload">
        <table>
            <tr>
                    <td><h4>FONT LIST</h4></td>
            </tr>
        <?php
            foreach ($finalFont as $key => $fonts) {  ?>
                
                <tr>
                    <td><?php echo $fonts;?>
                </tr>
            <?php }
        ?>
        </table>
    </form>
</div>
<div class="row">
    <div class="results" id="jsonResult"></div>
</div>
</div> 
