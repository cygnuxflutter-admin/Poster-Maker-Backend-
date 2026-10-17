<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <span class="ico-layout-7"></span>
            </div>
            <h1>Trending Festivals <small> <span class="cgreen">Active Now </span></small></h1>
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
                <div class="head blue">
                    <div class="icon"><span class="ico-plus"></span></div>
                    <h2>Add Trending Festival</h2>
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
                        <div class="row-form">
                            <div class="col-md-12"><button type="submit" class="btn btn-primary">Upload & Add to Trending</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="col-md-8">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-layout-9"></span></div>
                    <h2>Active Trending Festivals</h2>                                               
                </div> 
                <div class="data-fluid">
                    <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Sequence</th>
                                <th>Festival Name</th>
                                <th>Custom Banner Image</th>
                                <th class="TAC">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="row_position">
                            <?php if($data) { foreach ($data as $value) { ?>
                            <tr id="<?php echo $value['id']; ?>">
                                <td class="seq_num" style="text-align: center;"><?php echo $value['sequence_number']; ?></td>
                                <td><?php echo ucfirst($value['cat_name']); ?></td>
                                <td><img src="<?= base_url($value['banner_image']) ?>" alt="" class="img img-thumbnail" width="100"></td>
                                <td class="TAC">
                                    <a href="javascript:void(0);" onclick='editTrending(<?php echo json_encode($value); ?>)' class="button green" title="Edit Trending Festival">
                                        <div class="icon"><span class="ico-pencil"></span></div>
                                    </a>
                                    <a href="<?php echo base_url('site/delete_trending/'.$value['id']); ?>" class="button red" onclick="return confirm('Delete this from trending?');" title="Delete">
                                        <div class="icon"><span class="ico-remove"></span></div>
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
                    <h4 class="modal-title" id="editTrendingModalLabel">Edit Trending Festival</h4>
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
                            <img id="edit_trending_preview" src="" class="img img-thumbnail" width="120" style="margin-bottom: 10px; display: block;">
                            <input type="file" name="banner_image" accept="image/*">
                            <small class="text-muted">Leave blank to keep current image</small>
                        </div>
                    </div>

                    <div class="row-form" style="margin-bottom: 15px;">
                        <div class="col-md-12"><strong>Sequence Number:</strong></div>
                        <div class="col-md-12">
                            <input type="number" name="sequence_number" id="edit_trending_sequence_number" required class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Trending Festival</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function editTrending(trendingData) {
        $('#edit_trending_id').val(trendingData.id);
        $('#edit_trending_cat_id').val(trendingData.cat_id);
        $('#edit_trending_preview').attr('src', '<?php echo base_url(); ?>' + trendingData.banner_image);
        $('#edit_trending_sequence_number').val(trendingData.sequence_number);
        $('#editTrendingModal').modal('show');
    }

    $(document).ready(function() {
        $(".row_position").sortable({
            delay: 150,
            stop: function() {
                var selectedData = new Array();
                $('.row_position>tr').each(function(index) {
                    $(this).find('.seq_num').text(index);
                    selectedData.push($(this).attr("id"));
                });
                $.ajax({
                    url: '<?php echo base_url("site/trending_sorting"); ?>',
                    type: 'post',
                    data: {position: selectedData},
                    success: function(res) {
                        console.log('Trending festival order saved successfully');
                    }
                });
            }
        });
    });
</script>
