<x-layouts.app title="Dashboard Peserta - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f4f6f9] z-[-2]"></div>
    <div class="max-w-[1000px] mx-auto py-12 px-6">
        <!-- Welcome Message Section -->
        <div class="mb-12 border-b border-slate-200 pb-10">
            <h2 class="text-3xl md:text-4xl font-[900] text-[#003366] tracking-tight uppercase mb-3 leading-none">
                Selamat Datang, {{ explode(' ', $participant->name)[0] }}
            </h2>
            <p class="text-xs md:text-sm text-slate-500 font-bold uppercase tracking-widest leading-relaxed max-w-2xl opacity-70">
                Pantau status pendaftaran, periksa rincian logistik, dan lengkapi kategori tambahan Anda secara instan di portal atlet IPB RUN 2026.
            </p>
        </div>

        <!-- Section: Verified Registrations (Owned Tickets) -->
        <div class="space-y-10 mb-20">
            <div class="flex items-center gap-6">
                <h3 class="text-[12px] font-black text-slate-400 uppercase tracking-[4px] whitespace-nowrap">Status Pendaftaran</h3>
                <div class="h-px bg-slate-200 flex-grow"></div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                @foreach($participant->raceEntries as $entry)
                    <div class="group bg-white border-2 border-slate-100 rounded-[2.5rem] p-8 md:p-10 transition-all hover:border-blue-100 hover:shadow-2xl hover:shadow-blue-950/5 relative overflow-hidden">
                        {{-- Decorative status bar --}}
                        <div class="absolute left-0 top-0 bottom-0 w-2 
                            {{ $entry->status === 'paid' ? 'bg-emerald-500' : ($entry->status === 'failed' ? 'bg-rose-500' : 'bg-orange-500 animate-pulse') }}">
                        </div>
                        
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                            <div class="flex-grow">
                                <div class="flex items-center gap-4 mb-6">
                                    @if($entry->status === 'paid')
                                        <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100 text-[10px] font-black uppercase tracking-widest italic flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Terverifikasi
                                        </span>
                                    @elseif($entry->status === 'failed')
                                        <span class="px-4 py-1.5 bg-rose-50 text-rose-600 rounded-full border border-rose-100 text-[10px] font-black uppercase tracking-widest italic flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Pembayaran Kadaluwarsa / Gagal
                                        </span>
                                    @else
                                        <span class="px-4 py-1.5 bg-amber-50 text-amber-600 rounded-full border border-amber-100 text-[10px] font-black uppercase tracking-widest italic flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span> Menunggu Pembayaran
                                        </span>
                                    @endif
                                </div>
                                <h4 class="text-3xl md:text-4xl font-[900] text-[#003366] uppercase mb-6 tracking-tighter leading-tight">{{ $entry->ticket->category->name }}</h4>
                                
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-8 gap-x-6">
                                    <div class="item">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 leading-none">Paket Tiket</p>
                                        <p class="text-sm font-black text-[#E8630A] uppercase">{{ $entry->ticket->name ?: $entry->ticket->type }}</p>
                                    </div>
                                    <div class="item">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 leading-none">Jadwal Lomba</p>
                                        @php 
                                            $catName = strtoupper($entry->ticket->category->name);
                                            $day = (str_contains($catName, '5K') || str_contains($catName, '42K')) ? 'Sabtu, 6 Juni 2026' : 'Minggu, 7 Juni 2026';
                                        @endphp
                                        <p class="text-sm font-black text-[#003366] uppercase group-hover:text-blue-600 transition-colors">{{ $day }}</p>
                                    </div>
                                    <div class="item col-span-2 md:col-span-1">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 leading-none">Kode Pesanan</p>
                                        <p class="text-sm font-black text-slate-800 font-mono tracking-tighter">#{{ $entry->order->order_code }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="lg:w-72 shrink-0">
                                @if($entry->status === 'paid')
                                    <div class="p-8 bg-slate-50 border border-slate-100 rounded-[2rem] text-center lg:text-right group-hover:bg-blue-50/50 transition-colors">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 leading-none opacity-70">Nomor Dada (BIB)</p>
                                        <div class="text-5xl font-black text-[#003366] font-mono tracking-widest leading-none">{{ $entry->bib_number ?: 'TBA' }}</div>
                                    </div>
                                @elseif($entry->status === 'failed')
                                    <div class="flex flex-col gap-4 text-center lg:text-right">
                                        <p class="text-[10px] font-black text-rose-500 uppercase tracking-[2px] leading-relaxed">
                                            Waktu pembayaran telah habis. Pesanan ini dibatalkan secara otomatis.
                                        </p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest opacity-60">
                                            Silakan lakukan pendaftaran ulang jika ingin mengikuti kategori ini.
                                        </p>
                                    </div>
                                @else
                                    <div class="flex flex-col gap-4">
                                        <a href="{{ $entry->order->payment_url }}" 
                                           class="w-full bg-[#E8630A] text-white h-16 rounded-2xl flex items-center justify-center font-black text-xs uppercase tracking-[2px] shadow-xl shadow-orange-950/10 hover:bg-orange-700 hover:scale-[1.02] transform transition-all active:scale-95">
                                           Selesaikan Bayar
                                        </a>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[2px] text-center leading-relaxed opacity-60">
                                           Segera selesaikan sebelum slot kategori ini habis.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section: Additional Category Recommendation (Formal Upsell) -->
        @if($pairRecommendation && $participant->raceEntries->where('status', 'paid')->count() < 2)
            <div class="bg-[#003366] rounded-[3rem] p-10 md:p-14 text-white relative overflow-hidden group shadow-2xl shadow-blue-950/20 mb-20 border border-white/5">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-12">
                    <div class="flex-grow text-center lg:text-left">
                        <h4 class="text-2xl md:text-3xl font-black uppercase tracking-tight mb-6 leading-tight">Pendaftaran Tiket Tambahan</h4>
                        <p class="text-[12px] md:text-[13px] text-white/70 font-bold uppercase tracking-widest leading-loose italic opacity-80">
                            @php
                                $recCatName = strtoupper($pairRecommendation->category->name);
                                $recDay = (str_contains($recCatName, '5K') || str_contains($recCatName, '42K')) ? 'HARI SABTU' : 'HARI MINGGU';
                            @endphp
                            Anda memenuhi kualifikasi untuk mengikuti kategori {{ $recCatName }} ({{ $recDay }}). Gunakan tombol di samping untuk mendaftar secara instan
                        </p>
                    </div>

                    <div class="w-full lg:w-auto text-center border-t lg:border-t-0 lg:border-l border-white/10 pt-10 lg:pt-0 lg:pl-16 flex flex-col items-center lg:items-end">
                        <p class="text-[9px] font-black text-white/40 uppercase tracking-[5px] mb-4 leading-none">Biaya Pendaftaran</p>
                        
                        <div class="mb-10 text-center lg:text-right">
                            @if($potentialDiscount > 0)
                                <div class="flex items-center justify-center lg:justify-end gap-3 mb-2">
                                    <span class="text-xs font-bold text-white/30 line-through tracking-tighter italic decoration-orange-500/50 decoration-2">Rp {{ number_format($pairRecommendation->price, 0, ',', '.') }}</span>
                                    <span class="px-2 py-0.5 bg-orange-500 text-white rounded text-[8px] font-black uppercase tracking-widest">-{{ $potentialVoucher->type === 'percentage' ? $potentialVoucher->value . '%' : 'Rp' . number_format($potentialVoucher->value, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-4xl md:text-5xl font-[900] text-white leading-none tracking-tighter italic mb-3">Rp {{ number_format($pairRecommendation->price - $potentialDiscount, 0, ',', '.') }}</div>
                                <div class="inline-flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 px-3 py-1.5 rounded-xl">
                                    <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">🎟️ VOUCHER: {{ $potentialVoucher->code }}</span>
                                </div>
                            @else
                                <div class="text-4xl md:text-5xl font-[900] text-white leading-none tracking-tighter italic">Rp {{ number_format($pairRecommendation->price, 0, ',', '.') }}</div>
                            @endif
                        </div>

                        <a href="{{ route('participant.buy-more', $pairRecommendation->id) }}" 
                           class="w-full lg:w-auto inline-flex items-center justify-center px-12 h-16 bg-[#E8630A] text-white rounded-2xl font-black text-[11px] uppercase tracking-[4px] hover:bg-orange-700 transition-all shadow-2xl shadow-orange-950/20 transform hover:scale-[1.05] active:scale-95">
                           Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Section: Athlete Profile Detail (Grid Modules) -->
        <div class="space-y-8 mb-20">
            <div class="flex items-center gap-6">
                <h3 class="text-[12px] font-black text-slate-400 uppercase tracking-[4px] whitespace-nowrap">Detail Profil Peserta</h3>
                <div class="h-px bg-slate-200 flex-grow border-dashed border-t-2"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Module 1: Identitas & Kontak --}}
                <div class="bg-white border-2 border-slate-100 rounded-[2.5rem] p-10 space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-blue-50 text-[#003366] rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h5 class="text-[11px] font-black text-[#003366] uppercase tracking-widest">Identitas & Kontak</h5>
                    </div>

                    <div class="space-y-6">
                        <div class="group">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 opacity-70 group-hover:text-blue-500 transition-colors">NIK / Identity</label>
                            <p class="text-sm font-black text-slate-800 tracking-tight">{{ $participant->nik }}</p>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 opacity-70">Email Address</label>
                            <p class="text-sm font-black text-slate-800 break-all">{{ $participant->email }}</p>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 opacity-70">Phone (WhatsApp)</label>
                            <p class="text-sm font-black text-slate-800">{{ $participant->phone_number ?: '-' }}</p>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 opacity-70">Alamat</label>
                            <p class="text-[13px] font-black text-slate-800 leading-relaxed">{{ $participant->address ?: '-' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 opacity-70">Gender</label>
                                <p class="text-xs font-black text-slate-800 uppercase">{{ $participant->sex ?: '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 opacity-70">Lahir</label>
                                <p class="text-xs font-black text-slate-800">{{ $participant->date_birth ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Module 2: Medis & Darurat --}}
                <div class="bg-white border-2 border-slate-100 rounded-[2.5rem] p-10 space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-rose-50 text-rose-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h5 class="text-[11px] font-black text-rose-600 uppercase tracking-widest">Medis & Darurat</h5>
                    </div>

                    <div class="space-y-6">
                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100/50">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest opacity-70">GOL. Darah</label>
                            <p class="text-xl font-black text-rose-600">{{ $participant->blood_type ?: '-' }}</p>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2 opacity-70 italic font-bold">Kondisi Medis Khusus</label>
                            <p class="text-xs font-black text-slate-700 leading-relaxed">{{ $participant->medical_condition ?: 'Tidak Ada' }}</p>
                        </div>
                        
                        <div class="pt-6 border-t border-slate-50 space-y-4">
                            <label class="text-[9px] font-black text-slate-600 uppercase tracking-widest block mb-3 opacity-70 underline decoration-rose-200 decoration-2">Kontak Darurat</label>
                            <div>
                                <p class="text-xs font-black text-slate-800 uppercase mb-0.5">{{ $participant->emergency_contact_name ?: '-' }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $participant->emergency_contact_relationship ?: 'Relasi -' }}</p>
                                <p class="text-sm font-black text-rose-600">{{ $participant->emergency_contact_phone_number ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Module 3: Portfolio & Logistik --}}
                <div class="bg-white border-2 border-slate-100 rounded-[2.5rem] p-10 space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-orange-50 text-orange-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h5 class="text-[11px] font-black text-orange-600 uppercase tracking-widest">Logistik & Lomba</h5>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 opacity-70">Ukuran Jersey</label>
                                <p class="text-sm font-black text-[#003366] uppercase">{{ $participant->jersey_size }}</p>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 opacity-70">Best Time</label>
                                <p class="text-sm font-black text-[#003366]">{{ $participant->best_time ?: '00:00:00' }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2 opacity-70">Komunitas Lari</label>
                            <p class="text-sm font-black text-slate-800 italic">{{ $participant->running_community ?: 'Individu / Umum' }}</p>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 opacity-70">Shuttle Bus</label>
                            <p class="text-sm font-black text-[#003366] uppercase tracking-tighter">{{ $participant->shuttle_bus ?: 'TIDAK MENGGUNAKAN' }}</p>
                        </div>
                        <div class="pt-4 border-t border-slate-50">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2 opacity-70">Event Pernah Diikuti</label>
                            <p class="text-[11px] font-bold text-slate-500 italic leading-relaxed">{{ $participant->previous_events ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <p class="text-[10px] font-black text-slate-300 uppercase tracking-[5px] italic">Athlete Portal IPB RUN 2026 • Versi 2.1.0</p>
        </div>
    </div>
</x-layouts.app>

