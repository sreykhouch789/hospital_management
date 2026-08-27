<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('app.dashboard_title')) - MediPulse</title>
    
    <!-- Google Fonts: Inter & Kantumruy Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN for instant styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Kantumruy Pro', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        hospital: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            dark: '#0f172a',
                            card: '#1e293b',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Kantumruy Pro', 'Inter', sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
        }
        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .sidebar-link {
            transition: all 0.2s ease-in-out;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.05) 100%);
            border-left: 4px solid #10b981;
            color: #34d399;
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-slate-950/80 backdrop-blur-md border-r border-slate-800 flex flex-col justify-between shrink-0">
        <div>
            <!-- Brand Logo -->
            <div class="p-6 flex items-center gap-3 border-b border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-xl shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-hospital"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold tracking-wide text-white">Medi<span class="text-emerald-400">Pulse</span></h1>
                    <p class="text-xs text-slate-400 font-medium">{{ __('app.brand_subtitle') }}</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="mt-6 px-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-r-xl font-medium text-sm text-slate-300 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-emerald-400"></i>
                    <span>{{ __('app.nav_dashboard') }}</span>
                </a>

                <a href="{{ route('appointments.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-r-xl font-medium text-sm text-slate-300 {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check w-5 text-center text-emerald-400"></i>
                    <span>{{ __('app.nav_appointments') }}</span>
                </a>

                <a href="{{ route('doctors.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-r-xl font-medium text-sm text-slate-300 {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-doctor w-5 text-center text-emerald-400"></i>
                    <span>{{ __('app.nav_doctors') }}</span>
                </a>

                <a href="{{ route('patients.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-r-xl font-medium text-sm text-slate-300 {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-procedures w-5 text-center text-emerald-400"></i>
                    <span>{{ __('app.nav_patients') }}</span>
                </a>

                <a href="{{ route('prescriptions.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-r-xl font-medium text-sm text-slate-300 {{ request()->routeIs('prescriptions.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-prescription-bottle-medical w-5 text-center text-emerald-400"></i>
                    <span>{{ __('app.nav_prescriptions') }}</span>
                </a>

                <a href="{{ route('rooms.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-r-xl font-medium text-sm text-slate-300 {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-bed w-5 text-center text-emerald-400"></i>
                    <span>{{ __('app.nav_rooms') }}</span>
                </a>

                <a href="{{ route('bills.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-r-xl font-medium text-sm text-slate-300 {{ request()->routeIs('bills.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-emerald-400"></i>
                    <span>{{ __('app.nav_bills') }}</span>
                </a>
            </nav>
        </div>

        <!-- Footer Profile Info -->
        <div class="p-4 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-emerald-400">
                    A
                </div>
                <div>
                    <p class="text-xs font-semibold text-white">{{ __('app.admin_staff') }}</p>
                    <span class="inline-flex items-center gap-1 text-[10px] text-emerald-400 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> {{ __('app.system_active') }}
                    </span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Header -->
        <header class="h-16 border-b border-slate-800 bg-slate-900/60 backdrop-blur-md px-6 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <h2 class="text-lg font-semibold text-white">@yield('title', __('app.dashboard_title'))</h2>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Language Switcher Toggle -->
                <div class="flex items-center gap-1 bg-slate-800/80 border border-slate-700/80 p-1 rounded-xl text-xs">
                    <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 rounded-lg font-semibold transition-all flex items-center gap-1.5 {{ app()->getLocale() === 'en' ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/20' : 'text-slate-400 hover:text-white' }}">
                        <span>🇬🇧</span> <span class="hidden md:inline">English</span>
                    </a>
                    <a href="{{ route('lang.switch', 'km') }}" class="px-2.5 py-1 rounded-lg font-semibold transition-all flex items-center gap-1.5 {{ app()->getLocale() === 'km' ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/20' : 'text-slate-400 hover:text-white' }}">
                        <span>🇰🇭</span> <span class="hidden md:inline">ភាសាខ្មែរ</span>
                    </a>
                </div>

                <!-- Search bar -->
                <div class="relative hidden lg:block">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" placeholder="{{ __('app.search_placeholder') }}" class="bg-slate-800/80 border border-slate-700 text-xs text-slate-200 rounded-lg pl-8 pr-4 py-2 focus:outline-none focus:border-emerald-500 w-56 transition-all">
                </div>

                <!-- Emergency Contact / New Appointment Button -->
                <a href="{{ route('appointments.index') }}" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 font-semibold text-xs px-4 py-2 rounded-lg shadow-md shadow-emerald-500/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> <span>{{ __('app.new_appointment') }}</span>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mx-6 mt-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Content Slot -->
        <div class="p-6 space-y-6">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
