<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - MirvanOps</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        :root {
            --color-primary: #f59e0b;
            --color-primary-dark: #d97706;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            min-height: 100vh;
        }

        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-light {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        }

        .glow {
            box-shadow: 0 0 40px rgba(245, 158, 11, 0.15);
        }

        .glow-text {
            text-shadow: 0 0 30px rgba(245, 158, 11, 0.5);
        }

        .gradient-text {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #f59e0b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .animate-pulse-slow {
            animation: pulse-slow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 8s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .sidebar-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: linear-gradient(180deg, #f59e0b, #fbbf24);
            border-radius: 0 4px 4px 0;
            transition: height 0.3s ease;
        }

        .sidebar-link:hover::before,
        .sidebar-link.active::before {
            height: 60%;
        }

        .sidebar-link.active {
            background: rgba(245, 158, 11, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
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
            box-shadow: 0 10px 40px rgba(245, 158, 11, 0.3);
        }

        .stat-card {
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
            background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, transparent 70%);
            transition: all 0.5s ease;
        }

        .stat-card:hover::before {
            transform: scale(1.5);
        }

        .input-glass {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .input-glass:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(245, 158, 11, 0.5);
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.1);
            outline: none;
        }

        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Page transition */
        .page-enter {
            animation: pageEnter 0.4s ease-out;
        }

        @keyframes pageEnter {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Orb backgrounds */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            pointer-events: none;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.3) 0%, transparent 70%);
            top: -100px;
            right: -100px;
        }

        .orb-2 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
            bottom: 10%;
            left: 20%;
        }

        .orb-3 {
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, transparent 70%);
            top: 40%;
            right: 10%;
        }
    </style>
</head>
<body class="antialiased">
    <!-- Background orbs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="orb orb-1 animate-pulse-slow"></div>
        <div class="orb orb-2 animate-float"></div>
        <div class="orb orb-3 animate-pulse-slow" style="animation-delay: 2s;"></div>
    </div>

    <div class="min-h-screen flex relative">
        <!-- Sidebar -->
        <aside class="w-72 glass fixed h-full z-30 hidden lg:block">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="h-20 flex items-center px-6 border-b border-white/10">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:shadow-amber-500/50 transition-shadow">
                            <span class="text-white font-bold text-lg">M</span>
                        </div>
                        <div>
                            <span class="text-xl font-bold text-white">Mirvan</span>
                            <span class="text-xl font-light text-amber-400">Ops</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
                    <p class="px-4 text-xs font-semibold text-white/40 uppercase tracking-wider mb-4">Main Menu</p>
                    
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('dashboard') ? 'active text-amber-400' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('properties.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('properties.*') ? 'active text-amber-400' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Properties
                    </a>

                    <a href="{{ route('tenants.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('tenants.*') ? 'active text-amber-400' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Tenants
                    </a>

                    <p class="px-4 text-xs font-semibold text-white/40 uppercase tracking-wider mb-4 mt-8">Operations</p>

                    <a href="{{ route('maintenance.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('maintenance.*') ? 'active text-amber-400' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Maintenance
                        @php
                            $openRequests = auth()->user()->company->maintenanceRequests()->whereIn('status', ['new', 'assigned', 'in_progress'])->count();
                        @endphp
                        @if($openRequests > 0)
                        <span class="ml-auto bg-amber-500/20 text-amber-400 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $openRequests }}</span>
                        @endif
                    </a>

                    <a href="{{ route('vendors.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('vendors.*') ? 'active text-amber-400' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Vendors
                    </a>
                </nav>

                <!-- User Menu -->
                <div class="border-t border-white/10 p-4">
                    <div class="flex items-center p-3 rounded-xl hover:bg-white/5 transition-colors cursor-pointer group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <span class="text-sm font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-white/50 truncate">{{ auth()->user()->company->name }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            <button type="submit" class="p-2 text-white/40 hover:text-white rounded-lg hover:bg-white/10 transition-colors">
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
        <div class="lg:hidden fixed top-0 left-0 right-0 h-16 glass z-20 flex items-center px-4">
            <button onclick="document.getElementById('mobile-menu').classList.remove('translate-x-full')" class="p-2 rounded-xl text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="ml-3 flex items-center">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                    <span class="text-white font-bold text-sm">M</span>
                </div>
                <span class="ml-2 text-lg font-semibold text-white">MirvanOps</span>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden fixed inset-0 z-50 translate-x-full transition-transform duration-300">
            <div class="absolute inset-0 bg-black/60" onclick="document.getElementById('mobile-menu').classList.add('translate-x-full')"></div>
            <div class="absolute right-0 top-0 bottom-0 w-72 glass">
                <div class="h-16 flex items-center justify-between px-6 border-b border-white/10">
                    <span class="text-lg font-semibold text-white">Menu</span>
                    <button onclick="document.getElementById('mobile-menu').classList.add('translate-x-full')" class="p-2 rounded-xl text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="px-4 py-6 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('dashboard') ? 'bg-amber-500/10 text-amber-400' : 'text-white/70' }}">Dashboard</a>
                    <a href="{{ route('properties.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('properties.*') ? 'bg-amber-500/10 text-amber-400' : 'text-white/70' }}">Properties</a>
                    <a href="{{ route('tenants.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('tenants.*') ? 'bg-amber-500/10 text-amber-400' : 'text-white/70' }}">Tenants</a>
                    <a href="{{ route('maintenance.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('maintenance.*') ? 'bg-amber-500/10 text-amber-400' : 'text-white/70' }}">Maintenance</a>
                    <a href="{{ route('vendors.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('vendors.*') ? 'bg-amber-500/10 text-amber-400' : 'text-white/70' }}">Vendors</a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-72">
            <div class="lg:hidden h-16"></div>
            
            <div class="p-6 lg:p-8 page-enter">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 glass-card rounded-xl p-4 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-green-400 text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 glass-card rounded-xl p-4 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-red-400 text-sm font-medium">{{ session('error') }}</p>
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