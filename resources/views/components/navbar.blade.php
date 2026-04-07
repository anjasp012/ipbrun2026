@auth
    @php
        $user = auth()->user();
        $is_admin = $user->role === 'admin';
        $initials = strtoupper(substr($user->name ?? 'U', 0, 1));
    @endphp
@endauth

<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <!-- Logo & Brand -->
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('assets/images/logo_ipbrun2026.png') }}" 
                         alt="IPB RUN 2026" 
                         class="h-10 md:h-12 w-auto drop-shadow-sm">
                </a>
            </div>

            <!-- Navigation Actions -->
            <div class="flex items-center gap-3">
                @auth
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-100">
                        <div class="text-right hidden md:block">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                            <p class="text-xs font-black text-[#003366] truncate max-w-[150px]">{{ $user->name }}</p>
                        </div>
                        
                        <div class="relative group">
                            <button class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-black text-sm border border-orange-200 shadow-inner overflow-hidden">
                                {{ $initials }}
                            </button>
                        </div>

                        @if($is_admin)
                            <a href="{{ url('/admin/dashboard') }}"
                                class="hidden sm:inline-flex items-center px-4 h-10 bg-blue-50 text-blue-600 rounded-xl font-black text-[10px] uppercase tracking-widest border border-blue-100 hover:bg-blue-100 transition-all">
                                Admin
                            </a>
                        @else
                           <a href="{{ route('participant.dashboard') }}"
                                class="hidden sm:inline-flex items-center px-4 h-10 bg-slate-50 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest border border-slate-200 hover:bg-slate-100 transition-all">
                                Dashboard
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-3 px-6 h-12 bg-[#003366] hover:bg-[#002244] text-white rounded-2xl font-black text-[11px] uppercase tracking-[2px] shadow-xl shadow-blue-900/10 transition-all active:scale-95 group">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Masuk Akun
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
