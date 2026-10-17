<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <span class="ico-images"></span>
            </div>
            <h1>MIY Posters <small> <span class="cgreen">Active Now </span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Make It Yours Posters</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="block">
            <div class="head blue">
                <div class="icon"><span class="ico-images"></span></div>
                <h2>MIY Posters</h2>
                <ul class="buttons">
                    <li><a href="<?php echo base_url(); ?>MakeItYours/add_poster"><div class="icon"><span class="ico-add"></span></div></a></li>
                </ul>                                                   
            </div> 

          <div class="data-fluid">
            <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th width="">Title</th>
                        <th width="">Category</th>
                        <th width="">Poster</th>
                        <th width="">Thumbnail</th>
                        <th>Status</th>
                        <th width="" class="TAC">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($posters)){ foreach ($posters as $value) { ?>
                    <tr>
                        <td><?=$value['id']?></td>
                        <td><?php echo ucfirst($value['title']); ?></td>
                        <td><?php echo ucfirst($value['category_name']); ?></td>
                        <td>
                            <?php if(!empty($value['poster_image'])): ?>
                            <a href="<?= base_url($value['poster_image']) ?>" target="_blank"><img src="<?= base_url($value['poster_image']) ?>" alt="" class="img img-thumbnail" width=50></a>
                            <?php else: ?>
                            N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(!empty($value['thumbnail_image'])): ?>
                            <img src="<?= base_url($value['thumbnail_image']) ?>" alt="" class="img img-thumbnail" width=50>
                            <?php else: ?>
                            N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($value['is_published'] == 1): ?>
                                <span class="label label-success">Published</span>
                            <?php else: ?>
                                <span class="label label-danger">Unpublished</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo base_url()."MakeItYours/add_poster?edit=".$value['id']; ?>" class="button green">
                                <div class="icon"><span class="ico-pencil"></span></div>
                            </a>
                            <a href="<?php echo base_url()."MakeItYours/delete_poster/".$value['id']; ?>" class="button red" onclick="return confirm('Are you sure you want to delete this poster?');">
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
