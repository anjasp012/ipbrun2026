<x-layouts.admin title="Dashboard Summary">
    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 px-6 py-4 rounded-2xl border border-emerald-100 text-sm font-bold flex items-center gap-3 animate-slide-in shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Website Status Section (Maintained) -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 overflow-hidden relative">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-4">
                    <div @class([
                        'w-12 h-12 rounded-2xl flex items-center justify-center transition-colors',
                        'bg-emerald-50 text-emerald-600' => \App\Models\Setting::getValue('wa_notification_active', '0') === '1',
                        'bg-rose-50 text-rose-600' => \App\Models\Setting::getValue('wa_notification_active', '0') !== '1'
                    ])>
                        @if(\App\Models\Setting::getValue('wa_notification_active', '0') === '1')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        @else
                            <svg class="w-6 h-6 opacity-40 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest">WA Notifications</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            Fonnte Engine: 
                            <span @class([
                                'px-2 py-0.5 rounded-full',
                                'bg-emerald-100 text-emerald-700' => \App\Models\Setting::getValue('wa_notification_active', '0') === '1',
                                'bg-rose-100 text-rose-700' => \App\Models\Setting::getValue('wa_notification_active', '0') !== '1'
                            ])>
                                {{ \App\Models\Setting::getValue('wa_notification_active', '0') === '1' ? 'CONNECTED / ACTIVE' : 'DISABLED' }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div @class([
                        'w-12 h-12 rounded-2xl flex items-center justify-center transition-colors',
                        'bg-emerald-50 text-emerald-600' => $stats['is_running'],
                        'bg-rose-50 text-rose-600' => !$stats['is_running']
                    ])>
                        @if($stats['is_running'])
                            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest">Website Status</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            Current Mode: 
                            <span @class([
                                'px-2 py-0.5 rounded-full',
                                'bg-emerald-100 text-emerald-700' => $stats['is_running'],
                                'bg-rose-100 text-rose-700' => !$stats['is_running']
                            ])>
                                {{ $stats['is_running'] ? 'ACTIVE / OPEN' : 'MAINTENANCE / CLOSED' }}
                            </span>
                        </p>
                    </div>
                </div>
                
                <form action="{{ url('/admin/toggle-running') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status operasional website?')">
                    @csrf
                    <x-button type="submit" variant="{{ $stats['is_running'] ? 'danger' : 'success' }}" class="px-6 py-2.5 rounded-xl">
                        {{ $stats['is_running'] ? 'Set to Maintenance' : 'Open Registration' }}
                    </x-button>
                </form>
            </div>
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-64 h-64 bg-slate-50/50 rounded-full"></div>
        </div>

        <section class="space-y-6">
            <h3 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Ringkasan Data</h3>
            
            <!-- Summary Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- Total Pendapatan --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm border-l-4 border-l-blue-100">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">TOTAL PENDAPATAN TERBAYAR</p>
                    <h4 class="text-xl font-black text-slate-800">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
                </div>

                {{-- Total Order --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">TOTAL ORDER</p>
                    <h4 class="text-2xl font-black text-slate-800">{{ number_format($stats['total_order'], 0, ',', '.') }}</h4>
                </div>

                {{-- Total Peserta --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">TOTAL PESERTA</p>
                    <h4 class="text-2xl font-black text-slate-800">{{ number_format($stats['total_participants'], 0, ',', '.') }}</h4>
                </div>

                {{-- Total Sisa Tiket --}}
                <div @class(['bg-white p-6 rounded-2xl border border-slate-100 shadow-sm border-l-4 border-l-emerald-500'])>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">TOTAL SISA TIKET</p>
                    <h4 class="text-2xl font-black text-emerald-600">{{ number_format($stats['total_remaining_tickets'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </section>

        <!-- Periods Breakdown -->
        @foreach($periodsData as $period)
        <section class="space-y-6">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Periode: {{ $period->name }}</h3>
            
            <!-- Period Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">TOTAL KAPASITAS</p>
                    <h4 class="text-xl font-black text-slate-800">{{ number_format($period->total_kapasitas, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-yellow-50/50 p-5 rounded-2xl border border-yellow-100/50">
                    <p class="text-[9px] font-black text-yellow-600 uppercase tracking-widest mb-1">TOTAL TERJUAL</p>
                    <h4 class="text-xl font-black text-yellow-700 truncate">{{ number_format($period->total_terjual, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100">
                    <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">TOTAL SISA STOK</p>
                    <h4 class="text-xl font-black text-emerald-700 truncate">{{ number_format($period->total_sisa_stok, 0, ',', '.') }}</h4>
                </div>
            </div>

            <!-- Ticket Table -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 uppercase tracking-widest text-[11px] font-black text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4">KATEGORI</th>
                                <th class="px-6 py-4">NAMA TIKET</th>
                                <th class="px-6 py-4 text-center">HARGA</th>
                                <th class="px-6 py-4 text-center">KAPASITAS</th>
                                <th class="px-6 py-4 text-center">TERJUAL</th>
                                <th class="px-6 py-4 text-center">SISA STOK</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($period->tickets as $ticket)
                            <tr class="hover:bg-slate-50/10 transition-colors">
                                <td class="px-6 py-4 text-[13px] font-bold text-slate-600 uppercase">{{ $ticket->kategori }}</td>
                                <td class="px-6 py-4 text-[13px] font-black text-slate-800 uppercase">{{ $ticket->name }}</td>
                                <td class="px-6 py-4 text-center font-black text-slate-800 text-[13px]">Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center text-[13px] font-bold text-slate-500">{{ $ticket->kapasitas }}</td>
                                <td @class([
                                    'px-6 py-4 text-center text-[13px] font-bold',
                                    'text-yellow-600' => $ticket->terjual > 0,
                                    'text-slate-300' => $ticket->terjual == 0
                                ])>{{ $ticket->terjual }}</td>
                                <td @class([
                                    'px-6 py-4 text-center text-[13px] font-black',
                                    'text-emerald-600' => $ticket->sisa_stok > 0,
                                    'text-rose-500' => $ticket->sisa_stok == 0
                                ])>{{ $ticket->sisa_stok }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        @endforeach
    </div>
</x-layouts.admin>
