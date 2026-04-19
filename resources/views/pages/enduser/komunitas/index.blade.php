<x-layouts.app title="Registrasi Komunitas - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="py-12 flex items-center justify-center p-4">
        <div
            class="max-w-7xl mx-auto w-full bg-white/95 rounded-2xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.15)] border border-slate-100 relative flex flex-col p-6 md:p-16 overflow-hidden">

            <!-- Title Section for Community -->
            <div class="text-center mb-6 relative z-10">
                <h1 class="text-3xl md:text-5xl font-black text-[#003366] uppercase tracking-tighter mb-4">Registrasi Komunitas</h1>
                <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] md:text-xs">Gunakan kode voucher komunitas Anda di halaman checkout</p>
                <div class="h-1.5 w-20 bg-orange-500 mx-auto rounded-full mt-6"></div>
            </div>

            @if($isPeriodSoldOut)
            <!-- Sold Out Banner -->
            <div class="mb-6 relative overflow-hidden rounded-2xl border-2 border-rose-200 bg-gradient-to-r from-rose-50 via-red-50 to-rose-50 px-6 py-5 flex items-center gap-5 shadow-sm">
                <div class="flex-shrink-0 w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <div>
                    <p class="text-rose-700 font-black text-sm uppercase tracking-wider">Periode Flashsale Telah Berakhir</p>
                    <p class="text-rose-500 text-xs font-semibold mt-0.5">Periode <span class="font-black">{{ $activePeriod->name ?? '' }}</span> telah habis. Nantikan periode berikutnya!</p>
                </div>
                <div class="absolute right-0 top-0 h-full w-32 bg-gradient-to-l from-rose-100/50 to-transparent pointer-events-none"></div>
            </div>
            @endif

            <!-- Content Area -->
            <div class="relative pr-2 custom-scrollbar flex-grow">
                @php
                    $ticketGroups = [
                        ['data' => $tickets_ipb, 'title' => 'Keluarga Besar IPB', 'id' => 'ipb'],
                        ['data' => $tickets_public, 'title' => 'Umum', 'id' => 'umum'],
                    ];
                @endphp

                <!-- Tab Switcher (Mobile & Desktop) -->
                <div class="flex justify-center mb-6">
                    <div class="inline-flex gap-2 p-1.5 bg-slate-50 rounded-2xl border border-slate-200 shadow-sm w-full md:w-auto md:min-w-[400px]">
                        <button onclick="switchCategory('ipb')" id="tab-ipb"
                            class="flex-1 category-tab bg-[#00ACB1] text-white shadow-md px-4 py-2 rounded-lg text-xs md:text-sm font-[800] uppercase tracking-[1px] transition-all duration-300 active:scale-[0.98] whitespace-nowrap border border-transparent">
                             Kategori <br class="md:hidden"> Keluarga IPB
                        </button>
                        <button onclick="switchCategory('umum')" id="tab-umum"
                            class="flex-1 category-tab inactive-tab-pulse px-4 py-2 rounded-lg text-xs md:text-sm font-[800] uppercase tracking-[1px] transition-all duration-300 active:scale-[0.98] whitespace-nowrap text-slate-500 hover:text-[#00ACB1] border border-transparent">
                             Kategori <br class="md:hidden"> Umum
                        </button>
                    </div>
                </div>

                @foreach ($ticketGroups as $group)
                    @if (count($group['data']) > 0)
                        <div id="section-{{ $group['id'] }}"
                            class="ticket-section {{ $group['id'] !== 'ipb' ? 'hidden' : '' }} lg:mb-16 last:mb-0">
                            {{-- Section Header --}}
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-1.5 h-8 bg-[#003366] rounded-full"></div>
                                <h2
                                    class="text-xl md:text-2xl font-[900] text-[#003366] font-['Plus_Jakarta_Sans'] tracking-tight uppercase">
                                    {{ $group['title'] }}
                                </h2>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                                @foreach ($group['data'] as $ticket)
                                    @php
                                        $qty = $ticket->qty - $ticket->participants_count;
                                    @endphp
                                    <div
                                        class="relative bg-white border border-slate-100 rounded-2xl flex flex-col transition-all duration-300 shadow-sm hover:shadow-md group/card hover:-translate-y-1">

                                        <!-- Card Content -->
                                        <div class="p-3 md:p-6 pb-2">
                                            <div
                                                class="flex flex-col sm:flex-row justify-between items-start gap-2 mb-2">
                                                <h3
                                                    class="text-[13px] md:text-[17px] font-[800] text-[#003366] leading-[1.3] font-['Plus_Jakarta_Sans']">
                                                    {{ $ticket->category->name }}
                                                    {{ $ticket->name ?: strtoupper($ticket->type) }}
                                                </h3>
                                                <div class="flex-shrink-0">
                                                    @if ($qty <= 0)
                                                        <span
                                                            class="inline-flex items-center px-1.5 md:px-3 py-0.5 md:py-1 rounded text-[8px] md:text-[10px] font-black uppercase bg-slate-100 text-slate-500 border border-slate-200">Sold
                                                            Out</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div
                                                class="text-[9px] md:text-[11px] text-[#E8630A] font-[800] uppercase tracking-[0.5px] mb-3 opacity-80">
                                                {{ $ticket->period->name ?? 'Standard' }}
                                            </div>
                                        </div>

                                        <!-- Perforation -->
                                        <div class="relative flex items-center py-1 md:py-2 pointer-events-none">
                                            <div class="absolute -left-3 w-6 h-6 bg-[#f1f5f9] rounded-full">
                                            </div>
                                            <div class="absolute -right-3 w-6 h-6 bg-[#f1f5f9] rounded-full">
                                            </div>
                                            <div class="w-full border-t-2 border-dashed border-slate-200 mx-3 md:mx-5">
                                            </div>
                                        </div>

                                        <!-- Price / Action -->
                                        <div
                                            class="p-3 md:p-6 pt-2 md:pt-4 bg-slate-50/40 rounded-b-2xl transition-colors mt-auto">
                                            <!-- <div class="mb-3 md:mb-4">
                                                <span
                                                    class="text-[9px] md:text-[11px] text-slate-400 font-[800] uppercase tracking-wider block mb-0.5">Price
                                                    Entry</span>
                                                <span
                                                    class="text-[15px] md:text-[21px] font-[900] text-[#003366] leading-none font-['Plus_Jakarta_Sans']">Rp
                                                    {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                            </div> -->

                                            @if ($qty > 0 && !$isPeriodSoldOut)
                                                @auth
                                                    @else
                                                    <a href="{{ route('komunitas.checkout', $ticket->id) }}"
                                                        class="w-full bg-[#003366] text-white py-2 md:py-2.5 rounded-lg md:rounded-xl font-[800] text-[12px] md:text-[15px] transition-all active:scale-95 hover:bg-[#002244] flex items-center justify-center">
                                                        Daftar
                                                    </a>
                                                @endauth
                                            @elseif($isPeriodSoldOut)
                                                <button onclick="showSoldOutPopup('{{ $ticket->period->name ?? 'Flashsale' }}')"
                                                    class="w-full py-2 md:py-2.5 bg-rose-500 text-white text-center rounded-lg md:rounded-xl font-[900] text-[12px] md:text-[15px] uppercase tracking-wider hover:bg-rose-600 transition-all active:scale-95 flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Sold Out
                                                </button>
                                            @else
                                                <div
                                                    class="w-full py-2 md:py-2.5 bg-slate-100 text-slate-400 text-center rounded-lg md:rounded-xl font-[900] text-[12px] md:text-[15px] uppercase tracking-wider cursor-not-allowed">
                                                    Sold Out</div>
                                            @endif
                                        </div>

                                        <!-- BG Category Identity -->
                                        <div
                                            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[60px] md:text-[100px] font-black text-slate-400/5 select-none pointer-events-none -rotate-12 z-0 font-['Plus_Jakarta_Sans'] whitespace-nowrap">
                                            {{ $ticket->category->name }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
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
        @keyframes inactiveTabPulseGlow {
            0%, 100% {
                box-shadow: 0 0 10px 2px rgba(255, 247, 0, 0.6);
                border-color: #ffea00ff;
                color: #000000ff;
                background-color: rgba(255, 251, 0, 0.05);
            }
            50% {
                box-shadow: 0 0 25px 6px rgba(255, 247, 0, 0.6);
                border-color: #ffea00ff;
                color: #000000ff;
                background-color: rgba(255, 204, 0, 0.15);
            }
        }

        .inactive-tab-pulse {
            animation: inactiveTabPulseGlow 1.2s ease-in-out infinite !important;
            border: 2px solid #ffd900ff !important;
            position: relative;
            z-index: 10;
        }

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

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
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

            @if (session('period_sold_out'))
                showSoldOutPopup("{{ session('period_sold_out') }}");
            @endif
        });

        function showSoldOutPopup(periodName) {
            Swal.fire({
                html: `
                    <div style="padding: 8px 0">
                        <div style="width: 72px; height: 72px; background: linear-gradient(135deg, #fee2e2, #fecaca); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <svg width="36" height="36" fill="none" stroke="#dc2626" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        </div>
                        <h2 style="font-size: 22px; font-weight: 900; color: #003366; text-transform: uppercase; letter-spacing: -0.5px; margin-bottom: 10px;">Periode Berakhir!</h2>
                        <div style="display: inline-block; background: #fff0f0; border: 1px solid #fecaca; color: #dc2626; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; padding: 4px 14px; border-radius: 100px; margin-bottom: 16px;">${periodName}</div>
                        <p style="color: #64748b; font-size: 14px; line-height: 1.7; font-weight: 500;">
                            Periode flashsale <strong style="color: #003366;">${periodName}</strong> telah berakhir dan kuota telah habis.
                        </p>
                        <p style="color: #94a3b8; font-size: 13px; margin-top: 10px; font-weight: 600;">
                            🔔 Nantikan periode berikutnya untuk mendapatkan tiket IPB Run 2026!
                        </p>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonColor: '#003366',
                confirmButtonText: 'MENGERTI, NANTIKAN PERIODE SELANJUTNYA',
                showCancelButton: false,
                customClass: {
                    popup: 'rounded-3xl border border-rose-100 shadow-2xl',
                    confirmButton: 'rounded-xl px-8 py-3 font-black uppercase tracking-widest text-xs'
                },
                backdrop: 'rgba(0,51,102,0.5) url("/assets/images/bg.png") center/cover no-repeat'
            });
        }

        function switchCategory(id) {
            // Hide all sections on mobile
            document.querySelectorAll('.ticket-section').forEach(section => {
                section.classList.add('hidden');
            });

            // Show selected section
            const selectedSection = document.getElementById('section-' + id);
            if (selectedSection) {
                selectedSection.classList.remove('hidden');
            }

            // Update tab styles
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.classList.remove('bg-[#00ACB1]', 'text-white', 'shadow-md');
                tab.classList.add('text-slate-500', 'hover:text-[#00ACB1]', 'inactive-tab-pulse');
            });

            const activeTab = document.getElementById('tab-' + id);
            if (activeTab) {
                activeTab.classList.add('bg-[#00ACB1]', 'text-white', 'shadow-md');
                activeTab.classList.remove('text-slate-500', 'hover:text-[#00ACB1]', 'inactive-tab-pulse');
            }
        }
    </script>

    @if (!$isMaintenance)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-6 overflow-hidden select-none"
            x-data="launchControl()" x-show="!isLive" x-transition:leave="transition ease-in duration-1000"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="fixed inset-0 bg-[#001A33] backdrop-blur-3xl"></div>

            <!-- Background Elements -->
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10">
            </div>
            <div
                class="absolute inset-x-0 top-1/2 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-y-1/2">
            </div>
            <div
                class="absolute inset-y-0 left-1/2 w-px bg-gradient-to-b from-transparent via-[#FF7A21]/30 to-transparent -translate-x-1/2">
            </div>

            <div class="relative w-full max-w-4xl text-center">

                <!-- Registration Schedule Countdown -->
                <template x-if="hasSchedule && state === 'schedule'">
                    <div class="space-y-12">
                        <div class="space-y-6">
                            <img src="{{ asset('assets/images/logo_ipbrun2026.png') }}" alt="IPB Run 2026"
                                class="h-24 md:h-32 mx-auto drop-shadow-2xl">
                            <div>
                                <h2 class="text-[13px] font-[900] text-[#FF7A21] uppercase tracking-[0.6em] mb-4">
                                    COMUNITY REGISTRATION OPENS IN</h2>
                                <div class="w-16 h-1 bg-[#FF7A21] mx-auto rounded-full"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-4 max-w-3xl mx-auto items-center">
                            <div class="flex flex-col"><span
                                    class="text-6xl md:text-8xl font-black text-white tracking-tighter"
                                    x-text="time.d">00</span><span
                                    class="text-[10px] font-black uppercase tracking-[0.4em] text-white/40 mt-3">Days</span>
                            </div>
                            <div class="flex flex-col"><span
                                    class="text-6xl md:text-8xl font-black text-white tracking-tighter"
                                    x-text="time.h">00</span><span
                                    class="text-[10px] font-black uppercase tracking-[0.4em] text-white/40 mt-3">Hours</span>
                            </div>
                            <div class="flex flex-col"><span
                                    class="text-6xl md:text-8xl font-black text-white tracking-tighter"
                                    x-text="time.m">00</span><span
                                    class="text-[10px] font-black uppercase tracking-[0.4em] text-white/40 mt-3">Minutes</span>
                            </div>
                            <div class="flex flex-col"><span
                                    class="text-6xl md:text-8xl font-black text-[#FF7A21] tracking-tighter drop-shadow-[0_0_25px_rgba(255,122,33,0.3)]"
                                    x-text="time.s">00</span><span
                                    class="text-[10px] font-black uppercase tracking-[0.4em] text-[#FF7A21]/60 mt-3">Seconds</span>
                            </div>
                        </div>

                        <div class="pt-8 flex flex-col items-center gap-6">
                            <p
                                class="text-white/60 text-sm font-medium tracking-[0.1em] max-w-sm mx-auto leading-relaxed">
                                Persiapkan diri Anda untuk pendaftaran tercepat.</p>
                            <div
                                class="flex items-center gap-4 py-3 px-6 bg-white/5 rounded-full border border-white/10 backdrop-blur-sm">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span class="text-[9px] font-black text-white/40 uppercase tracking-[0.3em]">LIVE
                                    MONITORING ACTIVE</span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <script>
            function launchControl() {
                return {
                    isLive: false,
                    hasSchedule: @json(!empty($ticketSaleStart)),
                    targetDate: {{ ($ticketSaleStartValue->timestamp ?? 0) * 1000 }},
                    state: @json(!empty($ticketSaleStart) ? 'schedule' : 'manual'),
                    time: {
                        d: '00',
                        h: '00',
                        m: '00',
                        s: '00'
                    },

                    init() {
                        if (this.hasSchedule) {
                            this.startScheduleTimer();
                        }
                    },

                    startScheduleTimer() {
                        const update = () => {
                            const now = Date.now();
                            const diff = this.targetDate - now;

                            if (diff <= 0) {
                                this.isLive = true;
                                return;
                            }

                            this.time.d = Math.floor(diff / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
                            this.time.h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(
                                2, '0');
                            this.time.m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
                            this.time.s = Math.floor((diff % (1000 * 60)) / 1000).toString().padStart(2, '0');

                            requestAnimationFrame(update);
                        };
                        update();
                    }
                }
            }
        </script>
    @endif
</x-layouts.app>
