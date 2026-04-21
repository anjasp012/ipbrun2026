<x-layouts.admin title="Dashboard Summary">
    <div class="space-y-8">
        @if (session('success'))
            <div
                class="bg-emerald-50 text-emerald-700 px-8 py-5 rounded-xl border border-emerald-100 text-base font-bold flex items-center gap-4 animate-slide-in shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Website Status Section (SUPERADMIN ONLY) --}}
        @if (auth()->user()->role === 'superadmin')
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 overflow-hidden relative">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 relative z-10">
                    <div class="flex items-center gap-12">
                        {{-- WA Status --}}
                        <div class="flex items-center gap-6">
                            <div @class([
                                'w-14 h-14 rounded-lg flex items-center justify-center transition-colors',
                                'bg-emerald-50 text-emerald-600' =>
                                    \App\Models\Setting::getValue('wa_notification_active', '0') === '1',
                                'bg-rose-50 text-rose-600' =>
                                    \App\Models\Setting::getValue('wa_notification_active', '0') !== '1',
                            ])>
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-black text-slate-800 uppercase tracking-widest">WA
                                    Notifications</h2>
                                <p class="text-[12px] font-bold text-slate-400 uppercase tracking-wider">
                                    Fonnte Engine: <span @class([
                                        'px-3 py-1 rounded-full',
                                        'bg-emerald-100 text-emerald-700' =>
                                            \App\Models\Setting::getValue('wa_notification_active', '0') === '1',
                                        'bg-rose-100 text-rose-700' =>
                                            \App\Models\Setting::getValue('wa_notification_active', '0') !== '1',
                                    ])>
                                        {{ \App\Models\Setting::getValue('wa_notification_active', '0') === '1' ? 'CONNECTED / ACTIVE' : 'DISABLED' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        {{-- Web Status --}}
                        <div class="flex items-center gap-6">
                            <div @class([
                                'w-14 h-14 rounded-lg flex items-center justify-center transition-colors',
                                'bg-emerald-50 text-emerald-600' => $stats['is_running'],
                                'bg-rose-50 text-rose-600' => !$stats['is_running'],
                            ])>
                                @if ($stats['is_running'])
                                    <svg class="w-7 h-7 animate-pulse" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @else
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-base font-black text-slate-800 uppercase tracking-widest">Website Status
                                </h2>
                                <p class="text-[12px] font-bold text-slate-400 uppercase tracking-wider">
                                    Current Mode: <span @class([
                                        'px-3 py-1 rounded-full',
                                        'bg-emerald-100 text-emerald-700' => $stats['is_running'],
                                        'bg-rose-100 text-rose-700' => !$stats['is_running'],
                                    ])>
                                        {{ $stats['is_running'] ? 'ACTIVE / OPEN' : 'MAINTENANCE / CLOSED' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ url('/admin/toggle-running') }}" method="POST" id="toggle-running-form">
                        @csrf
                        <x-button type="button" onclick="confirmToggle()"
                            variant="{{ $stats['is_running'] ? 'danger' : 'success' }}"
                            class="px-8 py-4 rounded-xl text-sm">
                            {{ $stats['is_running'] ? 'Set to Maintenance' : 'Open Registration' }}
                        </x-button>
                    </form>
                </div>
                <div
                    class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-64 h-64 bg-slate-50/50 rounded-full">
                </div>
            </div>
        @endif

        @push('scripts')
            <script>
                function confirmToggle() {
                    const isRunning = {{ $stats['is_running'] ? 'true' : 'false' }};
                    const title = isRunning ? 'Set to Maintenance?' : 'Open Registration?';
                    const text = isRunning ? 'This will close registration for all participants.' :
                        'This will make the registration form live for participants.';
                    const confirmButtonText = isRunning ? 'Yes, Close it' : 'Yes, Open it';
                    const confirmButtonColor = isRunning ? '#e11d48' : '#10b981';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: confirmButtonColor,
                        cancelButtonColor: '#64748b',
                        confirmButtonText: confirmButtonText,
                        cancelButtonText: 'Cancel',
                        padding: '2rem',
                        borderRadius: '1.5rem',
                        customClass: {
                            popup: 'rounded-xl border border-slate-100 shadow-2xl',
                            title: 'font-black text-[#003366] uppercase tracking-tight',
                            confirmButton: 'rounded-md px-6 py-3 font-bold uppercase tracking-widest text-xs',
                            cancelButton: 'rounded-md px-6 py-3 font-bold uppercase tracking-widest text-xs'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('toggle-running-form').submit();
                        }
                    });
                }
            </script>
        @endpush

        <section class="space-y-6">
            <h3 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Ringkasan Data</h3>
            <!-- Summary Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                {{-- Total Pendapatan --}}
                <div class="bg-white p-8 rounded-xl border border-slate-100 shadow-sm border-l-[6px] border-l-blue-100">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">TOTAL PENDAPATAN
                        TERBAYAR
                    </p>
                    <h4 class="text-2xl font-black text-slate-800">Rp
                        {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">TOTAL PENJUALAN TIKET
                            Rp
                            {{ number_format($stats['total_revenue'] - $stats['total_donation_scholarship'] - $stats['total_donation_event'] - $stats['total_admin'], 0, ',', '.') }}
                        </p>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">TOTAL DONASI BEASISWA
                            Rp
                            {{ number_format($stats['total_donation_scholarship'], 0, ',', '.') }}
                        </p>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">TOTAL DONASI EVENT
                            Rp
                            {{ number_format($stats['total_donation_event'], 0, ',', '.') }}
                        </p>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">TOTAL BIAYA ADMIN
                            Rp
                            {{ number_format($stats['total_admin'], 0, ',', '.') }}
                        </p>
                    </h4>
                </div>
                {{-- Total Tiket Terjual --}}
                <div class="bg-white p-8 rounded-xl border border-slate-100 shadow-sm bg-blue-50/10">
                    <p class="text-[11px] font-black text-blue-600 uppercase tracking-widest mb-3">TOTAL TIKET TERJUAL</p>
                    <h4 class="text-3xl font-black text-blue-700">
                        {{ number_format($stats['total_tickets_sold'], 0, ',', '.') }}</h4>
                </div>
                {{-- Total Peserta --}}
                <div class="bg-white p-8 rounded-xl border border-slate-100 shadow-sm">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">TOTAL PESERTA</p>
                    <h4 class="text-3xl font-black text-slate-800">
                        {{ number_format($stats['total_participant'], 0, ',', '.') }}</h4>
                </div>
                {{-- Total Sisa Tiket --}}
                <div @class([
                    'bg-white p-8 rounded-xl border border-slate-100 shadow-sm border-l-[6px] border-l-emerald-500',
                ])>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">TOTAL SISA TIKET</p>
                    <h4 class="text-3xl font-black text-emerald-600">
                        {{ number_format($stats['total_remaining_tickets'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </section>

        <!-- Periods Breakdown -->
        @foreach ($periodsData as $period)
            <section class="space-y-6">
                <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Periode: {{ $period->name }}
                </h3>
                <!-- Period Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">TOTAL KAPASITAS
                        </p>
                        <h4 class="text-2xl font-black text-slate-800">
                            {{ number_format($period->total_kapasitas, 0, ',', '.') }}</h4>
                    </div>
                    <div class="bg-yellow-50/50 p-6 rounded-xl border border-yellow-100/50">
                        <p class="text-[11px] font-black text-yellow-600 uppercase tracking-widest mb-2">TOTAL TERJUAL
                        </p>
                        <h4 class="text-2xl font-black text-yellow-700 truncate">
                            {{ number_format($period->total_terjual, 0, ',', '.') }}</h4>
                    </div>
                    <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100">
                        <p class="text-[11px] font-black text-emerald-600 uppercase tracking-widest mb-2">TOTAL SISA
                            STOK
                        </p>
                        <h4 class="text-2xl font-black text-emerald-700 truncate">
                            {{ number_format($period->total_sisa_stok, 0, ',', '.') }}</h4>
                    </div>
                </div>

                <!-- Ticket Table -->
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr
                                    class="bg-slate-50 uppercase tracking-widest text-[13px] font-black text-slate-400 border-b border-slate-100">
                                    <th class="px-8 py-5">TIKET</th>
                                    <th class="px-8 py-5 text-center">HARGA</th>
                                    <th class="px-8 py-5 text-center">KAPASITAS</th>
                                    <th class="px-8 py-5 text-center">TERJUAL</th>
                                    <th class="px-8 py-5 text-center">SISA STOK</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($period->tickets as $ticket)
                                    <tr class="hover:bg-slate-50/10 transition-colors">
                                        <td class="px-8 py-5 text-[15px] font-black text-slate-800 uppercase">
                                            {{ $ticket->kategori }} ({{ $ticket->type }})</td>
                                        <td class="px-8 py-5 text-center font-black text-slate-800 text-[15px]">Rp
                                            {{ number_format($ticket->price, 0, ',', '.') }}</td>
                                        <td class="px-8 py-5 text-center text-[15px] font-bold text-slate-500">
                                            {{ $ticket->kapasitas }}</td>
                                        <td @class([
                                            'px-8 py-5 text-center text-[15px] font-bold',
                                            'text-yellow-600' => $ticket->terjual > 0,
                                            'text-slate-300' => $ticket->terjual == 0,
                                        ])>{{ $ticket->terjual }}</td>
                                        <td @class([
                                            'px-8 py-5 text-center text-[15px] font-black',
                                            'text-emerald-600' => $ticket->sisa_stok > 0,
                                            'text-rose-500' => $ticket->sisa_stok == 0,
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
