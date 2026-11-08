<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'SALEUP') }} &mdash; Sign In</title>
    <!-- Viewport for complete mobile responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#1e3a8a">
    
    <!-- Google Fonts for crisp modern typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('lte/bower_components/font-awesome/css/font-awesome.min.css') }}">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-navy: #0f172a;
            --accent-blue: #38bdf8;
            --bg-canvas: #0b1120;
            --card-bg: #ffffff;
            --text-heading: #0f172a;
            --text-body: #475569;
            --text-muted: #94a3b8;
            --input-border: #cbd5e1;
            --input-focus: #2563eb;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;
            --danger-text: #b91c1c;
            --success-bg: #f0fdf4;
            --success-border: #bbf7d0;
            --success-text: #15803d;
            --radius-lg: 20px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --shadow-card: 0 25px 60px -15px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.08);
            --transition-smooth: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-canvas);
            background-image: 
                radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.25) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(30, 58, 138, 0.3) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(15, 23, 42, 0.8) 0px, transparent 100%);
            background-attachment: fixed;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: var(--text-body);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Ambient Glow Blobs */
        .ambient-glow {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.4;
            pointer-events: none;
            z-index: 0;
        }
        .glow-1 {
            width: 320px;
            height: 320px;
            background: #2563eb;
            top: -60px;
            left: -60px;
        }
        .glow-2 {
            width: 380px;
            height: 380px;
            background: #0284c7;
            bottom: -80px;
            right: -80px;
        }

        /* Main Container */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 960px;
            margin: auto;
        }

        .login-card {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-card);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Left Hero / Brand Section */
        .hero-section {
            background: linear-gradient(145deg, #0f172a 0%, #1e3a8a 55%, #1d4ed8 100%);
            color: #ffffff;
            padding: 48px 42px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.5 0H0V1.5V28.5V30H1.5H28.5H30V28.5V1.5V0H28.5H1.5ZM28.5 28.5H1.5V1.5H28.5V28.5Z' fill='rgba(255,255,255,0.02)'/%3E%3C/svg%3E");
            opacity: 0.6;
            pointer-events: none;
        }

        .hero-brand {
            position: relative;
            z-index: 1;
        }

        .logo-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 14px 22px;
            border-radius: 14px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
            margin-bottom: 24px;
            max-width: 250px;
        }

        .company-logo {
            max-width: 100%;
            height: auto;
            max-height: 52px;
            display: block;
            object-fit: contain;
        }

        .hero-title {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.25;
            margin-bottom: 10px;
            color: #ffffff;
        }

        .hero-title span {
            background: linear-gradient(90deg, #93c5fd, #67e8f9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 14px;
            color: #cbd5e1;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        /* Features List */
        .hero-features {
            list-style: none;
            position: relative;
            z-index: 1;
            margin: 12px 0 32px 0;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .hero-features li {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 13.5px;
            color: #e2e8f0;
            font-weight: 500;
        }

        .feature-icon-box {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #38bdf8;
            font-size: 14px;
            flex-shrink: 0;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Hero Footer */
        .hero-footer {
            position: relative;
            z-index: 1;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: #94a3b8;
        }

        .hero-footer .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #93c5fd;
            font-weight: 600;
        }

        /* Right Form Section */
        .form-section {
            background: #ffffff;
            padding: 48px 42px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 28px;
        }

        .form-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-heading);
            letter-spacing: -0.4px;
            margin-bottom: 6px;
        }

        .form-header p {
            font-size: 14px;
            color: var(--text-muted);
        }

        /* Alerts */
        .alert-box {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            line-height: 1.45;
            margin-bottom: 22px;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-danger {
            background-color: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: var(--danger-text);
        }

        .alert-danger i {
            color: #dc2626;
            font-size: 16px;
            margin-top: 1px;
            flex-shrink: 0;
        }

        .alert-info {
            background-color: var(--success-bg);
            border: 1px solid var(--success-border);
            color: var(--success-text);
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 16px;
            color: #94a3b8;
            font-size: 15px;
            pointer-events: none;
            transition: var(--transition-smooth);
        }

        .form-input {
            width: 100%;
            height: 48px;
            padding: 0 46px 0 44px;
            font-size: 15px;
            font-family: inherit;
            color: var(--text-heading);
            background-color: #f8fafc;
            border: 1.5px solid var(--input-border);
            border-radius: var(--radius-md);
            outline: none;
            transition: var(--transition-smooth);
            -webkit-appearance: none;
        }

        .form-input:hover {
            border-color: #94a3b8;
            background-color: #ffffff;
        }

        .form-input:focus {
            border-color: var(--input-focus);
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .form-input:focus + .input-icon-left,
        .input-wrapper:focus-within .input-icon-left {
            color: var(--primary);
        }

        /* Password Toggle Button */
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 15px;
            padding: 8px;
            cursor: pointer;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
        }

        .toggle-password:hover {
            color: var(--text-heading);
            background-color: rgba(0, 0, 0, 0.04);
        }

        .toggle-password:focus {
            outline: none;
            color: var(--primary);
        }

        /* Checkbox & Options */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 13.5px;
        }

        .remember-checkbox {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            color: #475569;
            font-weight: 500;
        }

        .remember-checkbox input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: var(--primary);
            cursor: pointer;
            border-radius: 4px;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            color: #ffffff;
            border: none;
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-1px);
            box-shadow: 0 14px 24px -5px rgba(37, 99, 235, 0.5);
        }

        .btn-submit:active {
            transform: translateY(1px);
            box-shadow: 0 6px 14px -3px rgba(37, 99, 235, 0.35);
        }

        .btn-submit:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Footer Note */
        .form-footer-note {
            text-align: center;
            margin-top: 24px;
            font-size: 12.5px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .form-footer-note i {
            color: #10b981;
        }

        /* ==============================================================
           MOBILE & TABLET RESPONSIVENESS
           ============================================================== */
        @media (max-width: 860px) {
            body {
                padding: 16px 12px;
                align-items: center;
            }

            .login-card {
                grid-template-columns: 1fr;
                max-width: 480px;
                border-radius: 18px;
            }

            .hero-section {
                padding: 32px 24px 24px 24px;
                text-align: center;
                align-items: center;
            }

            .logo-badge {
                margin: 0 auto 16px auto;
                padding: 10px 18px;
                max-width: 200px;
            }

            .company-logo {
                max-height: 44px;
            }

            .hero-title {
                font-size: 22px;
                margin-bottom: 6px;
            }

            .hero-subtitle {
                font-size: 13px;
                margin-bottom: 0;
            }

            /* Hide heavy feature list on mobile to keep form readily visible */
            .hero-features,
            .hero-footer {
                display: none;
            }

            .form-section {
                padding: 32px 24px 32px 24px;
            }

            .form-header {
                text-align: center;
                margin-bottom: 22px;
            }

            .form-header h2 {
                font-size: 21px;
            }

            .form-input {
                font-size: 16px; /* Prevents auto-zoom on iOS */
            }
        }

        @media (max-width: 420px) {
            body {
                padding: 10px 8px;
            }

            .login-card {
                border-radius: 14px;
            }

            .hero-section {
                padding: 24px 18px 18px 18px;
            }

            .logo-badge {
                max-width: 170px;
                padding: 8px 14px;
            }

            .hero-title {
                font-size: 20px;
            }

            .form-section {
                padding: 24px 18px 24px 18px;
            }

            .form-input {
                height: 46px;
                padding-left: 40px;
            }

            .input-icon-left {
                left: 14px;
            }

            .btn-submit {
                height: 46px;
                font-size: 14.5px;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient decorative glows for depth -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <div class="login-wrapper">
        <div class="login-card">
            
            <!-- Left Hero / Branding Column -->
            <div class="hero-section">
                <div class="hero-brand">
                    <div class="logo-badge">
                        <img 
                            class="company-logo" 
                            src="{{ asset('custom_theme/images/lalshan.png') }}" 
                            alt="{{ config('app.name', 'SALEUP') }}"
                            onerror="this.onerror=null; this.src='{{ asset('custom_theme/images/ekt.png') }}';"
                        >
                    </div>

                    <h1 class="hero-title">
                        <span>SALEUP</span> CRM
                    </h1>
                    <p class="hero-subtitle">
                        Distribution Management &amp; Customer Relations Platform
                    </p>
                </div>

                <!-- Feature bullet points (visible on desktop) -->
                <ul class="hero-features">
                    <li>
                        <div class="feature-icon-box">
                            <i class="fa fa-line-chart"></i>
                        </div>
                        <span>Real-time Orders &amp; Follow-up Management</span>
                    </li>
                    <li>
                        <div class="feature-icon-box">
                            <i class="fa fa-users"></i>
                        </div>
                        <span>Team Hierarchy &amp; Client Tracking</span>
                    </li>
                    <li>
                        <div class="feature-icon-box">
                            <i class="fa fa-shield"></i>
                        </div>
                        <span>Zero-Leak Auditing &amp; Security Shield</span>
                    </li>
                </ul>

                <!-- Hero Footer -->
                <div class="hero-footer">
                    <span>&copy; {{ date('Y') }} {{ config('app.name', 'SALEUP') }}</span>
                    <div class="security-badge">
                        <i class="fa fa-lock"></i>
                        <span>SSL Secure</span>
                    </div>
                </div>
            </div>

            <!-- Right Form Column -->
            <div class="form-section">
                <div class="form-header">
                    <h2>Sign In</h2>
                    <p>Enter your credentials to access your dashboard</p>
                </div>

                <!-- Error Alert Box -->
                @if ($errors->any())
                    <div class="alert-box alert-danger" role="alert">
                        <i class="fa fa-exclamation-circle"></i>
                        <div>
                            <strong>Authentication Failed</strong><br>
                            {{ $errors->first() }}
                        </div>
                    </div>
                @endif

                <!-- Session Flash Message -->
                @if (session('status'))
                    <div class="alert-box alert-info" role="alert">
                        <i class="fa fa-info-circle"></i>
                        <div>{{ session('status') }}</div>
                    </div>
                @endif

                <!-- Login Form -->
                <form class="login-form" id="loginForm" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <!-- Username Field -->
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="form-input" 
                                placeholder="Enter username" 
                                value="{{ old('username') }}" 
                                required 
                                autofocus 
                                autocomplete="username"
                                spellcheck="false"
                                autocapitalize="none"
                            >
                            <i class="fa fa-user input-icon-left"></i>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input" 
                                placeholder="Enter password" 
                                required 
                                autocomplete="current-password"
                            >
                            <i class="fa fa-lock input-icon-left"></i>
                            <button 
                                type="button" 
                                class="toggle-password" 
                                id="togglePasswordBtn" 
                                aria-label="Toggle password visibility"
                                tabindex="-1"
                            >
                                <i class="fa fa-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Options (Remember me) -->
                    <div class="form-options">
                        <label class="remember-checkbox" for="remember">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                id="remember" 
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <span>Remember me</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <span id="btnText">Sign In</span>
                        <i class="fa fa-sign-in" id="btnIcon"></i>
                    </button>
                </form>

                <!-- Security Note -->
                <div class="form-footer-note">
                    <i class="fa fa-check-circle"></i>
                    <span>Protected with Enterprise Activity Monitoring</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Interactive Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Password Visibility Toggle
            var toggleBtn = document.getElementById('togglePasswordBtn');
            var passwordInput = document.getElementById('password');
            var toggleIcon = document.getElementById('togglePasswordIcon');

            if (toggleBtn && passwordInput && toggleIcon) {
                toggleBtn.addEventListener('click', function () {
                    var isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    toggleIcon.className = isPassword ? 'fa fa-eye-slash' : 'fa fa-eye';
                });
            }

            // 2. Double-Submit Prevention & Loading State
            var form = document.getElementById('loginForm');
            var submitBtn = document.getElementById('btnSubmit');
            var btnText = document.getElementById('btnText');
            var btnIcon = document.getElementById('btnIcon');

            if (form && submitBtn) {
                form.addEventListener('submit', function () {
                    submitBtn.disabled = true;
                    if (btnText) btnText.textContent = 'Authenticating...';
                    if (btnIcon) btnIcon.className = 'fa fa-circle-o-notch fa-spin';
                });
            }

            // 3. Smart Autofocus
            var usernameInput = document.getElementById('username');
            if (usernameInput && usernameInput.value) {
                if (passwordInput) passwordInput.focus();
            } else if (usernameInput) {
                usernameInput.focus();
            }
        });
    </script>
</body>
</html>