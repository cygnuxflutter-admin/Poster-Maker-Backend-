<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <span class="ico-layout-7"></span>
            </div>
            <h1>MIY Categories <small> <span class="cgreen">Active Now </span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Make It Yours Categories</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="block">
            <div class="head blue">
                <div class="icon"><span class="ico-layout-9"></span></div>
                <h2>MIY Categories</h2>
                <ul class="buttons">
                    <li><a href="<?php echo base_url(); ?>MakeItYours/add_category"><div class="icon"><span class="ico-add"></span></div></a></li>
                </ul>                                                   
            </div> 

          <div class="data-fluid">
            <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th width="">Category Name</th>
                        <th width="">Thumbnail</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th width="" class="TAC">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($categories)){ foreach ($categories as $value) { ?>
                    <tr>
                        <td><?=$value['id']?></td>
                        <td><?php echo ucfirst($value['name']); ?></td>
                        <td>
                            <?php if(!empty($value['image'])): ?>
                            <img src="<?= base_url($value['image']) ?>" alt="" class="img img-thumbnail" width=50>
                            <?php else: ?>
                            N/A
                            <?php endif; ?>
                        </td>
                        <td class="seq_num" style="text-align: center"><?php echo $value['sort_order']; ?></td>
                        <td>
                            <?php if($value['is_active'] == 1): ?>
                                <span class="label label-success">Active</span>
                            <?php else: ?>
                                <span class="label label-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo base_url()."MakeItYours/add_category?edit=".$value['id']; ?>" class="button green">
                                <div class="icon"><span class="ico-pencil"></span></div>
                            </a>
                            <a href="<?php echo base_url()."MakeItYours/delete_category/".$value['id']; ?>" class="button red" onclick="return confirm('Are you sure you want to delete this category? All posters inside will be deleted.');">
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
