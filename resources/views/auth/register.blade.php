<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('theme') || 'dark' }" x-init="document.documentElement.setAttribute('data-theme', theme)" :data-theme="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account - MirvanOps</title>
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
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --bg-input: #ffffff;
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
        
        .glass-card { 
            background: var(--bg-card); 
            border: 1px solid var(--border-primary); 
        }
        
        [data-theme="dark"] .glass-card {
            backdrop-filter: blur(20px);
        }
        
        [data-theme="light"] .glass-card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }
        
        .input-field { 
            background: var(--bg-input); 
            border: 1px solid var(--border-primary); 
            color: var(--text-primary);
            transition: all 0.2s ease;
        }
        
        [data-theme="light"] .input-field {
            border: 1px solid var(--border-secondary);
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
        
        .text-primary { color: var(--text-primary); }
        .text-secondary { color: var(--text-secondary); }
        .text-muted { color: var(--text-muted); }
        .text-accent { color: var(--accent); }
        
        [data-theme="dark"] .orb { 
            position: absolute; 
            border-radius: 50%; 
            filter: blur(100px); 
            pointer-events: none; 
        }
        [data-theme="dark"] .orb-1 { width: 500px; height: 500px; background: rgba(245, 158, 11, 0.08); top: -200px; right: -100px; }
        [data-theme="dark"] .orb-2 { width: 400px; height: 400px; background: rgba(59, 130, 246, 0.06); bottom: -100px; left: -100px; }
        [data-theme="light"] .orb { display: none; }
        
        /* Theme toggle */
        .theme-toggle {
            padding: 0.5rem;
            border-radius: 0.5rem;
            color: var(--text-muted);
            transition: all 0.2s ease;
        }
        
        .theme-toggle:hover {
            color: var(--text-primary);
            background: var(--bg-card);
        }
        
        [data-theme="light"] .theme-toggle:hover {
            background: #e2e8f0;
        }
    </style>
</head>
<body class="antialiased">
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12 relative">
        <!-- Logo -->
        <a href="/" class="flex items-center space-x-3 mb-8 group">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg group-hover:shadow-amber-500/30 transition-shadow">
                <span class="text-white font-bold text-xl">M</span>
            </div>
            <div>
                <span class="text-2xl font-bold text-primary">Mirvan</span>
                <span class="text-2xl font-light text-accent">Ops</span>
            </div>
        </a>

        <!-- Register Card -->
        <div class="glass-card rounded-2xl p-8 w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-primary mb-2">Create your account</h1>
                <p class="text-secondary">Start managing properties in minutes</p>
            </div>

            @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20">
                <ul class="text-sm text-red-400 space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                
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

                <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold">
                    Create Account
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-muted text-xs">
                    By signing up, you agree to our 
                    <a href="#" class="text-accent hover:underline">Terms of Service</a> and 
                    <a href="#" class="text-accent hover:underline">Privacy Policy</a>
                </p>
            </div>

            <div class="mt-8 text-center">
                <p class="text-secondary text-sm">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-accent hover:underline font-medium">Sign in</a>
                </p>
            </div>
        </div>

        <!-- Theme Toggle -->
        <button @click="theme = theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('theme', theme); document.documentElement.setAttribute('data-theme', theme)" class="theme-toggle mt-6">
            <svg x-cloak x-show="theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            <svg x-cloak x-show="theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
        </button>
    </div>
</body>
</html>