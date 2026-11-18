<?php 
$getUser = $this->db->where('user_id',$this->session->userdata('poster_sess_user_id'))->get('login_user')->result_array();
$menu_id = (isset($getUser[0]) && isset($getUser[0]['menu_id'])) ? $getUser[0]['menu_id'] : '1,2,3,4';
$menu = explode(",",$menu_id);

$s1 = strtolower($this->uri->segment(1) ?? '');
$s2 = strtolower($this->uri->segment(2) ?? '');
$username = ucfirst($this->session->userdata('poster_sess_user_name') ?? 'Admin');
$initial = strtoupper(substr($username, 0, 1));
?>

<div class="sidebar" id="mainSidebar">
    <!-- Modern Brand Header -->
    <a href="<?php echo base_url(); ?>admin/showAdmin" class="sidebar-brand">
        <div class="brand-icon-box">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>
        <div class="brand-info">
            <h1 class="brand-title">Poster Maker</h1>
            <span class="brand-subtitle">Pro Admin Console</span>
        </div>
    </a>
    
    <div class="nContainer">
        <!-- Section: Content Management -->
        <span class="sidebar-section-title">Core Management</span>
        <ul class="navigation">  
            <?php if (in_array('1', $menu)) { 
                $isCat = ($s1 == 'site' && in_array($s2, ['category', 'showaddcatform', 'editcat']));
            ?>
            <li class="<?php echo $isCat ? 'nav-active current-page' : ''; ?>">
                <a href="<?php echo base_url(); ?>site/category">
                    <i class="fa-solid fa-layer-group nav-icon"></i>
                    <span>Poster Category</span>
                </a>
            </li>
            <?php } ?>

            <?php if (in_array('2', $menu)) { 
                $isPoster = ($s1 == 'site' && in_array($s2, ['poster', 'showaddposterform', 'editposter']));
            ?>
            <li class="<?php echo $isPoster ? 'nav-active current-page' : ''; ?>">
                <a href="<?php echo base_url(); ?>site/poster">
                    <i class="fa-solid fa-images nav-icon"></i>
                    <span>Poster Templates</span>
                </a>
            </li>
            <?php } ?>

            <?php 
            $isFestivals = ($s1 == 'site' && $s2 == 'trending_festivals');
            ?>
            <li class="<?php echo $isFestivals ? 'nav-active current-page' : ''; ?>">
                <a href="<?php echo base_url(); ?>site/trending_festivals">
                    <i class="fa-solid fa-fire nav-icon"></i>
                    <span>Trending Festivals</span>
                    <span class="nav-badge nav-badge-fire">Hot</span>
                </a>
            </li>

            <?php 
            $isHero = ($s1 == 'site' && $s2 == 'hero_banners');
            ?>
            <li class="<?php echo $isHero ? 'nav-active current-page' : ''; ?>">
                <a href="<?php echo base_url(); ?>site/hero_banners">
                    <i class="fa-solid fa-panorama nav-icon"></i>
                    <span>Hero Banners</span>
                    <span class="nav-badge nav-badge-star">Top</span>
                </a>
            </li>
        </ul>

        <!-- Section: Customization -->
        <span class="sidebar-section-title">Customization (MIY)</span>
        <ul class="navigation">
            <?php 
            $isMiyCat = ($s1 == 'makeityours' && in_array($s2, ['categories', 'add_category']));
            $isMiyPost = ($s1 == 'makeityours' && in_array($s2, ['posters', 'add_poster']));
            ?>
            <li class="<?php echo $isMiyCat ? 'nav-active current-page' : ''; ?>">
                <a href="<?php echo base_url(); ?>MakeItYours/categories">
                    <i class="fa-solid fa-palette nav-icon"></i>
                    <span>MIY Categories</span>
                </a>
            </li>
            <li class="<?php echo $isMiyPost ? 'nav-active current-page' : ''; ?>">
                <a href="<?php echo base_url(); ?>MakeItYours/posters">
                    <i class="fa-solid fa-swatchbook nav-icon"></i>
                    <span>MIY Posters</span>
                </a>
            </li>
        </ul>

        <!-- Section: Notifications -->
        <span class="sidebar-section-title">Marketing & FCM</span>
        <ul class="navigation">
            <?php 
            $isPush = ($s1 == 'pushnotification');
            $isRtoPush = ($s1 == 'rtopushnotification');
            ?>
            <li class="<?php echo $isPush ? 'nav-active current-page' : ''; ?>">
                <a href="<?php echo base_url(); ?>PushNotification">
                    <i class="fa-solid fa-bell nav-icon"></i>
                    <span>Push Notifications</span>
                    <span class="nav-badge nav-badge-bell">FCM</span>
                </a>
            </li>
            <li class="<?php echo $isRtoPush ? 'nav-active current-page' : ''; ?>">
                <a href="<?php echo base_url(); ?>RtoPushNotification">
                    <i class="fa-solid fa-car-side nav-icon"></i>
                    <span>RTO Push System</span>
                </a>
            </li>
        </ul>

        <!-- Section: System & Settings -->
        <span class="sidebar-section-title">Settings & Access</span>
        <ul class="navigation">
            <?php if (in_array('3', $menu)) { 
                $isKey = ($s1 == 'api_key');
            ?>
            <li class="<?php echo $isKey ? 'nav-active current-page' : ''; ?>">
                <a href="<?php echo base_url(); ?>Api_key">
                    <i class="fa-solid fa-key nav-icon"></i>
                    <span>API Keys</span>
                </a>
            </li>
            <?php } ?>

            <?php if (in_array('4', $menu)) { 
                $isUser = ($s1 == 'user');
            ?>
            <li class="<?php echo $isUser ? 'nav-active current-page' : ''; ?>">
                <a href="<?php echo base_url(); ?>user">
                    <i class="fa-solid fa-users-gear nav-icon"></i>
                    <span>User Management</span>
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>

    <!-- Sidebar Bottom User Card -->
    <div class="sidebar-bottom-card">
        <div class="sidebar-avatar">
            <?php echo $initial; ?>
        </div>
        <div class="sidebar-user-details">
            <span class="sidebar-user-name"><?php echo $username; ?></span>
            <span class="sidebar-user-role">Administrator</span>
        </div>
    </div>
</div>

<div class="body">
    <!-- Modern Sticky Top Navbar -->
    <header class="top-navbar">
        <div class="top-navbar-left">
            <button type="button" class="mobile-nav-toggle" id="toggleMobileSidebar" aria-label="Toggle menu">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="top-page-badge">
                <span class="top-status-dot"></span>
                <span>Poster Maker Admin &bull; Live</span>
            </div>
        </div>
        
        <div class="top-navbar-right">
            <div class="user-profile-pill">
                <div class="user-avatar-circle">
                    <?php echo $initial; ?>
                </div>
                <span class="user-name-text"><?php echo $username; ?></span>
            </div>
            
            <a href="<?php echo base_url(); ?>admin/logout" class="btn-logout-modern" title="Sign out of Admin Panel">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </div>
    </header>

    <!-- Backward compatibility hooks for legacy actions.js (hidden) -->
    <ul class="navigation" style="display: none !important;">                          
        <li>
            <div class="user">
                <a href="#" class="name"><span><?php echo $username; ?></span></a>
            </div>
            <div class="buttons">
                <div class="sbutton green navButton"><a href="#"><span class="ico-align-justify"></span></a></div>
                <div><a href="<?php echo base_url(); ?>admin/logout"></a></div>                        
            </div>
        </li>                
    </ul>

    <script type="text/javascript">
    (function(){
        // Mobile Sidebar Toggle
        var toggleBtn = document.getElementById('toggleMobileSidebar');
        var sidebar = document.getElementById('mainSidebar');
        var backdrop = document.getElementById('sidebarBackdrop');
        if(toggleBtn && sidebar && backdrop) {
            toggleBtn.addEventListener('click', function(){
                sidebar.classList.toggle('mobile-open');
                backdrop.classList.toggle('active');
            });
            backdrop.addEventListener('click', function(){
                sidebar.classList.remove('mobile-open');
                backdrop.classList.remove('active');
            });
        }
    })();
    </script>
