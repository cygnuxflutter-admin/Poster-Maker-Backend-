<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <i class="fa-solid fa-fire"></i>
            </div>
            <h1>Trending Festivals <small><span class="cgreen"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Active</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li class="active">Trending Festivals</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="<?php echo (isset($app_id) && $app_id == 1) ? 'active' : ''; ?>"><a href="<?php echo base_url('site/trending_festivals?app_id=1'); ?>">App 1 (Default)</a></li>
                <li class="<?php echo (isset($app_id) && $app_id == 2) ? 'active' : ''; ?>"><a href="<?php echo base_url('site/trending_festivals?app_id=2'); ?>">App 2</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- Add New Form -->
        <div class="col-md-4">
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-plus-circle"></i> Add Trending Festival</h2>
                </div>
                <div class="data-fluid">
                    <form action="<?php echo base_url('site/insert_trending'); ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="app_id" value="<?php echo isset($app_id) ? $app_id : 1; ?>">
                        <div class="row-form">
                            <div class="col-md-12"><strong>Select Category:</strong></div>
                            <div class="col-md-12">
                                <select name="cat_id" class="form-control" required>
                                    <option value="">-- Choose Festival --</option>
                                    <?php if(isset($categories) && !empty($categories)) { foreach($categories as $cat) { ?>
                                        <option value="<?php echo $cat['cat_id']; ?>"><?php echo $cat['cat_name']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-12"><strong>Upload Custom Poster Banner:</strong></div>
                            <div class="col-md-12"><input type="file" name="banner_image" required accept="image/*"></div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-12"><strong>Sequence Number:</strong></div>
                            <div class="col-md-12"><input type="number" name="sequence_number" value="0" required class="form-control"></div>
                        </div>
                        <div class="row-form" style="padding-top: 18px;">
                            <div class="col-md-12"><button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-cloud-arrow-up"></i> Upload & Add to Trending</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="col-md-8">
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-list-check"></i> Active Trending Festivals</h2>                                               
                </div> 
                <div class="data-fluid">
                    <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="15%" style="text-align: center;">Sequence</th>
                                <th width="35%">Festival Name</th>
                                <th width="30%">Custom Banner</th>
                                <th width="20%" class="TAC">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="row_position">
                            <?php if($data) { foreach ($data as $value) { ?>
                            <tr id="<?php echo $value['id']; ?>">
                                <td class="seq_num" style="text-align: center;"><?php echo $value['sequence_number']; ?></td>
                                <td><strong style="color: #0f172a; font-size: 14px;"><?php echo ucfirst($value['cat_name']); ?></strong></td>
                                <td><img src="<?= base_url($value['banner_image']) ?>" alt="" class="img img-thumbnail" width="100" style="border-radius: 8px;"></td>
                                <td class="TAC">
                                    <a href="javascript:void(0);" onclick='editTrending(<?php echo json_encode($value); ?>)' class="button green" title="Edit Trending Festival">
                                        <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                    </a>
                                    <a href="<?php echo base_url('site/delete_trending/'.$value['id']); ?>" class="button red" onclick="return confirm('Delete this from trending?');" title="Delete">
                                        <div class="icon"><i class="fa-solid fa-trash"></i></div>
                                    </a>
                                </td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>                    
                </div> 
            </div>                       
        </div>
    </div>  
</div>

<!-- Edit Trending Modal -->
<div class="modal fade" id="editTrendingModal" tabindex="-1" role="dialog" aria-labelledby="editTrendingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo base_url('site/update_trending'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="editTrendingModalLabel"><i class="fa-solid fa-pen-to-square" style="color: #4f46e5; margin-right: 8px;"></i> Edit Trending Festival</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="app_id" value="<?php echo isset($app_id) ? $app_id : 1; ?>">
                    <input type="hidden" name="id" id="edit_trending_id">

                    <div class="row-form" style="margin-bottom: 15px;">
                        <div class="col-md-12"><strong>Select Category:</strong></div>
                        <div class="col-md-12">
                            <select name="cat_id" id="edit_trending_cat_id" class="form-control" required>
                                <option value="">-- Choose Festival --</option>
                                <?php if(isset($categories) && !empty($categories)) { foreach($categories as $cat) { ?>
                                    <option value="<?php echo $cat['cat_id']; ?>"><?php echo $cat['cat_name']; ?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row-form" style="margin-bottom: 15px;">
                        <div class="col-md-12"><strong>Current Banner Image:</strong></div>
                        <div class="col-md-12">
                            <img id="edit_trending_preview" src="" class="img img-thumbnail" width="120" style="margin-bottom: 10px; display: block; border-radius: 8px;">
                            <input type="file" name="banner_image" accept="image/*">
                            <small class="text-muted">Leave blank to keep current image</small>
                        </div>
                    </div>

                    <div class="row-form" style="margin-bottom: 15px;">
                        <div class="col-md-12"><strong>Sequence Number:</strong></div>
                        <div class="col-md-12">
                            <input type="number" name="sequence_number" id="edit_sequence_number" required class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Update Trending Festival</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type='text/javascript' src='<?php echo base_url(); ?>assets/js/jquery.sortable.js'></script>
<script type="text/javascript">
    function editTrending(data) {
        $('#edit_trending_id').val(data.id);
        $('#edit_trending_cat_id').val(data.cat_id);
        $('#edit_sequence_number').val(data.sequence_number);
        $('#edit_trending_preview').attr('src', '<?php echo base_url(); ?>' + data.banner_image);
        $('#editTrendingModal').modal('show');
    }

    $( ".row_position" ).sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $('.row_position>tr').each(function(index) {
                $(this).find('.seq_num').text(index);
                selectedData.push($(this).attr("id"));
            });
            updateOrder(selectedData);
        }
    });
    function updateOrder(data) {
        $.ajax({           
            url:'<?php echo base_url() ?>Site/trending_sorting',
            type:'post',
            data:{position:data},
            success:function(res){
                console.log('Order updated');
            }
        })
    }
</script>
