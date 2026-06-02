<x-layouts.admin title="Participant Master List">
    <div class="space-y-6" x-data="{ 
        showExportModal: false, 
        showImportModal: {{ $errors->any() ? 'true' : 'false' }}, 
        showPasswordModal: false, 
        selectedParticipantId: null, 
        selectedParticipantName: '',
        selectedIds: [],
        get allIds() {
            return Array.from(document.querySelectorAll('.participant-checkbox')).map(el => el.value);
        },
        toggleAll() {
            if (this.selectedIds.length === this.allIds.length) {
                this.selectedIds = [];
            } else {
                this.selectedIds = this.allIds;
            }
        }
    }">
        <!-- Filter & Search Bar -->
        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm flex flex-col gap-6 w-full">
            <form action="{{ url('/admin/participants') }}" method="GET" class="flex flex-col gap-4 w-full">
                <div class="flex flex-col md:flex-row gap-4 w-full">
                    <div class="relative flex-[2]">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Name, Email, NIK, or Order..."
                            class="w-full h-14 pl-14 pr-6 bg-slate-50 border border-slate-100 rounded-lg text-base font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all">
                        <svg class="absolute left-5 top-4 w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row gap-4 w-full justify-between items-center">
                    <div class="flex flex-col md:flex-row gap-4 flex-1 w-full flex-wrap">
                        <select name="status"
                            class="h-14 px-8 bg-slate-50 border border-slate-100 rounded-lg text-sm font-black uppercase tracking-widest outline-none focus:ring-2 focus:ring-blue-100 transition-all flex-1 min-w-[150px]">
                            <option value="">All Payments</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Settle / Paid</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed / Expired
                            </option>
                        </select>
                        <select name="ticket_type"
                            class="h-14 px-8 bg-slate-50 border border-slate-100 rounded-lg text-sm font-black uppercase tracking-widest outline-none focus:ring-2 focus:ring-blue-100 transition-all flex-1 min-w-[150px]">
                            <option value="">All Types</option>
                            <option value="ipb" {{ request('ticket_type') == 'ipb' ? 'selected' : '' }}>IPB Family</option>
                            <option value="umum" {{ request('ticket_type') == 'umum' ? 'selected' : '' }}>Public (Umum)
                            </option>
                        </select>
                        <select name="category_id"
                            class="h-14 px-8 bg-slate-50 border border-slate-100 rounded-lg text-sm font-black uppercase tracking-widest outline-none focus:ring-2 focus:ring-blue-100 transition-all flex-1 min-w-[150px]">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <select name="period_id"
                            class="h-14 px-8 bg-slate-50 border border-slate-100 rounded-lg text-sm font-black uppercase tracking-widest outline-none focus:ring-2 focus:ring-blue-100 transition-all flex-1 min-w-[150px]">
                            <option value="">All Periods</option>
                            @foreach($periods as $period)
                                <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
                            @endforeach
                        </select>
                        <select name="rpc_status"
                            class="h-14 px-8 bg-slate-50 border border-slate-100 rounded-lg text-sm font-black uppercase tracking-widest outline-none focus:ring-2 focus:ring-blue-100 transition-all flex-1 min-w-[160px]">
                            <option value="">All RPC Status</option>
                            <option value="taken" {{ request('rpc_status') == 'taken' ? 'selected' : '' }}>✅ Sudah Diambil</option>
                            <option value="not_taken" {{ request('rpc_status') == 'not_taken' ? 'selected' : '' }}>⬜ Belum Diambil</option>
                        </select>
                    </div>
                    <div class="flex gap-4 w-full lg:w-auto">
                        <button type="submit"
                            class="h-14 px-10 bg-[#003366] w-full lg:w-auto text-white rounded-lg text-sm font-black uppercase tracking-widest hover:bg-[#002244] transition-all">Filter</button>
                        <button type="button" @click="showImportModal = true"
                            class="h-14 px-10 bg-blue-50 w-full lg:w-auto text-blue-600 rounded-lg text-sm font-black uppercase tracking-widest border border-blue-100 flex items-center justify-center gap-3 hover:bg-blue-100 transition-all whitespace-nowrap">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg> Import Data
                        </button>
                        <button type="button" @click="showExportModal = true"
                            class="h-14 px-10 bg-emerald-50 w-full lg:w-auto text-emerald-600 rounded-lg text-sm font-black uppercase tracking-widest border border-emerald-100 flex items-center justify-center gap-3 hover:bg-emerald-100 transition-all whitespace-nowrap">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg> Export Data
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Participants Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr
                            class="bg-slate-50/50 border-b border-slate-100 uppercase tracking-widest text-[12px] font-black text-slate-400">
                            <th class="px-6 py-8 text-center w-10">
                                <input type="checkbox" @click="toggleAll()" :checked="selectedIds.length === allIds.length && allIds.length > 0"
                                    class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all cursor-pointer">
                            </th>
                            <th class="px-10 py-8">Order Code</th>
                            <th class="px-8 py-8">Participant Info</th>
                            <th class="px-8 py-8">Ticket Details</th>
                            <th class="px-8 py-8">Payment</th>
                            <th class="px-8 py-8">Status</th>
                            <th class="px-8 py-8">RPC Status</th>
                            <th class="px-10 py-8 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($participants as $p)
                            <tr class="hover:bg-slate-50/50 transition-colors" :class="selectedIds.includes('{{ $p->id }}') ? 'bg-blue-50/30' : ''">
                                <td class="px-6 py-8 text-center">
                                    <input type="checkbox" x-model="selectedIds" value="{{ $p->id }}"
                                        class="participant-checkbox w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all cursor-pointer">
                                </td>
                                <td class="px-10 py-8">
                                @foreach ($p->raceEntries->pluck('order')->unique('id') as $order)
                                    <div class="mb-2 last:mb-0">
                                        <span class="text-base font-black text-blue-600 tracking-tighter block">#{{ $order->order_code }}</span>
                                        @foreach ($order->voucherUsages as $usage)
                                            <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-tight block">Voucher: {{ $usage->voucher->code ?? '-' }}</span>
                                        @endforeach
                                    </div>
                                @endforeach
                                    <p class="text-[11px] font-bold text-slate-400 mt-1 tracking-widest">
                                        {{ $p->created_at->format('d/m/Y') }}</p>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="font-bold text-base text-slate-800 uppercase tracking-tighter">
                                        {{ $p->name }}</div>
                                    <div class="text-[12px] font-medium text-slate-400 lowercase">{{ $p->email }}
                                    </div>
                                    <div class="text-[10px] font-black text-slate-400 uppercase mt-1">NIK:
                                        {{ $p->nik }}</div>
                                </td>
                                <td class="px-8 py-8">
                                    @foreach ($p->raceEntries as $entry)
                                        <div
                                            class="mb-4 last:mb-0 p-3 bg-slate-50/50 rounded-lg border border-slate-100/50 w-full">
                                            <div class="flex items-center justify-between gap-6">
                                                <div>
                                                    <div
                                                        class="text-[13px] font-black text-slate-800 uppercase tracking-tighter">
                                                        {{ $entry->ticket->category->name }}
                                                        ({{ $entry->ticket->name ?: strtoupper($entry->ticket->type) }})
                                                    </div>
                                                    <div
                                                        class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                                        {{ $entry->ticket->period->name ?? 'Standard' }}
                                                    </div>
                                                </div>
                                                @if ($entry->status == 'paid')
                                                    <span
                                                        class="text-[11px] font-black text-emerald-500 uppercase">Paid</span>
                                                @elseif($entry->status == 'pending')
                                                    <span
                                                        class="text-[11px] font-black text-orange-500 uppercase">Pending</span>
                                                @else
                                                    <span
                                                        class="text-[11px] font-black text-red-500 uppercase">Failed</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="text-[11px] font-bold text-slate-400 mt-3 uppercase italic opacity-60">
                                        Jersey: {{ $p->jersey_size }}</div>
                                </td>
                                <td class="px-8 py-8 font-bold text-base text-slate-800">
                                    Rp
                                    {{ number_format($p->raceEntries->where('status', 'paid')->pluck('order')->unique('id')->sum('total_price'), 0, ',', '.') }}
                                </td>
                                <td class="px-8 py-8">
                                    @php
                                        $paidCount = $p->raceEntries->where('status', 'paid')->count();
                                        $totalCount = $p->raceEntries->count();
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-[11px] font-black uppercase tracking-widest border border-blue-100">
                                            {{ $paidCount }}/{{ $totalCount }} PAID
                                        </span>
                                    </div>
                                </td>
                                {{-- RPC Status Column --}}
                                <td class="px-8 py-8">
                                    <div class="space-y-2">
                                        @foreach ($p->raceEntries->where('status', 'paid') as $entry)
                                            <div class="mb-2 last:mb-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-tight">
                                                        {{ $entry->ticket->category->name }}
                                                    </span>
                                                    @if ($entry->scanned_at)
                                                        <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase rounded border border-emerald-100 whitespace-nowrap">
                                                            Diambil
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 bg-slate-50 text-slate-400 text-[9px] font-bold uppercase rounded border border-slate-100 whitespace-nowrap">
                                                            Belum
                                                        </span>
                                                    @endif
                                                </div>
                                                @if ($entry->scanned_at)
                                                    <span class="text-[9px] font-medium text-slate-400 block mt-0.5">
                                                        {{ \Carbon\Carbon::parse($entry->scanned_at)->timezone('Asia/Jakarta')->format('d/m H:i') }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                        @if ($p->raceEntries->where('status', 'paid')->isEmpty())
                                            <span class="text-[10px] font-bold text-slate-200 uppercase tracking-widest italic">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <div class="flex items-center justify-end gap-2 flex-wrap">
                                        @if (auth()->user()->role !== 'pic')
                                            {{-- View Detail --}}
                                            <a href="{{ url('/admin/participants/' . $p->id) }}"
                                                class="p-2 bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all inline-flex items-center justify-center" title="View Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>

                                            {{-- Ganti Password (Superadmin) --}}
                                            @if (auth()->user()->role === 'superadmin')
                                                <button @click="showPasswordModal = true; selectedParticipantId = '{{ $p->id }}'; selectedParticipantName = '{{ addslashes($p->name) }}'"
                                                    class="p-2 bg-red-50 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-md transition-all inline-flex items-center justify-center" title="Ganti Password">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4v-3.586l8.172-8.172A6 6 0 1115 7z"></path>
                                                    </svg>
                                                </button>
                                            @endif

                                            {{-- Reset RPC buttons (Admin & Superadmin) --}}
                                            @if (in_array(auth()->user()->role, ['superadmin', 'admin']))
                                                @foreach ($p->raceEntries->where('status', 'paid')->where('scanned_at', '!=', null) as $entry)
                                                    <form action="{{ route('participants.reset-rpc', [$p->id, $entry->id]) }}" method="POST"
                                                        onsubmit="return confirm('Reset status RPC {{ $entry->ticket->category->name }} untuk {{ addslashes($p->name) }}?')">
                                                        @csrf
                                                        <button type="submit"
                                                            class="flex items-center gap-1 px-2 py-1 bg-orange-50 text-orange-500 hover:bg-orange-100 hover:text-orange-700 rounded text-[9px] font-black uppercase tracking-wide border border-orange-100 transition-all whitespace-nowrap"
                                                            title="Reset RPC {{ $entry->ticket->category->name }}">
                                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                            </svg>
                                                            Reset {{ $entry->ticket->category->name }}
                                                        </button>
                                                    </form>
                                                @endforeach
                                            @endif

                                            {{-- Cancel/Nonaktifkan (Superadmin) --}}
                                            @if (auth()->user()->role === 'superadmin')
                                                <form action="{{ route('participants.cancel', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin MENONAKTIFKAN peserta ini? Akun login akan dihapus dan semua pesanan akan menjadi FAILED.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 bg-rose-100 text-rose-600 hover:text-white hover:bg-rose-600 rounded-md transition-all inline-flex items-center justify-center" title="Cancel/Nonaktifkan Peserta">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span
                                                class="text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">No Access</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center text-slate-400 italic text-sm">No
                                    participants found matching the criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($participants->hasPages())
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                    {{ $participants->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Export Modal -->
        <div x-show="showExportModal"
            class="fixed inset-0 z-[150] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden"
                @click.away="showExportModal = false">
                <div class="p-10 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Export Participant Data</h3>
                    <p class="text-sm text-slate-400 font-bold uppercase tracking-wider mt-2">Filter the data you want
                        to download</p>
                </div>

                <form action="{{ route('participants.export') }}" method="GET" @submit="showExportModal = false">
                    <div class="p-10 space-y-6 max-h-[70vh] overflow-y-auto w-full">
                        <div>
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Search By Keyword</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full h-14 px-6 bg-slate-50 border border-slate-100 rounded-xl text-sm font-medium outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all"
                                placeholder="Name, Email, NIK, Order...">
                        </div>

                        {{-- Bundling Options --}}
                        <div class="grid grid-cols-1 gap-6 w-full">
                            <div class="w-full">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Tipe Peserta</label>
                                <select name="participant_type"
                                    class="w-full h-14 px-6 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold uppercase tracking-widest outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all">
                                    <option value="all">Semua Peserta</option>
                                    <option value="regular">Peserta Regular (1 Tiket)</option>
                                    <option value="bundling">Peserta Bundling (>1 Tiket)</option>
                                </select>
                            </div>
                            <div class="w-full">
                                <label class="flex items-center gap-4 cursor-pointer group p-4 bg-amber-50/50 rounded-xl border border-amber-100/50 hover:bg-amber-50 transition-all">
                                    <input type="checkbox" name="split_bundling" value="1"
                                        class="w-5 h-5 rounded border-slate-200 text-amber-500 focus:ring-amber-500 transition-all">
                                    <div>
                                        <span class="text-[12px] font-black text-slate-700 uppercase tracking-tight block">Pisah Row Tiket Bundling</span>
                                        <span class="text-[10px] font-bold text-slate-400 tracking-wide block mt-1">Peserta bundling akan dipecah menjadi 2 baris terpisah (1 row per tiket)</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 w-full">
                            <div class="w-full">
                                <label
                                    class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Start
                                    Date</label>
                                <input type="date" name="start_date"
                                    class="w-full h-14 px-6 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all">
                            </div>
                            <div class="w-full">
                                <label
                                    class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">End
                                    Date</label>
                                <input type="date" name="end_date"
                                    class="w-full h-14 px-6 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 w-full">
                            <div class="w-full">
                                <label
                                    class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Payment
                                    Status</label>
                                <select name="status"
                                    class="w-full h-14 px-6 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold uppercase tracking-widest outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all">
                                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Payments</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Settle / Paid Only</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Only</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed / Expired Only</option>
                                </select>
                            </div>
                            <div class="w-full">
                                <label
                                    class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Ticket Type</label>
                                <select name="ticket_type"
                                    class="w-full h-14 px-6 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold uppercase tracking-widest outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all">
                                    <option value="" {{ request('ticket_type') == '' ? 'selected' : '' }}>All Types</option>
                                    <option value="ipb" {{ request('ticket_type') == 'ipb' ? 'selected' : '' }}>IPB Family</option>
                                    <option value="umum" {{ request('ticket_type') == 'umum' ? 'selected' : '' }}>Public (Umum)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 w-full">
                            <div class="w-full">
                                <label
                                    class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Category</label>
                                <select name="category_id"
                                    class="w-full h-14 px-6 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold uppercase tracking-widest outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all">
                                    <option value="" {{ request('category_id') == '' ? 'selected' : '' }}>All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full">
                                <label
                                    class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Period</label>
                                <select name="period_id"
                                    class="w-full h-14 px-6 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold uppercase tracking-widest outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all">
                                    <option value="" {{ request('period_id') == '' ? 'selected' : '' }}>All Periods</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Selection Columns --}}
                        <div class="pt-6 border-t border-slate-50">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-6">Pilih Kolom Ekspor</label>
                            
                            <div class="space-y-8">
                                <!-- Data Diri Section -->
                                <div>
                                    <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-4 border-b pb-2">Data Diri Peserta</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        @php
                                            $personalFields = [
                                                'name' => 'Nama Lengkap',
                                                'email' => 'Email',
                                                'phone' => 'Telepon',
                                                'nik' => 'NIK',
                                                'birth_date' => 'Tgl Lahir',
                                                'sex' => 'Gender',
                                                'blood_type' => 'Gol. Darah',
                                                'jersey_size' => 'Ukuran Jersey',
                                                'nim_nrp' => 'NIM/NRP',
                                                'nationality' => 'Kewarganegaraan',
                                                'address' => 'Alamat Lengkap',
                                                'created_at' => 'Tgl Registrasi',
                                            ];
                                        @endphp
                                        @foreach($personalFields as $key => $label)
                                            <label class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-slate-50 transition-all">
                                                <input type="checkbox" name="columns[]" value="{{ $key }}" checked 
                                                    class="w-4 h-4 rounded border-slate-200 text-emerald-500 focus:ring-emerald-500 transition-all">
                                                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-tight">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Info Tambahan Section -->
                                <div>
                                    <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-4 border-b pb-2">Info Tambahan & Medis</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        @php
                                            $additionalFields = [
                                                'running_community' => 'Komunitas Lari',
                                                'medical_condition' => 'Kondisi Medis',
                                                'shuttle_bus' => 'Shuttle Bus',
                                                'best_time' => 'Best Time',
                                                'previous_events' => 'Event Sebelumnya',
                                            ];
                                        @endphp
                                        @foreach($additionalFields as $key => $label)
                                            <label class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-slate-50 transition-all">
                                                <input type="checkbox" name="columns[]" value="{{ $key }}" checked 
                                                    class="w-4 h-4 rounded border-slate-200 text-emerald-500 focus:ring-emerald-500 transition-all">
                                                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-tight">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Emergency & Address Section -->
                                <div>
                                    <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-4 border-b pb-2">Kontak Darurat</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        @php
                                            $emergencyFields = [
                                                'emergency_name' => 'Nama Kontak',
                                                'emergency_phone' => 'No. Kontak',
                                                'emergency_relationship' => 'Hubungan',
                                            ];
                                        @endphp
                                        @foreach($emergencyFields as $key => $label)
                                            <label class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-slate-50 transition-all">
                                                <input type="checkbox" name="columns[]" value="{{ $key }}" checked 
                                                    class="w-4 h-4 rounded border-slate-200 text-emerald-500 focus:ring-emerald-500 transition-all">
                                                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-tight">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Order & Finance Section -->
                                <div>
                                    <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-4 border-b pb-2">Data Order & Keuangan</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @php
                                            $orderFields = [
                                                'order_codes' => 'Order Codes',
                                                'order_statuses' => 'Order Statuses',
                                                'voucher_codes' => 'Voucher Codes',
                                                'ticket_details' => 'Ticket Details',
                                                'paid_amount' => 'Paid Amount (Price)',
                                                'donation_scholarship' => 'Donation Scholarship',
                                                'donation_event' => 'Donation Event',
                                                'admin_fee' => 'Admin Fee',
                                                'total_paid' => 'Total Paid Amount',
                                            ];
                                        @endphp
                                        @foreach($orderFields as $key => $label)
                                            <label class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-slate-50 transition-all">
                                                <input type="checkbox" name="columns[]" value="{{ $key }}" checked 
                                                    class="w-4 h-4 rounded border-slate-200 text-emerald-500 focus:ring-emerald-500 transition-all">
                                                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-tight">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-slate-50 flex items-center justify-end gap-4">
                        <button type="button" @click="showExportModal = false"
                            class="px-8 py-4 text-sm font-black text-slate-400 uppercase tracking-widest hover:text-slate-600">Cancel</button>
                        <button type="submit"
                            class="px-10 py-4 bg-emerald-500 text-white rounded-xl text-sm font-black uppercase tracking-widest shadow-lg shadow-emerald-900/20 hover:bg-emerald-600 transition-all">Download
                            CSV</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div x-show="showPasswordModal"
            class="fixed inset-0 z-[150] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm"
            style="display: none;"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden"
                @click.away="showPasswordModal = false">
                <div class="p-10 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Ganti Password Participant</h3>
                    <p class="text-sm text-slate-400 font-bold uppercase tracking-wider mt-2" x-text="'Peserta: ' + selectedParticipantName"></p>
                </div>

                <form :action="'{{ url('/admin/participants') }}/' + selectedParticipantId + '/change-password'" method="POST" @submit="showPasswordModal = false">
                    @csrf
                    @method('PUT')
                    <div class="p-10 space-y-8">
                        <div>
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Password Baru</label>
                            <input type="password" name="password" required minlength="6"
                                class="w-full h-14 px-6 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-red-50 focus:border-red-200 transition-all" placeholder="Masukkan password baru...">
                        </div>
                    </div>

                    <div class="p-8 bg-slate-50 flex items-center justify-end gap-4">
                        <button type="button" @click="showPasswordModal = false"
                            class="px-8 py-4 text-sm font-black text-slate-400 uppercase tracking-widest hover:text-slate-600">Batal</button>
                        <button type="submit"
                            class="px-10 py-4 bg-red-500 text-white rounded-xl text-sm font-black uppercase tracking-widest shadow-lg shadow-red-900/20 hover:bg-red-600 transition-all">Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Import Modal -->
        <div x-show="showImportModal"
            class="fixed inset-0 z-[150] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm"
            style="display: none;"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden"
                @click.away="showImportModal = false">
                <div class="p-10 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Import Participant Data</h3>
                    <p class="text-sm text-slate-400 font-bold uppercase tracking-wider mt-2">Upload Excel file to add multiple participants</p>
                </div>

                <form action="{{ route('participants.import') }}" method="POST" enctype="multipart/form-data" @submit="showImportModal = false">
                    @csrf
                    @if($errors->any())
                        <div class="px-10 py-4 bg-red-50 border-b border-red-100">
                            <ul class="list-disc list-inside text-xs font-bold text-red-600 uppercase tracking-wide space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="p-10 space-y-6">
                        <div>
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Periode Tiket</label>
                            <select name="period_id" required
                                class="w-full h-14 px-6 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold uppercase tracking-widest outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all">
                                <option value="">Pilih Periode Sponsorship...</option>
                                @foreach($sponsorshipPeriods as $period)
                                    <option value="{{ $period->id }}">{{ $period->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Tipe Tiket</label>
                            <select name="ticket_type" required
                                class="w-full h-14 px-6 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold uppercase tracking-widest outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all">
                                <option value="umum">Public (Umum)</option>
                                <option value="ipb">IPB Family</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Email Penanggung Jawab / User Login</label>
                            <input type="email" name="order_email" required
                                class="w-full h-14 px-6 bg-slate-50 border border-slate-100 rounded-xl text-sm font-medium outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all"
                                placeholder="Email untuk login & notifikasi order...">
                        </div>

                        <div>
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">File Excel (.xlsx / .csv)</label>
                            <input type="file" name="file" required accept=".xlsx, .csv"
                                class="w-full p-4 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all">
                        </div>

                        <div class="pt-4">
                            <a href="{{ route('participants.import-template') }}" 
                                class="flex items-center gap-3 text-blue-600 hover:text-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-xs font-black uppercase tracking-widest">Download Template Excel</span>
                            </a>
                        </div>
                    </div>

                    <div class="p-8 bg-slate-50 flex items-center justify-end gap-4">
                        <button type="button" @click="showImportModal = false"
                            class="px-8 py-4 text-sm font-black text-slate-400 uppercase tracking-widest hover:text-slate-600">Batal</button>
                        <button type="submit"
                            class="px-10 py-4 bg-[#003366] text-white rounded-xl text-sm font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 hover:bg-[#002244] transition-all">Upload & Import</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Bulk Action Bar --}}
        <div x-show="selectedIds.length > 0"
            class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[1000] animate-slide-in"
            style="display: none;">
            <div class="bg-slate-900 text-white px-8 py-5 rounded-2xl shadow-2xl flex items-center gap-6 border border-slate-700/50 backdrop-blur-xl">
                <div class="flex flex-col">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Bulk Action</span>
                    <span class="text-sm font-bold"><span x-text="selectedIds.length"></span> Peserta Terpilih</span>
                </div>
                <div class="h-8 w-[1px] bg-slate-700"></div>

                {{-- Bulk Resend Email --}}
                <form id="bulk-resend-form" action="{{ route('participants.bulk-resend') }}" method="POST">
                    @csrf
                    <template x-for="id in selectedIds" :key="'resend-' + id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit"
                        onclick="return confirm('Kirim ulang E-Invoice ke semua peserta yang dipilih? Hanya peserta dengan order Paid yang akan mendapat email.')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Resend Email
                    </button>
                </form>

                <div class="h-8 w-[1px] bg-slate-700"></div>

                {{-- Bulk Cancel --}}
                <form id="bulk-action-form" action="{{ route('participants.bulk-cancel') }}" method="POST">
                    @csrf
                    <template x-for="id in selectedIds" :key="'cancel-' + id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit"
                        onclick="return confirm('Apakah Anda yakin ingin MENONAKTIFKAN semua peserta yang dipilih secara massal? Akun login akan dihapus dan semua pesanan akan menjadi FAILED.')"
                        class="bg-rose-600 hover:bg-rose-700 text-white px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        Nonaktifkan Massal
                    </button>
                </form>

                <button type="button" @click="selectedIds = []" class="text-slate-400 hover:text-white transition-colors ml-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</x-layouts.admin>
