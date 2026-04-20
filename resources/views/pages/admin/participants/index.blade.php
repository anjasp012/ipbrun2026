<x-layouts.admin title="Participant Master List">
    <div class="space-y-6" x-data="{ showExportModal: false, showPasswordModal: false, selectedParticipantId: null, selectedParticipantName: '' }">
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
                    </div>
                    <div class="flex gap-4 w-full lg:w-auto">
                        <button type="submit"
                            class="h-14 px-10 bg-[#003366] w-full lg:w-auto text-white rounded-lg text-sm font-black uppercase tracking-widest hover:bg-[#002244] transition-all">Filter</button>
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
                            <th class="px-10 py-8">Order Code</th>
                            <th class="px-8 py-8">Participant Info</th>
                            <th class="px-8 py-8">Ticket Details</th>
                            <th class="px-8 py-8">Payment</th>
                            <th class="px-8 py-8">Status</th>
                            <th class="px-10 py-8 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($participants as $p)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-10 py-8">
                                    @foreach ($p->raceEntries->pluck('order.order_code')->unique() as $orderCode)
                                        <span
                                            class="text-base font-black text-blue-600 tracking-tighter block">#{{ $orderCode }}</span>
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
                                <td class="px-10 py-8 text-right">
                                    @if (auth()->user()->role !== 'pic')
                                        <a href="{{ url('/admin/participants/' . $p->id) }}"
                                            class="p-3 bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all inline-block">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        @if (auth()->user()->role === 'superadmin')
                                            <button @click="showPasswordModal = true; selectedParticipantId = '{{ $p->id }}'; selectedParticipantName = '{{ addslashes($p->name) }}'"
                                                class="p-3 bg-red-50 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-md transition-all inline-block ml-2" title="Ganti Password">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4v-3.586l8.172-8.172A6 6 0 1115 7z"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    @else
                                        <span
                                            class="text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">No
                                            Access</span>
                                    @endif
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
    </div>
</x-layouts.admin>
