<x-layouts.app>
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="min-h-screen py-16 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <!-- Header Section -->
        <div class="max-w-4xl w-full text-center mb-12 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-[900] text-white uppercase tracking-tight mb-4 drop-shadow-md">
                Race <span class="text-[#FF7A21]">Time Results</span>
            </h1>
            <p class="text-white/80 font-bold uppercase tracking-[3px] text-xs md:text-sm">
                Cek Catatan Waktu Resmi Pelari IPB RUN 2026
            </p>
        </div>

        <div class="max-w-7xl w-full px-2 md:px-0">
            <!-- Search & Filter Card -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-6 md:p-8 mb-8 shadow-xl">
                <form action="{{ route('time-result') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
                    @if($activeTab)
                        <input type="hidden" name="tab" value="{{ $activeTab }}">
                    @endif
                    <div class="relative w-full flex-1">
                        <span class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-white/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan Nomor BIB atau Nama Pelari..." class="w-full h-16 pl-14 pr-12 bg-white/10 border border-white/25 rounded-2xl text-white placeholder-white/50 font-bold focus:outline-none focus:ring-2 focus:ring-[#FF7A21] focus:border-[#FF7A21] transition-all">
                        
                        @if($search)
                            <a href="{{ route('time-result', ['tab' => $activeTab]) }}" class="absolute inset-y-0 right-0 pr-5 flex items-center text-white/50 hover:text-[#FF7A21] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="w-full md:w-auto h-16 px-10 bg-[#FF7A21] hover:bg-[#e06516] text-white rounded-2xl font-[900] uppercase text-sm tracking-wider shadow-lg shadow-orange-500/20 active:scale-95 transition-all">
                        Cari Hasil
                    </button>
                </form>
            </div>

            <!-- Categories Tabs -->
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <a href="{{ route('time-result', ['tab' => 'SEMUA', 'search' => $search]) }}" 
                   class="px-6 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest transition-all {{ $activeTab === 'SEMUA' ? 'bg-white text-[#003366] shadow-lg' : 'bg-white/10 hover:bg-white/20 text-white border border-white/10' }}">
                    SEMUA
                </a>
                @if($categories->isNotEmpty())
                    @foreach($categories as $category)
                        <a href="{{ route('time-result', ['tab' => $category, 'search' => $search]) }}" 
                           class="px-6 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest transition-all {{ $activeTab === $category ? 'bg-white text-[#003366] shadow-lg' : 'bg-white/10 hover:bg-white/20 text-white border border-white/10' }}">
                            {{ $category }}
                        </a>
                    @endforeach
                @endif
            </div>

            <!-- Main Results Table -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#003366] text-white">
                                <th class="py-5 pl-8 text-[11px] font-black uppercase tracking-widest w-20">Rank</th>
                                <th class="py-5 text-[11px] font-black uppercase tracking-widest w-24">BIB</th>
                                <th class="py-5 text-[11px] font-black uppercase tracking-widest">Nama Pelari</th>
                                <th class="py-5 text-[11px] font-black uppercase tracking-widest hidden md:table-cell">Kategori</th>
                                <th class="py-5 text-[11px] font-black uppercase tracking-widest text-center hidden md:table-cell">Gender</th>
                                <th class="py-5 text-[11px] font-black uppercase tracking-widest text-center">Net Time</th>
                                <th class="py-5 text-[11px] font-black uppercase tracking-widest text-center hidden sm:table-cell">Gun Time</th>
                                <th class="py-5 text-[11px] font-black uppercase tracking-widest text-center hidden lg:table-cell">CP 1</th>
                                <th class="py-5 text-[11px] font-black uppercase tracking-widest text-center hidden lg:table-cell">CP 2</th>
                                <th class="py-5 pr-8 text-[11px] font-black uppercase tracking-widest text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($results as $res)
                                @php
                                    $rank = ($results->currentPage() - 1) * $results->perPage() + $loop->iteration;
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-all font-bold text-sm text-slate-600">
                                    <!-- Rank with premium style badge -->
                                    <td class="py-5 pl-8">
                                        @if($res->status === 'Finished')
                                            @if($rank === 1)
                                                <span class="w-7 h-7 rounded-full bg-yellow-400 text-yellow-950 flex items-center justify-center text-xs font-black shadow-md shadow-yellow-400/20">1</span>
                                            @elseif($rank === 2)
                                                <span class="w-7 h-7 rounded-full bg-slate-300 text-slate-900 flex items-center justify-center text-xs font-black shadow-md shadow-slate-300/20">2</span>
                                            @elseif($rank === 3)
                                                <span class="w-7 h-7 rounded-full bg-amber-600 text-white flex items-center justify-center text-xs font-black shadow-md shadow-amber-600/20">3</span>
                                            @else
                                                <span class="text-slate-400 font-bold text-xs pl-2">{{ $rank }}</span>
                                            @endif
                                        @else
                                            <span class="text-slate-400 font-bold text-xs pl-2">-</span>
                                        @endif
                                    </td>
                                    
                                    <!-- BIB -->
                                    <td class="py-5 font-black text-[#003366]">{{ $res->bib }}</td>
                                    
                                    <!-- Name -->
                                    <td class="py-5 font-black text-[#003366] text-base">
                                        {{ $res->name }}
                                    </td>
                                    
                                    <!-- Category -->
                                    <td class="py-5 hidden md:table-cell">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-800 rounded-full text-[10px] font-black uppercase tracking-wider">
                                            {{ $res->item }}
                                        </span>
                                    </td>
                                    
                                    <!-- Gender -->
                                    <td class="py-5 text-center hidden md:table-cell">
                                        <span class="text-slate-500">{{ $res->gender ?: '-' }}</span>
                                    </td>
                                    
                                    <!-- Net Time (Primary Highlight) -->
                                    <td class="py-5 text-center text-base font-black text-[#E8630A] font-mono">
                                        {{ $res->net_time ?: '-' }}
                                    </td>
                                    
                                    <!-- Gun Time -->
                                    <td class="py-5 text-center font-mono hidden sm:table-cell text-slate-500">
                                        {{ $res->gun_time ?: '-' }}
                                    </td>
                                    
                                    <!-- CP1 & CP2 -->
                                    <td class="py-5 text-center font-mono hidden lg:table-cell text-slate-400 text-xs">
                                        {{ $res->cp1 ?: '-' }}
                                    </td>
                                    <td class="py-5 text-center font-mono hidden lg:table-cell text-slate-400 text-xs">
                                        {{ $res->cp2 ?: '-' }}
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="py-5 pr-8 text-right">
                                        @if($res->status)
                                            <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ trim(strtoupper($res->status)) === 'FINISHED' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                                {{ $res->status }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 font-bold text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-slate-400 font-bold text-lg">Catatan waktu tidak ditemukan</p>
                                            <p class="text-slate-400/70 font-medium text-sm mt-1">Coba cari dengan Nomor BIB atau Nama lain</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($results->hasPages())
                    <div class="p-6 bg-slate-50 border-t border-slate-100">
                        {{ $results->links() }}
                    </div>
                @endif
            </div>
            
            <!-- Disclaimer / Back link -->
            <div class="mt-8 text-center pb-20">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 px-8 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-md text-white rounded-2xl font-[800] text-sm uppercase tracking-widest transition-all active:scale-95 border border-white/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
