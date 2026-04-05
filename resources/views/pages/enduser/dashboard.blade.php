<x-layouts.app title="Dashboard Peserta - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f4f6f9] z-[-2]"></div>
    
    <!-- Official Header -->
    <div class="h-20 border-b border-slate-200 bg-white sticky top-0 z-50 px-6 sm:px-12 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-6">
             <div class="flex flex-col">
                 <h1 class="text-lg font-black text-[#003366] uppercase tracking-tighter leading-none mb-1">IPB RUN 2026</h1>
                 <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Official Participant Portal</p>
             </div>
        </div>
        <div class="flex items-center gap-6">
             <div class="hidden sm:flex flex-col text-right border-r border-slate-100 pr-6 mr-2">
                 <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Halaman Akun</span>
                 <span class="text-xs font-black text-[#003366] uppercase">{{ auth()->user()->name }}</span>
             </div>
             <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-[11px] font-black text-white bg-[#003366] px-6 h-11 rounded-xl uppercase tracking-widest hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/10 active:scale-95">Keluar</button>
             </form>
        </div>
    </div>

    <div class="max-w-[1000px] mx-auto py-12 px-6">
        
        <!-- Welcome Message Section -->
        <div class="mb-12 border-b border-slate-200 pb-10">
            <h2 class="text-3xl font-[900] text-[#003366] tracking-tight uppercase mb-3">Selamat Datang, {{ explode(' ', $participant->name)[0] }}</h2>
            <p class="text-sm text-slate-500 font-bold uppercase tracking-widest leading-relaxed max-w-2xl opacity-70">
                Gunakan dashboard ini untuk memantau status pendaftaran, mengunduh bukti pembayaran, dan melengkapi pendaftaran kategori tambahan.
            </p>
        </div>

        <!-- Section: Verified Registrations (Owned Tickets) -->
        <div class="space-y-10 mb-16">
            <div class="flex items-center gap-6">
                 <h3 class="text-[12px] font-black text-slate-400 uppercase tracking-[4px] whitespace-nowrap">Status Pendaftaran</h3>
                 <div class="h-px bg-slate-200 flex-grow"></div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                @foreach($participant->raceEntries as $entry)
                <div class="bg-white border-2 border-slate-100 rounded-[2.5rem] p-10 transition-all hover:border-blue-100/50 hover:shadow-2xl hover:shadow-blue-950/5">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-10">
                        <div class="flex-grow">
                             <div class="flex items-center gap-4 mb-4">
                                @if($entry->status === 'paid')
                                     <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100 text-[10px] font-black uppercase tracking-widest italic">Terverifikasi</span>
                                @else
                                     <span class="px-4 py-1.5 bg-amber-50 text-amber-600 rounded-full border border-amber-100 text-[10px] font-black uppercase tracking-widest italic">Menunggu Pembayaran</span>
                                @endif
                             </div>
                             
                             <h4 class="text-3xl font-[900] text-[#003366] uppercase mb-4 tracking-tighter">{{ $entry->ticket->category->name }}</h4>
                             
                             <div class="flex flex-wrap items-center gap-x-10 gap-y-4">
                                <div class="item">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Paket Tiket</p>
                                    <p class="text-sm font-black text-[#E8630A] uppercase">{{ $entry->ticket->name }}</p>
                                </div>
                                <div class="item">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Jadwal Lomba</p>
                                    @php
                                        $catName = strtoupper($entry->ticket->category->name);
                                        $day = (str_contains($catName, '5K') || str_contains($catName, '42K')) ? 'Sabtu, 5 April 2026' : 'Minggu, 6 April 2026';
                                    @endphp
                                    <p class="text-sm font-black text-[#003366] uppercase">{{ $day }}</p>
                                </div>
                                <div class="item">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Kode Pesanan</p>
                                    <p class="text-sm font-black text-slate-500 uppercase tracking-tighter">#{{ $entry->order->order_code }}</p>
                                </div>
                             </div>
                        </div>

                        <div class="md:w-64 shrink-0">
                            @if($entry->status === 'paid')
                                <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl text-center md:text-right">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 leading-none">Nomor Dada (BIB)</p>
                                    <div class="text-4xl font-black text-[#003366] font-mono tracking-widest">{{ $entry->bib_number ?: 'TBA' }}</div>
                                </div>
                            @else
                                <div class="flex flex-col gap-3">
                                    <a href="{{ $entry->order->payment_url }}" class="w-full bg-[#E8630A] text-white h-14 rounded-2xl flex items-center justify-center font-black text-xs uppercase tracking-[2px] shadow-lg shadow-orange-950/10 hover:bg-orange-700 transition-all active:scale-95">Selesaikan Pembayaran</a>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center leading-relaxed">Pastikan pembayaran dilakukan sebelum batas waktu berakhir.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Section: Additional Category Recommendation (Formal Upsell) -->
        @if($pairRecommendation)
        <div class="bg-[#003366] rounded-[3rem] p-12 md:p-14 text-white relative overflow-hidden group shadow-2xl shadow-blue-950/20 mb-16">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
                <div class="max-w-xl text-center md:text-left">
                    <h4 class="text-2xl font-black uppercase tracking-tight mb-4">Pendaftaran Kategori Tambahan</h4>
                    <p class="text-[13px] text-white/60 font-bold uppercase tracking-widest leading-loose italic opacity-80 mb-0">
                        Anda memenuhi kualifikasi untuk mengikuti kategori {{ $pairRecommendation->category->name }} (HARI {{ (str_contains(strtoupper($pairRecommendation->category->name), '5K') || str_contains(strtoupper($pairRecommendation->category->name), '42K')) ? 'SABTU' : 'MINGGU' }}). Gunakan tombol di samping untuk mendaftar secara instan.
                    </p>
                </div>
                <div class="w-full md:w-auto text-center border-t md:border-t-0 md:border-l border-white/10 pt-10 md:pt-0 md:pl-12">
                    <p class="text-[10px] font-black text-white/40 uppercase tracking-[4px] mb-4 leading-none">Biaya Pendaftaran</p>
                    <div class="text-3xl font-black text-white leading-none mb-8 tracking-tighter italic">Rp {{ number_format($pairRecommendation->price, 0, ',', '.') }}</div>
                    <a href="{{ route('participant.buy-more', $pairRecommendation->id) }}" 
                        class="inline-flex items-center justify-center px-10 h-14 bg-white text-[#003366] rounded-2xl font-black text-[11px] uppercase tracking-[3px] hover:bg-orange-500 hover:text-white transition-all shadow-xl active:scale-95">
                        Daftar Kategori Ini
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Section: Participant Information (Formal Profile Brief) -->
        <div class="bg-white border-2 border-slate-100 rounded-[2.5rem] p-10">
             <div class="flex items-center gap-6 mb-8">
                 <h3 class="text-[12px] font-black text-slate-400 uppercase tracking-[4px] whitespace-nowrap">Detail Profil Peserta</h3>
                 <div class="h-px bg-slate-200 flex-grow border-dashed border-t-2"></div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block opacity-70">NIK / Kartu Identitas</label>
                    <p class="text-sm font-black text-[#003366] leading-none">{{ $participant->nik }}</p>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block opacity-70">Ukuran Jersey</label>
                    <p class="text-sm font-black text-[#003366] leading-none">{{ $participant->jersey_size }} (Standard)</p>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block opacity-70">Golongan Darah</label>
                    <p class="text-sm font-black text-[#003366] leading-none">{{ $participant->blood_type }}</p>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block opacity-70">Shuttle Bus</label>
                    <p class="text-sm font-black text-[#003366] leading-none">{{ $participant->shuttle_bus ?: 'Tidak Menggunakan' }}</p>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
