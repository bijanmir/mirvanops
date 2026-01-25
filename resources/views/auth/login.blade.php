<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('theme') || 'dark' }" x-init="document.documentElement.setAttribute('data-theme', theme)" :data-theme="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In - Mirvan Properties</title>
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
        
        /* Checkbox styling */
        .checkbox-custom {
            width: 1rem;
            height: 1rem;
            border-radius: 0.25rem;
            border: 1px solid var(--border-secondary);
            background: var(--bg-input);
            appearance: none;
            cursor: pointer;
            transition: all 0.2s ease;
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
                <span class="text-2xl font-light text-accent">Properties</span>
            </div>
        </a>

        <!-- Login Card -->
        <div class="glass-card rounded-2xl p-8 w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-primary mb-2">Welcome back</h1>
                <p class="text-secondary">Sign in to your account</p>
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

            @if (session('status'))
            <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20">
                <p class="text-sm text-green-400">{{ session('status') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-secondary mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="input-field w-full px-4 py-3 rounded-xl" placeholder="you@example.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-secondary mb-2">Password</label>
                    <input type="password" id="password" name="password" required class="input-field w-full px-4 py-3 rounded-xl" placeholder="••••••••">
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

                <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold">
                    Sign In
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-secondary text-sm">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-accent hover:underline font-medium">Sign up</a>
                </p>
            </div>
        </div>

    </div>
</body>
</html>