<x-layouts.app>
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="py-12 md:py-20 flex items-center justify-center p-4">
        <div
            class="max-w-7xl mx-auto w-full bg-white rounded-[2.5rem] shadow-[0_20px_50px_-12px_rgba(0,0,0,0.15)] border border-slate-100 relative flex flex-col p-6 md:p-16 overflow-hidden">

            <!-- Content Area -->
            <div class="relative space-y-4 pr-2 custom-scrollbar pb-10 flex-grow">
                @php
                    $ticketGroups = [
                        ['data' => $tickets_ipb, 'title' => 'Keluarga Besar IPB', 'id' => 'ipb'],
                        ['data' => $tickets_public, 'title' => 'Kategori Umum', 'id' => 'umum'],
                    ];
                @endphp

                @foreach ($ticketGroups as $group)
                    @if (count($group['data']) > 0)
                        <div class="border border-slate-100 rounded-[2.5rem] bg-white shadow-sm overflow-hidden transition-all duration-500">
                            {{-- Accordion Header --}}
                            <button onclick="toggleAccordion('{{ $group['id'] }}')" 
                                class="w-full px-8 md:px-12 py-6 md:py-8 flex items-center justify-between bg-white hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-4">
                                    <div class="w-2 h-8 bg-[#003366] rounded-full group-hover:scale-y-125 transition-transform duration-300"></div>
                                    <h2 class="text-lg md:text-2xl font-[900] text-[#003366] font-['Plus_Jakarta_Sans'] tracking-tight uppercase">
                                        {{ $group['title'] }}
                                    </h2>
                                </div>
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-[#003366] group-hover:bg-[#003366] group-hover:text-white transition-all duration-300 shadow-inner">
                                    <svg id="icon-{{ $group['id'] }}" class="w-5 h-5 md:w-6 md:h-6 transition-transform duration-500 {{ $group['id'] !== 'ipb' ? 'rotate-0' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>

                            {{-- Accordion Content --}}
                            <div id="content-{{ $group['id'] }}" class="transition-all duration-500 ease-in-out {{ $group['id'] !== 'ipb' ? 'max-h-0 opacity-0' : 'max-h-[5000px] opacity-100' }} overflow-hidden">
                                <div class="px-4 md:px-12 pb-8 md:pb-12">
                                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                                        @foreach ($group['data'] as $ticket)
                                            @php $qty = $ticket->qty - $ticket->participants_count; @endphp
                                            <div class="relative bg-white border border-slate-100 rounded-2xl flex flex-col transition-all duration-300 overflow-hidden shadow-sm hover:shadow-md group/card hover:-translate-y-1">
                                                <!-- Card Content -->
                                                <div class="p-3 md:p-6 pb-2">
                                                    <div class="flex flex-col sm:flex-row justify-between items-start gap-2 mb-2">
                                                        <h3 class="text-[13px] md:text-[17px] font-[800] text-[#003366] leading-[1.3] font-['Plus_Jakarta_Sans']">
                                                            {{ $ticket->category->name }}
                                                            {{ $ticket->name ?: strtoupper($ticket->type) }}
                                                        </h3>

                                                        <div class="flex-shrink-0">
                                                            @if ($qty <= 0)
                                                                <span class="inline-flex items-center px-1.5 md:px-3 py-0.5 md:py-1 rounded text-[8px] md:text-[10px] font-black uppercase bg-slate-100 text-slate-500 border border-slate-200">Sold Out</span>
                                                            @elseif($qty < 10)
                                                                <span class="inline-flex items-center px-1.5 md:px-3 py-0.5 md:py-1 rounded text-[8px] md:text-[10px] font-black uppercase bg-red-50 text-red-600 border border-red-100/50">Sisa: {{ $qty }}</span>
                                                            @elseif($qty < 30)
                                                                <span class="inline-flex items-center px-1.5 md:px-3 py-0.5 md:py-1 rounded text-[8px] md:text-[10px] font-black uppercase bg-amber-50 text-amber-600 border border-amber-100/50">Sisa: {{ $qty }}</span>
                                                            @else
                                                                <span class="inline-flex items-center px-1.5 md:px-3 py-0.5 md:py-1 rounded text-[8px] md:text-[10px] font-black uppercase bg-emerald-50 text-emerald-600 border border-emerald-100/50">Sisa: {{ $qty }}</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="text-[9px] md:text-[11px] text-[#E8630A] font-[800] uppercase tracking-[0.5px] mb-3 opacity-80">
                                                        {{ $ticket->period->name ?? 'Standard' }}
                                                    </div>
                                                </div>

                                                <!-- Perforation -->
                                                <div class="relative flex items-center py-1 md:py-2 overflow-hidden pointer-events-none">
                                                    <div class="absolute -left-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-200/20"></div>
                                                    <div class="absolute -right-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-200/20"></div>
                                                    <div class="w-full border-t-2 border-dashed border-slate-200 mx-3 md:mx-5"></div>
                                                </div>

                                                <!-- Price / Action -->
                                                <div class="p-3 md:p-6 pt-2 md:pt-4 bg-slate-50/40 rounded-b-2xl transition-colors mt-auto">
                                                    <div class="mb-3 md:mb-4">
                                                        <span class="text-[9px] md:text-[11px] text-slate-400 font-[800] uppercase tracking-wider block mb-0.5">Price Entry</span>
                                                        <span class="text-[15px] md:text-[21px] font-[900] text-[#003366] leading-none font-['Plus_Jakarta_Sans']">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                                    </div>

                                                    @if ($qty > 0)
                                                        @auth
                                                            <a href="{{ route('participant.buy-more', $ticket->id) }}"
                                                                class="w-full bg-orange-600 text-white py-2 md:py-2.5 rounded-lg md:rounded-xl font-[800] text-[12px] md:text-[15px] transition-all active:scale-95 hover:bg-orange-700 flex items-center justify-center gap-1 md:gap-2">
                                                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
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
                                                        <div class="w-full py-2 md:py-2.5 bg-slate-100 text-slate-400 text-center rounded-lg md:rounded-xl font-[900] text-[12px] md:text-[15px] uppercase tracking-wider cursor-not-allowed">Sold</div>
                                                    @endif
                                                </div>

                                                <!-- BG Category Identity -->
                                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[60px] md:text-[100px] font-black text-slate-400/5 select-none pointer-events-none -rotate-12 z-0 font-['Plus_Jakarta_Sans'] whitespace-nowrap">
                                                    {{ $ticket->category->name }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
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

        function toggleAccordion(id) {
            const content = document.getElementById('content-' + id);
            const icon = document.getElementById('icon-' + id);
            
            // Toggle current one
            if (content.classList.contains('max-h-0')) {
                // Open it
                content.classList.remove('max-h-0', 'opacity-0');
                content.classList.add('max-h-[5000px]', 'opacity-100');
                icon.classList.add('rotate-180');
            } else {
                // Close it
                content.classList.add('max-h-0', 'opacity-0');
                content.classList.remove('max-h-[5000px]', 'opacity-100');
                icon.classList.remove('rotate-180');
            }
        }
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
