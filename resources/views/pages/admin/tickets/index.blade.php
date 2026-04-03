<x-layouts.admin title="Event Management">
    <div class="space-y-10">
        <!-- Section: Registration Periods -->
        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-[#003366] uppercase tracking-tight">Registration Periods</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Select the active registration window</p>
                </div>
                <x-button variant="secondary" class="rounded-xl px-6 h-10">New Period</x-button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($periods as $period)
                <div @class([
                    'bg-white p-6 rounded-3xl border transition-all duration-500 relative overflow-hidden group',
                    'border-[#E8630A] ring-4 ring-orange-50 shadow-xl' => $period->is_active,
                    'border-slate-100 hover:border-slate-200 shadow-sm' => !$period->is_active
                ])>
                    @if($period->is_active)
                        <div class="absolute top-0 right-0 p-3">
                            <span class="flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#E8630A] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-[#E8630A]"></span>
                            </span>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <div class="text-[10px] font-black {{ $period->is_active ? 'text-[#E8630A]' : 'text-slate-400' }} uppercase tracking-[3px] mb-1">Registration Phase</div>
                            <h4 class="text-2xl font-black text-slate-800 tracking-tight">{{ $period->name }}</h4>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                            <div class="flex flex-col">
                                <span class="text-[9px] uppercase font-black tracking-widest text-slate-300">Status</span>
                                <span class="text-xs font-bold {{ $period->is_active ? 'text-emerald-500' : 'text-slate-400' }}">
                                    {{ $period->is_active ? 'Currently Active' : 'Inactive' }}
                                </span>
                            </div>
                            
                            @if(!$period->is_active)
                            <form action="{{ route('periods.toggle', $period) }}" method="POST">
                                @csrf
                                <x-button variant="navy" type="submit" class="rounded-xl px-4 py-2 text-[10px]">
                                    Activate
                                </x-button>
                            </form>
                            @else
                            <div class="bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                                Active Now
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Graphic --}}
                    <svg class="absolute -bottom-4 -right-4 w-24 h-24 {{ $period->is_active ? 'text-orange-500/5' : 'text-slate-50' }} group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Section: All Tickets -->
        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-[#003366] uppercase tracking-tight">Inventory & Tickets</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Configure pricing and capacity for each category</p>
                </div>
                <x-button variant="navy" class="rounded-xl px-6 h-10">New Ticket</x-button>
            </div>

            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 uppercase tracking-widest text-[9px] font-black text-slate-400 border-b border-slate-100">
                                <th class="px-8 py-6">Ticket Name</th>
                                <th class="px-6 py-6 text-center">Category</th>
                                <th class="px-6 py-6 text-center">Assigned Period</th>
                                <th class="px-6 py-6 text-center">Unit Price</th>
                                <th class="px-6 py-6 text-center">Capacity</th>
                                <th class="px-8 py-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($tickets as $ticket)
                            <tr class="hover:bg-slate-50/30 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="font-bold text-slate-800 uppercase tracking-tight">{{ $ticket->name }}</div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-[2px] mt-0.5 opacity-60">REF: {{ $ticket->id }}</div>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span class="inline-flex px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                                        {{ $ticket->category->name ?? 'None' }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span @class([
                                        'inline-flex px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border',
                                        'bg-orange-50 text-[#E8630A] border-orange-100' => $ticket->period->is_active ?? false,
                                        'bg-slate-50 text-slate-400 border-slate-100' => !$ticket->period->is_active ?? true
                                    ])>
                                        {{ $ticket->period->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <div class="text-sm font-black text-slate-800">Rp{{ number_format($ticket->price, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs font-bold text-slate-700">{{ $ticket->qty }} Units</span>
                                        <div class="w-16 h-1 bg-slate-100 rounded-full mt-2 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-[#003366] to-[#E8630A]" style="width: 60%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button class="p-2.5 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button class="p-2.5 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-layouts.admin>
