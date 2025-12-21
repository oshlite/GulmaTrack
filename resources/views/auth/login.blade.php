<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - GulmaTrack</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #197B40 0%, #0D5C2E 100%);
            position: relative;
            overflow: hidden;
        }

        /* Animated Background Elements */
        .login-wrapper::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            animation: float 6s ease-in-out infinite;
        }

        .login-wrapper::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(214, 223, 32, 0.05);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideInUp 0.6s ease;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header Section */
        .login-header {
            background: linear-gradient(135deg, #197B40 0%, #0D5C2E 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
            position: relative;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, #D6DF20, transparent);
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: rgba(214, 223, 32, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(214, 223, 32, 0.3);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(214, 223, 32, 0);
            }
        }

        .logo-icon {
            font-size: 48px;
            color: #D6DF20;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 500;
        }

        /* Content Section */
        .login-content {
            padding: 40px;
        }

        /* Alert Messages */
        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            border-left: 4px solid;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: slideIn 0.4s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border-left-color: #c62828;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left-color: #2e7d32;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #197B40;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.3px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-group input::placeholder {
            color: #999;
            font-weight: 500;
        }

        .form-group input:focus {
            outline: none;
            border-color: #197B40;
            background: white;
            box-shadow: 0 0 0 4px rgba(25, 123, 64, 0.1);
            transform: translateY(-2px);
        }

        /* Remember Me */
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            gap: 12px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #197B40;
            border-radius: 4px;
        }

        .remember-me label {
            margin: 0;
            cursor: pointer;
            color: #555;
            font-weight: 500;
            user-select: none;
        }

        .forgot-link {
            color: #197B40;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .forgot-link:hover {
            color: #D6DF20;
        }

        /* Login Button */
        .btn-login {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #197B40 0%, #0D5C2E 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(25, 123, 64, 0.3);
        }

        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-login span {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        /* Info Box */
        .info-section {
            margin-top: 32px;
        }

        .info-box {
            background: linear-gradient(135deg, rgba(25, 123, 64, 0.08) 0%, rgba(214, 223, 32, 0.08) 100%);
            border: 2px solid rgba(25, 123, 64, 0.15);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .info-box:hover {
            border-color: rgba(25, 123, 64, 0.3);
            background: linear-gradient(135deg, rgba(25, 123, 64, 0.12) 0%, rgba(214, 223, 32, 0.12) 100%);
        }

        .info-box-title {
            color: #197B40;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box-content {
            font-size: 13px;
            color: #555;
            line-height: 1.8;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid rgba(25, 123, 64, 0.1);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #666;
        }

        .info-value {
            color: #197B40;
            font-weight: 700;
            font-family: 'Monaco', 'Courier', monospace;
            background: rgba(25, 123, 64, 0.05);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        /* Footer Links */
        .login-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 2px solid #f0f0f0;
        }

        .login-footer p {
            font-size: 14px;
            color: #666;
            margin-bottom: 12px;
        }

        .login-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .login-link {
            color: #197B40;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .login-link:hover {
            color: #D6DF20;
            transform: translateX(-3px);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 15px;
            }

            .login-header {
                padding: 35px 20px;
            }

            .login-content {
                padding: 30px 20px;
            }

            .logo-circle {
                width: 70px;
                height: 70px;
            }

            .logo-icon {
                font-size: 40px;
            }

            .login-header h1 {
                font-size: 24px;
            }

            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
            }

            .login-links {
                gap: 10px;
            }
        }

        /* Loading State */
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-card">
                <!-- Header -->
                <div class="login-header">
                    <div class="logo-circle">
                        <i class="fas fa-leaf logo-icon"></i>
                    </div>
                    <h1>GulmaTrack Admin</h1>
                    <p>Sistem Visualisasi Penyebaran Gulma</p>
                </div>

                <!-- Content -->
                <div class="login-content">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                @if ($errors->has('email'))
                                    {{ $errors->first('email') }}
                                @else
                                    {{ $errors->first() }}
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope" style="margin-right: 6px; color: #D6DF20;"></i>Email Admin
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="Masukkan email admin Anda" 
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </div>

                        <!-- Password Field -->
                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-lock" style="margin-right: 6px; color: #D6DF20;"></i>Password
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Masukkan password Anda" 
                                required
                            >
                        </div>

                        <!-- Remember Me -->
                        <div class="remember-forgot">
                            <div class="remember-me">
                                <input 
                                    type="checkbox" 
                                    id="remember" 
                                    name="remember"
                                >
                                <label for="remember">Ingat saya</label>
                            </div>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="btn-login">
                            <span>
                                <i class="fas fa-sign-in-alt"></i> MASUK KE DASHBOARD
                            </span>
                        </button>
                    </form>

                    <!-- Info Section -->
                    <div class="info-section">
                        <div class="info-box">
                            <div class="info-box-title">
                                <i class="fas fa-info-circle"></i> Demo Credentials
                            </div>
                            <div class="info-box-content">
                                <div class="info-row">
                                    <span class="info-label"><i class="fas fa-user"></i> Email:</span>
                                    <span class="info-value">admin@gmail.com</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label"><i class="fas fa-key"></i> Password:</span>
                                    <span class="info-value">admin123</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Links -->
                    <div class="login-footer">
                        <p>Kembali ke halaman utama?</p>
                        <div class="login-links">
                            <a href="{{ route('home') }}" class="login-link">
                                <i class="fas fa-arrow-left"></i> Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle (optional enhancement)
        const passwordInput = document.getElementById('password');
        const passwordLabel = document.querySelector('label[for="password"]');
        
        // Add focus animation
        document.querySelectorAll('.form-group input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>