 <style type="text/css">
 .rrr .row-form .col-sm-1{
    width: 7.333% !important;

}

.ui-state-default .col-sm-1{
    width: 7.333% !important;
    
}
</style>
<script type="text/javascript">
    var next = 0;
</script>
<div class="container">
    <div class="page-header">
        <div class="wrap-title">

            <h1>Update Poster</h1>
        </div>
        <ul class="breadcrumb">
            <li>
                <a href="index">Home</a>
            </li>
            <li class="active">Update Poster</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-12">                
            <div class="block">
                <form id="validate" method="POST" enctype="multipart/form-data">
                    <div class="data-fluid">
                        <!-- <div class="row-form">
                            <div class="col-md-2">Wallpaper's Name:</div>
                            <div class="col-md-10">
                                <input type="text" class="form-control validate[required]" name='app_name'/>
                                <span class="bottom">Required</span>
                            </div>
                        </div> -->
                        
                        <div class="row-form">
                            <div class="col-md-2">Poster Thumb :</div>
                            <div class="col-md-10">
                                <img src="<?= base_url($data[0]["post_thumb"]) ?>" alt="" width="50">
                                <input type='file' class="validate[required]" name='post_thumb' id="post_thumb"/>
                                <span class="bottom">Required</span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-2">Background Image:</div>
                            <div class="col-md-10">
                               <img src="<?= base_url($data[0]["back_image"]) ?>" alt="" width="50">
                               <input type='file' class="validate[required]" name='back_image' id="back_image"/>
                               <span class="bottom">Required</span>
                           </div>
                       </div>

                       <div class="row-form">
                        <div class="col-md-2">Ratio</div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name='ratio' id="ratio" value="<?= $data[0]['ratio'] ?>" />  
                        </div>
                    </div>
                    <input type="hidden" id="post_id" value="<?= $data[0]['post_id'] ?>">
                    <div class="row-form">
                        <div class="col-md-2">Poster Catagory:</div>
                        <div class="col-md-10">                            
                            <select name="cat_id" class="form-control validate[required]" style="width: 100%;" id="cat_id">
                                <option value="">Select Poster Category</option>
                                <?php foreach($data as $value){
                                    ?>
                                    <option value="<?php echo $value['cat_id']; ?>" <?php if($data[0]['cat_id']==$value['cat_id']){ echo "selected"; }?> ><?php echo $value['cat_name']; ?></option>
                                    <?php } ?>                                                                      
                                </select>                            
                                <span class="bottom">Required</span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="sticker_table">
                                <caption><h3>Sticker Info</h3></caption>
                                <thead>
                                    <th>Sticker Id</th>
                                    <th>X Position</th>
                                    <th>Y Position</th>
                                    <th>Width</th>
                                    <th>Height</th>
                                    <th>Rotation</th>
                                    <th>Y Rotation</th>
                                    <th>Resource Id</th>
                                    <th>Resource URI</th>
                                    <th>URL</th>
                                    <th>Type</th>
                                    <th>Order</th>
                                    <th width="5%"></th>
                                </thead>
                                <tbody>
                                    <?php 
                                    if(!empty($data[0]['sticker_info']))
                                    {
                                        $stk = json_decode($data[0]['sticker_info']) ;
                                        foreach($stk as $val){    
                                            ?>
                                            <tr>
                                                <td contenteditable="true" class="sticker_id"><?= $val->sticker_id?></td>
                                                <td contenteditable="true" class="st_x_pos"><?= $val->st_x_pos ?></td>
                                                <td contenteditable="true" class="st_y_pos"><?= $val->st_y_pos ?></td>
                                                <td contenteditable="true" class="st_width"><?= $val->st_width ?></td>
                                                <td contenteditable="true" class="st_height"><?= $val->st_height ?></td>
                                                <td contenteditable="true" class="st_rotation"><?= $val->st_rotation ?></td>
                                                <td contenteditable="true" class="st_y_rotation"><?= $val->st_y_rotation ?></td>
                                                <td contenteditable="true" class="st_res_id"><?= $val->st_res_id ?></td>
                                                <td contenteditable="true" class="st_res_uri"><?= $val->st_res_uri ?></td>
                                                <td contenteditable="true" class="st_bitmap_url"><?= $val->st_bitmap_url ?></td>
                                                <td contenteditable="true" class="st_type"><?= $val->st_type ?></td>
                                                <td contenteditable="true" class="st_order"><?= $val->st_order ?></td>
                                                <td></td>
                                            </tr>
                                            <?php } 
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div align="right">
                                    <button type="button" name="add_sticker" id="add_sticker" class="btn btn-success btn-xs" style="font-size: 15px;">Add More</button>
                                </div>
                            </div>

                            <!-- Table for Adding Text Info -->
                            <div class="table-responsive">
                                <table class="table table-bordered" id="text_table">
                                    <caption><h3>Text Info</h3></caption>
                                    <thead>
                                        <th width="5%">Text Id</th>
                                        <th width="20%">Text</th>
                                        <th>Font Family</th>
                                        <th>Colour</th>
                                        <th>X Position</th>
                                        <th>Y Position</th>
                                        <th>Width</th>
                                        <th>Height</th>
                                        <th>Rotation</th>
                                        <th>Type</th>
                                        <th>Order</th>
                                        <th width="5%"></th>
                                    </thead>
                                    <tbody>
                                        <?php 
                                      // print_r($data[0]);
                                        if(!empty($data[0]['text_info']))
                                        {
                                            $txt =  json_decode($data[0]['text_info']);
                                            foreach($txt as $val){
                                                ?>
                                                <tr>
                                                    <td contenteditable="true" class="text_id"><?= $val->text_id ?></td>
                                                    <td contenteditable="true" class="text"><?= $val->text ?></td>
                                                    <td contenteditable="true" class="font_family"><?= $val->font_family ?></td>
                                                    <td contenteditable="true" class="txt_color"><?= $val->txt_color ?></td>
                                                    <td contenteditable="true" class="txt_x_pos"><?= $val->txt_x_pos ?></td>
                                                    <td contenteditable="true" class="txt_y_pos"><?= $val->txt_y_pos ?></td>
                                                    <td contenteditable="true" class="txt_width"><?= $val->txt_width ?></td>
                                                    <td contenteditable="true" class="txt_height"><?= $val->txt_height ?></td>
                                                    <td contenteditable="true" class="txt_rotation"><?= $val->txt_rotation ?></td>
                                                    <td contenteditable="true" class="txt_type"><?= $val->txt_type ?></td>
                                                    <td contenteditable="true" class="txt_order"><?= $val->txt_order ?></td>
                                                    <td></td>
                                                </tr>
                                                <?php } 
                                            }?>
                                        </tbody>
                                    </table>
                                    <div align="right">
                                        <button type="button" name="add_text" id="add_text" class="btn btn-success btn-xs" style="font-size: 15px;">Add More</button>
                                    </div>
                                </div>


                                <div class="toolbar bottom tar">
                                    <div class="btn-group">
                                        <button class="btn btn-info" type="button" onClick="$('#validate').validationEngine('hide');">Hide prompts</button>
                                        <button class="btn" type="button" id="save">Update</button>
                                    </div>
                                </div>
                            </div>                
                        </form>
                    </div>
                </div>
            </div>                 
        </div>
        <script type="text/javascript">
            $(document).ready(function(){

                var count = 0;

                $('#add_sticker').click(function(){
                    count = count + 1;
                    var html_code = "<tr id='row"+count+"'>";
                    html_code +="<td contenteditable='true' class='sticker_id'></td>";
                    html_code +="<td contenteditable='true' class='st_x_pos'></td>";
                    html_code +="<td contenteditable='true' class='st_y_pos'></td>";
                    html_code +="<td contenteditable='true' class='st_width'></td>";
                    html_code +="<td contenteditable='true' class='st_height'></td>";
                    html_code +="<td contenteditable='true' class='st_rotation'></td>";
                    html_code +="<td contenteditable='true' class='st_y_rotation'></td>";
                    html_code +="<td contenteditable='true' class='st_res_id'></td>";
                    html_code +="<td contenteditable='true' class='st_res_uri'></td>";
                    html_code +="<td contenteditable='true' class='st_bitmap_url'></td>";
                    html_code +="<td contenteditable='true' class='st_type'></td>";
                    html_code +="<td contenteditable='true' class='st_order'></td>";
                    html_code +="<td><button type='button' class='btn btn-danger btn-xs remove' name='remove' data-row='row"+count+"'>-</button></td>";
                    html_code +="</tr>"
                    console.log(count);
                    $("#sticker_table").append(html_code);
                });

                $('#add_text').click(function(){
                    count = count + 1;
                    var html_code = "<tr id='row"+count+"'>";
                    html_code+="<td contenteditable='true' class='text_id'></td>";
                    html_code+="<td contenteditable='true' class='text'></td>";
                    html_code+="<td contenteditable='true' class='font_family'></td>";
                    html_code+="<td contenteditable='true' class='txt_color'></td>";
                    html_code+="<td contenteditable='true' class='txt_x_pos'></td>";
                    html_code+="<td contenteditable='true' class='txt_y_pos'></td>";
                    html_code+="<td contenteditable='true' class='txt_width'></td>";
                    html_code+="<td contenteditable='true' class='txt_height'></td>";
                    html_code+="<td contenteditable='true' class='txt_rotation'></td>";
                    html_code+="<td contenteditable='true' class='txt_type'></td>";
                    html_code+="<td contenteditable='true' class='txt_order'></td>";

                    html_code +="<td><button type='button' class='btn btn-danger btn-xs remove' name='remove' data-row='row"+count+"'>-</button></td>";
                    html_code +="</tr>"
                    console.log(count);
                    $("#text_table").append(html_code);
                });

                $(document).on('click','.remove',function(){
                    var delete_row = $(this).data("row");
                    console.log(delete_row);
                    $('#'+ delete_row).remove();
                });

                $("#save").click(function(){
                    var post_thumb = document.getElementById("post_thumb").files[0];
                    var back_image = document.getElementById("back_image").files[0];
                    var ratio = $("#ratio").val();
                    var cat_id = $("#cat_id").val();
                    var post_id = $("#post_id").val();
                // Sticker Variables
                var sticker_id = [];
                var st_x_pos =[];
                var st_y_pos =[];
                var st_width =[];
                var st_height=[];
                var st_rotation=[];
                var st_y_rotation =[];
                var st_res_id = [];
                var st_res_uri = [];
                var st_bitmap_url = [];
                var st_type = [];
                var st_order = [];

                // Text Variables 
                var text_id = [];
                var text = [];
                var font_family = [];
                var txt_color = [];
                var txt_x_pos =[];
                var txt_y_pos =[];
                var txt_width =[];
                var txt_height=[];
                var txt_rotation=[];
                var txt_type = [];
                var txt_order = [];

                //Assign Each Variable Values into array for Sticker Table

                $('.sticker_id').each(function(){
                    sticker_id.push($(this).text());
                });
                $('.st_x_pos').each(function(){
                    st_x_pos.push($(this).text());
                });
                $('.st_y_pos').each(function(){
                    st_y_pos.push($(this).text());
                });
                $('.st_width').each(function(){
                    st_width.push($(this).text());
                });
                $('.st_height').each(function(){
                    st_height.push($(this).text());
                });
                $('.st_rotation').each(function(){
                    st_rotation.push($(this).text());
                });
                $('.st_y_rotation').each(function(){
                    st_y_rotation.push($(this).text());
                });
                $('.st_res_id').each(function(){
                    st_res_id.push($(this).text());
                });
                $('.st_res_uri').each(function(){
                    st_res_uri.push($(this).text());
                });
                $('.st_bitmap_url').each(function(){
                    st_bitmap_url.push($(this).text());
                });
                $('.st_type').each(function(){
                    st_type.push($(this).text());
                });
                $('.st_order').each(function(){
                    st_order.push($(this).text());
                });

               //Assign Each Variable Values into array for Sticker Table
               $('.text_id').each(function(){
                text_id.push($(this).text());
            });
               $('.text').each(function(){
                text.push($(this).text());
            });
               $('.font_family').each(function(){
                font_family.push($(this).text());
            });
               $('.txt_color').each(function(){
                txt_color.push($(this).text());
            });
               $('.txt_x_pos').each(function(){
                txt_x_pos.push($(this).text());
            });
               $('.txt_y_pos').each(function(){
                txt_y_pos.push($(this).text());
            });
               $('.txt_width').each(function(){
                txt_width.push($(this).text());
            });
               $('.txt_height').each(function(){
                txt_height.push($(this).text());
            });
               $('.txt_rotation').each(function(){
                txt_rotation.push($(this).text());
            });
               $('.txt_order').each(function(){
                txt_order.push($(this).text());
            });
               $('.txt_type').each(function(){
                txt_type.push($(this).text());
            });
               var form_data =new FormData();
               form_data.append("post_thumb", post_thumb);
               form_data.append("back_image", back_image);
               form_data.append("ratio", ratio);
               form_data.append("cat_id", cat_id);
               form_data.append("post_id",post_id);
               form_data.append("sticker_id", sticker_id);
               form_data.append("st_x_pos", st_x_pos);
               form_data.append("st_y_pos", st_y_pos);
               form_data.append("st_width", st_width);
               form_data.append("st_height", st_height);
               form_data.append("st_rotation", st_rotation);
               form_data.append("st_y_rotation", st_y_rotation);
               form_data.append("st_res_id", st_res_id);
               form_data.append("st_res_uri", st_res_uri);
               form_data.append("st_bitmap_url", st_bitmap_url);
               form_data.append("st_type", st_type);
               form_data.append("st_order", st_order);

               form_data.append("text_id", text_id);
               form_data.append("text", text);
               form_data.append("txt_color", txt_color);
               form_data.append("txt_y_pos", txt_y_pos);
               form_data.append("txt_x_pos", txt_x_pos);
               form_data.append("txt_width", txt_width);
               form_data.append("txt_height", txt_height);
               form_data.append("font_family", font_family);
               form_data.append("txt_rotation", txt_rotation);
               form_data.append("txt_order", txt_order);
               form_data.append("txt_type", txt_type);
               console.log(st_x_pos);
               $.ajax({
                url : '<?php echo base_url(); ?>site/updateposter',
                type:'post',
                data: form_data,
                processData: false,
                contentType: false,
                dataType:'json',
                success:function(data){
                    location.href = "<?= base_url('site/poster'); ?>"
                }
            });
           });




});
</script>