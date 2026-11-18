<?php
$username = ucfirst($this->session->userdata('poster_sess_user_name') ?? $this->session->userdata('username') ?? 'Admin');
$totalCategories = $this->db->table_exists('category') ? $this->db->count_all('category') : 0;
$totalPosters = $this->db->table_exists('poster') ? $this->db->count_all('poster') : 0;
$totalTrending = $this->db->table_exists('trending_festivals') ? $this->db->count_all('trending_festivals') : 0;
$totalHero = $this->db->table_exists('hero_banners') ? $this->db->count_all('hero_banners') : 0;
$totalUsers = $this->db->table_exists('login_user') ? $this->db->count_all('login_user') : 0;
$totalMiy = $this->db->table_exists('make_it_yours_categories') ? $this->db->count_all('make_it_yours_categories') : 0;
?>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <i class="fa-solid fa-gauge-high"></i>
            </div>
            <h1>Dashboard <small><span class="cgreen"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> System Active</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li class="active">Dashboard</li>
        </ul>
        <div class="clear"></div>
    </div>

    <!-- Welcome Hero Banner -->
    <div class="dashboard-hero-banner" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%); border-radius: 16px; padding: 28px 32px; color: #ffffff; margin-bottom: 28px; box-shadow: 0 10px 25px -5px rgba(67, 56, 202, 0.3); position: relative; overflow: hidden;">
        <div style="position: absolute; right: -30px; top: -30px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
        <div style="position: relative; z-index: 2; max-width: 720px;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); padding: 4px 12px; border-radius: 9999px; font-size: 11.5px; font-weight: 600; margin-bottom: 14px; letter-spacing: 0.03em;">
                <i class="fa-solid fa-sparkles"></i> Poster Maker Pro Management
            </div>
            <h2 style="color: #ffffff; font-size: 26px; font-weight: 800; margin: 0 0 10px 0; letter-spacing: -0.02em;">
                Welcome back, <?php echo $username; ?>! 👋
            </h2>
            <p style="color: #c7d2fe; font-size: 14px; margin: 0 0 20px 0; line-height: 1.6;">
                Manage your poster templates, festival banners, customization packs, and automated push notifications with the all-new modern control console.
            </p>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="<?php echo base_url(); ?>site/showaddposterform" class="btn btn-primary" style="background: #ffffff !important; color: #4338ca !important; border: none !important; font-weight: 700; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    <i class="fa-solid fa-plus"></i> Add New Poster
                </a>
                <a href="<?php echo base_url(); ?>PushNotification" class="btn" style="background: rgba(255,255,255,0.15) !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important; font-weight: 600;">
                    <i class="fa-solid fa-bell"></i> Push Campaigns
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stat Cards Grid -->
    <div class="row" style="margin-left: -10px; margin-right: -10px; margin-bottom: 12px;">
        <!-- Card 1: Poster Categories -->
        <div class="col-md-3 col-sm-6" style="padding: 10px;">
            <div class="block" style="padding: 22px; border-radius: 14px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 0;">
                <div>
                    <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">Categories</span>
                    <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;"><?php echo number_format($totalCategories); ?></h3>
                    <a href="<?php echo base_url(); ?>site/category" style="font-size: 12px; font-weight: 600; color: #4f46e5; display: inline-flex; align-items: center; gap: 4px;">
                        Manage categories <i class="fa-solid fa-arrow-right" style="font-size: 10px;"></i>
                    </a>
                </div>
                <div style="width: 52px; height: 52px; border-radius: 14px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Poster Templates -->
        <div class="col-md-3 col-sm-6" style="padding: 10px;">
            <div class="block" style="padding: 22px; border-radius: 14px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 0;">
                <div>
                    <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">Templates</span>
                    <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;"><?php echo number_format($totalPosters); ?></h3>
                    <a href="<?php echo base_url(); ?>site/poster" style="font-size: 12px; font-weight: 600; color: #0284c7; display: inline-flex; align-items: center; gap: 4px;">
                        View templates <i class="fa-solid fa-arrow-right" style="font-size: 10px;"></i>
                    </a>
                </div>
                <div style="width: 52px; height: 52px; border-radius: 14px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fa-solid fa-images"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Trending Festivals -->
        <div class="col-md-3 col-sm-6" style="padding: 10px;">
            <div class="block" style="padding: 22px; border-radius: 14px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 0;">
                <div>
                    <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">Festivals</span>
                    <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;"><?php echo number_format($totalTrending); ?></h3>
                    <a href="<?php echo base_url(); ?>site/trending_festivals" style="font-size: 12px; font-weight: 600; color: #ea580c; display: inline-flex; align-items: center; gap: 4px;">
                        Manage festivals <i class="fa-solid fa-arrow-right" style="font-size: 10px;"></i>
                    </a>
                </div>
                <div style="width: 52px; height: 52px; border-radius: 14px; background: #ffedd5; color: #ea580c; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fa-solid fa-fire"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Hero Banners -->
        <div class="col-md-3 col-sm-6" style="padding: 10px;">
            <div class="block" style="padding: 22px; border-radius: 14px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 0;">
                <div>
                    <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">Hero Banners</span>
                    <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;"><?php echo number_format($totalHero); ?></h3>
                    <a href="<?php echo base_url(); ?>site/hero_banners" style="font-size: 12px; font-weight: 600; color: #d97706; display: inline-flex; align-items: center; gap: 4px;">
                        Configure banners <i class="fa-solid fa-arrow-right" style="font-size: 10px;"></i>
                    </a>
                </div>
                <div style="width: 52px; height: 52px; border-radius: 14px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fa-solid fa-panorama"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Shortcuts & System Overview -->
    <div class="row" style="margin-left: -10px; margin-right: -10px;">
        <div class="col-md-8" style="padding: 10px;">
            <div class="block">
                <div class="head">
                    <div class="icon"><i class="fa-solid fa-bolt"></i></div>
                    <h2>Quick Action Shortcuts</h2>
                </div>
                <div class="data-fluid">
                    <div class="row" style="margin-left: -8px; margin-right: -8px;">
                        <div class="col-sm-6" style="padding: 8px;">
                            <a href="<?php echo base_url(); ?>site/showaddposterform" style="display: flex; align-items: center; gap: 14px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px; text-decoration: none; color: inherit; transition: all 0.2s ease; background: #ffffff;" onmouseover="this.style.borderColor='#818cf8'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)';">
                                <div style="width: 42px; height: 42px; border-radius: 10px; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div>
                                    <strong style="display: block; font-size: 14px; color: #0f172a;">Upload Poster Template</strong>
                                    <span style="font-size: 12px; color: #64748b;">Add new backgrounds, stickers & text layers</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6" style="padding: 8px;">
                            <a href="<?php echo base_url(); ?>site/showaddcatform" style="display: flex; align-items: center; gap: 14px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px; text-decoration: none; color: inherit; transition: all 0.2s ease; background: #ffffff;" onmouseover="this.style.borderColor='#818cf8'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)';">
                                <div style="width: 42px; height: 42px; border-radius: 10px; background: #dbeafe; color: #1d4ed8; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                    <i class="fa-solid fa-folder-plus"></i>
                                </div>
                                <div>
                                    <strong style="display: block; font-size: 14px; color: #0f172a;">Create New Category</strong>
                                    <span style="font-size: 12px; color: #64748b;">Organize posters into searchable groups</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6" style="padding: 8px;">
                            <a href="<?php echo base_url(); ?>PushNotification" style="display: flex; align-items: center; gap: 14px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px; text-decoration: none; color: inherit; transition: all 0.2s ease; background: #ffffff;" onmouseover="this.style.borderColor='#818cf8'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)';">
                                <div style="width: 42px; height: 42px; border-radius: 10px; background: #f3e8ff; color: #7e22ce; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </div>
                                <div>
                                    <strong style="display: block; font-size: 14px; color: #0f172a;">Send Push Notification</strong>
                                    <span style="font-size: 12px; color: #64748b;">Automated & custom daily notifications</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6" style="padding: 8px;">
                            <a href="<?php echo base_url(); ?>MakeItYours/categories" style="display: flex; align-items: center; gap: 14px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px; text-decoration: none; color: inherit; transition: all 0.2s ease; background: #ffffff;" onmouseover="this.style.borderColor='#818cf8'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)';">
                                <div style="width: 42px; height: 42px; border-radius: 10px; background: #ecfdf5; color: #047857; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                    <i class="fa-solid fa-palette"></i>
                                </div>
                                <div>
                                    <strong style="display: block; font-size: 14px; color: #0f172a;">Make It Yours (MIY)</strong>
                                    <span style="font-size: 12px; color: #64748b;">Custom frame and sticker assets</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4" style="padding: 10px;">
            <div class="block">
                <div class="head">
                    <div class="icon"><i class="fa-solid fa-server"></i></div>
                    <h2>Environment & Status</h2>
                </div>
                <div class="data-fluid">
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px;">
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="color: #64748b;">Database Connection</span>
                            <span class="label label-success"><i class="fa-solid fa-circle-check"></i> Connected</span>
                        </li>
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="color: #64748b;">Timezone</span>
                            <strong style="color: #0f172a;">Asia/Kolkata</strong>
                        </li>
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="color: #64748b;">Active Admin Users</span>
                            <strong style="color: #0f172a;"><?php echo $totalUsers; ?> user(s)</strong>
                        </li>
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0;">
                            <span style="color: #64748b;">Admin Security</span>
                            <span class="label label-info"><i class="fa-solid fa-shield-halved"></i> Active</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
