<x-layouts.admin title="Participant Master List">
    <div class="space-y-6">
        <!-- Filter & Search Bar -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
            <form action="{{ url('/admin/participants') }}" method="GET" class="flex flex-1 gap-4 w-full">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Name, Email, or Order Code..." class="w-full h-12 pl-12 pr-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all">
                    <svg class="absolute left-4 top-3.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <select name="status" class="h-12 px-6 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-black uppercase tracking-widest outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                    <option value="">All Status</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Settle / Paid</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
                <button type="submit" class="h-12 px-8 bg-[#003366] text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-[#002244] transition-all">Filter</button>
            </form>
            
            <a href="#" class="h-12 px-8 bg-emerald-50 text-emerald-600 rounded-2xl text-xs font-black uppercase tracking-widest border border-emerald-100 flex items-center gap-2 hover:bg-emerald-100 transition-all">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                 Export CSV
            </a>
        </div>

        <!-- Participants Table -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 uppercase tracking-widest text-[10px] font-black text-slate-400">
                            <th class="px-8 py-6">Order Code</th>
                            <th class="px-6 py-6">Participant Info</th>
                            <th class="px-6 py-6">Ticket Details</th>
                            <th class="px-6 py-6">Payment</th>
                            <th class="px-6 py-6">Status</th>
                            <th class="px-8 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($participants as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                @foreach($p->raceEntries->pluck('order.order_code')->unique() as $orderCode)
                                    <span class="text-xs font-black text-blue-600 tracking-tighter block">#{{ $orderCode }}</span>
                                @endforeach
                                <p class="text-[9px] font-bold text-slate-400 mt-0.5 tracking-widest">{{ $p->created_at->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-6 py-6">
                                <div class="font-bold text-sm text-slate-800 uppercase tracking-tighter">{{ $p->name }}</div>
                                <div class="text-[10px] font-medium text-slate-400 lowercase">{{ $p->email }}</div>
                            </td>
                            <td class="px-6 py-6">
                                @foreach($p->raceEntries as $entry)
                                    <div class="mb-3 last:mb-0 p-2 bg-slate-50/50 rounded-xl border border-slate-100/50 w-full">
                                        <div class="flex items-center justify-between gap-4">
                                            <div>
                                                <div class="text-[10px] font-black text-slate-800 uppercase tracking-tighter">{{ $entry->ticket->category->name }} ({{ $entry->ticket->name ?: strtoupper($entry->ticket->type) }})</div>
                                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                                    {{ $entry->ticket->period->name ?? 'Standard' }}
                                                </div>
                                            </div>
                                            @if($entry->status == 'paid')
                                                <span class="text-[9px] font-black text-emerald-500 uppercase">Paid</span>
                                            @else
                                                <span class="text-[9px] font-black text-orange-500 uppercase">Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                <div class="text-[9px] font-bold text-slate-400 mt-2 uppercase italic opacity-60">Jersey: {{ $p->jersey_size }}</div>
                            </td>
                            <td class="px-6 py-6 font-bold text-xs text-slate-800">
                                Rp {{ number_format($p->raceEntries->where('status', 'paid')->sum(fn($se) => $se->order->total_price ?? 0), 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-6">
                                @php
                                    $paidCount = $p->raceEntries->where('status', 'paid')->count();
                                    $totalCount = $p->raceEntries->count();
                                @endphp
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-blue-100">
                                        {{ $paidCount }}/{{ $totalCount }} PAID
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ url('/admin/participants/'.$p->id) }}" class="p-2 bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all inline-block">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-slate-400 italic text-sm">No participants found matching the criteria.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($participants->hasPages())
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $participants->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
