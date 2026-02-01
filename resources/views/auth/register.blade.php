<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('theme') || 'dark' }" x-init="document.documentElement.setAttribute('data-theme', theme)" :data-theme="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account - Mirvan</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        
        :root { --transition-speed: 150ms; }
        
        [data-theme="dark"] {
            --bg-primary: #030712;
            --bg-secondary: #0a0f1a;
            --bg-card: rgba(255, 255, 255, 0.03);
            --bg-input: rgba(255, 255, 255, 0.05);
            --border-primary: rgba(255, 255, 255, 0.08);
            --border-secondary: rgba(255, 255, 255, 0.12);
            --text-primary: #f9fafb;
            --text-secondary: rgba(255, 255, 255, 0.7);
            --text-muted: rgba(255, 255, 255, 0.5);
            --accent: #f59e0b;
            --accent-hover: #fbbf24;
            --accent-muted: rgba(245, 158, 11, 0.15);
        }
        
        [data-theme="light"] {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-card: #ffffff;
            --bg-input: #f8fafc;
            --border-primary: #e2e8f0;
            --border-secondary: #cbd5e1;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --accent: #d97706;
            --accent-hover: #b45309;
            --accent-muted: rgba(217, 119, 6, 0.1);
        }
        
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-primary); 
            color: var(--text-primary); 
            min-height: 100vh; 
        }
        
        .input-field { 
            background: var(--bg-input); 
            border: 1px solid var(--border-primary); 
            color: var(--text-primary);
            transition: all 0.2s ease;
        }
        
        [data-theme="light"] .input-field {
            border: 1px solid var(--border-secondary);
            background: #ffffff;
        }
        
        .input-field::placeholder { 
            color: var(--text-muted); 
        }
        
        .input-field:focus { 
            border-color: var(--accent); 
            box-shadow: 0 0 0 3px var(--accent-muted); 
            outline: none; 
        }
        
        .btn-primary { 
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
            color: white !important;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover { 
            transform: translateY(-1px); 
            box-shadow: 0 10px 40px rgba(245, 158, 11, 0.3); 
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .text-primary { color: var(--text-primary); }
        .text-secondary { color: var(--text-secondary); }
        .text-muted { color: var(--text-muted); }
        .text-accent { color: var(--accent); }
        
        /* Premium dark brand panel - always dark regardless of theme */
        .brand-panel {
            background: linear-gradient(135deg, #0c0a09 0%, #1c1917 50%, #0c0a09 100%);
            position: relative;
            overflow: hidden;
        }
        
        /* Subtle grid pattern */
        .brand-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(245, 158, 11, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(245, 158, 11, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse at center, black 0%, transparent 70%);
            -webkit-mask-image: radial-gradient(ellipse at center, black 0%, transparent 70%);
        }
        
        /* Ambient glow effects */
        .glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
        }
        
        .glow-1 {
            width: 500px;
            height: 500px;
            background: rgba(245, 158, 11, 0.15);
            top: -150px;
            left: -150px;
            animation: pulse-glow 8s ease-in-out infinite;
        }
        
        .glow-2 {
            width: 400px;
            height: 400px;
            background: rgba(217, 119, 6, 0.1);
            bottom: -100px;
            right: -100px;
            animation: pulse-glow 8s ease-in-out infinite reverse;
        }
        
        .glow-3 {
            width: 300px;
            height: 300px;
            background: rgba(251, 191, 36, 0.08);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: float 10s ease-in-out infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.1); }
        }
        
        @keyframes float {
            0%, 100% { transform: translate(-50%, -50%) translateY(0); }
            50% { transform: translate(-50%, -50%) translateY(-30px); }
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
        }
        
        .feature-icon {
            width: 1.25rem;
            height: 1.25rem;
            color: #f59e0b;
            flex-shrink: 0;
        }
        
        .theme-toggle {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            color: var(--text-muted);
            transition: all 0.2s ease;
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
            z-index: 10;
        }
        
        .theme-toggle:hover {
            color: var(--text-primary);
            background: var(--bg-input);
            border-color: var(--border-primary);
        }
        
        /* Testimonial card */
        .testimonial-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
        }
        
        /* reCAPTCHA responsive */
        .g-recaptcha {
            transform-origin: center;
        }
        
        @media (max-width: 400px) {
            .g-recaptcha {
                transform: scale(0.9);
            }
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex">
        <!-- Left Panel - Brand (always dark) -->
        <div class="hidden lg:flex lg:w-1/2 brand-panel items-center justify-center p-12 relative">
            <!-- Glow effects -->
            <div class="glow glow-1"></div>
            <div class="glow glow-2"></div>
            <div class="glow glow-3"></div>
            
            <div class="relative z-10 max-w-md">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-3 mb-12 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/20 group-hover:shadow-amber-500/40 transition-all group-hover:scale-105">
                        <span class="text-white font-bold text-xl">M</span>
                    </div>
                    <span class="text-2xl font-bold text-white">Mirvan</span>
                </a>
                
                <!-- Tagline -->
                <h2 class="text-3xl font-bold text-white mb-4">Start managing properties<br><span class="text-amber-500">in minutes</span></h2>
                <p class="text-gray-400 mb-10 text-base leading-relaxed">Join hundreds of landlords who are saving time and money with Mirvan's intuitive property management platform.</p>
                
                <!-- Features -->
                <div class="space-y-4 mb-10 pb-10 border-b border-white/10">
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Free for up to 5 units</span>
                    </div>
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>No credit card required</span>
                    </div>
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Cancel anytime</span>
                    </div>
                </div>
                
                <!-- Testimonial -->
                <div class="testimonial-card rounded-2xl p-6">
                    <div class="flex gap-1 mb-3">
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-gray-300 text-sm italic mb-4">"Mirvan has completely transformed how I manage my properties. What used to take hours now takes minutes."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">JD</span>
                        </div>
                        <div>
                            <p class="text-white text-sm font-medium">John Davis</p>
                            <p class="text-gray-500 text-xs">Property Owner, 24 units</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-8 lg:p-12 relative" style="background-color: var(--bg-primary);">
            <!-- Theme Toggle -->
            <button @click="theme = theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('theme', theme); document.documentElement.setAttribute('data-theme', theme)" class="theme-toggle" aria-label="Toggle theme">
                <template x-if="theme === 'dark'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </template>
                <template x-if="theme === 'light'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                </template>
            </button>
            
            <div class="w-full max-w-sm">
                <!-- Mobile Logo -->
                <a href="/" class="flex lg:hidden items-center justify-center space-x-2 mb-8 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-lg">M</span>
                    </div>
                    <span class="text-xl font-bold text-primary">Mirvan</span>
                </a>
                
                <!-- Header -->
                <div class="text-center lg:text-left mb-8">
                    <h1 class="text-2xl sm:text-3xl font-bold text-primary mb-2">Create your account</h1>
                    <p class="text-secondary">Get started with your free account</p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20">
                    <ul class="text-sm text-red-500 space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Honeypot -->
                    <div style="position: absolute; left: -9999px;" aria-hidden="true">
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>
                    <input type="hidden" name="timestamp" value="{{ time() }}">
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-secondary mb-2">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus class="input-field w-full px-4 py-3 rounded-xl" placeholder="John Doe">
                    </div>

                    <div>
                        <label for="company_name" class="block text-sm font-medium text-secondary mb-2">Company Name</label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required class="input-field w-full px-4 py-3 rounded-xl" placeholder="Acme Properties LLC">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-secondary mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required class="input-field w-full px-4 py-3 rounded-xl" placeholder="you@example.com">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-secondary mb-2">Password</label>
                        <input type="password" id="password" name="password" required class="input-field w-full px-4 py-3 rounded-xl" placeholder="••••••••">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-secondary mb-2">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="input-field w-full px-4 py-3 rounded-xl" placeholder="••••••••">
                    </div>

                    <!-- reCAPTCHA -->
                    <div class="flex justify-center pt-2">
                        <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.site_key') }}" x-bind:data-theme="theme"></div>
                    </div>
                    @error('g-recaptcha-response')
                        <p class="text-red-500 text-sm text-center">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-semibold text-base">
                        Create Account
                    </button>
                    
                    <p class="text-center text-muted text-xs pt-2">
                        By signing up, you agree to our 
                        <a href="#" class="text-accent hover:underline">Terms</a> and 
                        <a href="#" class="text-accent hover:underline">Privacy Policy</a>
                    </p>
                </form>

                <!-- Sign In Link -->
                <p class="mt-8 text-center text-secondary text-sm">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-accent hover:underline font-medium">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>