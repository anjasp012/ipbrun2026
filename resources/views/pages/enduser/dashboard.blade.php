<x-layouts.app title="My Dashboard - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f8fafc] z-[-2]"></div>
    
    <!-- Minimal Header -->
    <div class="h-16 border-b border-slate-200 bg-white sticky top-0 z-50 px-6 sm:px-10 flex items-center justify-between">
        <div class="flex items-center gap-3">
             <div class="w-8 h-8 rounded-lg bg-[#003366] flex items-center justify-center font-bold text-white text-sm italic">IR</div>
             <h1 class="text-sm font-bold text-[#003366] uppercase tracking-wider">Dashboard Runner</h1>
        </div>
        <div class="flex items-center gap-4">
             <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest hidden sm:inline">{{ auth()->user()->name }}</span>
             <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-[10px] font-bold text-red-500 uppercase tracking-widest px-3 py-1.5 border border-red-100 rounded-lg hover:bg-red-50 transition-all">Logout</button>
             </form>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-10 px-6">
        
        <!-- Welcome Section -->
        <div class="mb-10 text-center sm:text-left">
            <h2 class="text-2xl font-black text-[#003366] tracking-tight uppercase mb-2">Halo, {{ explode(' ', $participant->name)[0] }}!</h2>
            <p class="text-sm text-slate-500 font-medium">Ini adalah riwayat pendaftaran dan status tiket Anda untuk IPB Run 2026.</p>
        </div>

        <!-- Owned Tickets (Simple Professional List) -->
        <div class="space-y-6 mb-12">
            <div class="flex items-center gap-4 mb-4">
                 <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[3px]">Tiket Saya</h3>
                 <div class="h-px bg-slate-200 flex-grow"></div>
            </div>

            @foreach($participant->raceEntries as $entry)
            <div class="bg-white border border-slate-200 rounded-2xl p-6 transition-all hover:shadow-md">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="flex gap-6">
                        <div class="w-14 h-14 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100">
                             <svg class="w-6 h-6 text-[#003366]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        </div>
                        <div>
                            <div class="flex items-baseline gap-2 mb-1">
                                <h4 class="text-lg font-black text-[#003366] uppercase">{{ $entry->ticket->category->name }}</h4>
                                <span class="text-[10px] font-bold text-slate-400">#{{ $entry->order->order_code }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-slate-500">
                                <span>{{ $entry->ticket->name }}</span>
                                <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                                @php
                                    $catName = strtoupper($entry->ticket->category->name);
                                    $day = (str_contains($catName, '5K') || str_contains($catName, '42K')) ? 'Sabtu' : 'Minggu';
                                @endphp
                                <span class="text-[#E8630A]">{{ $day }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center sm:text-right">
                        @if($entry->status === 'paid')
                            <div class="flex flex-col items-start sm:items-end">
                                <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1 italic">Confirmed</span>
                                <div class="text-2xl font-black text-[#003366] font-mono tracking-widest">{{ $entry->bib_number ?: 'TBA' }}</div>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">No. BIB (Garis Start)</span>
                            </div>
                        @else
                            <div class="flex flex-col items-start sm:items-end gap-2">
                                <span class="text-[9px] font-black text-orange-500 uppercase tracking-widest mb-1 italic">Pending</span>
                                <a href="{{ $entry->order->payment_url }}" class="bg-orange-600 text-white px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-orange-700 transition-all">Bayar Sekarang</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Single Recommendation (Simple & Professional) -->
        @if($pairRecommendation)
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-center md:text-left">
                <h4 class="text-lg font-black text-[#003366] uppercase tracking-tight mb-2">Tambah Kategori {{ $pairRecommendation->category->name }}?</h4>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest leading-relaxed">
                    Satu pendaftar boleh mengikuti lebih dari satu kategori (untuk hari yang berbeda).
                </p>
            </div>
            <a href="{{ route('participant.buy-more', $pairRecommendation->id) }}" 
                class="bg-[#003366] text-white px-8 py-4 rounded-xl font-black text-xs uppercase tracking-[2px] transition-all active:scale-95 hover:bg-blue-900 shadow-xl shadow-blue-900/10">
                GAS DAFTAR LAGI!
            </a>
        </div>
        @endif

        <!-- Profile Brief -->
        <div class="mt-12 pt-8 border-t border-slate-100 grid grid-cols-2 md:grid-cols-4 gap-6">
            <div>
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">NIK</label>
                <p class="text-xs font-bold text-[#003366]">{{ $participant->nik }}</p>
            </div>
            <div>
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Jersey</label>
                <p class="text-xs font-bold text-[#003366]">{{ $participant->jersey_size }}</p>
            </div>
            <div>
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Darah</label>
                <p class="text-xs font-bold text-[#003366]">{{ $participant->blood_type }}</p>
            </div>
            <div>
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Shuttle</label>
                <p class="text-xs font-bold text-[#003366]">{{ $participant->shuttle_bus ?: '-' }}</p>
            </div>
        </div>

    </div>
</x-layouts.app>
