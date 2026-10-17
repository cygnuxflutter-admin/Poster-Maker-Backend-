    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>

    <script type="text/javascript">
      $(document).ready(function(){
       $(".table").on("click", ".delete", function()
       {
        var catID= $(this).attr('id');
        $( "#dialog-confirm" ).dialog({
          resizable: false,
          height: "auto",
          width: 400,
          modal: true,
          buttons: {
            "Yes": function() {

              $( this ).dialog( "close" );
              $.ajax({
                url:'<?php echo base_url(); ?>site/deletecat',
                dataType: 'json',
                method:'post',
                data:{cat_id:catID},
                success:function(data){
                  alert(data);
                  location.reload();
                }
              });
            },
            No: function() {
              $(this).dialog( "close" );
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
        <h1>Fonts <small><?php echo ucfirst($this->session->userdata('invi_sess_user_name'));?>: <span class="cgreen">Active Now </span><span class="cgreen ico-circle" style="font-size: 8px;"></span></small></h1>
      </div>
      <ul class="breadcrumb">
        <li>
          <a href="/">Home</a>
        </li>
        <li class="active">Fonts</li>
      </ul>
      <div class="clear"></div>
    </div>
    <?php 
    if($notFound > 0){
      ?>
      <div class="alert alert-warning alert-dismissible" style="background-color: #e41f1fcc;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <h4><strong><?=$notFound?> </strong> Font files Not Found in Font Panel !</h4>
      </div>
    <?php }?>
    <div class="row">
      <div class="block">
        <div class="head blue">
          <div class="icon"><span class="ico-layout-9"></span></div>
          <h2>Fonts</h2>
          <ul class="buttons">
            <li>
             <button style="background: none;border: none;" onclick=""><div class="icon"><span class="ico-download"></span></div><h4 style="display: inline">Download</h4></button>
           </li>
         </ul>                                                   
       </div> 

       <div id="dialog-confirm" title="Delete Models?" style="display: none;">
        <p style="margin:10px; font-size: 16px; color: red"><span class="ui-icon ui-icon-alert" style="float:left; margin:10px; font-size: 18px;"></span>This Category will be permanently deleted. Are you sure?</p>
      </div>

      <div class="data-fluid">
        <table class="table lcnp" cellpadding="0" cellspacing="0" width="100%" id="fonttable">
          <thead>
            <tr>
              <th></th>
              <th>ID</th>
              <th width="">Android Fonts Name</th>
              <th width="">iOS Fonts Name</th>
            </tr>
          </thead>
          <tbody>

            <?php if($data){ foreach ($data as $value) { ?>
              <tr>
                <td></td>
                <td><?=$value->id?></td>
                <td><?=$value->android_font?></td>
                <td><?=$value->ios_font ?></td>
              </tr>
            <?php } } ?>                          
          </tbody>
        </table>                    
      </div> 
    </div>                       
  </div>
</div>  


<script type="text/javascript">
  $(document).ready(function(){
    var buttonCommon = {
      exportOptions: {
        format: {
          body: function ( data, row, column, node ) {
                    // Strip $ from salary column to make it numeric
                    return column === 5 ?
                    data.replace( /[$,]/g, '' ) :
                    data;
                  }
                }
              }
            };

            $('#fonttable').DataTable( {
              "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
              dom: 'Bfrtip',
              lengthMenu: [
              [ 10, 25, 50, -1 ],
              [ '10 rows', '25 rows', '50 rows', 'Show all' ]
              ],
              buttons: [
               'pageLength',
              $.extend( true, {}, buttonCommon, {
                extend: 'copyHtml5',
                 title: 'Fonts List'
              } ),
              $.extend( true, {}, buttonCommon, {
                extend: 'excelHtml5',
                title: 'Fonts List'
              } ),
              $.extend( true, {}, buttonCommon, {
                extend: 'pdfHtml5',
                title: 'Fonts List'
              } )
              ]
            } );
          });
        </script>
