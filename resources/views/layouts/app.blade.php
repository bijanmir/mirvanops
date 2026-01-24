<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') }" x-init="$watch('theme', val => { localStorage.setItem('theme', val); document.documentElement.setAttribute('data-theme', val) }); document.documentElement.setAttribute('data-theme', theme)" :data-theme="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - MirvanOps</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <!-- Alpine.js -->
    
    <style>
        /* ========================================
           Theme Variables
        ======================================== */
        :root {
            --transition-speed: 200ms;
        }

        /* Dark Theme (Default) */
        [data-theme="dark"] {
            --bg-primary: #030712;
            --bg-secondary: #0a0f1a;
            --bg-tertiary: #111827;
            --bg-card: rgba(255, 255, 255, 0.02);
            --bg-card-hover: rgba(255, 255, 255, 0.04);
            --bg-input: rgba(255, 255, 255, 0.03);
            --bg-input-focus: rgba(255, 255, 255, 0.06);
            
            --border-primary: rgba(255, 255, 255, 0.06);
            --border-secondary: rgba(255, 255, 255, 0.1);
            --border-hover: rgba(255, 255, 255, 0.15);
            
            --text-primary: #f9fafb;
            --text-secondary: rgba(255, 255, 255, 0.7);
            --text-muted: rgba(255, 255, 255, 0.5);
            --text-subtle: rgba(255, 255, 255, 0.3);
            
            --accent: #f59e0b;
            --accent-hover: #fbbf24;
            --accent-muted: rgba(245, 158, 11, 0.15);
            --accent-glow: rgba(245, 158, 11, 0.25);
            
            --success: #10b981;
            --success-muted: rgba(16, 185, 129, 0.15);
            --warning: #f59e0b;
            --warning-muted: rgba(245, 158, 11, 0.15);
            --danger: #ef4444;
            --danger-muted: rgba(239, 68, 68, 0.15);
            --info: #3b82f6;
            --info-muted: rgba(59, 130, 246, 0.15);
            
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.5);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.5);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.5);
            --shadow-xl: 0 25px 50px rgba(0, 0, 0, 0.6);
            
            --gradient-mesh: 
                radial-gradient(ellipse at 0% 0%, rgba(245, 158, 11, 0.03) 0%, transparent 50%),
                radial-gradient(ellipse at 100% 100%, rgba(59, 130, 246, 0.03) 0%, transparent 50%);
        }

        /* Light Theme */
        [data-theme="light"] {
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --bg-tertiary: #f3f4f6;
            --bg-card: rgba(255, 255, 255, 0.8);
            --bg-card-hover: rgba(255, 255, 255, 0.95);
            --bg-input: rgba(0, 0, 0, 0.02);
            --bg-input-focus: rgba(0, 0, 0, 0.04);
            
            --border-primary: rgba(0, 0, 0, 0.06);
            --border-secondary: rgba(0, 0, 0, 0.1);
            --border-hover: rgba(0, 0, 0, 0.15);
            
            --text-primary: #111827;
            --text-secondary: rgba(0, 0, 0, 0.7);
            --text-muted: rgba(0, 0, 0, 0.5);
            --text-subtle: rgba(0, 0, 0, 0.3);
            
            --accent: #d97706;
            --accent-hover: #b45309;
            --accent-muted: rgba(217, 119, 6, 0.1);
            --accent-glow: rgba(217, 119, 6, 0.2);
            
            --success: #059669;
            --success-muted: rgba(5, 150, 105, 0.1);
            --warning: #d97706;
            --warning-muted: rgba(217, 119, 6, 0.1);
            --danger: #dc2626;
            --danger-muted: rgba(220, 38, 38, 0.1);
            --info: #2563eb;
            --info-muted: rgba(37, 99, 235, 0.1);
            
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 25px 50px rgba(0, 0, 0, 0.15);
            
            --gradient-mesh: 
                radial-gradient(ellipse at 0% 0%, rgba(245, 158, 11, 0.05) 0%, transparent 50%),
                radial-gradient(ellipse at 100% 100%, rgba(59, 130, 246, 0.05) 0%, transparent 50%);
        }

        /* ========================================
           Base Styles
        ======================================== */
        * {
            transition: background-color var(--transition-speed) ease,
                        border-color var(--transition-speed) ease,
                        color var(--transition-speed) ease;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background-color: var(--bg-primary);
            background-image: var(--gradient-mesh);
            background-attachment: fixed;
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* ========================================
           Component Styles
        ======================================== */
        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-primary);
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            background: var(--bg-card-hover);
            border-color: var(--border-secondary);
            box-shadow: var(--shadow-lg);
        }

        .sidebar {
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-primary);
        }

        .sidebar-link {
            color: var(--text-muted);
            position: relative;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            color: var(--text-primary);
            background: var(--bg-input);
        }

        .sidebar-link.active {
            color: var(--accent);
            background: var(--accent-muted);
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: var(--accent);
            border-radius: 0 4px 4px 0;
            transition: height 0.2s ease;
        }

        .sidebar-link:hover::before,
        .sidebar-link.active::before {
            height: 50%;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-primary);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, var(--accent-muted) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            color: white;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px var(--accent-glow);
        }

        .btn-secondary {
            background: var(--bg-input);
            border: 1px solid var(--border-secondary);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background: var(--bg-input-focus);
            border-color: var(--border-hover);
        }

        .input-field {
            background: var(--bg-input);
            border: 1px solid var(--border-primary);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .input-field::placeholder {
            color: var(--text-subtle);
        }

        .input-field:focus {
            background: var(--bg-input-focus);
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-muted);
            outline: none;
        }

        .text-primary { color: var(--text-primary); }
        .text-secondary { color: var(--text-secondary); }
        .text-muted { color: var(--text-muted); }
        .text-subtle { color: var(--text-subtle); }
        .text-accent { color: var(--accent); }

        .bg-card { background: var(--bg-card); }
        .bg-input { background: var(--bg-input); }
        .border-primary { border-color: var(--border-primary); }
        .border-secondary { border-color: var(--border-secondary); }

        /* Badge Colors */
        .badge-success {
            background: var(--success-muted);
            color: var(--success);
        }

        .badge-warning {
            background: var(--warning-muted);
            color: var(--warning);
        }

        .badge-danger {
            background: var(--danger-muted);
            color: var(--danger);
        }

        .badge-info {
            background: var(--info-muted);
            color: var(--info);
        }

        /* ========================================
           Animations
        ======================================== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulse-slow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 0.3; }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-pulse-slow {
            animation: pulse-slow 4s ease-in-out infinite;
        }

        /* ========================================
           Scrollbar
        ======================================== */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-input);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-secondary);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-subtle);
        }

        /* ========================================
           Orbs (Dark mode only)
        ======================================== */
        [data-theme="dark"] .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
        }

        [data-theme="dark"] .orb-1 {
            width: 500px;
            height: 500px;
            background: rgba(245, 158, 11, 0.08);
            top: -200px;
            right: -100px;
        }

        [data-theme="dark"] .orb-2 {
            width: 400px;
            height: 400px;
            background: rgba(59, 130, 246, 0.06);
            bottom: 10%;
            left: 10%;
        }

        [data-theme="light"] .orb {
            display: none;
        }

        /* Theme Toggle Button */
        .theme-toggle {
            background: var(--bg-input);
            border: 1px solid var(--border-primary);
            color: var(--text-muted);
            transition: all 0.2s ease;
        }

        .theme-toggle:hover {
            background: var(--bg-input-focus);
            border-color: var(--border-secondary);
            color: var(--text-primary);
        }
    </style>
</head>
<body class="antialiased">
    <!-- Background orbs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="orb orb-1 animate-pulse-slow"></div>
        <div class="orb orb-2 animate-float"></div>
    </div>

    <div class="min-h-screen flex relative">
        <!-- Sidebar -->
        <aside class="sidebar w-72 fixed h-full z-30 hidden lg:block">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="h-20 flex items-center px-6 border-b border-primary">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg group-hover:shadow-amber-500/30 transition-shadow">
                            <span class="text-white font-bold text-lg">M</span>
                        </div>
                        <div>
                            <span class="text-xl font-bold text-primary">Mirvan</span>
                            <span class="text-xl font-light text-accent">Ops</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
                    <p class="px-4 text-xs font-semibold text-subtle uppercase tracking-wider mb-4">Main Menu</p>
                    
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('properties.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('properties.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Properties
                    </a>

                    <a href="{{ route('tenants.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Tenants
                    </a>

                    <p class="px-4 text-xs font-semibold text-subtle uppercase tracking-wider mb-4 mt-8">Operations</p>

                    <a href="{{ route('maintenance.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Maintenance
                        @php
                            $openRequests = auth()->user()->company->maintenanceRequests()->whereIn('status', ['new', 'assigned', 'in_progress'])->count();
                        @endphp
                        @if($openRequests > 0)
                        <span class="ml-auto badge-warning text-xs font-semibold px-2 py-0.5 rounded-full">{{ $openRequests }}</span>
                        @endif
                    </a>

                    <a href="{{ route('vendors.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('vendors.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Vendors
                    </a>
                </nav>

                <!-- User Menu & Theme Toggle -->
                <div class="border-t border-primary p-4 space-y-3">
                    <!-- Theme Toggle -->
                    <div class="flex items-center justify-between px-3 py-2">
                        <span class="text-sm text-muted">Theme</span>
                        <button 
                            @click="theme = theme === 'dark' ? 'light' : 'dark'"
                            class="theme-toggle p-2 rounded-lg"
                        >
                            <!-- Sun icon (shown in dark mode) -->
                            <svg x-show="theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <!-- Moon icon (shown in light mode) -->
                            <svg x-show="theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                    </div>

                    <!-- User Info -->
                    <div class="flex items-center p-3 rounded-xl bg-input group cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <span class="text-sm font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-primary truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-muted truncate">{{ auth()->user()->company->name }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            <button type="submit" class="p-2 text-muted hover:text-primary rounded-lg hover:bg-input transition-colors" title="Sign out">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Header -->
        <div class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-card backdrop-blur-xl border-b border-primary z-20 flex items-center justify-between px-4">
            <button onclick="document.getElementById('mobile-menu').classList.remove('translate-x-full')" class="p-2 rounded-xl text-muted hover:text-primary hover:bg-input transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center">
                    <span class="text-white font-bold text-sm">M</span>
                </div>
                <span class="ml-2 text-lg font-semibold text-primary">MirvanOps</span>
            </div>
            <!-- Mobile Theme Toggle -->
            <button 
                @click="theme = theme === 'dark' ? 'light' : 'dark'"
                class="theme-toggle p-2 rounded-lg"
            >
                <svg x-show="theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg x-show="theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden fixed inset-0 z-50 translate-x-full transition-transform duration-300">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="document.getElementById('mobile-menu').classList.add('translate-x-full')"></div>
            <div class="absolute right-0 top-0 bottom-0 w-72 bg-card border-l border-primary">
                <div class="h-16 flex items-center justify-between px-6 border-b border-primary">
                    <span class="text-lg font-semibold text-primary">Menu</span>
                    <button onclick="document.getElementById('mobile-menu').classList.add('translate-x-full')" class="p-2 rounded-xl text-muted hover:text-primary hover:bg-input transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="px-4 py-6 space-y-2">
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('properties.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('properties.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Properties
                    </a>
                    <a href="{{ route('tenants.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Tenants
                    </a>
                    <a href="{{ route('maintenance.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Maintenance
                    </a>
                    <a href="{{ route('vendors.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('vendors.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Vendors
                    </a>
                </nav>
                
                <!-- Mobile User Menu -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-primary">
                    <div class="flex items-center p-3 rounded-xl bg-input">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <span class="text-sm font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-primary truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-muted truncate">{{ auth()->user()->company->name }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-muted hover:text-primary rounded-lg hover:bg-input transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-72">
            <div class="lg:hidden h-16"></div>
            
            <div class="p-4 sm:p-6 lg:p-8 animate-fade-in">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 glass-card rounded-xl p-4 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-green-500 text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 glass-card rounded-xl p-4 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-red-500 text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>