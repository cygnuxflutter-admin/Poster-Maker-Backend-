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
    <h1>Download <small><?php echo ucfirst($this->session->userdata('poster_sess_user_name'));?>: <span class="cgreen">Active Now </span><span class="cgreen ico-circle" style="font-size: 8px;"></span></small></h1>
</div>
<ul class="breadcrumb">
  <li>
    <a href="/">Home</a>
</li>
<li class="active">Download</li>
</ul>
<div class="clear"></div>
</div>

<div class="row">
    <div class="block">
      <div class="head blue">
        <div class="icon"><span class="ico-layout-9"></span></div>
        <h2>Download</h2>
      <!--   <ul class="buttons">
          <li><button onclick="location.href='<?=base_url().'site/showaddposterform'?>'" style="background: none;border: none;"><div class="icon"><span class="ico-add"></span></div><h2>&nbsp;Add Poster</h2></button></li>
      </ul>   -->  

  </div> 

  <div id="dialog-confirm" title="Delete Models?" style="display: none;">
    <p style="margin:10px; font-size: 16px; color: red"><span class="ui-icon ui-icon-alert" style="float:left; margin:10px; font-size: 18px;"></span>This Apps will be permanently deleted. Are you sure?</p>
</div>
</div>                       
</div>
<div class="row">
    <div class="col-md-4">
        <div class="">
            <select name="jscategory" id="jscategory" class="catselect" style="width: 100%;">
                <option value="0">Select Category</option>
                <?php 
                foreach ($cat as $c) {
                    echo '<option value='.$c['cat_id'].'>'.$c['cat_name'].'</option>';
                }
                ?>
            </select>
        </div>
    </div> 
    <div class="col-md-4">
        <div class="">
            <select name="ratio" id="ratio" class="catselect" style="width: 100%;">
                <option value="0">Select Ratio</option>
                <?php 
                foreach ($ratio as $rec) {
                    echo '<option value='.$rec['ratio'].'>'.$rec['ratio'].'</option>';
                }
                ?>             
            </select>
        </div>
    </div> 
    <form method="post" action="<?php echo base_url() ?>/download/swiperCatDownload">
        <input type="hidden" name="cat_id" id="cat_id" >
        <input type="hidden" name="ratiodata" id="ratiodata" >
    <div class="col-md-4">
        <input value="Download this Data" type="submit" name="submit" class="btn btn-primary" style="display: none;" id="downloadBtn">
    </div>
</form>
</div>
<div class="row">
    <div class="results" id="jsonResult"></div>
</div>
</div> 

<script type="text/javascript">
  $(document).ready(function(){

    $('#jscategory').on('change',function(){
        getData();
    });
    $('#ratio').on('change',function(){
        getData();
    });

    $('.catselect').select2({});

    

});

  function getData(){

$("#cat_id").val($('#jscategory').val());
$("#ratiodata").val($('#ratio').val());
    if($('#jscategory').val() || $('#ratio').val()){
        var param={
            cat_id:$('#jscategory').val(),
            ratio:$('#ratio').val()
        }

        $.ajax({
            url:'<?php echo base_url() ?>download/swiperCat',
            method:'post',
            dataType:'json',
            data:param,
            success: function(data){
                $('#jsonResult').html('');
                var myJSON = JSON.stringify(data,undefined,5); 
                output(syntaxHighlight(myJSON));
                $('#downloadBtn').show(200);
            }
        })    
    }
    else{
        $('#jsonResult').html('');
        $('#downloadBtn').hide(200);
    }

    
}
function output(inp) {
    document.getElementById('jsonResult').appendChild(document.createElement('pre')).innerHTML = inp;
}
function syntaxHighlight(json) {
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}
</script>