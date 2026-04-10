<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AryaMeals | Sign In</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Merienda:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at 10% 30%, #f5f0ff 0%, #e8eefe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 24px;
            position: relative;
        }

        /* subtle food decoration */
        body::before {
            content: "🍲";
            font-size: 180px;
            opacity: 0.04;
            position: fixed;
            bottom: -30px;
            right: -40px;
            pointer-events: none;
            transform: rotate(-10deg);
        }

        body::after {
            content: "🥗";
            font-size: 150px;
            opacity: 0.04;
            position: fixed;
            top: 5%;
            left: -30px;
            pointer-events: none;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            max-width: 520px;
            width: 100%;
            transition: transform 0.2s ease;
        }

        .login-inner {
            padding: 2.5rem 2.2rem;
        }

        .brand-icon {
            background: linear-gradient(135deg, #f97316 0%, #dc2626 100%);
            width: 64px;
            height: 64px;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.2rem;
            box-shadow: 0 12px 20px -8px rgba(249, 115, 22, 0.3);
        }

        .brand-icon i {
            font-size: 32px;
            color: white;
        }

        h2 {
            font-family: 'Merienda', cursive;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -0.3px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-right: none;
            color: #64748b;
            transition: all 0.2s;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-left: none;
            border-radius: 0 14px 14px 0;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            background: white;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
            outline: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: #f97316;
        }

        .btn-login {
            background: linear-gradient(105deg, #f97316 0%, #ea580c 100%);
            border: none;
            border-radius: 14px;
            padding: 0.85rem 1.5rem;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            transition: all 0.25s ease;
            box-shadow: 0 6px 14px rgba(249, 115, 22, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 22px rgba(249, 115, 22, 0.4);
            background: linear-gradient(105deg, #ea580c 0%, #c2410c 100%);
            color:white;
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        /* social icons row - modern pill style */
        .social-icons-container {
            display: flex;
            justify-content: center;
            gap: 1.25rem;
            margin: 1.5rem 0 0.5rem;
        }

        .social-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0.75rem 1.6rem;
            border-radius: 60px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            text-decoration: none;
            background: white;
            border: 1.5px solid #e9eef3;
            color: #1f2937;
            flex: 1;
            max-width: 180px;
            cursor: pointer;
        }

        .social-icon-btn i {
            font-size: 1.25rem;
        }

        .social-icon-btn.google-btn {
            background: white;
            border-color: #e2e8f0;
            color: #3c4043;
        }

        .social-icon-btn.google-btn:hover {
            background: #fef6e6;
            border-color: #f97316;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
        }

        .social-icon-btn.apple-btn {
            background: white;
            border-color: #e2e8f0;
            color: #1f2937;
        }

        .social-icon-btn.apple-btn:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.06);
        }

        .divider {
            text-align: center;
            margin: 1.8rem 0 1.5rem;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9edf2;
        }

        .divider span {
            background: white;
            padding: 0 1.2rem;
            position: relative;
            color: #94a3b8;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .is-valid {
            border-color: #22c55e !important;
        }

        .is-invalid {
            border-color: #ef4444 !important;
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.75rem;
            font-weight: 500;
            margin-top: 0.4rem;
            display: none;
        }

        .text-link {
            color: #f97316;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .text-link:hover {
            color: #c2410c;
            text-decoration: underline;
        }

        .password-toggle-btn {
            border: 2px solid #e2e8f0;
            border-left: none;
            background: white;
            border-radius: 0 14px 14px 0;
            padding: 0 16px;
            transition: all 0.2s;
        }

        .password-toggle-btn:hover {
            background-color: #f8fafc;
        }

        /* notification toast modern */
        .notification-toast {
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(-24px);
            z-index: 9999;
            min-width: 320px;
            max-width: 460px;
            background: white;
            border-radius: 60px;
            padding: 0.85rem 1.8rem;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            opacity: 0;
            transition: opacity 0.25s ease, transform 0.25s ease;
            pointer-events: none;
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.98);
            border-left: 5px solid;
        }

        @media (max-width: 520px) {
            .login-inner {
                padding: 1.8rem 1.5rem;
            }
            .social-icons-container {
                flex-direction: column;
                align-items: center;
            }
            .social-icon-btn {
                max-width: 100%;
                width: 100%;
            }
        }

        @keyframes gentleFade {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-inner {
            animation: gentleFade 0.4s ease-out;
        }

        .checkbox-custom {
            accent-color: #f97316;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-inner">
            <div class="text-center mb-4">
                <div class="brand-icon mx-auto">
                    <i class="fas fa-utensils"></i>
                </div>
                <h2 class="mt-2">AryaMeals</h2>
                <p class="text-muted mt-1" style="font-size: 0.9rem;">Sign in to continue to your account</p>
            </div>
            
            <!-- Social sign-in: Google + Apple as elegant icon buttons (professional) -->
            <div class="social-icons-container">
                <a href="{{ route('auth.google.redirect') }}" class="social-icon-btn google-btn" id="googleSignInBtn">
                    <i class="fab fa-google"></i>
                    <span>Google</span>
                </a>
                <a href="#" class="social-icon-btn apple-btn" id="appleSignInBtn">
                    <i class="fab fa-apple"></i>
                    <span>Apple</span>
                </a>
            </div>
            
            <div class="divider">
                <span>OR CONTINUE WITH EMAIL</span>
            </div>
            
            <form id="loginForm" method="POST" action="{{ route('auth.login.submit') }}">
                @csrf
                
                <!-- Email Field -->
                <div class="mb-4">
                    <label class="form-label"><i class="far fa-envelope me-1"></i> EMAIL ADDRESS</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" 
                                autocomplete="email">
                    </div>
                    <div class="invalid-feedback" id="emailError"></div>
                </div>
                
                <!-- Password Field with toggle -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-key me-1"></i> PASSWORD</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" 
                             autocomplete="current-password">
                        <button class="btn password-toggle-btn" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback" id="passwordError"></div>
                </div>
                
                <!-- Remember Me & Forgot (professional subtle) -->
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input checkbox-custom" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label small text-secondary" for="remember">
                            Keep me signed in
                        </label>
                    </div>
                    <a href="#" class="text-link small" style="font-size: 0.75rem;">Forgot password?</a>
                </div>
                
                <!-- Login Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-login btn-lg" id="loginBtn">
                        <i class="fas fa-arrow-right-to-bracket me-2"></i> Sign In
                    </button>
                </div>
            </form>
            
            <div class="text-center mt-4 pt-2">
                <p class="small text-muted mt-3 mb-0">
                    <i class="fas fa-shield-alt me-1"></i> Secure login · Encrypted
                </p>
            </div>
            
            <noscript>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    JavaScript is required for enhanced security. Please enable JS.
                </div>
            </noscript>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        (function() {
            'use strict';
            
            // DOM elements
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const togglePasswordBtn = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');
            const emailField = document.getElementById('email');
            
            // Toast notification (professional version)
            function showNotification(message, type = 'success') {
                const existingToast = document.querySelector('.notification-toast');
                if (existingToast) existingToast.remove();
                
                const toast = document.createElement('div');
                toast.className = 'notification-toast';
                const accentColor = type === 'success' ? '#22c55e' : '#ef4444';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-circle-exclamation';
                toast.style.borderLeftColor = accentColor;
                toast.innerHTML = `
                    <i class="fas ${icon}" style="color: ${accentColor}; font-size: 1.2rem;"></i>
                    <span style="flex:1;">${escapeHtml(message)}</span>
                    <i class="fas fa-times-circle" style="color: #94a3b8; cursor: pointer; pointer-events: auto;" onclick="this.closest('.notification-toast').remove()"></i>
                `;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateX(-50%) translateY(0px)';
                }, 10);
                
                setTimeout(() => {
                    if (toast && toast.parentNode) {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateX(-50%) translateY(-24px)';
                        setTimeout(() => toast.remove(), 250);
                    }
                }, 3800);
            }
            
            function escapeHtml(str) {
                if (!str) return '';
                return str.replace(/[&<>]/g, function(m) {
                    if (m === '&') return '&amp;';
                    if (m === '<') return '&lt;';
                    if (m === '>') return '&gt;';
                    return m;
                });
            }
            
            function resetValidation() {
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.is-valid').forEach(el => el.classList.remove('is-valid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
            }
            
            // Password visibility toggle
            if (togglePasswordBtn && passwordField) {
                togglePasswordBtn.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    const icon = togglePasswordBtn.querySelector('i');
                    if (type === 'text') {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }
            
            // Real-time email validation (visual)
            if (emailField) {
                emailField.addEventListener('blur', function() {
                    const emailVal = this.value.trim();
                    const emailErrorDiv = document.getElementById('emailError');
                    
                    if (emailVal && emailVal.includes('@') && emailVal.includes('.')) {
                        // Check if it's a Google account
                        const isGoogleAccount = emailVal.toLowerCase().endsWith('@gmail.com') || 
                                               emailVal.toLowerCase().endsWith('@googlemail.com');
                        
                        if (!this.classList.contains('is-invalid')) {
                            this.classList.add('is-valid');
                            this.classList.remove('is-invalid');
                            if (emailErrorDiv) emailErrorDiv.style.display = 'none';
                            
                            // Show neutral hint for Google accounts
                            if (isGoogleAccount) {
                                const googleHint = document.createElement('small');
                                googleHint.className = 'text-muted mt-1 d-block';
                                googleHint.innerHTML = '<i class="fas fa-check-circle me-1"></i>Google account detected - you can use either login method';
                                
                                // Remove existing hint if any
                                const existingHint = this.parentNode.parentNode.querySelector('.google-hint');
                                if (existingHint) existingHint.remove();
                                
                                // Add new hint
                                googleHint.classList.add('google-hint');
                                this.parentNode.parentNode.appendChild(googleHint);
                            }
                        }
                    } else if (emailVal.length === 0) {
                        this.classList.remove('is-valid', 'is-invalid');
                        // Remove Google hint
                        const existingHint = this.parentNode.parentNode.querySelector('.google-hint');
                        if (existingHint) existingHint.remove();
                    } else if (emailVal && (!emailVal.includes('@') || !emailVal.includes('.'))) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                        if (emailErrorDiv) {
                            emailErrorDiv.textContent = 'Enter a valid email address (e.g., name@example.com)';
                            emailErrorDiv.style.display = 'block';
                        }
                        // Remove Google hint
                        const existingHint = this.parentNode.parentNode.querySelector('.google-hint');
                        if (existingHint) existingHint.remove();
                    }
                });
            }
            
            // Real-time password validation visual
            if (passwordField) {
                passwordField.addEventListener('blur', function() {
                    const pwd = this.value;
                    const pwdError = document.getElementById('passwordError');
                    
                    if (pwd && pwd.length >= 6) {
                        this.classList.add('is-valid');
                        this.classList.remove('is-invalid');
                        if (pwdError) pwdError.style.display = 'none';
                    } else if (pwd && pwd.length < 6) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                        if (pwdError) {
                            pwdError.textContent = 'Password must be at least 6 characters';
                            pwdError.style.display = 'block';
                        }
                    } else if (!pwd) {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                });
            }
            
            // Handle login form submission (preserve original fetch logic)
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    resetValidation();
                    
                    const email = emailField.value.trim();
                    const password = passwordField.value;
                    const emailErrorDiv = document.getElementById('emailError');
                    const passwordErrorDiv = document.getElementById('passwordError');
                    let hasClientError = false;
                    
                    // email format check
                    if (!email || !email.includes('@') || !email.includes('.')) {
                        emailField.classList.add('is-invalid');
                        if (emailErrorDiv) {
                            emailErrorDiv.textContent = 'Please enter a valid email address';
                            emailErrorDiv.style.display = 'block';
                        }
                        hasClientError = true;
                    }
                    
                    if (!password || password.length < 6) {
                        passwordField.classList.add('is-invalid');
                        if (passwordErrorDiv) {
                            passwordErrorDiv.textContent = 'Password must be at least 6 characters';
                            passwordErrorDiv.style.display = 'block';
                        }
                        hasClientError = true;
                    }
                    
                    if (hasClientError) return;
                    
                    // prepare payload
                    const payload = {
                        email: email,
                        password: password,
                        remember: document.getElementById('remember')?.checked || false,
                        _token: '{{ csrf_token() }}'
                    };
                    
                    const originalBtnHtml = loginBtn.innerHTML;
                    loginBtn.innerHTML = '<i class="fas fa-spinner fa-pulse me-2"></i>Signing in...';
                    loginBtn.disabled = true;
                    
                    fetch('{{ route("auth.login.submit") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            // visual success feedback
                            emailField.classList.add('is-valid');
                            passwordField.classList.add('is-valid');
                            
                            showNotification(result.message || 'Login successful! Redirecting...', 'success');
                            
                            setTimeout(() => {
                                window.location.href = result.redirect_url || '/';
                            }, 1400);
                        } else {
                            // handle server errors
                            if (result.errors) {
                                for (const [field, msgs] of Object.entries(result.errors)) {
                                    const inputEl = document.getElementById(field);
                                    const errDiv = document.getElementById(`${field}Error`);
                                    if (inputEl) {
                                        inputEl.classList.add('is-invalid');
                                        if (errDiv) {
                                            errDiv.textContent = msgs[0];
                                            errDiv.style.display = 'block';
                                        }
                                    }
                                }
                            } else {
                                // generic or field-specific messages from backend
                                const errorMsg = result.message || 'Invalid email or password.';
                                
                                // Check if fallback to OAuth is suggested
                                if (result.fallback_to_oauth) {
                                    showNotification('Google verification failed. You can try the "Continue with Google" button or check your credentials.', 'error');
                                    // Don't highlight Google button aggressively, just show message
                                } else if (errorMsg.toLowerCase().includes('google credentials')) {
                                    // Show Google credential error message
                                    showNotification(errorMsg, 'error');
                                    emailField.classList.add('is-invalid');
                                    if (emailErrorDiv) {
                                        emailErrorDiv.textContent = 'Invalid Google credentials';
                                        emailErrorDiv.style.display = 'block';
                                    }
                                } else if (errorMsg.toLowerCase().includes('email') || errorMsg.toLowerCase().includes('account')) {
                                    emailField.classList.add('is-invalid');
                                    if (emailErrorDiv) {
                                        emailErrorDiv.textContent = errorMsg;
                                        emailErrorDiv.style.display = 'block';
                                    }
                                } else {
                                    // show on both fields as general credential error
                                    emailField.classList.add('is-invalid');
                                    passwordField.classList.add('is-invalid');
                                    if (emailErrorDiv) {
                                        emailErrorDiv.textContent = 'Invalid email or password';
                                        emailErrorDiv.style.display = 'block';
                                    }
                                    if (passwordErrorDiv) {
                                        passwordErrorDiv.textContent = 'Invalid email or password';
                                        passwordErrorDiv.style.display = 'block';
                                    }
                                }
                                showNotification(errorMsg, 'error');
                            }
                            loginBtn.innerHTML = originalBtnHtml;
                            loginBtn.disabled = false;
                        }
                    })
                    .catch(err => {
                        console.error('Login fetch error:', err);
                        showNotification('Network error. Please check your connection.', 'error');
                        loginBtn.innerHTML = originalBtnHtml;
                        loginBtn.disabled = false;
                    });
                });
            }
            
            // Optional: small prevention for Google/Apple links if needed (but they work naturally)
            const googleBtn = document.getElementById('googleSignInBtn');
            const appleBtn = document.getElementById('appleSignInBtn');
            if (googleBtn) {
                googleBtn.addEventListener('click', function(e) {
                    // no prevent default, keep native redirect.
                    showNotification('Redirecting to Google...', 'success');
                });
            }
            if (appleBtn) {
                appleBtn.addEventListener('click', function(e) {
                    showNotification('Redirecting to Apple ID...', 'success');
                });
            }
        })();
    </script>
</body>
</html>