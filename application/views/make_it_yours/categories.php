<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <i class="fa-solid fa-palette"></i>
            </div>
            <h1>MIY Categories <small><span class="cgreen"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Active</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li class="active">Make It Yours Categories</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="head">
                    <h2><i class="fa-solid fa-swatchbook"></i> Make It Yours Categories</h2>
                    <ul class="buttons">
                        <li>
                            <a href="<?php echo base_url(); ?>MakeItYours/add_category" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Add MIY Category
                            </a>
                        </li>
                    </ul>                                                   
                </div> 

                <div class="data-fluid">
                    <table class="table fpTable lcnp" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="8%">ID</th>
                                <th width="35%">Category Name</th>
                                <th width="20%">Thumbnail</th>
                                <th width="15%" style="text-align: center;">Sort Order</th>
                                <th width="10%">Status</th>
                                <th width="12%" class="TAC">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($categories)){ foreach ($categories as $value) { ?>
                            <tr>
                                <td><strong style="color: #64748b;">#<?=$value['id']?></strong></td>
                                <td><strong style="color: #0f172a; font-size: 14px;"><?php echo ucfirst($value['name']); ?></strong></td>
                                <td>
                                    <?php if(!empty($value['image'])): ?>
                                    <img src="<?= base_url($value['image']) ?>" alt="" class="img img-thumbnail" width="50" style="border-radius: 8px;">
                                    <?php else: ?>
                                    <span style="color: #94a3b8;">N/A</span>
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
                                <td class="TAC">
                                    <a href="<?php echo base_url()."MakeItYours/add_category?edit=".$value['id']; ?>" class="button green" title="Edit Category">
                                        <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                    </a>
                                    <a href="<?php echo base_url()."MakeItYours/delete_category/".$value['id']; ?>" class="button red" onclick="return confirm('Are you sure you want to delete this category? All posters inside will be deleted.');" title="Delete Category">
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
