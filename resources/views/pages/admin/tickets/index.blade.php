<x-layouts.admin title="Ticket Management">
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-black text-[#003366] uppercase tracking-tight">Active Event Tickets</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Configure pricing, categories, and inventory caps</p>
            </div>
            <div class="flex gap-3">
                <button class="h-10 px-6 bg-[#003366] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#002244] transition-all shadow-lg shadow-blue-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Ticket
                </button>
                <button class="h-10 px-6 bg-white text-slate-600 border border-slate-100 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Manage Periods
                </button>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <!-- Table Filters -->
            <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/30">
                <div class="relative flex-1 max-w-md">
                    <input type="text" placeholder="Search by name or category..." class="w-full h-10 pl-10 pr-4 bg-white border border-slate-100 rounded-xl text-xs font-medium focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                    <svg class="absolute left-3.5 top-2.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div class="flex gap-2">
                    <select class="h-10 px-4 bg-white border border-slate-100 rounded-xl text-[10px] font-black uppercase tracking-widest outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                        <option>All Categories</option>
                    </select>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 uppercase tracking-widest text-[10px] font-black text-slate-400 border-b border-slate-100">
                            <th class="px-8 py-5">Ticket Detail</th>
                            <th class="px-6 py-5 text-center">Category</th>
                            <th class="px-6 py-5 text-center">Period</th>
                            <th class="px-6 py-5 text-center">Price info</th>
                            <th class="px-6 py-5 text-center">Inventory</th>
                            <th class="px-8 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($tickets as $ticket)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="font-bold text-slate-800 uppercase tracking-tighter">{{ $ticket->name }}</div>
                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">UID: #{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">
                                    {{ $ticket->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-orange-50 text-[#E8630A] border border-orange-100">
                                    {{ $ticket->period->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="text-xs font-black text-slate-800 tracking-tighter">Rp{{ number_format($ticket->price, 0, ',', '.') }}</div>
                                @if($ticket->discount > 0)
                                    <div class="text-[9px] text-emerald-500 font-bold uppercase tracking-widest mt-0.5">Incl. Diskon</div>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="text-xs font-black text-slate-800">{{ $ticket->qty }} <span class="text-slate-300 font-bold">Limit</span></div>
                                <div class="w-20 h-1 bg-slate-100 rounded-full mx-auto mt-2 overflow-hidden border border-slate-50">
                                    <div class="h-full bg-gradient-to-r from-[#003366] to-[#E8630A]" style="width: 75%"></div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <button class="p-2 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit Ticket">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button class="p-2 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Delete Ticket">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-slate-400 italic text-sm font-bold uppercase tracking-widest">No tickets configured for this event.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>
