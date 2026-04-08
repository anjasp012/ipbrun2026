<x-layouts.app>
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="py-12 flex items-center justify-center p-4">
        <div
            class="max-w-7xl mx-auto w-full bg-white/95 rounded-2xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.15)] border border-slate-100 relative flex flex-col p-6 md:p-16 overflow-hidden">

            <!-- Content Area -->
            <div class="relative pr-2 custom-scrollbar flex-grow">
                @php
                    $ticketGroups = [
                        ['data' => $tickets_ipb, 'title' => 'Keluarga Besar IPB', 'id' => 'ipb'],
                        ['data' => $tickets_public, 'title' => 'Umum', 'id' => 'umum'],
                    ];
                @endphp

                <!-- Tab Switcher (Mobile Only) -->
                <div class="flex justify-center mb-8 md:hidden">
                    <div class="inline-flex p-1 bg-slate-50 rounded-xl border border-slate-200 shadow-sm w-full">
                        <button onclick="switchCategory('ipb')" id="tab-ipb"
                            class="flex-1 category-tab px-4 py-3 rounded-lg text-xs font-[800] uppercase tracking-[1px] transition-all duration-300 active:scale-[0.98] whitespace-nowrap bg-[#00ACB1] text-white shadow-md">
                            Keluarga IPB
                        </button>
                        <button onclick="switchCategory('umum')" id="tab-umum"
                            class="flex-1 category-tab px-4 py-3 rounded-lg text-xs font-[800] uppercase tracking-[1px] transition-all duration-300 active:scale-[0.98] whitespace-nowrap text-slate-500 hover:text-[#00ACB1]">
                            Umum
                        </button>
                    </div>
                </div>

                @foreach ($ticketGroups as $group)
                    @if (count($group['data']) > 0)
                        <div id="section-{{ $group['id'] }}"
                            class="ticket-section md:!block {{ $group['id'] !== 'ipb' ? 'hidden' : '' }} mb-16 last:mb-0">
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
                                        class="relative bg-white border border-slate-100 rounded-2xl flex flex-col transition-all duration-300 overflow-hidden shadow-sm hover:shadow-md group/card hover:-translate-y-1">

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
                                                    @elseif($qty < 10)
                                                        <span
                                                            class="inline-flex items-center px-1.5 md:px-3 py-0.5 md:py-1 rounded text-[8px] md:text-[10px] font-black uppercase bg-red-50 text-red-600 border border-red-100/50">Sisa:
                                                            {{ $qty }}</span>
                                                    @elseif($qty < 30)
                                                        <span
                                                            class="inline-flex items-center px-1.5 md:px-3 py-0.5 md:py-1 rounded text-[8px] md:text-[10px] font-black uppercase bg-amber-50 text-amber-600 border border-amber-100/50">Sisa:
                                                            {{ $qty }}</span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-1.5 md:px-3 py-0.5 md:py-1 rounded text-[8px] md:text-[10px] font-black uppercase bg-emerald-50 text-emerald-600 border border-emerald-100/50">Sisa:
                                                            {{ $qty }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div
                                                class="text-[9px] md:text-[11px] text-[#E8630A] font-[800] uppercase tracking-[0.5px] mb-3 opacity-80">
                                                {{ $ticket->period->name ?? 'Standard' }}
                                            </div>
                                        </div>

                                        <!-- Perforation -->
                                        <div
                                            class="relative flex items-center py-1 md:py-2 overflow-hidden pointer-events-none">
                                            <div
                                                class="absolute -left-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-200/20">
                                            </div>
                                            <div
                                                class="absolute -right-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-200/20">
                                            </div>
                                            <div class="w-full border-t-2 border-dashed border-slate-200 mx-3 md:mx-5">
                                            </div>
                                        </div>

                                        <!-- Price / Action -->
                                        <div
                                            class="p-3 md:p-6 pt-2 md:pt-4 bg-slate-50/40 rounded-b-2xl transition-colors mt-auto">
                                            <div class="mb-3 md:mb-4">
                                                <span
                                                    class="text-[9px] md:text-[11px] text-slate-400 font-[800] uppercase tracking-wider block mb-0.5">Price
                                                    Entry</span>
                                                <span
                                                    class="text-[15px] md:text-[21px] font-[900] text-[#003366] leading-none font-['Plus_Jakarta_Sans']">Rp
                                                    {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                            </div>

                                            @if ($qty > 0)
                                                @auth
                                                    <a href="{{ route('participant.buy-more', $ticket->id) }}"
                                                        class="w-full bg-orange-600 text-white py-2 md:py-2.5 rounded-lg md:rounded-xl font-[800] text-[12px] md:text-[15px] transition-all active:scale-95 hover:bg-orange-700 flex items-center justify-center gap-1 md:gap-2">
                                                        <svg class="w-3 h-3 md:w-4 md:h-4" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                        Beli Lagi
                                                    </a>
                                                @else
                                                    <a href="{{ route('checkout', $ticket->id) }}"
                                                        class="w-full bg-[#003366] text-white py-2 md:py-2.5 rounded-lg md:rounded-xl font-[800] text-[12px] md:text-[15px] transition-all active:scale-95 hover:bg-[#002244] flex items-center justify-center">
                                                        Daftar
                                                    </a>
                                                @endauth
                                            @else
                                                <div
                                                    class="w-full py-2 md:py-2.5 bg-slate-100 text-slate-400 text-center rounded-lg md:rounded-xl font-[900] text-[12px] md:text-[15px] uppercase tracking-wider cursor-not-allowed">
                                                    Sold</div>
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
                tab.classList.add('text-slate-500', 'hover:text-[#00ACB1]');
            });

            const activeTab = document.getElementById('tab-' + id);
            if (activeTab) {
                activeTab.classList.add('bg-[#00ACB1]', 'text-white', 'shadow-md');
                activeTab.classList.remove('text-slate-500', 'hover:text-[#00ACB1]');
            }
        }
    </script>

    @php
        $isFuture = isset($ticketSaleStartValue) && $ticketSaleStartValue->isFuture() && \App\Models\Setting::getValue('is_running', '0') !== '1';
    @endphp

    @if ($isFuture)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-6 overflow-hidden select-none"
             x-data="startTool()"
             x-show="state !== 'finish'"
             x-transition:leave="transition ease-in duration-1000"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-110">
            
            <div class="fixed inset-0 bg-[#001A33] backdrop-blur-2xl"></div>
            
            <!-- Background Elements -->
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="absolute inset-x-0 top-1/2 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-y-1/2"></div>
            <div class="absolute inset-y-0 left-1/2 w-px bg-gradient-to-b from-transparent via-[#FF7A21]/30 to-transparent -translate-x-1/2"></div>

            <div class="relative w-full max-w-lg">
                <!-- State 1: Password Input -->
                <template x-if="state === 'password'">
                    <div class="max-w-md mx-auto text-center space-y-10" 
                         x-transition:enter="transition ease-out duration-500" 
                         x-transition:enter-start="opacity-0 translate-y-8" 
                         x-transition:enter-end="opacity-100 translate-y-0">
                        <img src="{{ asset('assets/images/logo_ipbrun2026.png') }}" class="h-24 mx-auto drop-shadow-2xl" alt="">
                        
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#FF7A21] uppercase tracking-[5px] block">SECURITY CLEARANCE</label>
                            <input type="password" x-model="password" 
                                @focus="playStandby()"
                                @keyup.enter="checkPass()"
                                placeholder="••••••••••••"
                                class="w-full bg-white/5 border border-white/10 rounded-2xl h-16 text-center text-white text-xl font-bold tracking-[4px] focus:outline-none focus:border-[#FF7A21]/50 focus:bg-white/10 transition-all">
                            <template x-if="error">
                                <p class="text-xs font-bold text-red-500 uppercase tracking-widest italic" x-text="error"></p>
                            </template>
                        </div>

                        <button @click="checkPass()" 
                            class="w-full h-16 bg-[#FF7A21] text-white rounded-2xl font-black text-xs uppercase tracking-[4px] shadow-lg shadow-orange-900/20 active:scale-95 transition-all">
                            Verify Control
                        </button>
                    </div>
                </template>

                <!-- State 2: Ready Button -->
                <template x-if="state === 'ready'">
                    <div class="text-center space-y-12" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                        <div class="space-y-4">
                            <h2 class="text-white text-3xl font-[900] uppercase tracking-tighter italic">SYSTEM READY</h2>
                            <p class="text-[11px] font-bold text-white/40 uppercase tracking-[4px]">PRESS START TO TRIGGER COUNTDOWN</p>
                        </div>

                        <div class="relative group">
                            <div class="absolute inset-0 bg-red-600 blur-3xl opacity-20 pointer-events-none group-hover:opacity-40 transition-opacity"></div>
                            <button @click="startCountdown()" 
                                class="relative w-48 h-48 bg-red-600 text-white rounded-full font-black text-2xl uppercase tracking-[2px] shadow-2xl shadow-red-950/50 border-8 border-red-500/50 hover:bg-red-500 hover:scale-105 active:scale-90 transition-all animate-pulse">
                                START
                            </button>
                        </div>
                    </div>
                </template>

                <!-- State 3: Countdown -->
                <template x-if="state === 'count'">
                    <div class="text-center" x-transition:enter="transition ease-out duration-300">
                        <div class="text-[200px] leading-none font-black text-[#FF7A21] drop-shadow-[0_0_50px_rgba(255,122,33,0.4)] animate-bounce" x-text="count"></div>
                        <p class="text-white font-[900] text-3xl uppercase tracking-[15px] opacity-40 mt-8">INITIATING</p>
                    </div>
                </template>
            </div>
        </div>

        <script>
            function startTool() {
                return {
                    state: 'password',
                    password: '',
                    error: '',
                    count: 6,
                    sounds: {
                        standby: new Audio("{{ asset('assets/sounds/standby.mpeg') }}"),
                        start: new Audio("{{ asset('assets/sounds/start.mpeg') }}"),
                        countdown: new Audio("{{ asset('assets/sounds/countdown.mpeg') }}")
                    },

                    init() {
                        this.sounds.standby.loop = true;
                        this.sounds.countdown.loop = true;
                    },

                    playStandby() {
                        this.sounds.standby.play().catch(e => console.log('Autoplay blocked'));
                    },

                    checkPass() {
                        if (this.password === 'IpbRun2026#') {
                            this.state = 'ready';
                            this.error = '';
                        } else {
                            this.error = 'Invalid Credentials';
                            this.password = '';
                        }
                    },

                    startCountdown() {
                        this.playStandby();
                        this.sounds.standby.pause();
                        this.sounds.start.play();
                        
                        this.state = 'count';
                        const timer = setInterval(() => {
                            this.count--;
                            if (this.count <= 0) {
                                clearInterval(timer);
                                this.triggerAdminStart();
                            }
                        }, 1000);
                    },

                    async triggerAdminStart() {
                        try {
                            const response = await fetch("{{ route('trigger.start') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ password: this.password })
                            });

                            const result = await response.json();
                            if (result.success) {
                                this.sounds.countdown.play().catch(e => {});
                                this.state = 'finish';
                                // Music will continue playing on the now-visible homepage
                            } else {
                                alert(result.message);
                                window.location.reload();
                            }
                        } catch (e) {
                            alert("Error triggering system");
                            window.location.reload();
                        }
                    }
                }
            }
        </script>
    @endif
</x-layouts.app>
