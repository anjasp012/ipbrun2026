<x-layouts.app title="Runner Dashboard - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <!-- Header / Auth Navigation -->
    <div class="absolute top-0 left-0 right-0 h-20 px-10 md:px-12 flex items-center justify-between border-b border-slate-100 bg-white/80 backdrop-blur-md z-30">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-2xl bg-[#003366] flex items-center justify-center font-black text-white text-lg shadow-lg shadow-blue-950/10">I</div>
            <div class="hidden sm:block text-left">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[3px] leading-tight">Runner Dashboard</p>
                <h1 class="text-lg font-black text-[#003366] uppercase leading-tight tracking-tighter">IPB RUN 2026</h1>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
             <div class="flex items-center gap-4 px-5 h-12 bg-white/50 backdrop-blur rounded-2xl border border-white">
                <div class="text-right hidden md:block">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Authenticated</p>
                    <p class="text-xs font-black text-[#003366] truncate max-w-[150px]">{{ $participant->name }}</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-black text-xs border border-orange-200 shadow-inner">
                    {{ strtoupper(substr($participant->name, 0, 1)) }}
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

    <div class="min-h-screen pt-32 pb-20 px-4 sm:px-8 flex flex-col items-center">
        <div class="max-w-[1240px] w-full grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Left Sidebar: Runner Identity -->
            <div class="lg:col-span-1">
                 <div class="sticky top-28 space-y-6">
                    <div class="bg-white/90 backdrop-blur-2xl rounded-[3rem] border border-slate-100 p-8 shadow-xl shadow-blue-950/5 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-50 group-hover:bg-blue-100 transition-all duration-700"></div>
                        
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl flex items-center justify-center text-xl font-black text-white shadow-xl shadow-blue-900/20 mb-6">
                                {{ strtoupper(substr($participant->name, 0, 1)) }}
                            </div>
                            <p class="text-[10px] font-black text-[#E8630A] uppercase tracking-[4px] mb-2 opacity-80">Verified Athlete</p>
                            <h2 class="text-2xl font-[900] text-[#003366] leading-tight mb-8 font-['Plus_Jakarta_Sans']">{{ $participant->name }}</h2>
                            
                            <div class="space-y-4">
                                <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-100/50">
                                    <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[2px] mb-1">NIK Number</label>
                                    <p class="text-xs font-black text-[#003366]">{{ $participant->nik }}</p>
                                </div>
                                <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-100/50">
                                    <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[2px] mb-1">Jersey Size</label>
                                    <p class="text-xs font-black text-[#003366]">{{ $participant->jersey_size }} (Standard)</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-100/50">
                                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[2px] mb-1">Blood</label>
                                        <p class="text-xs font-black text-[#003366]">{{ $participant->blood_type }}</p>
                                    </div>
                                    <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-100/50 text-center">
                                         <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[2px] mb-1">Tickets</label>
                                        <p class="text-xs font-black text-[#E8630A]">{{ $participant->raceEntries->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-[#003366] rounded-[2.5rem] text-white shadow-xl shadow-blue-950/20 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
                        <div class="relative z-10">
                            <h4 class="text-sm font-black uppercase tracking-widest mb-4">Official Supporter</h4>
                            <p class="text-[11px] leading-relaxed font-bold opacity-70 mb-6">Nikmati pengalaman lari TERBAIK di IPB Run 2026. Persiapkan diri Anda di garis start!</p>
                            <a href="#" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-orange-400 hover:text-orange-300 transition-colors">
                                Download Handbook 
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </a>
                        </div>
                    </div>
                 </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-10">
                
                <!-- TOP SECTION: OWNED TICKETS (MAIN FOCUS) -->
                <section>
                    <div class="flex items-center gap-6 mb-8">
                         <h2 class="text-[20px] font-black text-[#003366] uppercase tracking-[4px] font-['Plus_Jakarta_Sans']">Owned Tickets</h2>
                         <div class="h-[1px] bg-slate-200 flex-grow"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($participant->raceEntries as $entry)
                        <div class="bg-white rounded-[3rem] border border-slate-100 p-8 shadow-sm relative overflow-hidden group hover:shadow-2xl hover:shadow-blue-950/5 transition-all duration-500">
                             <!-- Status Badge -->
                             <div class="absolute top-8 right-8">
                                @if($entry->status === 'paid')
                                    <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-full">
                                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest italic">Confirmed</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 px-4 py-2 bg-orange-50 border border-orange-100 rounded-full">
                                        <div class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"></div>
                                        <span class="text-[10px] font-black text-orange-600 uppercase tracking-widest italic">Pending Payment</span>
                                    </div>
                                @endif
                             </div>

                             <div class="mb-10">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[4px] mb-2">RACE CATEGORY</p>
                                <h3 class="text-4xl font-[900] text-[#003366] leading-none uppercase tracking-tighter mb-4">{{ $entry->ticket->category->name }}</h3>
                                <div class="flex items-center gap-3">
                                    <span class="text-[11px] font-bold text-[#E8630A] uppercase tracking-[2px]">{{ $entry->ticket->name }}</span>
                                    <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-[2px] italic">#{{ $entry->order->order_code }}</span>
                                </div>
                             </div>

                             <!-- Footer Details Area -->
                             <div class="bg-slate-50/50 rounded-3xl p-6 border border-slate-100/50 flex items-center justify-between group-hover:bg-blue-50/50 transition-colors">
                                @if($entry->status === 'paid')
                                    <div class="flex-grow">
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">BIB Number Assigned</p>
                                        <span class="text-3xl font-black text-blue-600 tracking-[5px] font-mono select-all">{{ $entry->bib_number ?: 'TBA' }}</span>
                                    </div>
                                    <div class="w-12 h-12 bg-white rounded-2xl border border-slate-100 flex items-center justify-center text-blue-600 shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    </div>
                                @else
                                    <div class="flex-grow">
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-tight">Complete your payment <br> to secure your slot</p>
                                    </div>
                                    <a href="{{ $entry->order->payment_url }}" class="flex items-center gap-2 px-5 h-12 bg-orange-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-orange-900/10 hover:bg-orange-700 transition-all active:scale-95">
                                        PAY NOW
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                @endif
                             </div>
                        </div>
                        @endforeach
                    </div>
                </section>

                <!-- BOTTOM SECTION: PAIR RECOMMENDATION (NEW CHALLENGE) -->
                @if($pairRecommendation)
                <section>
                    <div class="flex items-center gap-6 mb-8">
                         <h2 class="text-[18px] font-black text-[#003366] uppercase tracking-[4px] font-['Plus_Jakarta_Sans']">Recommended Challenge</h2>
                         <div class="h-[1px] bg-slate-200 flex-grow border-dashed border-t-2"></div>
                    </div>

                    <div class="bg-gradient-to-br from-[#003366] to-blue-900 rounded-[3.5rem] p-10 md:p-14 relative overflow-hidden group shadow-2xl shadow-blue-950/20">
                        <div class="absolute -top-32 -right-32 w-80 h-80 bg-orange-500/20 rounded-full blur-[100px] group-hover:scale-125 transition-transform duration-1000"></div>
                        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-blue-400/10 rounded-full blur-[100px] group-hover:translate-x-12 transition-transform duration-1000"></div>
                        
                        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
                            <div class="max-w-xl text-center md:text-left">
                                <span class="inline-flex items-center px-4 py-2 bg-orange-500 text-white text-[9px] font-black uppercase tracking-[3px] rounded-full mb-8 shadow-lg shadow-orange-500/20">SPECIAL UPSELL OFFER</span>
                                <h3 class="text-4xl md:text-5xl font-[900] text-white leading-none uppercase tracking-tighter mb-6">
                                    LENGKAPI PAKET <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-300 italic underline decoration-white/20 underline-offset-8">GANDA KATEGORI</span>
                                </h3>
                                <p class="text-lg text-white/50 leading-relaxed font-bold tracking-tight mb-0">Daftar kategori <span class="text-white">{{ $pairRecommendation->category->name }}</span> ({{ $pairRecommendation->name }}) sekarang tanpa perlu mengisi ulang formulir pendaftaran.</p>
                            </div>

                            <div class="w-full md:w-auto bg-white/5 backdrop-blur-xl border border-white/10 rounded-[3rem] p-10 text-center flex flex-col items-center">
                                 <p class="text-[10px] font-black text-white/40 uppercase tracking-[4px] mb-4">BUNDLE PRICE</p>
                                 <div class="text-4xl font-[900] text-white leading-none mb-10 font-['Plus_Jakarta_Sans'] uppercase tracking-tighter italic">
                                    Rp {{ number_format($pairRecommendation->price, 0, ',', '.') }}
                                 </div>
                                 <a href="{{ route('participant.buy-more', $pairRecommendation->id) }}" 
                                    class="w-full md:w-64 h-16 bg-white hover:bg-orange-500 hover:text-white text-[#003366] rounded-2xl font-black text-xs uppercase tracking-[3px] shadow-2xl transition-all active:scale-[0.98] flex items-center justify-center group-hover:scale-105">
                                    AMBIL TIKET INI!
                                 </a>
                            </div>
                        </div>
                    </div>
                </section>
                @endif

                @if(!$pairRecommendation && $participant->raceEntries->count() >= 2)
                <section class="bg-emerald-50 rounded-[3rem] border border-emerald-100/50 p-12 text-center">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600 shadow-xl shadow-emerald-950/10">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-[#003366] uppercase tracking-widest mb-2 font-['Plus_Jakarta_Sans']">RACE STATUS: COMPLETE</h3>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest leading-loose italic opacity-60">Selamat! Anda telah terdaftar di semua kategori <br> utama IPB Run 2026. Sampai jumpa di Race Day!</p>
                </section>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
