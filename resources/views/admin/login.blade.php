<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arya Meals - Admin Login</title>
    <!-- Tailwind CSS via CDN (no JS needed) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-white">
    <div class="relative w-full max-w-md">
        <div class="mb-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200">
                <span class="inline-flex h-2 w-2 rounded-full bg-purple-500"></span>
                <span class="text-xs uppercase tracking-[0.2em] font-semibold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Arya Meals</span>
            </div>
            <h1 class="mt-4 text-3xl font-semibold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Admin Sign In</h1>
            <p class="mt-1 text-sm text-slate-600">Manage restaurants, menus, and live orders.</p>
        </div>

        <div class="w-full bg-white shadow-2xl rounded-2xl p-8 border border-purple-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs font-medium bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent uppercase tracking-[0.16em]">Admin Portal</p>
                    <p class="mt-1 text-sm text-slate-500">Secure access for Arya Meals team.</p>
                </div>
                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-white text-sm font-semibold shadow-sm">
                    AM
                </div>
            </div>

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
            @csrf
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-purple-700 tracking-wide">ADMIN EMAIL</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-purple-400 text-xs">@</span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full rounded-xl border border-purple-200 bg-white pl-8 pr-3 py-2.5 text-sm text-slate-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                    >
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-semibold text-purple-700 tracking-wide">PASSWORD</label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full rounded-xl border border-purple-200 bg-white pr-10 pl-3 py-2.5 text-sm text-slate-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                    >
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-purple-400 hover:text-purple-600">
                        <svg id="eyeIcon" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg id="eyeSlashIcon" class="h-4 w-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" id="rememberToggle" class="sr-only" 
                           {{ old('remember') ? 'checked' : (request()->cookie('arya_meals_session') ? 'checked' : (request()->cookie('laravel_session') ? 'checked' : '')) }}>
                    <div class="relative">
                        <div id="toggleTrack" class="block bg-gray-200 w-11 h-6 rounded-full transition-colors duration-200"></div>
                        <div id="toggleDot" class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform"></div>
                    </div>
                    <span class="ml-3 text-sm text-slate-600">Remember me</span>
                </label>
            </div>

            <button
                type="submit"
                class="w-full inline-flex justify-center items-center rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 focus:ring-offset-white transition-all"
            >
                Log in to dashboard
            </button>
        </form>

        <p class="mt-4 text-[11px] text-slate-400 text-center">
            Protected area. Activity may be monitored for security.
        </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rememberToggle = document.getElementById('rememberToggle');
            const toggleTrack = document.getElementById('toggleTrack');
            const toggleDot = document.getElementById('toggleDot');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const togglePasswordBtn = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeSlashIcon = document.getElementById('eyeSlashIcon');
            
            // Cookie helper functions
            function setCookie(name, value, days) {
                const expires = new Date();
                expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
                document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
            }
            
            function getCookie(name) {
                const nameEQ = name + "=";
                const ca = document.cookie.split(';');
                for(let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }
            
            function deleteCookie(name) {
                document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
            }
            
            // Debug: Check all cookies
            console.log('All cookies:', document.cookie);
            console.log('Checkbox initial state:', rememberToggle.checked);
            
            // Check if user previously used remember me
            const rememberMeState = getCookie('rememberMe');
            const savedEmail = getCookie('rememberedEmail');
            const savedPassword = getCookie('rememberedPassword');
            
            if (rememberMeState === 'true' && !rememberToggle.checked) {
                rememberToggle.checked = true;
                console.log('Set checkbox to true from cookie');
            }
            
            // Auto-fill email and password if remember me was used
            if (rememberMeState === 'true' && savedEmail && !emailInput.value) {
                emailInput.value = savedEmail;
                console.log('Auto-filled email from cookie:', savedEmail);
            }
            
            if (rememberMeState === 'true' && savedPassword && !passwordInput.value) {
                passwordInput.value = savedPassword;
                console.log('Auto-filled password from cookie');
            }
            
            // Password show/hide functionality
            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'text') {
                    eyeIcon.classList.add('hidden');
                    eyeSlashIcon.classList.remove('hidden');
                } else {
                    eyeIcon.classList.remove('hidden');
                    eyeSlashIcon.classList.add('hidden');
                }
            });
            
            function updateToggle() {
                console.log('Checkbox checked:', rememberToggle.checked); // Debug log
                if (rememberToggle.checked) {
                    toggleTrack.classList.remove('bg-gray-200');
                    toggleTrack.classList.add('bg-purple-600');
                    toggleDot.style.transform = 'translateX(20px)';
                    setCookie('rememberMe', 'true', 30); // 30 days
                    // Save email and password when toggle is ON
                    if (emailInput.value) {
                        setCookie('rememberedEmail', emailInput.value, 30);
                    }
                    if (passwordInput.value) {
                        setCookie('rememberedPassword', passwordInput.value, 30);
                    }
                } else {
                    toggleTrack.classList.remove('bg-purple-600');
                    toggleTrack.classList.add('bg-gray-200');
                    toggleDot.style.transform = 'translateX(0px)';
                    setCookie('rememberMe', 'false', 30);
                    // Clear saved password when toggle is OFF
                    deleteCookie('rememberedPassword');
                }
            }
            
            // Save email and password when user types
            emailInput.addEventListener('input', function() {
                if (rememberToggle.checked) {
                    setCookie('rememberedEmail', emailInput.value, 30);
                }
            });
            
            passwordInput.addEventListener('input', function() {
                if (rememberToggle.checked) {
                    setCookie('rememberedPassword', passwordInput.value, 30);
                }
            });
            
            // Initialize toggle state from checkbox
            updateToggle();
            
            // Add click event to toggle
            rememberToggle.addEventListener('change', updateToggle);
            
            // Make the toggle clickable when clicking on the visual toggle
            toggleTrack.addEventListener('click', function(e) {
                e.preventDefault();
                rememberToggle.checked = !rememberToggle.checked;
                updateToggle();
            });
            
            toggleDot.addEventListener('click', function(e) {
                e.preventDefault();
                rememberToggle.checked = !rememberToggle.checked;
                updateToggle();
            });
        });
    </script>
</body>
</html>

