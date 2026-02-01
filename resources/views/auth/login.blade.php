<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('theme') || 'dark' }" x-init="document.documentElement.setAttribute('data-theme', theme)" :data-theme="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In - Mirvan</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
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
            border: 1px solid #d1d5db;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        [data-theme="light"] .input-field:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-muted), 0 1px 2px rgba(0, 0, 0, 0.05);
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
            color: white;
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
        
        .checkbox-custom {
            width: 1.125rem;
            height: 1.125rem;
            border-radius: 0.25rem;
            border: 2px solid #9ca3af;
            background: #ffffff;
            appearance: none;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        
        [data-theme="dark"] .checkbox-custom {
            border-color: #6b7280;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .checkbox-custom:checked {
            background: var(--accent);
            border-color: var(--accent);
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
        }
        
        .checkbox-custom:focus {
            box-shadow: 0 0 0 3px var(--accent-muted);
            outline: none;
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
        
        /* Stats row */
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.05em;
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
                <h2 class="text-3xl font-bold text-white mb-4">Property Management<br><span class="text-amber-500">Made Simple</span></h2>
                <p class="text-gray-400 mb-10 text-base leading-relaxed">Track rent payments, manage tenants, handle maintenance requests, and grow your portfolio — all in one place.</p>
                
                <!-- Stats -->
                <div class="flex items-center gap-8 mb-10 pb-10 border-b border-white/10">
                    <div class="stat-item">
                        <div class="stat-value">500+</div>
                        <div class="stat-label">Landlords</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">10k+</div>
                        <div class="stat-label">Units</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">$2M+</div>
                        <div class="stat-label">Rent Tracked</div>
                    </div>
                </div>
                
                <!-- Features -->
                <div class="space-y-4">
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
                        <span>Set up in under 5 minutes</span>
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
                    <h1 class="text-2xl sm:text-3xl font-bold text-primary mb-2">Welcome back</h1>
                    <p class="text-secondary">Sign in to continue to your dashboard</p>
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

                @if (session('status'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20">
                    <p class="text-sm text-green-500">{{ session('status') }}</p>
                </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-secondary mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="input-field w-full px-4 py-3 rounded-xl" placeholder="you@example.com">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-secondary mb-2">Password</label>
                        <input type="password" id="password" name="password" required class="input-field w-full px-4 py-3 rounded-xl">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="checkbox-custom">
                            <span class="ml-2 text-sm text-secondary">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-accent hover:underline">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-semibold text-base">
                        Sign In
                    </button>
                </form>

                <!-- Sign Up Link -->
                <p class="mt-8 text-center text-secondary text-sm">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-accent hover:underline font-medium">Create one</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>