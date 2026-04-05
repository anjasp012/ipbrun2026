<x-layouts.app title="Participant Dashboard - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <!-- Header / Auth Navigation -->
    <div class="absolute top-0 left-0 right-0 h-20 px-10 md:px-12 flex items-center justify-between border-b border-slate-100 bg-white/80 backdrop-blur-md z-30">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-2xl bg-blue-600 flex items-center justify-center font-black text-white text-lg shadow-lg shadow-blue-200">I</div>
            <div class="hidden sm:block text-left">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[3px] leading-tight">Runner Account</p>
                <h1 class="text-lg font-black text-[#003366] uppercase leading-tight tracking-tighter">IPB RUN 2026</h1>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-4 px-5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl">
                <div class="text-right hidden md:block">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                    <p class="text-xs font-black text-[#003366]">{{ auth()->user()->name }}</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-black text-xs border border-orange-200 shadow-inner">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="min-h-screen pt-32 pb-20 px-6 sm:px-12 flex flex-col items-center">
        <div class="max-w-[1200px] w-full grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Sidebar: Player Profile -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] border border-slate-100 p-8 shadow-sm overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 -mr-16 -mt-16 rounded-full blur-3xl opacity-50"></div>
                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div class="w-24 h-24 bg-blue-50 border-4 border-white rounded-[2rem] flex items-center justify-center text-3xl font-black text-blue-600 shadow-xl shadow-blue-950/5 mb-6">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <p class="text-[10px] font-black text-orange-500 uppercase tracking-[4px] mb-1">REGISTERED ATHLETE</p>
                        <h2 class="text-2xl font-black text-[#003366] leading-tight mb-8">{{ $participant->name }}</h2>
                        
                        <div class="w-full space-y-4 text-left">
                            <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl">
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">NIK/KTP IDENTITY</label>
                                <p class="text-sm font-black text-[#003366]">{{ $participant->nik }}</p>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl">
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">WA NUMBER</label>
                                <p class="text-sm font-black text-[#003366]">{{ $participant->phone_number }}</p>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-1 bg-slate-50 border border-slate-100 p-4 rounded-2xl">
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">SHIRT</label>
                                    <p class="text-sm font-black text-[#003366]">{{ $participant->jersey_size }}</p>
                                </div>
                                <div class="flex-1 bg-slate-50 border border-slate-100 p-4 rounded-2xl">
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">BLOOD</label>
                                    <p class="text-sm font-black text-[#003366]">{{ $participant->blood_type }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Dashboard: Tickets -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Recommendations / Upsell (Ganda Kategori) -->
                @if($recommendations->count() > 0)
                <div class="bg-gradient-to-br from-orange-500 to-amber-500 rounded-[2.5rem] p-8 md:p-10 relative overflow-hidden group shadow-xl shadow-orange-950/10 h-full flex flex-col justify-between">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 -mr-32 -mt-32 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <div class="text-[11px] font-black text-white/60 uppercase tracking-[5px] mb-6">EXCLUSIVE ADD-ON</div>
                        <h3 class="text-3xl font-[900] text-white leading-[1.1] mb-10 max-w-sm uppercase tracking-tighter">
                            MAU IKUT LAGI <br>DI HARI BERBEDA?
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($recommendations as $rec)
                            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-3xl flex flex-col justify-between group/rec hover:bg-white/20 transition-all">
                                <div>
                                    <h4 class="text-lg font-black text-white uppercase tracking-tight">{{ $rec->category->name }}</h4>
                                    <p class="text-[10px] font-bold text-white/60 uppercase tracking-widest">{{ $rec->name }}</p>
                                </div>
                                <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between">
                                    <span class="text-sm font-black text-white">Rp {{ number_format($rec->price, 0, ',', '.') }}</span>
                                    <a href="{{ route('participant.buy-more', $rec->id) }}" 
                                        class="bg-white text-orange-600 px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg hover:scale-105 transition-all">
                                        GAS IKUT!
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Your Tickets Section -->
                <div>
                    <div class="flex items-center justify-between mb-8 px-4">
                        <h3 class="text-xl font-black text-[#003366] uppercase tracking-[3px]">YOUR TICKETS</h3>
                        <div class="h-0.5 flex-grow mx-6 bg-slate-200"></div>
                        <span class="text-[10px] font-black text-slate-400 bg-white border border-slate-100 px-3 py-1 rounded-full uppercase tracking-widest">
                            {{ $participant->raceEntries->count() }} Entry
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($participant->raceEntries as $entry)
                        <div class="bg-white rounded-[2rem] border border-slate-100 p-6 shadow-sm flex flex-col justify-between group hover:border-blue-100 transition-all">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Category Dist.</p>
                                    <h4 class="text-2xl font-black text-[#003366] uppercase leading-none tracking-tighter">{{ $entry->ticket->category->name }}</h4>
                                    <p class="text-[9px] font-bold text-orange-500 uppercase tracking-[2px] mt-2">{{ $entry->ticket->name }}</p>
                                </div>
                                <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                                    <div class="text-left">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Order Code</p>
                                        <span class="text-[11px] font-black text-[#003366] font-mono">#{{ $entry->order->order_code }}</span>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Payment</p>
                                        @if($entry->status === 'paid')
                                            <span class="text-[10px] font-black text-emerald-600 uppercase italic">SUCCESS</span>
                                        @else
                                            <a href="{{ $entry->order->payment_url }}" class="text-[10px] font-black text-orange-600 uppercase underline animate-pulse">PENDING</a>
                                        @endif
                                    </div>
                                </div>

                                @if($entry->status === 'paid')
                                <div class="flex items-center justify-between p-4 bg-blue-600 rounded-2xl text-white">
                                    <div>
                                        <p class="text-[9px] font-bold text-white/60 uppercase tracking-widest mb-0.5">BIB Number</p>
                                        <span class="text-xl font-black font-mono tracking-widest">{{ $entry->bib_number ?: 'TBA' }}</span>
                                    </div>
                                    <svg class="w-8 h-8 opacity-40 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
