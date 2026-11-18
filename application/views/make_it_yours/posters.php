<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <i class="fa-solid fa-paintbrush"></i>
            </div>
            <h1>MIY Posters <small><span class="cgreen"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Active</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li class="active">Make It Yours Posters</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-images"></i> Make It Yours Posters</h2>
                    <ul class="buttons">
                        <li>
                            <a href="<?php echo base_url(); ?>MakeItYours/add_poster" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Add MIY Poster
                            </a>
                        </li>
                    </ul>                                                   
                </div> 

                <div class="data-fluid">
                    <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="8%">ID</th>
                                <th width="28%">Title</th>
                                <th width="20%">Category</th>
                                <th width="14%">Poster</th>
                                <th width="14%">Thumbnail</th>
                                <th width="10%">Status</th>
                                <th width="12%" class="TAC">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($posters)){ foreach ($posters as $value) { ?>
                            <tr>
                                <td><strong style="color: #64748b;">#<?=$value['id']?></strong></td>
                                <td><strong style="color: #0f172a; font-size: 14px;"><?php echo ucfirst($value['title']); ?></strong></td>
                                <td><span class="label label-info"><?php echo ucfirst($value['category_name']); ?></span></td>
                                <td>
                                    <?php if(!empty($value['poster_image'])): ?>
                                    <a href="<?= base_url($value['poster_image']) ?>" target="_blank"><img src="<?= base_url($value['poster_image']) ?>" alt="" class="img img-thumbnail" width="50" style="border-radius: 8px;"></a>
                                    <?php else: ?>
                                    <span style="color: #94a3b8;">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($value['thumbnail_image'])): ?>
                                    <img src="<?= base_url($value['thumbnail_image']) ?>" alt="" class="img img-thumbnail" width="50" style="border-radius: 8px;">
                                    <?php else: ?>
                                    <span style="color: #94a3b8;">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($value['is_published'] == 1): ?>
                                        <span class="label label-success">Published</span>
                                    <?php else: ?>
                                        <span class="label label-danger">Unpublished</span>
                                    <?php endif; ?>
                                </td>
                                <td class="TAC">
                                    <a href="<?php echo base_url()."MakeItYours/add_poster?edit=".$value['id']; ?>" class="button green" title="Edit Poster">
                                        <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                    </a>
                                    <a href="<?php echo base_url()."MakeItYours/delete_poster/".$value['id']; ?>" class="button red" onclick="return confirm('Are you sure you want to delete this poster?');" title="Delete Poster">
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
