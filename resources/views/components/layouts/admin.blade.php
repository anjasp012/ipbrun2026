@props(['title' => 'Admin - IPB RUN 2026'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; }
        .sidebar { background: #003366; }
        .sidebar-item { transition: all 0.2s; color: rgba(255, 255, 255, 0.7); }
        .sidebar-item:hover { background: rgba(255, 255, 255, 0.1); color: white; }
        .sidebar-item.active { background: rgba(255, 255, 255, 0.15); color: white; border-left: 4px solid #E8630A; }
    </style>
    @stack('styles')
</head>
<body class="antialiased text-slate-800">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 sidebar text-white flex-shrink-0 flex flex-col fixed h-full z-50">
            <div class="p-6 text-center border-b border-white/10">
                <h1 class="text-xl font-black uppercase tracking-widest text-[#E8630A]">IPB RUN <span class="text-white">2026</span></h1>
                <p class="text-[10px] uppercase font-bold tracking-[3px] mt-1 opacity-50">ADMIN DASHBOARD</p>
            </div>
            
            <nav class="flex-1 p-4 space-y-2 mt-4">
                <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl sidebar-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="font-bold text-sm tracking-wide">Overview</span>
                </a>
                <a href="{{ url('/admin/participants') }}" class="flex items-center gap-3 p-3 rounded-xl sidebar-item {{ request()->is('admin/participants*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="font-bold text-sm tracking-wide">Participants</span>
                </a>
                <a href="{{ url('/admin/categories') }}" class="flex items-center gap-3 p-3 rounded-xl sidebar-item {{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span class="font-bold text-sm tracking-wide">Ticket Categories</span>
                </a>
                <a href="{{ url('/admin/tickets') }}" class="flex items-center gap-3 p-3 rounded-xl sidebar-item {{ request()->is('admin/tickets*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    <span class="font-bold text-sm tracking-wide">Periods & Tickets</span>
                </a>
            </nav>

            <div class="p-4 mt-auto border-t border-white/10">
                <a href="{{ url('/') }}" class="flex items-center gap-2 p-3 text-xs font-bold text-white/50 hover:text-white transition-colors capitalize">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    View Website Front
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64 min-h-screen">
            <header class="bg-white h-20 border-b border-slate-200 sticky top-0 z-40 px-8 flex items-center justify-between shadow-sm">
                <h2 class="text-xl font-black text-[#003366] uppercase tracking-tight">{{ $title }}</h2>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name ?? 'Admin Master' }}</p>
                        <p class="text-[10px] font-black text-[#E8630A] uppercase tracking-wider">Super Administrator</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-[#003366] font-black">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <form action="{{ url('/logout') }}" method="POST" class="ml-4">
                        @csrf
                        <button class="p-2 text-slate-400 hover:text-rose-600 transition-colors" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </header>

            <main class="p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')
</body>
</html>
