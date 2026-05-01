<x-layouts.app title="Registrasi Sponsorship - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="py-12 flex items-center justify-center p-4">
        <div
            class="max-w-7xl mx-auto w-full bg-white/95 rounded-2xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.15)] border border-slate-100 relative flex flex-col p-6 md:p-16 overflow-hidden">

            <!-- Title Section for Sponsorship -->
            <div class="text-center mb-6 relative z-10">
                <h1 class="text-3xl md:text-5xl font-black text-[#003366] uppercase tracking-tighter mb-4">Registrasi Sponsorship</h1>
                <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] md:text-xs">Halaman pendaftaran khusus mitra dan sponsor IPB Run 2026</p>
                <div class="h-1.5 w-20 bg-orange-500 mx-auto rounded-full mt-6"></div>
            </div>

            @if($isPeriodSoldOut)
            <!-- Sold Out Banner -->
            <div class="mb-6 relative overflow-hidden rounded-2xl border-2 border-rose-200 bg-gradient-to-r from-rose-50 via-red-50 to-rose-50 px-6 py-5 flex items-center gap-5 shadow-sm">
                <div class="flex-shrink-0 w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <div>
                    <p class="text-rose-700 font-black text-sm uppercase tracking-wider">Kuota Telah Habis</p>
                    <p class="text-rose-500 text-xs font-semibold mt-0.5">Tiket untuk kategori sponsorship telah habis terjual.</p>
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

                <!-- Tab Switcher -->
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
                                            @if ($qty > 0 && !$isPeriodSoldOut)
                                                <a href="{{ route('sponsorship.checkout', $ticket->id) }}"
                                                    class="w-full bg-[#003366] text-white py-2 md:py-2.5 rounded-lg md:rounded-xl font-[800] text-[12px] md:text-[15px] transition-all active:scale-95 hover:bg-[#002244] flex items-center justify-center">
                                                    Daftar
                                                </a>
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
                        <p class="text-slate-400 font-[800] text-lg font-['Plus_Jakarta_Sans']">Pendaftaran sponsorship sedang tidak
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#003366',
                    confirmButtonText: 'OKE'
                });
            @endif
        });

        function switchCategory(id) {
            // Hide all sections
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
</x-layouts.app>
