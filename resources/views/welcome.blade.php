<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('theme') || 'dark' }" x-init="document.documentElement.setAttribute('data-theme', theme)" :data-theme="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-G3GPK1QMJF"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-G3GPK1QMJF');
    </script>
    <title>Mirvan - Property Management Made Simple</title>
    <meta name="description" content="Track rent payments, manage tenants, handle maintenance requests, and grow your portfolio — all in one beautiful dashboard.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Prevent flash of unstyled content with Alpine */
        [x-cloak] { display: none !important; }
        
        :root { --transition-speed: 150ms; }
        
        [data-theme="dark"] {
            --bg-primary: #030712;
            --bg-secondary: #0a0f1a;
            --bg-tertiary: #111827;
            --bg-card: rgba(255, 255, 255, 0.03);
            --bg-card-hover: rgba(255, 255, 255, 0.05);
            --border-primary: rgba(255, 255, 255, 0.08);
            --border-secondary: rgba(255, 255, 255, 0.12);
            --text-primary: #f9fafb;
            --text-secondary: rgba(255, 255, 255, 0.7);
            --text-muted: rgba(255, 255, 255, 0.5);
            --accent: #f59e0b;
            --accent-hover: #fbbf24;
            --accent-muted: rgba(245, 158, 11, 0.15);
            --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }
        
        [data-theme="light"] {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --bg-card: #ffffff;
            --bg-card-hover: #ffffff;
            --border-primary: #e2e8f0;
            --border-secondary: #cbd5e1;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --accent: #d97706;
            --accent-hover: #b45309;
            --accent-muted: rgba(217, 119, 6, 0.1);
            --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }
        
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-primary); 
            color: var(--text-primary); 
        }
        
        .glass-card { 
            background: var(--bg-card); 
            border: 1px solid var(--border-primary); 
            box-shadow: var(--shadow-card);
        }
        
        [data-theme="dark"] .glass-card {
            backdrop-filter: blur(20px);
        }
        
        [data-theme="light"] .glass-card {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        /* Pricing cards need stronger styling in light mode */
        .pricing-card {
            background: var(--bg-card);
            border: 1px solid var(--border-primary);
            box-shadow: var(--shadow-card);
            transition: all 0.2s ease;
        }
        
        [data-theme="light"] .pricing-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        }
        
        .pricing-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        [data-theme="light"] .pricing-card:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .pricing-card.featured {
            border: 2px solid #f59e0b;
        }
        
        .glass-card:hover {
            border-color: var(--border-secondary);
        }
        
        .btn-primary { 
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
            color: white !important;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 10px 40px rgba(245, 158, 11, 0.3); 
        }
        
        .btn-secondary { 
            background: var(--bg-card); 
            border: 1px solid var(--border-secondary); 
            color: var(--text-primary); 
            transition: all 0.2s ease;
        }
        
        .btn-secondary:hover { 
            background: var(--bg-card-hover); 
            border-color: var(--accent);
            transform: translateY(-1px);
        }
        
        [data-theme="light"] .btn-secondary {
            background: #ffffff;
            border: 2px solid #d1d5db;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        
        [data-theme="light"] .btn-secondary:hover {
            background: #f9fafb;
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .text-primary { color: var(--text-primary); }
        .text-secondary { color: var(--text-secondary); }
        .text-muted { color: var(--text-muted); }
        .text-accent { color: var(--accent); }
        .border-primary { border-color: var(--border-primary); }
        
        .gradient-text { 
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #f59e0b 100%); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            background-clip: text; 
        }
        
        .hero-gradient { 
            background: radial-gradient(ellipse at 50% 0%, rgba(245, 158, 11, 0.12) 0%, transparent 60%); 
        }
        
        .feature-gradient { 
            background: radial-gradient(ellipse at 50% 100%, rgba(59, 130, 246, 0.08) 0%, transparent 60%); 
        }
        
        @keyframes float { 
            0%, 100% { transform: translateY(0px); } 
            50% { transform: translateY(-20px); } 
        }
        
        @keyframes pulse-slow { 
            0%, 100% { opacity: 0.6; } 
            50% { opacity: 0.3; } 
        }
        
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float 6s ease-in-out infinite; animation-delay: 2s; }
        .animate-pulse-slow { animation: pulse-slow 4s ease-in-out infinite; }
        
        [data-theme="dark"] .orb { 
            position: absolute; 
            border-radius: 50%; 
            filter: blur(100px); 
            pointer-events: none; 
        }
        [data-theme="dark"] .orb-1 { width: 600px; height: 600px; background: rgba(245, 158, 11, 0.1); top: -200px; right: -100px; }
        [data-theme="dark"] .orb-2 { width: 500px; height: 500px; background: rgba(59, 130, 246, 0.08); bottom: 20%; left: -100px; }
        [data-theme="dark"] .orb-3 { width: 400px; height: 400px; background: rgba(168, 85, 247, 0.08); bottom: -100px; right: 20%; }
        
        [data-theme="light"] .orb { display: none; }
        
        /* Dashboard Preview */
        .dashboard-preview {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
        
        /* Feature Cards */
        .feature-card {
            transition: all 0.2s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-4px);
        }
        
        /* Nav link hover */
        .nav-link {
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.2s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        /* Floating notification cards */
        .floating-card {
            background: var(--bg-card);
            border: 1px solid var(--border-primary);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        
        [data-theme="light"] .floating-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        
        [data-theme="dark"] .floating-card {
            backdrop-filter: blur(20px);
        }
        
        /* Theme toggle button */
        .theme-toggle {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s ease;
            border: 1px solid var(--border-primary);
            background: transparent;
            cursor: pointer;
            flex-shrink: 0;
        }
        
        .theme-toggle:hover {
            background: var(--bg-card);
            border-color: var(--border-secondary);
        }
        
        [data-theme="light"] .theme-toggle {
            background: #f1f5f9;
            border-color: #e2e8f0;
        }
        
        [data-theme="light"] .theme-toggle:hover {
            background: #e2e8f0;
        }
        
        /* Mobile menu */
        .mobile-menu {
            display: none;
        }
        
        @media (max-width: 768px) {
            .mobile-menu {
                display: flex;
            }
            .desktop-nav {
                display: none;
            }
        }
    </style>
</head>
<body class="antialiased min-h-screen">
    <!-- Background Orbs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="orb orb-1 animate-pulse-slow"></div>
        <div class="orb orb-2 animate-float"></div>
        <div class="orb orb-3 animate-float-delayed"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative z-50 border-b border-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2 sm:space-x-3 group flex-shrink-0">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg group-hover:shadow-amber-500/30 transition-shadow">
                        <span class="text-white font-bold text-base sm:text-lg">M</span>
                    </div>
                    <span class="text-lg sm:text-xl font-bold text-primary">Mirvan</span>
                </a>

                <!-- Desktop Nav Links -->
                <div class="desktop-nav hidden md:flex items-center space-x-8">
                    <a href="#features" class="nav-link text-secondary hover:text-primary transition-colors">Features</a>
                    <a href="#pricing" class="nav-link text-secondary hover:text-primary transition-colors">Pricing</a>
                    <a href="#contact" class="nav-link text-secondary hover:text-primary transition-colors">Contact</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <button @click="theme = theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('theme', theme); document.documentElement.setAttribute('data-theme', theme)" class="theme-toggle text-muted hover:text-primary" aria-label="Toggle theme">
                        <template x-if="theme === 'dark'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </template>
                        <template x-if="theme === 'light'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                        </template>
                    </button>
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center justify-center h-10 px-4 text-secondary hover:text-primary transition-colors font-medium text-sm">Sign In</a>
                    <a href="{{ route('register') }}" class="btn-primary h-9 sm:h-10 px-4 sm:px-5 rounded-lg sm:rounded-xl font-medium inline-flex items-center justify-center text-sm whitespace-nowrap">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative hero-gradient pt-12 sm:pt-16 pb-20 sm:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center px-3 sm:px-4 py-2 rounded-full glass-card text-xs sm:text-sm text-secondary mb-6 sm:mb-8">
                    <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                    Now in Beta — Free for early adopters
                </div>
                
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-primary leading-tight mb-4 sm:mb-6">
                    Property Management<br>
                    <span class="gradient-text">Made Simple</span>
                </h1>
                
                <p class="text-base sm:text-lg md:text-xl text-secondary max-w-2xl mx-auto mb-8 sm:mb-10 px-4">
                    Track rent payments, manage tenants, handle maintenance requests, and grow your portfolio — all in one beautiful dashboard.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 px-4">
                    <a href="{{ route('register') }}" class="btn-primary w-full sm:w-auto h-12 sm:h-14 px-6 sm:px-8 rounded-xl font-semibold text-base sm:text-lg inline-flex items-center justify-center">
                        Start Free Trial
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </a>
                    <a href="#features" class="btn-secondary w-full sm:w-auto h-12 sm:h-14 px-6 sm:px-8 rounded-xl font-semibold text-base sm:text-lg inline-flex items-center justify-center">
                        See Features
                    </a>
                </div>

                <p class="text-xs sm:text-sm text-muted mt-4 sm:mt-6">No credit card required • Free for up to 5 units</p>
            </div>

            <!-- Dashboard Preview -->
            <div class="mt-12 sm:mt-20 relative max-w-5xl mx-auto">

                <!-- Main Dashboard Card -->
                <div class="glass-card rounded-xl sm:rounded-2xl p-1.5 sm:p-2">
                    <div class="dashboard-preview rounded-lg sm:rounded-xl p-3 sm:p-4 md:p-8">
                        <!-- Fake Dashboard UI -->
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-4 sm:mb-6">
                            <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-red-500"></div>
                            <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-green-500"></div>
                            <span class="ml-2 sm:ml-4 text-gray-400 text-xs sm:text-sm">Mirvan Dashboard</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-4 mb-4 sm:mb-6">
                            <div class="bg-white/10 rounded-lg sm:rounded-xl p-2.5 sm:p-4">
                                <p class="text-lg sm:text-2xl font-bold text-white">$24,500</p>
                                <p class="text-[10px] sm:text-xs text-gray-400">Collected</p>
                            </div>
                            <div class="bg-white/10 rounded-lg sm:rounded-xl p-2.5 sm:p-4">
                                <p class="text-lg sm:text-2xl font-bold text-white">94%</p>
                                <p class="text-[10px] sm:text-xs text-gray-400">Occupancy</p>
                            </div>
                            <div class="bg-white/10 rounded-lg sm:rounded-xl p-2.5 sm:p-4">
                                <p class="text-lg sm:text-2xl font-bold text-white">12</p>
                                <p class="text-[10px] sm:text-xs text-gray-400">Properties</p>
                            </div>
                            <div class="bg-white/10 rounded-lg sm:rounded-xl p-2.5 sm:p-4">
                                <p class="text-lg sm:text-2xl font-bold text-white">3</p>
                                <p class="text-[10px] sm:text-xs text-gray-400">Open Requests</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                            <div class="bg-white/5 rounded-lg sm:rounded-xl p-3 sm:p-4 h-20 sm:h-32 flex items-center justify-center">
                                <span class="text-gray-500 text-xs sm:text-sm">Payments Chart</span>
                            </div>
                            <div class="bg-white/5 rounded-lg sm:rounded-xl p-3 sm:p-4 h-20 sm:h-32 flex items-center justify-center">
                                <span class="text-gray-500 text-xs sm:text-sm">Recent Activity</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Card - Left (positioned completely outside) -->
                <div class="absolute right-full mr-6 top-20 floating-card rounded-xl p-4 hidden 2xl:block animate-float z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-primary whitespace-nowrap">Payment Received</p>
                            <p class="text-xs text-muted whitespace-nowrap">$1,850 from Unit 4B</p>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Card - Right (positioned completely outside) -->
                <div class="absolute left-full ml-6 top-40 floating-card rounded-xl p-4 hidden 2xl:block animate-float-delayed z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-primary whitespace-nowrap">New Request</p>
                            <p class="text-xs text-muted whitespace-nowrap">HVAC issue - Unit 2A</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="relative feature-gradient py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary mb-3 sm:mb-4">Everything You Need</h2>
                <p class="text-base sm:text-lg text-secondary max-w-2xl mx-auto">Powerful tools to manage your properties efficiently, all in one place.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Feature 1 -->
                <div class="feature-card glass-card rounded-xl sm:rounded-2xl p-5 sm:p-6 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-green-500/15 flex items-center justify-center mb-3 sm:mb-4 group-hover:bg-green-500/25 transition-colors">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-primary mb-2">Payment Tracking</h3>
                    <p class="text-secondary text-sm">Record payments via ACH, check, cash, Zelle, Venmo, and more. Track collection rates and late fees automatically.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card glass-card rounded-xl sm:rounded-2xl p-5 sm:p-6 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-blue-500/15 flex items-center justify-center mb-3 sm:mb-4 group-hover:bg-blue-500/25 transition-colors">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-primary mb-2">Property Management</h3>
                    <p class="text-secondary text-sm">Organize properties and units with detailed information. Track occupancy, market rent, and unit status.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card glass-card rounded-xl sm:rounded-2xl p-5 sm:p-6 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-purple-500/15 flex items-center justify-center mb-3 sm:mb-4 group-hover:bg-purple-500/25 transition-colors">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-primary mb-2">Tenant Management</h3>
                    <p class="text-secondary text-sm">Store tenant details, contact info, employment history, and emergency contacts all in one place.</p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card glass-card rounded-xl sm:rounded-2xl p-5 sm:p-6 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-cyan-500/15 flex items-center justify-center mb-3 sm:mb-4 group-hover:bg-cyan-500/25 transition-colors">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-primary mb-2">Lease Tracking</h3>
                    <p class="text-secondary text-sm">Manage lease terms, rent amounts, pet deposits, and get alerts when leases are expiring.</p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card glass-card rounded-xl sm:rounded-2xl p-5 sm:p-6 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-amber-500/15 flex items-center justify-center mb-3 sm:mb-4 group-hover:bg-amber-500/25 transition-colors">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-primary mb-2">Maintenance Requests</h3>
                    <p class="text-secondary text-sm">Track repair requests with priority levels, assign vendors, and keep tenants informed with status updates.</p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card glass-card rounded-xl sm:rounded-2xl p-5 sm:p-6 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-rose-500/15 flex items-center justify-center mb-3 sm:mb-4 group-hover:bg-rose-500/25 transition-colors">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-primary mb-2">Dashboard Analytics</h3>
                    <p class="text-secondary text-sm">See collection rates, occupancy stats, upcoming payments, and expiring leases at a glance.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-16 sm:py-24" style="background-color: var(--bg-secondary);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary mb-3 sm:mb-4">Simple Pricing</h2>
                <p class="text-base sm:text-lg text-secondary max-w-2xl mx-auto">Start free, upgrade as you grow.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 max-w-5xl mx-auto">
                <!-- Free -->
                <div class="pricing-card rounded-xl sm:rounded-2xl p-6 sm:p-8">
                    <h3 class="text-base sm:text-lg font-semibold text-primary mb-2">Starter</h3>
                    <p class="text-muted text-xs sm:text-sm mb-4 sm:mb-6">Perfect for getting started</p>
                    <div class="mb-4 sm:mb-6">
                        <span class="text-3xl sm:text-4xl font-bold text-primary">$0</span>
                        <span class="text-muted text-sm">/month</span>
                    </div>
                    <ul class="space-y-2 sm:space-y-3 mb-6 sm:mb-8">
                        <li class="flex items-center text-xs sm:text-sm text-secondary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Up to 5 units
                        </li>
                        <li class="flex items-center text-xs sm:text-sm text-secondary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Payment tracking
                        </li>
                        <li class="flex items-center text-xs sm:text-sm text-secondary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Maintenance requests
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-secondary w-full h-11 sm:h-12 rounded-lg sm:rounded-xl font-medium flex items-center justify-center text-sm">Get Started</a>
                </div>

                <!-- Pro -->
                <div class="pricing-card featured rounded-xl sm:rounded-2xl p-6 sm:p-8 relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-[10px] sm:text-xs font-bold px-3 sm:px-4 py-1 rounded-full">POPULAR</div>
                    <h3 class="text-base sm:text-lg font-semibold text-primary mb-2">Professional</h3>
                    <p class="text-muted text-xs sm:text-sm mb-4 sm:mb-6">For growing portfolios</p>
                    <div class="mb-4 sm:mb-6">
                        <span class="text-3xl sm:text-4xl font-bold text-primary">$29</span>
                        <span class="text-muted text-sm">/month</span>
                    </div>
                    <ul class="space-y-2 sm:space-y-3 mb-6 sm:mb-8">
                        <li class="flex items-center text-xs sm:text-sm text-secondary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Up to 50 units
                        </li>
                        <li class="flex items-center text-xs sm:text-sm text-secondary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Everything in Starter
                        </li>
                        <li class="flex items-center text-xs sm:text-sm text-secondary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Online payments
                        </li>
                        <li class="flex items-center text-xs sm:text-sm text-secondary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Tenant portal
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-primary w-full h-11 sm:h-12 rounded-lg sm:rounded-xl font-medium flex items-center justify-center text-sm">Start Free Trial</a>
                </div>

                <!-- Enterprise -->
                <div class="pricing-card rounded-xl sm:rounded-2xl p-6 sm:p-8">
                    <h3 class="text-base sm:text-lg font-semibold text-primary mb-2">Enterprise</h3>
                    <p class="text-muted text-xs sm:text-sm mb-4 sm:mb-6">For large portfolios</p>
                    <div class="mb-4 sm:mb-6">
                        <span class="text-3xl sm:text-4xl font-bold text-primary">$99</span>
                        <span class="text-muted text-sm">/month</span>
                    </div>
                    <ul class="space-y-2 sm:space-y-3 mb-6 sm:mb-8">
                        <li class="flex items-center text-xs sm:text-sm text-secondary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Unlimited units
                        </li>
                        <li class="flex items-center text-xs sm:text-sm text-secondary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Everything in Pro
                        </li>
                        <li class="flex items-center text-xs sm:text-sm text-secondary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Priority support
                        </li>
                        <li class="flex items-center text-xs sm:text-sm text-secondary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Custom integrations
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-secondary w-full h-11 sm:h-12 rounded-lg sm:rounded-xl font-medium flex items-center justify-center text-sm">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 sm:py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="glass-card rounded-2xl sm:rounded-3xl p-6 sm:p-8 md:p-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary mb-3 sm:mb-4">Ready to simplify your property management?</h2>
                <p class="text-base sm:text-lg text-secondary mb-6 sm:mb-8">Join hundreds of landlords who are saving time and money with Mirvan.</p>
                <a href="{{ route('register') }}" class="btn-primary h-12 sm:h-14 px-6 sm:px-8 rounded-xl font-semibold text-base sm:text-lg inline-flex items-center justify-center">
                    Get Started for Free
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="border-t border-primary py-10 sm:py-12" style="background-color: var(--bg-secondary);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <a href="/" class="flex items-center space-x-2 sm:space-x-3 mb-4">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center">
                            <span class="text-white font-bold text-base sm:text-lg">M</span>
                        </div>
                        <span class="text-lg sm:text-xl font-bold text-primary">Mirvan</span>
                    </a>
                    <p class="text-secondary text-xs sm:text-sm max-w-sm">Property management software designed for landlords who want simplicity without sacrificing power.</p>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-semibold text-primary uppercase tracking-wide mb-3 sm:mb-4">Product</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-secondary hover:text-primary text-xs sm:text-sm transition-colors">Features</a></li>
                        <li><a href="#pricing" class="text-secondary hover:text-primary text-xs sm:text-sm transition-colors">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-semibold text-primary uppercase tracking-wide mb-3 sm:mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li><a href="/cdn-cgi/l/email-protection#285b5d5858475a5c6845415a5e494606495858" class="text-secondary hover:text-primary text-xs sm:text-sm transition-colors break-all"><span class="__cf_email__" data-cfemail="b7c4c2c7c7d8c5c3f7dadec5c1d6d999d6c7c7">[email&#160;protected]</span></a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-primary mt-8 sm:mt-12 pt-6 sm:pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-muted text-xs sm:text-sm">© {{ date('Y') }} Mirvan. All rights reserved.</p>
                <div class="flex items-center space-x-4 sm:space-x-6">
                    <a href="#" class="text-muted hover:text-primary text-xs sm:text-sm transition-colors">Privacy</a>
                    <a href="#" class="text-muted hover:text-primary te