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
                                <span class="text-xs font-black text-blue-600 tracking-tighter">#{{ $p->order_code }}</span>
                                <p class="text-[9px] font-bold text-slate-400 mt-0.5">{{ $p->created_at->format('d/m/Y H:i') }}</p>
                            </td>
                            <td class="px-6 py-6">
                                <div class="font-bold text-sm text-slate-800 uppercase tracking-tighter">{{ $p->name }}</div>
                                <div class="text-[10px] font-medium text-slate-400 lowercase">{{ $p->email }}</div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="text-xs font-black text-slate-800 uppercase tracking-tighter">{{ $p->ticket->name }}</div>
                                <div class="text-[10px] font-bold text-[#E8630A] uppercase tracking-wider mt-0.5">
                                    {{ $p->ticket->category->name }} • {{ $p->ticket->period->name ?? 'Standard' }}
                                </div>
                                <div class="text-[9px] font-bold text-slate-400 mt-1 uppercase italic opacity-60">Size: {{ $p->jersey_size }}</div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="text-xs font-black text-slate-800 tracking-tighter">Rp {{ number_format($p->total_price, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-6">
                                @if($p->status == 'paid')
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-100">Settled</span>
                                @else
                                <span class="px-3 py-1 bg-orange-50 text-[#E8630A] rounded-full text-[9px] font-black uppercase tracking-widest border border-orange-100 animate-pulse">Pending</span>
                                @endif
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
