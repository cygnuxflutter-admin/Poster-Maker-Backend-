
<div class="container">  
    <div class="row">
        <div class="col-sm-12">
            <?php 
            if( !empty($editposter) ) {
                $post_id = $editposter['post_id'];
                $cat_id = $editposter['cat_id'];
                $post_thumb = $editposter['post_thumb'];
                $post_background = $editposter['back_image'];
                $ratio = $editposter['ratio'];
                $dataSticker  = json_decode($editposter['sticker_info']);
                $dataText  = json_decode($editposter['text_info']);

            } else {
                $post_id = $cat_id = $post_thumb = $post_background = $dataSticker = $dataText = "";
                $ratio = 0;  
            }
            ?>
            <script type="text/javascript">
                var next = 0;
                var Tnext = 0;
            </script>
            <div class="block">
                <div class="head">
                    <h2 class="col-md-12"><?php if($editposter) { echo "EDIT"; } else{ echo "ADD"; } ?> Poster</h2>
                </div>
                <div class="data-fluid">
                    <form method="POST" enctype="multipart/form-data" action="<?php if(empty($editposter)){ echo base_url()."site/insertposter"; } else { echo base_url()."site/updateposter"; } ?>">
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <div class="row-form">
                            <div class="col-md-2">Poster Thumb :</div>
                            <div class="col-md-10">
                                <img src="<?= base_url($post_thumb) ?>" alt="" width="50">
                                <input type='file' class="validate[required]" name='post_thumb' id="post_thumb"/>
                                <span class="bottom">Required</span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-2">Background Image:</div>
                            <div class="col-md-10">
                                <img src="<?= base_url($post_background) ?>" alt="" width="50">
                                <input type='file' class="validate[required]" name='back_image' id="back_image"/>
                                <span class="bottom">Required</span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-sm-2">Ratio:</div>
                            <div class="col-sm-3">
                                <div class="row-form">
                                    <input type="text" class="form-control" name="ratio" id="" value="<?=$ratio?>">
                                </div>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-2">Category:</div>
                            <div class="col-md-3">
                                <select name="cat_id" class="select" style="width: 100%;" required>
                                    <?php
                                    $catID = explode( ",", $cat_id );
                                    foreach ( $category as $CatVal ) {
                                        $i = 0;
                                        foreach ( $catID as $CATVal ) {
                                            if( $CATVal == $CatVal['cat_id'] ) {
                                                $i = 1;
                                            }
                                        }
                                        if( $i == 1 ) {
                                            echo '<option value="'.$CatVal['cat_id'].'" selected="selected">'.$CatVal['cat_name'].'</option>';
                                        } else {
                                            echo '<option value="'.$CatVal['cat_id'].'">'.$CatVal['cat_name'].'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-12">
                             <h3 align="center"> Sticker Info</h3>
                             <div id="fieldAAAA">
                                <div class="row-form">
                                    <!-- Text input-->
                                    <div class="col-sm-1 text-center" >
                                        Sticker ID
                                    </div>

                                    <div class="col-sm-1 text-center">
                                        X Position
                                    </div>

                                    <div class="col-sm-1 text-center">
                                        Y Position
                                    </div>

                                    <div class="col-sm-1 text-center">
                                        Width
                                    </div>

                                    <div class="col-sm-1 text-center">
                                       Height
                                   </div>

                                   <div class="col-sm-1 text-center">
                                    Rotation
                                </div>

                                <div class="col-sm-1 text-center">
                                    Resource ID
                                </div>

                                <div class="col-sm-1 text-center">
                                    Resource URI
                                </div>

                                <div class="col-sm-3 ">
                                    Image
                                </div>

                                <div class="col-sm-1 text-center">
                                    Order
                                </div>

                                <div class="col-sm-1 text-center">
                                    ADD / REMOVE
                                </div>
                                <!-- Text input-->
                            </div>
                        </div>
                        <div id="field"  class='all-slides'>
                            <?php 
                            $iJ = 0;
                            if(empty($dataSticker)){
                                ?>                                            
                                <div id="field0"  class="ui-state-default">
                                    <input type="hidden" value="1">
                                    <div class="row-form">
                                        <!-- Text input-->
                                        <div class="col-sm-1">
                                            <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                                <input class="form-control ui-spinner-input spinner" type="text" name="sticker_id[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                                            </span>
                                        </div>

                                        <div class="col-sm-1">
                                            <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                                <input class="form-control ui-spinner-input spinner" type="text" name="st_x_pos[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                                            </span>
                                        </div>

                                        <div class="col-sm-1">
                                            <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                                <input class="form-control ui-spinner-input spinner" type="text" name="st_y_pos[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                                            </span>
                                        </div>

                                        <div class="col-sm-1">
                                            <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                                <input class="form-control ui-spinner-input spinner" type="text" name="st_width[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                                            </span>
                                        </div>

                                        <div class="col-sm-1">
                                            <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                                <input class="form-control ui-spinner-input spinner" type="text" name="st_height[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                                            </span>
                                        </div>



                                        <div class="col-sm-1">
                                            <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                                <input class="form-control ui-spinner-input spinner" type="text" name="st_rotation[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton">
                                            </span>
                                        </div>

                                        <div class="col-sm-1">
                                            <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                                <input class="form-control ui-spinner-input spinner" type="text" name="st_res_id[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton"  onkeypress="return isNumber(event)">
                                            </span>
                                        </div>
                                        <div class="col-sm-1">
                                            <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                                <input class="form-control ui-spinner-input spinner" type="text" name="st_res_uri[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton"  onkeypress="return isNumber(event)">
                                            </span>
                                        </div>
                                        <div class="col-sm-3" >
                                           <input class="" type="file" name="st_image[]"  style="display: inline-block;" />
                                           <input class="form-" type="hidden" name="st_imageOLD[]" value="" />
                                       </div>

                                       <div class="col-sm-1">
                                        <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                            <input class="form-control ui-spinner-input spinner" type="text" name="st_order[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                                        </span>
                                    </div>

                                    <div class="col-sm-1">
                                        <a id="sticker-add-more" name="add-more" class="a">Add More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    else
                    {
                        foreach ($dataSticker as $dataVal) {

                            ?>
                            <script type="text/javascript">
                                next++;
                            </script>
                            <div id="field<?php echo $iJ; ?>"  class="ui-state-default">
                                <input type="hidden" value="1">
                                <div class="row-form">

                                    <div class="col-sm-1">
                                        <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                            <input class="form-control ui-spinner-input spinner" type="text" name="sticker_id[]" value="<?=$dataVal->sticker_id?>" aria-valuenow="2" autocomplete="off" role="spinbutton"   onkeypress="return isNumber(event)">
                                        </span>
                                    </div>

                                    <div class="col-sm-1">
                                        <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                            <input class="form-control ui-spinner-input spinner" type="text" name="st_x_pos[]" value="<?=$dataVal->st_x_pos?>" aria-valuenow="2" autocomplete="off" role="spinbutton"   onkeypress="return isNumber(event)">
                                        </span>
                                    </div>

                                    <div class="col-sm-1">
                                        <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                            <input class="form-control ui-spinner-input spinner" type="text" name="st_y_pos[]" value="<?=$dataVal->st_y_pos?>" aria-valuenow="2" autocomplete="off" role="spinbutton"   onkeypress="return isNumber(event)">
                                        </span>
                                    </div>

                                    <div class="col-sm-1">
                                        <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                            <input class="form-control ui-spinner-input spinner" type="text" name="st_width[]" value="<?=$dataVal->st_width?>" aria-valuenow="2" autocomplete="off" role="spinbutton"   onkeypress="return isNumber(event)">
                                        </span>
                                    </div>

                                    <div class="col-sm-1">
                                        <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                            <input class="form-control ui-spinner-input spinner" type="text" name="st_height[]" value="<?=$dataVal->st_height?>" aria-valuenow="2" autocomplete="off" role="spinbutton"   onkeypress="return isNumber(event)">
                                        </span>
                                    </div>



                                    <div class="col-sm-1">
                                        <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                            <input class="form-control ui-spinner-input spinner" type="text" name="st_rotation[]" value="<?=$dataVal->st_rotation?>" aria-valuenow="2" autocomplete="off" role="spinbutton" >
                                        </span>
                                    </div>

                                    <div class="col-sm-1">
                                        <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                            <input class="form-control ui-spinner-input spinner" type="text" name="st_res_id[]" value="<?=$dataVal->st_res_id?>" aria-valuenow="2" autocomplete="off" role="spinbutton"  onkeypress="return isNumber(event)">
                                        </span>
                                    </div>
                                    <div class="col-sm-1">
                                        <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                            <input class="form-control ui-spinner-input spinner" type="text" name="st_res_uri[]" value="<?=$dataVal->st_res_uri?>" aria-valuenow="2" autocomplete="off" role="spinbutton" onkeypress="return isNumber(event)">
                                        </span>
                                    </div>
                                    <div class="col-sm-3" >

                                     <input class="" type="file" name="st_image[]"  style="display: inline-block;" />
                                     <input class="form-" type="hidden" name="st_imageOLD[]" value="<?php echo $dataVal->st_image; ?>" />
                                     <img src="<?php echo base_url().$dataVal->st_image; ?>" width="50" style="display: inline-block;">
                                 </div>

                                 <div class="col-sm-1">
                                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                        <input class="form-control ui-spinner-input spinner" type="text" name="st_order[]" value="<?=$dataVal->st_order?>" aria-valuenow="2" autocomplete="off" role="spinbutton"   onkeypress="return isNumber(event)">
                                    </span>
                                </div>
                                <div class="col-sm-1">
                                    <?php
                                    if($iJ == 0){
                                        ?>
                                        <a id="sticker-add-more" name="sticker-add-more" class="btn btn-primary">Add More</a>
                                        <?php
                                    }else{
                                        ?>
                                        <a id="<?php echo $iJ; ?>" class="btn btn-danger st_remove" >Remove</a>
                                        <?php
                                    }
                                    ?>

                                </div>
                                
                            </div>
                        </div>
                        <?php
                        $iJ++;
                    }
                }

                ?>

            </div>

        </div>

        <!-- //////////////// Text Info Table Creation ////////////////-->
        <!-- //////////////// Text Info Table Creation ////////////////-->
        <!-- //////////////// Text Info Table Creation ////////////////-->
        <!-- //////////////// Text Info Table Creation ////////////////-->

        <div class="col-md-12">
            <div class="col-md-12">
                <h3 align="center"> Text Info</h3>
                <div id="textfieldAAAA">
                    <div class="row-form">
                        <!-- Text input-->
                        <div class="col-sm-1 text-center" >
                            Text ID
                        </div>

                        <div class="col-sm-1 text-center">
                         Text
                     </div>

                     <div class="col-sm-1 text-center">
                        Font Family
                    </div>

                    <div class="col-sm-1 text-center">
                     Colour
                 </div>

                 <div class="col-sm-1 text-center">
                   X Position
               </div>

               <div class="col-sm-1 text-center">
                Y Position
            </div>

            <div class="col-sm-1 text-center">
                Width
            </div>

            <div class="col-sm-1 text-center">
                Height
            </div>

            <div class="col-sm-1 text-center">
             Rotation
         </div>

         <div class="col-sm-1 text-center">
            Order
        </div>

        <div class="col-sm-1 text-center">
            ADD / REMOVE
        </div>
        <!-- Text input-->
    </div>
</div>
<div id="textfield"  class='all-slides'>
    <?php 
    $iT = 0;
    if(empty($dataText)){
        ?>                                            
        <div id="textfield0"  class="ui-state-default">
            <input type="hidden" value="1">
            <div class="row-form">
                <!-- Text input-->
                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="text_id[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                    </span>
                </div>

                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="text[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required>
                    </span>
                </div>

                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="font_family[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  >
                    </span>
                </div>

                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_color[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  >
                    </span>
                </div>

                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_x_pos[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                    </span>
                </div>

                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_y_pos[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                    </span>
                </div>

                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_width[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                    </span>
                </div>
                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_height[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                    </span>
                </div>
                <div class="col-sm-1" >
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_rotation[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton"  >
                    </span>
                </div>

                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_order[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                    </span>
                </div>

                <div class="col-sm-1">
                    <a id="text-add-more" name="text-add-more" class="btn btn-primary">Add More</a>
                </div>
            </div>


            <!-- Text input-->
        </div>
    </div>
    <?php
}
else
{
    foreach ($dataText as $datatVal) {

        ?>
        <script type="text/javascript">
            Tnext++;
        </script>
        <div id="textfield<?php echo $iT; ?>"  class="ui-state-default">
            <input type="hidden" value="1">
            <div class="row-form">
                <!-- Text input-->
                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="text_id[]" value="<?=$datatVal->text_id?>" aria-valuenow="2" autocomplete="off" role="spinbutton"   onkeypress="return isNumber(event)">
                    </span>
                </div>

                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="text[]" value="<?=$datatVal->text?>" aria-valuenow="2" autocomplete="off" role="spinbutton" >
                    </span>
                </div>

                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="font_family[]" value="<?=$datatVal->font_family?>" aria-valuenow="2" autocomplete="off" role="spinbutton"   >
                    </span>
                </div>

                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_color[]" value="<?=$datatVal->txt_color?>" aria-valuenow="2" autocomplete="off" role="spinbutton" >
                    </span>
                </div>

                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_x_pos[]" value="<?=$datatVal->txt_x_pos?>" aria-valuenow="2" autocomplete="off" role="spinbutton"   onkeypress="return isNumber(event)">
                    </span>
                </div>



                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_y_pos[]" value="<?=$datatVal->txt_y_pos?>" aria-valuenow="2" autocomplete="off" role="spinbutton"   onkeypress="return isNumber(event)">
                    </span>
                </div>

                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_width[]" value="<?=$datatVal->txt_width?>" aria-valuenow="2" autocomplete="off" role="spinbutton"   onkeypress="return isNumber(event)">
                    </span>
                </div>
                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_height[]" value="<?=$datatVal->txt_height?>" aria-valuenow="2" autocomplete="off" role="spinbutton"   onkeypress="return isNumber(event)">
                    </span>
                </div>
                <div class="col-sm-1" >
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_rotation[]" value="<?=$datatVal->txt_rotation?>" aria-valuenow="2" autocomplete="off" role="spinbutton" >
                    </span>
                </div>


                <div class="col-sm-1">
                    <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                        <input class="form-control ui-spinner-input spinner" type="text" name="txt_order[]" value="<?=$datatVal->txt_order?>" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">
                    </span>
                </div>
                <div class="col-sm-1">
                    <?php
                    if($iT == 0){
                        ?>
                        <a id="text-add-more" name="text-add-more" class="btn btn-primary">Add More</a>
                        <?php
                    }else{
                        ?>
                        <a id="<?php echo $iT; ?>" class="btn btn-danger text_remove" >Remove</a>
                        <?php
                    }
                    ?>

                </div>
                <!-- Text input-->
            </div>
        </div>
        <?php
        $iT++;
    }
}

?>

</div>

</div>

</div>

<div class="row-form col-sm-11 text-right">
    <?php 
    if( !empty($editposter) ) 
    { 
        ?>
        <input class="btn btn-info" type="submit" name="save" value="Update" />
        <?php 
    }
    else 
    { 
        ?>
        <input class="btn" type="submit" name="save" value="Save" />
        <?php 
    }
    ?>
</div>

</form>
</div>
</div>

</div>
</div> 

<style type="text/css">
.head{
    display: flex;
}
.head h2{
    background: #009AD7;
    padding: 15px 10px 10px 10px !important;
    color: white !important;
    text-transform: uppercase;
}
.row-form .col-sm-1{
    padding-left: 5px;
    padding-right: 5px;
    width: 7.33%;
}
</style>
<script type="text/javascript">
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 46 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>
</div>

</div>
<!-- <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/jquery.sortable.js'></script> -->
<script type="text/javascript">
    $(document).ready(function() 
    {


////////// STICKER INFO FIELD APPENDING ....//////////

$("#sticker-add-more").click(function(){

    // e.preventDefault();
    var addto = "#field" + next;
    console.log(addto);
    var addRemove = "#field" + (next);
    next = next + 1;
    var newIn = ' <div id="field'+ next +'"  class="ui-state-default" >'+
    '<div class="row-form">'+
    '<input type="hidden" value="'+ (next + 1) +'">'+
    ' <!-- Text input-->'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="sticker_id[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="st_x_pos[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="st_y_pos[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="st_width[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="st_height[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="st_rotation[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" >'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="st_res_id[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton"   onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="st_res_uri[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton"  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-3" >'+
    '<input class="" type="file" name="st_image[]"  style="display: inline-block;" />'+
    '<input class="form-" type="hidden" name="st_imageOLD[]" value="" />'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="st_order[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    ' <div class="col-sm-1">'+
    '<a id="' + next + '" class="btn btn-danger st_remove" onclick="removestfiled(' + next + ');" >Remove</a>'+
    '</div>'+
    ' <!-- Text input-->'+
    ' </div>'+
    ' </div>';

 $("#field").append(newIn);
   /* var newInput = $(newIn);

    var removeBtn = '';
    var removeButton = $(removeBtn);
    $(addto).after(newInput);
    $(addRemove).after(removeButton);
    $("#field" + next).attr('data-source',$(addto).attr('data-source'));
    $("#count").val(next);  */

    

  /*  $( "#field" ).sortable({
        connectWith: ".connectedSortable",
        stop: function(event, ui) {
            $('.connectedSortable').each(function() {
                result = "";
                // alert($(this).sortable("toArray"));
                $(this).find("li").each(function(){
                    result += $(this).text() + ",";
                });
                $("."+$(this).attr("id")+".list").html(result);
            });
        }
    });*/
});

$('.st_remove').click(function(e)
{
    e.preventDefault();
    if( next != 0 ){
        next = next - 1;
    }else{
        next = 0;
    }
    var fieldNum = this.id.charAt(this.id.length-1);
    var fieldID = "#field" + fieldNum; 
    console.log(fieldID);
    $(fieldID).remove();
});
// TEXT INFO FIELD APPENDING HERE ...//

$("#text-add-more").click(function(){
    // e.preventDefault();
    var addto = "#textfield" + Tnext;
    console.log(addto);
    var addRemove = "#textfield" + (Tnext);
    Tnext = Tnext + 1;
    var newIn = ' <div id="textfield'+ Tnext +'"  class="ui-state-default"  draggable="true">'+
    '<div class="row-form">'+
    '<input type="hidden" value="'+ (Tnext + 1) +'">'+
    ' <!-- Text input-->'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="text_id[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="text[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required>'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="font_family[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required>'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="txt_color[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required>'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="txt_x_pos[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="txt_y_pos[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="txt_width[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="txt_height[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="txt_rotation[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton">'+
    '</span>'+
    '</div>'+

    '<div class="col-sm-1">'+
    '<span class="ui-spinner ui-widget ui-widget-content ui-corner-all">'+
    '<input class="form-control ui-spinner-input spinner" type="text" name="txt_order[]" value="" aria-valuenow="2" autocomplete="off" role="spinbutton" required  onkeypress="return isNumber(event)">'+
    '</span>'+
    '</div>'+

    ' <div class="col-sm-1">'+
    '<a id="' + Tnext + '" class="btn btn-danger text_remove" onclick="removefiled(' + Tnext + ');">Remove</a>'+
    '</div>'+
    ' <!-- Text input-->'+
    ' </div>'+
    ' </div>';

    // var newInput = $(newIn);
    $("#textfield").append(newIn);
    // var removeBtn = '';
    // var removeButton = $(removeBtn);
    // $(addto).after(newInput);
    // $(addRemove).after(removeButton);
   //$("#textfield" + Tnext).attr('data-source',$(addto).attr('data-source'));
    //$("#count").val(Tnext);  



});


$('.text_remove').click(function(e)
{
    e.preventDefault();
    if( Tnext != 0 ){
        Tnext = Tnext - 1;
    }else{
        Tnext = 0;
    }
    var fieldNum = this.id.charAt(this.id.length-1);
    var fieldID = "#textfield" + fieldNum; 
    var removeid = $(this).attr("id");
    // console.log(fieldID);
    $("#textfield"+removeid).remove();
});

});

function removefiled (id){

    $("#textfield"+id).remove();
}

function removestfiled (id){

    $("#field"+id).remove();
}


</script>


