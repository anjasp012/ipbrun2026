<x-layouts.app>
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="h-screen overflow-hidden flex items-center justify-center p-4">
        <div
            class="max-w-auto w-full max-h-[98vh] bg-white rounded-3xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.15)] border border-slate-100 relative flex flex-col p-10 md:p-12 overflow-hidden">
            <!-- Header / Auth Navigation -->
            <div class="absolute top-0 left-0 right-0 h-20 px-10 md:px-12 flex items-center justify-between border-b border-slate-100 bg-white/80 backdrop-blur-md z-30">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-blue-600 flex items-center justify-center font-black text-white text-lg shadow-lg shadow-blue-200">I</div>
                    <div class="hidden sm:block">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[3px] leading-tight">Official Platform</p>
                        <h1 class="text-lg font-black text-[#003366] uppercase leading-tight tracking-tighter">IPB RUN 2026</h1>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    @auth
                        <div class="flex items-center gap-4 px-5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl">
                            <div class="text-right hidden md:block">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                                <p class="text-xs font-black text-[#003366] truncate max-w-[150px]">{{ auth()->user()->name }}</p>
                            </div>
                            <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-black text-xs border border-orange-200 shadow-inner">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ url('/admin/dashboard') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest border border-blue-200 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-all">Dashboard</a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-3 px-6 h-12 bg-[#003366] hover:bg-[#002244] text-white rounded-2xl font-black text-[11px] uppercase tracking-[2px] shadow-xl shadow-blue-900/10 transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Masuk Akun
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Content Area (Added Pt-20 for header space) -->
            <div class="relative space-y-10 overflow-y-auto pr-2 custom-scrollbar pt-24 pb-10 flex-grow">
                @php
                    $ticketGroups = [
                        ['data' => $tickets_ipb, 'title' => 'Mahasiswa & Alumni IPB'],
                        ['data' => $tickets_public, 'title' => 'Pendaftaran Umum (Public)'],
                    ];
                @endphp

                @foreach ($ticketGroups as $group)
                    @if (count($group['data']) > 0)
                        <section>
                            <div class="text-center mb-6">
                                <h2
                                    class="text-xl font-[800] text-[#003366] font-['Plus_Jakarta_Sans'] tracking-tight uppercase">
                                    {{ $group['title'] }}
                                </h2>
                                <div class="mx-auto w-12 h-1 mt-2 bg-[#E8630A] rounded-full"></div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                @foreach ($group['data'] as $ticket)
                                    @php $qty = $ticket->qty - $ticket->participants_count; @endphp
                                    <div
                                        class="relative bg-white border border-slate-100 rounded-2xl flex flex-col transition-all duration-300 overflow-hidden">
                                        <!-- Card Content -->
                                        <div class="p-6 pb-2 text-[16px]">
                                            <div class="flex justify-between items-start gap-4 mb-2">
                                                <h3
                                                    class="text-[17px] font-[800] text-[#003366] leading-[1.3] font-['Plus_Jakarta_Sans']">
                                                    {{ $ticket->category->name }} {{ $ticket->name ?: strtoupper($ticket->type) }}
                                                </h3>

                                                <div class="flex-shrink-0">
                                                    @if ($qty <= 0)
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded text-[10px] font-black uppercase bg-slate-100 text-slate-500 border border-slate-200">Sold
                                                            Out</span>
                                                    @elseif($qty < 10)
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded text-[10px] font-black uppercase bg-red-50 text-red-600 border border-red-100/50">Sisa:
                                                            {{ $qty }}</span>
                                                    @elseif($qty < 30)
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded text-[10px] font-black uppercase bg-amber-50 text-amber-600 border border-amber-100/50">Sisa:
                                                            {{ $qty }}</span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded text-[10px] font-black uppercase bg-emerald-50 text-emerald-600 border border-emerald-100/50">Sisa:
                                                            {{ $qty }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div
                                                class="text-[11px] text-[#E8630A] font-[800] uppercase tracking-[0.5px] mb-3 opacity-80">
                                                {{ $ticket->period->name ?? 'Standard' }}
                                            </div>
                                        </div>

                                        <!-- Perforation -->
                                        <div
                                            class="relative flex items-center py-2 overflow-hidden pointer-events-none">
                                            <div
                                                class="absolute -left-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-200/20">
                                            </div>
                                            <div
                                                class="absolute -right-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-200/20">
                                            </div>
                                            <div class="w-full border-t-2 border-dashed border-slate-200 mx-5"></div>
                                        </div>

                                        <!-- Price / Action -->
                                        <div class="p-6 pt-4 bg-slate-50/40 rounded-b-2xl transition-colors">
                                            <div class="mb-4">
                                                <span
                                                    class="text-[11px] text-slate-400 font-[800] uppercase tracking-wider block mb-1">Price
                                                    Entry</span>
                                                <span
                                                    class="text-[21px] font-[900] text-[#003366] leading-none font-['Plus_Jakarta_Sans']">Rp
                                                    {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                            </div>

                                            @if ($qty > 0)
                                                @auth
                                                    <a href="{{ route('participant.buy-more', $ticket->id) }}"
                                                        class="w-full bg-orange-600 text-white py-2.5 rounded-xl font-[800] text-[15px] transition-all active:scale-95 hover:bg-orange-700 flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                                        Beli Lagi
                                                    </a>
                                                @else
                                                    <a href="{{ route('checkout', $ticket->id) }}"
                                                        class="w-full bg-[#003366] text-white py-2.5 rounded-xl font-[800] text-[15px] transition-all active:scale-95 hover:bg-[#002244] flex items-center justify-center">
                                                        Daftar Sekarang
                                                    </a>
                                                @endauth
                                            @else
                                                <div
                                                    class="w-full py-2.5 bg-slate-100 text-slate-400 text-center rounded-xl font-[900] text-[15px] uppercase tracking-wider cursor-not-allowed">
                                                    Sold Out</div>
                                            @endif
                                        </div>

                                        <!-- BG Category Identity -->
                                        <div
                                            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[100px] font-black text-slate-400/5 select-none pointer-events-none -rotate-12 z-0 font-['Plus_Jakarta_Sans']">
                                            {{ $ticket->category->name }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endforeach

                @if (count($tickets_ipb) == 0 && count($tickets_public) == 0)
                    <div class="text-center py-20 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                        <p class="text-slate-400 font-[800] text-lg font-['Plus_Jakarta_Sans']">Pendaftaran sedang tidak
                            tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Waduh, Maaf!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#003366',
                    confirmButtonText: 'OKE, SAYA MENGERTI',
                    customClass: {
                        popup: 'rounded-3xl border border-slate-100 shadow-2xl',
                        title: 'font-black text-[#003366] uppercase tracking-tight',
                        confirmButton: 'rounded-xl px-10 py-3 font-bold uppercase tracking-widest text-xs'
                    }
                });
            @endif

            @if (session('success'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif
        });
    </script>

    @php
        $isFuture = isset($ticketSaleStartValue) && $ticketSaleStartValue->isFuture();
    @endphp

    @if ($isFuture)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-6 overflow-hidden">
            <div class="fixed inset-0 bg-[#001A33]/90 backdrop-blur-2xl"></div>

            <div class="relative w-full max-w-4xl text-center">
                {{-- Decorative Lines --}}
                <div
                    class="absolute top-1/2 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-y-1/2">
                </div>
                <div
                    class="absolute top-1/2 left-1/2 w-[1px] h-32 bg-gradient-to-b from-transparent via-[#FF7A21]/30 to-transparent -translate-x-1/2 -translate-y-1/2">
                </div>

                <div class="relative space-y-12">
                    <div class="space-y-6">
                        <img src="{{ asset('assets/images/logo_ipbrun2026.png') }}" alt="IPB Run 2026"
                            class="h-24 md:h-32 mx-auto drop-shadow-[0_0_20px_rgba(255,255,255,0.1)]">
                        <div>
                            <h2
                                class="text-[13px] font-[900] text-[#FF7A21] uppercase tracking-[0.6em] mb-4 drop-shadow-md">
                                OFFICIAL REGISTRATION OPENS IN</h2>
                            <div class="w-16 h-1 bg-[#FF7A21] mx-auto rounded-full"></div>
                        </div>
                    </div>

                    <div id="index-countdown"
                        class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-4 max-w-3xl mx-auto items-center">
                        <div class="flex flex-col group">
                            <span id="idx-days"
                                class="text-6xl md:text-8xl font-black font-outfit tabular-nums text-white tracking-tighter transition-all group-hover:scale-110">00</span>
                            <span
                                class="text-[10px] font-black uppercase tracking-[0.4em] text-white/40 mt-3">Days</span>
                        </div>
                        <div class="flex flex-col group">
                            <span id="idx-hours"
                                class="text-6xl md:text-8xl font-black font-outfit tabular-nums text-white tracking-tighter transition-all group-hover:scale-110">00</span>
                            <span
                                class="text-[10px] font-black uppercase tracking-[0.4em] text-white/40 mt-3">Hours</span>
                        </div>
                        <div class="flex flex-col group">
                            <span id="idx-minutes"
                                class="text-6xl md:text-8xl font-black font-outfit tabular-nums text-white tracking-tighter transition-all group-hover:scale-110">00</span>
                            <span
                                class="text-[10px] font-black uppercase tracking-[0.4em] text-white/40 mt-3">Minutes</span>
                        </div>
                        <div class="flex flex-col group">
                            <span id="idx-seconds"
                                class="text-6xl md:text-8xl font-black font-outfit tabular-nums text-[#FF7A21] tracking-tighter drop-shadow-[0_0_25px_rgba(255,122,33,0.3)] transition-all group-hover:scale-110">00</span>
                            <span
                                class="text-[10px] font-black uppercase tracking-[0.4em] text-[#FF7A21]/60 mt-3">Seconds</span>
                        </div>
                    </div>

                    <div class="pt-8 flex flex-col items-center gap-6">
                        <p class="text-white/60 text-sm font-medium tracking-[0.1em] max-w-sm mx-auto leading-relaxed">
                            Pastikan koneksi internet Anda stabil dan data diri sudah siap untuk pendaftaran tercepat.
                        </p>
                        <div
                            class="flex items-center gap-4 py-3 px-6 bg-white/5 rounded-full border border-white/10 backdrop-blur-sm">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-[9px] font-black text-white/40 uppercase tracking-[0.3em]">Countdown
                                Active • Live Update</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const targetDate = {{ $ticketSaleStartValue->timestamp * 1000 }};

            function updateIndexCountdown() {
                const now = Date.now();
                const distance = targetDate - now;

                if (distance <= 0) {
                    window.location.reload();
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("idx-days").innerText = days.toString().padStart(2, '0');
                document.getElementById("idx-hours").innerText = hours.toString().padStart(2, '0');
                document.getElementById("idx-minutes").innerText = minutes.toString().padStart(2, '0');
                document.getElementById("idx-seconds").innerText = seconds.toString().padStart(2, '0');
            }

            setInterval(updateIndexCountdown, 1000);
            updateIndexCountdown();
        </script>
    @endif
    </x-app>
