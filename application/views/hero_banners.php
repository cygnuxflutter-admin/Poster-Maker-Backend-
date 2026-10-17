<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <span class="ico-layout-7"></span>
            </div>
            <h1>Hero Banners (Top Slider) <small> <span class="cgreen">Active Now </span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li class="active">Hero Banners</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="<?php echo (isset($app_id) && $app_id == 1) ? 'active' : ''; ?>"><a href="<?php echo base_url('site/hero_banners?app_id=1'); ?>">App 1 (Default)</a></li>
                <li class="<?php echo (isset($app_id) && $app_id == 2) ? 'active' : ''; ?>"><a href="<?php echo base_url('site/hero_banners?app_id=2'); ?>">App 2</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- Add New Form -->
        <div class="col-md-4">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-plus"></span></div>
                    <h2>Add New Hero Banner</h2>
                </div>
                <div class="data-fluid">
                    <form action="<?php echo base_url('site/insert_hero'); ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="app_id" value="<?php echo isset($app_id) ? $app_id : 1; ?>">
                        <div class="row-form">
                            <div class="col-md-12"><strong>Upload Hero Poster (Wide Image):</strong></div>
                            <div class="col-md-12"><input type="file" name="banner_image" required accept="image/*"></div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-12"><strong>Select Category (Auto-fills Action URL):</strong></div>
                            <div class="col-md-12">
                                <select id="cat_select_add" class="form-control" onchange="if(this.value){ $('#action_url_add').val('category/' + this.value); }">
                                    <option value="">-- Choose Category --</option>
                                    <?php if(isset($categories) && !empty($categories)) { foreach($categories as $cat) { ?>
                                        <option value="<?php echo $cat['cat_id']; ?>"><?php echo $cat['cat_id']; ?> - <?php echo $cat['cat_name']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-12"><strong>Action URL (Optional, link to open when clicked):</strong></div>
                            <div class="col-md-12">
                                <input type="text" name="action_url" id="action_url_add" placeholder="e.g. category/53" class="form-control">
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="col-md-12"><strong>Sequence Number:</strong></div>
                            <div class="col-md-12"><input type="number" name="sequence_number" value="0" required class="form-control"></div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-12"><button type="submit" class="btn btn-primary">Upload Hero Banner</button></div>
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
                    <h2>Active Hero Banners</h2>                                               
                </div> 
                <div class="data-fluid">
                    <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Sequence</th>
                                <th>Hero Banner Image</th>
                                <th>Action URL</th>
                                <th class="TAC">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="row_position">
                            <?php if($data) { foreach ($data as $value) { ?>
                            <tr id="<?php echo $value['id']; ?>">
                                <td class="seq_num" style="text-align: center;"><?php echo $value['sequence_number']; ?></td>
                                <td><img src="<?= base_url($value['banner_image']) ?>" alt="" class="img img-thumbnail" width="150"></td>
                                <td><?php echo $value['action_url']; ?></td>
                                <td class="TAC">
                                    <a href="javascript:void(0);" onclick='editHero(<?php echo json_encode($value); ?>)' class="button green" title="Edit Hero Banner">
                                        <div class="icon"><span class="ico-pencil"></span></div>
                                    </a>
                                    <a href="<?php echo base_url('site/delete_hero/'.$value['id']); ?>" class="button red" onclick="return confirm('Delete this Hero Banner?');" title="Delete">
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

<!-- Edit Hero Modal -->
<div class="modal fade" id="editHeroModal" tabindex="-1" role="dialog" aria-labelledby="editHeroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo base_url('site/update_hero'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="editHeroModalLabel">Edit Hero Banner</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="app_id" value="<?php echo isset($app_id) ? $app_id : 1; ?>">
                    <input type="hidden" name="id" id="edit_hero_id">
                    
                    <div class="row-form" style="margin-bottom: 15px;">
                        <div class="col-md-12"><strong>Current Banner Image:</strong></div>
                        <div class="col-md-12">
                            <img id="edit_hero_preview" src="" class="img img-thumbnail" width="150" style="margin-bottom: 10px; display: block;">
                            <input type="file" name="banner_image" accept="image/*">
                            <small class="text-muted">Leave blank to keep current image</small>
                        </div>
                    </div>

                    <div class="row-form" style="margin-bottom: 15px;">
                        <div class="col-md-12"><strong>Select Category (Auto-fills Action URL):</strong></div>
                        <div class="col-md-12">
                            <select id="cat_select_edit" class="form-control" onchange="if(this.value){ $('#edit_action_url').val('category/' + this.value); }">
                                <option value="">-- Choose Category --</option>
                                <?php if(isset($categories) && !empty($categories)) { foreach($categories as $cat) { ?>
                                    <option value="<?php echo $cat['cat_id']; ?>"><?php echo $cat['cat_id']; ?> - <?php echo $cat['cat_name']; ?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row-form" style="margin-bottom: 15px;">
                        <div class="col-md-12"><strong>Action URL:</strong></div>
                        <div class="col-md-12">
                            <input type="text" name="action_url" id="edit_action_url" placeholder="e.g. category/53" class="form-control">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Hero Banner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function editHero(heroData) {
        $('#edit_hero_id').val(heroData.id);
        $('#edit_hero_preview').attr('src', '<?php echo base_url(); ?>' + heroData.banner_image);
        $('#edit_action_url').val(heroData.action_url);
        $('#edit_sequence_number').val(heroData.sequence_number);

        // Match category in dropdown if action_url is category/XX
        if (heroData.action_url && heroData.action_url.indexOf('category/') === 0) {
            var catId = heroData.action_url.replace('category/', '');
            $('#cat_select_edit').val(catId);
        } else {
            $('#cat_select_edit').val('');
        }

        $('#editHeroModal').modal('show');
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
                    url: '<?php echo base_url("site/hero_sorting"); ?>',
                    type: 'post',
                    data: {position: selectedData},
                    success: function(res) {
                        console.log('Hero banner order saved successfully');
                    }
                });
            }
        });
    });
</script>
