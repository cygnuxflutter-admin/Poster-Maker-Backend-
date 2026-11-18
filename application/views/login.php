<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />    
    <title>Login - Poster Maker Admin</title>

    <!-- Modern Typography & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Core Stylesheets -->
    <link href="<?php echo base_url(); ?>assets/css/stylesheets.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/modern-theme.css" rel="stylesheet" type="text/css" />

    <!-- Scripts for validation & interactions -->
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/jquery/jquery-1.10.2.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/validationEngine/languages/jquery.validationEngine-en.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>assets/js/plugins/validationEngine/jquery.validationEngine.js'></script>    

    <style>
        /* -------------------------------------------------------------
           THEME VARIABLES & COLOR PALETTES
           ------------------------------------------------------------- */
        :root {
            /* Default: Modern Royal Blue Light Theme */
            --login-bg: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 50%, #dbeafe 100%);
            --login-card-bg: #ffffff;
            --login-card-border: #e2e8f0;
            --login-card-shadow: 0 20px 45px -12px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(15, 23, 42, 0.04);
            --brand-gradient: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            --brand-glow: rgba(37, 99, 235, 0.35);
            --title-color: #0f172a;
            --subtitle-color: #64748b;
            --label-color: #334155;
            --input-bg: #f8fafc;
            --input-border: #cbd5e1;
            --input-color: #0f172a;
            --input-placeholder: #94a3b8;
            --input-icon-color: #64748b;
            --input-focus-border: #2563eb;
            --input-focus-ring: rgba(37, 99, 235, 0.15);
            --btn-gradient: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            --btn-hover: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            --btn-shadow: rgba(37, 99, 235, 0.35);
            --footer-text: #64748b;
            --blob-1: #bfdbfe;
            --blob-2: #c7d2fe;
        }

        /* Emerald Mint Theme */
        body.theme-emerald {
            --login-bg: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #ccfbf1 100%);
            --login-card-bg: #ffffff;
            --login-card-border: #bbf7d0;
            --login-card-shadow: 0 20px 45px -12px rgba(6, 78, 59, 0.08), 0 0 0 1px rgba(6, 78, 59, 0.04);
            --brand-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
            --brand-glow: rgba(16, 185, 129, 0.35);
            --title-color: #064e3b;
            --subtitle-color: #047857;
            --label-color: #065f46;
            --input-bg: #f0fdf4;
            --input-border: #a7f3d0;
            --input-color: #064e3b;
            --input-placeholder: #6ee7b7;
            --input-icon-color: #059669;
            --input-focus-border: #059669;
            --input-focus-ring: rgba(5, 150, 105, 0.15);
            --btn-gradient: linear-gradient(135deg, #059669 0%, #047857 100%);
            --btn-hover: linear-gradient(135deg, #047857 0%, #064e3b 100%);
            --btn-shadow: rgba(5, 150, 105, 0.35);
            --footer-text: #059669;
            --blob-1: #a7f3d0;
            --blob-2: #99f6e4;
        }

        /* Vivid Indigo / Violet Theme */
        body.theme-indigo {
            --login-bg: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #e0e7ff 100%);
            --login-card-bg: #ffffff;
            --login-card-border: #ddd6fe;
            --login-card-shadow: 0 20px 45px -12px rgba(76, 29, 149, 0.08), 0 0 0 1px rgba(76, 29, 149, 0.04);
            --brand-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            --brand-glow: rgba(99, 102, 241, 0.35);
            --title-color: #1e1b4b;
            --subtitle-color: #6d28d9;
            --label-color: #4338ca;
            --input-bg: #f5f3ff;
            --input-border: #c4b5fd;
            --input-color: #1e1b4b;
            --input-placeholder: #a78bfa;
            --input-icon-color: #7c3aed;
            --input-focus-border: #6366f1;
            --input-focus-ring: rgba(99, 102, 241, 0.18);
            --btn-gradient: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
            --btn-hover: linear-gradient(135deg, #4f46e5 0%, #6d28d9 100%);
            --btn-shadow: rgba(99, 102, 241, 0.35);
            --footer-text: #6d28d9;
            --blob-1: #c4b5fd;
            --blob-2: #ddd6fe;
        }

        /* Midnight Slate (Dark Theme with Crystal-Clear Contrast) */
        body.theme-dark {
            --login-bg: radial-gradient(circle at 10% 20%, #0f172a 0%, #020617 90%);
            --login-card-bg: rgba(30, 41, 59, 0.95);
            --login-card-border: rgba(255, 255, 255, 0.12);
            --login-card-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            --brand-gradient: linear-gradient(135deg, #38bdf8 0%, #6366f1 100%);
            --brand-glow: rgba(56, 189, 248, 0.45);
            --title-color: #ffffff;
            --subtitle-color: #94a3b8;
            --label-color: #e2e8f0;
            --input-bg: #0f172a;
            --input-border: #334155;
            --input-color: #ffffff;
            --input-placeholder: #64748b;
            --input-icon-color: #38bdf8;
            --input-focus-border: #38bdf8;
            --input-focus-ring: rgba(56, 189, 248, 0.25);
            --btn-gradient: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            --btn-hover: linear-gradient(135deg, #0284c7 0%, #1d4ed8 100%);
            --btn-shadow: rgba(14, 165, 233, 0.45);
            --footer-text: #94a3b8;
            --blob-1: rgba(56, 189, 248, 0.2);
            --blob-2: rgba(99, 102, 241, 0.2);
        }

        /* -------------------------------------------------------------
           PAGE BASE & LAYOUT
           ------------------------------------------------------------- */
        body.login-page-modern {
            margin: 0 !important;
            padding: 20px !important;
            min-height: 100vh !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background: var(--login-bg) !important;
            position: relative !important;
            overflow: hidden !important;
            transition: background 0.3s ease;
        }

        /* Ambient Glow Blobs */
        .ambient-blob {
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.55;
            pointer-events: none;
            z-index: 0;
            transition: background 0.3s ease;
        }
        .blob-1 {
            top: -100px;
            left: -100px;
            background: var(--blob-1);
        }
        .blob-2 {
            bottom: -100px;
            right: -100px;
            background: var(--blob-2);
        }

        /* Card Container */
        .login-card-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            margin: auto;
        }

        .login-card {
            background: var(--login-card-bg) !important;
            border: 1px solid var(--login-card-border) !important;
            border-radius: 24px !important;
            padding: 38px 36px 32px !important;
            box-shadow: var(--login-card-shadow) !important;
            transition: all 0.3s ease;
            position: relative;
        }

        /* Theme Switcher Header */
        .theme-switcher-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px dashed var(--login-card-border);
        }

        .theme-switcher-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--subtitle-color);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .theme-pills {
            display: flex;
            gap: 7px;
        }

        .theme-pill-btn {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            outline: 1px solid var(--input-border);
            cursor: pointer;
            transition: transform 0.2s ease, outline-color 0.2s ease;
            padding: 0;
        }
        .theme-pill-btn:hover {
            transform: scale(1.2);
        }
        .theme-pill-btn.active {
            outline: 2px solid var(--title-color);
            transform: scale(1.25);
        }

        .pill-blue { background: #2563eb; }
        .pill-emerald { background: #059669; }
        .pill-indigo { background: #7c3aed; }
        .pill-dark { background: #0f172a; }

        /* Brand Header */
        .login-brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-logo-badge {
            width: 54px;
            height: 54px;
            margin: 0 auto 14px;
            border-radius: 16px;
            background: var(--brand-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #ffffff;
            box-shadow: 0 8px 20px var(--brand-glow);
            transition: all 0.3s ease;
        }

        .login-title {
            font-size: 24px !important;
            font-weight: 800 !important;
            color: var(--title-color) !important;
            margin: 0 0 6px 0 !important;
            letter-spacing: -0.02em !important;
            line-height: 1.2 !important;
        }

        .login-subtitle {
            font-size: 13px !important;
            color: var(--subtitle-color) !important;
            margin: 0 !important;
            line-height: 1.5 !important;
        }

        /* Error Alert */
        .login-error-alert {
            background: #fee2e2 !important;
            border: 1px solid #fca5a5 !important;
            border-radius: 10px !important;
            padding: 11px 14px !important;
            color: #b91c1c !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            margin-bottom: 20px !important;
        }

        /* Form Groups */
        .form-group-modern {
            margin-bottom: 18px !important;
            text-align: left !important;
        }

        .form-label-modern {
            display: block !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            color: var(--label-color) !important;
            margin-bottom: 7px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }

        /* Input Wrapper - High Specificity to PREVENT OVERLAPS */
        .input-wrapper {
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .input-icon {
            position: absolute !important;
            left: 16px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: var(--input-icon-color) !important;
            font-size: 16px !important;
            width: 16px !important;
            text-align: center !important;
            pointer-events: none !important;
            z-index: 10 !important;
            line-height: 1 !important;
            transition: color 0.2s ease;
        }

        /* Login Input: Forced 48px Left Padding so text never collides with icon */
        body.login-page-modern .login-input,
        body.login-page-modern input.login-input,
        body.login-page-modern input[type="text"].login-input,
        body.login-page-modern input[type="password"].login-input {
            width: 100% !important;
            height: 48px !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            padding-left: 48px !important;   /* <--- GENEROUS GAP: Icon is at 16px, text starts at 48px */
            padding-right: 44px !important;
            background: var(--input-bg) !important;
            border: 1.5px solid var(--input-border) !important;
            border-radius: 12px !important;
            color: var(--input-color) !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            outline: none !important;
            box-shadow: none !important;
            box-sizing: border-box !important;
            line-height: normal !important;
            transition: all 0.2s ease !important;
        }

        body.login-page-modern .login-input::placeholder {
            color: var(--input-placeholder) !important;
            opacity: 1 !important;
        }

        body.login-page-modern .login-input:focus {
            border-color: var(--input-focus-border) !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 4px var(--input-focus-ring) !important;
        }

        body.login-page-modern .input-wrapper:focus-within .input-icon {
            color: var(--input-focus-border) !important;
        }

        /* Password Toggle Button */
        .password-toggle-btn {
            position: absolute !important;
            right: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            background: none !important;
            border: none !important;
            color: var(--input-icon-color) !important;
            cursor: pointer !important;
            padding: 8px !important;
            font-size: 15px !important;
            line-height: 1 !important;
            z-index: 10 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .password-toggle-btn:hover {
            color: var(--title-color) !important;
        }

        /* Submit Button */
        .login-submit-btn {
            width: 100% !important;
            height: 48px !important;
            margin-top: 10px !important;
            border-radius: 12px !important;
            background: var(--btn-gradient) !important;
            color: #ffffff !important;
            font-size: 14.5px !important;
            font-weight: 700 !important;
            border: none !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            box-shadow: 0 4px 14px var(--btn-shadow) !important;
            transition: all 0.2s ease !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }

        .login-submit-btn:hover {
            background: var(--btn-hover) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 22px var(--btn-shadow) !important;
            color: #ffffff !important;
        }

        .login-submit-btn:active {
            transform: translateY(0) !important;
        }

        /* Footer Info */
        .login-footer-info {
            text-align: center;
            margin-top: 22px;
            font-size: 12px;
            color: var(--footer-text);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-weight: 500;
        }
    </style>
</head>
<body class="login-page-modern">
    <!-- Ambient Glow Background Blobs -->
    <div class="ambient-blob blob-1"></div>
    <div class="ambient-blob blob-2"></div>

    <?php 
    if($this->session->userdata('poster_sess_user_name') != null){ 
        redirect('admin/showAdmin');
    } else { 
    ?>

    <div class="login-card-container">
        <div class="login-card">
            <!-- Theme Color Switcher Bar -->
            <div class="theme-switcher-bar">
                <span class="theme-switcher-label">
                    <i class="fa-solid fa-palette"></i> Color Theme:
                </span>
                <div class="theme-pills">
                    <button type="button" class="theme-pill-btn pill-blue active" onclick="setLoginTheme('blue')" title="Royal Blue (Clean Light)"></button>
                    <button type="button" class="theme-pill-btn pill-emerald" onclick="setLoginTheme('emerald')" title="Emerald Mint (Fresh Light)"></button>
                    <button type="button" class="theme-pill-btn pill-indigo" onclick="setLoginTheme('indigo')" title="Modern Indigo (Violet Light)"></button>
                    <button type="button" class="theme-pill-btn pill-dark" onclick="setLoginTheme('dark')" title="Midnight Slate (Dark Theme)"></button>
                </div>
            </div>

            <!-- Brand Header -->
            <div class="login-brand-header">
                <div class="login-logo-badge">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <h1 class="login-title">Poster Maker</h1>
                <p class="login-subtitle">Sign in to access your administrative control panel</p>
            </div>

            <?php if($this->session->flashdata('error')): ?>
                <div class="login-error-alert" id="error_text">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?php echo $this->session->flashdata('error'); ?></span>
                </div>
            <?php endif; ?>

            <form id="validate" method="POST" action="<?php echo base_url()."admin/authenticate" ?>">
                <div class="form-group-modern">
                    <label class="form-label-modern" for="username">Username</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-user input-icon"></i>
                        <input type="text" id="username" class="login-input validate[required]" placeholder="Enter username" name="username" required autocomplete="username" />
                    </div>
                </div>

                <div class="form-group-modern">
                    <label class="form-label-modern" for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" id="password" class="login-input validate[required]" placeholder="Enter password" name="password" required autocomplete="current-password" />
                        <button type="button" class="password-toggle-btn" id="togglePasswordBtn" aria-label="Toggle password visibility">
                            <i class="fa-regular fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-submit-btn" name="submit">
                    <span>Sign In</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <div class="login-footer-info">
                <i class="fa-solid fa-shield-halved" style="color: #10b981;"></i>
                <span>Secure 256-bit Encrypted Session</span>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    function setLoginTheme(theme) {
        var body = document.body;
        body.classList.remove('theme-blue', 'theme-emerald', 'theme-indigo', 'theme-dark');
        if(theme !== 'blue') {
            body.classList.add('theme-' + theme);
        }
        
        // Update active pill button
        var pills = document.querySelectorAll('.theme-pill-btn');
        pills.forEach(function(pill){ pill.classList.remove('active'); });
        var activePill = document.querySelector('.pill-' + theme);
        if(activePill) activePill.classList.add('active');

        // Persist selection
        try {
            localStorage.setItem('poster_admin_theme', theme);
        } catch(e){}
    }

    $(document).ready(function(){
        $("#validate").validationEngine();

        // Restore saved theme
        try {
            var savedTheme = localStorage.getItem('poster_admin_theme');
            if(savedTheme && ['blue', 'emerald', 'indigo', 'dark'].indexOf(savedTheme) !== -1) {
                setLoginTheme(savedTheme);
            }
        } catch(e){}
        
        // Password Show/Hide Toggle
        var passwordInput = document.getElementById('password');
        var toggleBtn = document.getElementById('togglePasswordBtn');
        var toggleIcon = document.getElementById('togglePasswordIcon');
        
        if(toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function(e){
                e.preventDefault();
                if(passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                }
            });
        }
    });
    </script>

    <?php } ?>
</body>
</html>
