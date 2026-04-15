<x-layouts.app title="Registrasi Komunitas - IPB RUN 2026">
    <div class="bg-gray-50 min-h-screen py-20 px-6 sm:px-12">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-black text-[#003366] uppercase tracking-tighter mb-4">Registrasi Komunitas</h1>
                <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Pilih kategori lomba untuk melanjutkan pendaftaran</p>
                <div class="h-1.5 w-20 bg-orange-500 mx-auto rounded-full mt-6"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($tickets as $ticket)
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-900/5 border border-slate-100 p-8 flex flex-col group hover:scale-[1.02] transition-all duration-300">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="text-[10px] font-black text-white bg-blue-600 px-3 py-1 rounded-full uppercase tracking-widest">{{ $ticket->type === 'ipb' ? 'IPB FAMILY' : 'UMUM' }}</span>
                                <h3 class="text-2xl font-black text-slate-800 mt-2 uppercase">{{ $ticket->category->name }}</h3>
                            </div>
                            <div class="text-right">
                                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Price</span>
                                <span class="text-xl font-black text-orange-600">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="space-y-4 mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-slate-600 uppercase tracking-wide">{{ $ticket->period->name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-600 uppercase tracking-wide">Available Slots</span>
                                </div>
                                <span class="font-black text-[#003366]">{{ $ticket->qty - $ticket->participants_count }}</span>
                            </div>
                        </div>

                        <a href="{{ route('komunitas.checkout', $ticket) }}" 
                           class="w-full h-14 bg-[#003366] text-white rounded-2xl flex items-center justify-center gap-3 font-bold text-sm tracking-widest uppercase hover:bg-blue-900 active:scale-[0.98] transition-all shadow-xl shadow-blue-900/10">
                            Pilih Kategori
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
