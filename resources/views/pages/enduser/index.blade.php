<x-layouts.app title="Official Registration - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="h-screen overflow-hidden flex items-center justify-center p-4">
        <div
            class="max-w-auto w-full max-h-[98vh] bg-white rounded-3xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.15)] border border-slate-100 relative flex flex-col p-10 md:p-12 overflow-hidden">
            <!-- Content Area -->
            <div class="relative space-y-10 overflow-y-auto pr-2 custom-scrollbar">
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
                                                    {{ $ticket->category->name }} {{ $ticket->name }}
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
                                                <a href="{{ route('checkout', $ticket->id) }}"
                                                    class="w-full bg-[#003366] text-white py-2.5 rounded-xl font-[800] text-[15px] transition-all active:scale-95 hover:bg-[#002244] flex items-center justify-center">
                                                    Daftar
                                                </a>
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
            @if(session('error'))
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

            @if(session('success'))
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
</x-layouts.app>
