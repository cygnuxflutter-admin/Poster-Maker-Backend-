 <div class="sidebar">
    <div class="top">
        <a href="<?php echo base_url(); ?>site" class="logo"></a>
    </div>
    
    <?php 
    $getUser = $this->db->where('user_id',$this->session->userdata('poster_sess_user_id'))->get('login_user')->result_array();
    $menu_id = (isset($getUser[0]) && isset($getUser[0]['menu_id'])) ? $getUser[0]['menu_id'] : '1,2,3,4';
    $menu = explode(",",$menu_id);

    ?>
    <div class="nContainer">
        <ul class="navigation">  
            <?php 
            for($i=0;$i<sizeof($menu);$i++)
            {
              if($menu[$i]=='1')
              {
                 ?>
                 <li class="active">
                    <a href="<?php echo base_url(); ?>site/category" class="blred">Poster Category</a>
                </li>
                <?php 
            }
            if($menu[$i]=='2'){ 
                ?>
                <li class="active">
                    <a href="<?php echo base_url(); ?>site/poster" class="blblue">Poster Templates</a>
                </li>
                <?php
            }

            if($menu[$i]=='3'){
                ?>
                <li class="active">
                    <a href="<?php echo base_url(); ?>Api_key" class="blyellow">Key</a>
                </li>
            <?php }
            if($menu[$i]=='4'){
                ?>
                <li class="active">
                    <a href="<?php echo base_url(); ?>user" class="blgreen">Users</a>
                </li>
                <?php 
            }

            /*if($menu[$i]=='3')
            {
                ?>
                <li class="active">
                    <a href="<?php echo base_url(); ?>site/bgCategory" class="blgreen">Background Category</a>
                </li>
                <?php 
            }

            if($menu[$i]=='4')
            {
                ?>
                <li class="active">
                    <a href="<?php echo base_url(); ?>site/posterbackground" class="blgreen">Background Image</a>
                </li>
                <?php 
            }*/
            
        } ?>
            <li class="active">
                <a href="<?php echo base_url(); ?>site/trending_festivals" class="blred">🔥 Trending Festivals</a>
            </li>
            <li class="active">
                <a href="<?php echo base_url(); ?>site/hero_banners" class="blyellow">🌟 Hero Banners</a>
            </li>
            
            <li class="active">
                <a href="<?php echo base_url(); ?>MakeItYours/categories" class="blblue">🎨 MIY Categories</a>
            </li>
            <li class="active">
                <a href="<?php echo base_url(); ?>MakeItYours/posters" class="blgreen">🎨 MIY Posters</a>
            </li>
    </ul>
</div>
</div>
<div class="body">

    <ul class="navigation">                          
        <li>
            <div class="user">
                <a href="#" class="name">
                    <span><?php echo ucfirst($this->session->userdata('poster_sess_user_name')); ?></span>
                    <!-- <span class="sm">Administrator</span> -->
                </a>
            </div>
            <div class="buttons">
                <div class="sbutton green navButton">
                    <a href="#"><span class="ico-align-justify"></span></a>
                </div>
                <div >
                    <a href="<?php echo base_url(); ?>admin/logout"><img src="<?php echo base_url(); ?>assets/img/logout.png" alt="" class="sbutton" style="background: none;"></a>
                </div>                        
            </div>
        </li>                
    </ul>