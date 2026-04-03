<x-layouts.admin title="Periode & Tiket">
    <div class="space-y-6">
        <!-- Header Info -->
        <div class="flex items-center justify-between bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <div>
                <h3 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Manajemen Periode & Tiket</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Kelola jendela registrasi dan inventaris tiket</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                    Total Periods: {{ count($periods) }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 px-6 py-4 rounded-2xl border border-emerald-100 text-sm font-bold flex items-center gap-3 animate-slide-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Accordion Table -->
        <div class="space-y-4" x-data="{ activeAccordion: null }">
            @foreach($periods as $period)
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden transition-all duration-300" 
                 :class="{ 'ring-2 ring-blue-100 border-blue-200': activeAccordion === '{{ $period->id }}' }">
                
                {{-- Accordion Header (Period Row) --}}
                <div class="flex items-center justify-between p-6 cursor-pointer hover:bg-slate-50 transition-colors"
                     @click="activeAccordion = (activeAccordion === '{{ $period->id }}' ? null : '{{ $period->id }}')">
                    
                    <div class="flex items-center gap-6">
                        {{-- Icon / Indicator --}}
                        <div @class([
                            'w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-500',
                            'bg-orange-50 text-[#E8630A] shadow-inner' => $period->is_active,
                            'bg-slate-50 text-slate-400' => !$period->is_active
                        ])>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        
                        <div>
                            <div class="flex items-center gap-3">
                                <h4 class="text-lg font-black text-slate-800 uppercase tracking-tight">{{ $period->name }}</h4>
                                @if($period->is_active)
                                    <span class="inline-flex px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase tracking-widest border border-emerald-200">
                                        ACTIVE
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                {{ count($tickets->where('period_id', $period->id)) }} Tiket Terdaftar
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        {{-- Switcher --}}
                        @if(!$period->is_active)
                        <form action="{{ route('periods.toggle', $period) }}" method="POST" @click.stop="">
                            @csrf
                            <x-button variant="navy" type="submit" class="rounded-xl px-5 h-10 text-[9px]">
                                Aktifkan Periode
                            </x-button>
                        </form>
                        @endif

                        {{-- Chevron --}}
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-transform duration-300"
                             :class="{ 'rotate-180 bg-blue-50 text-blue-600': activeAccordion === '{{ $period->id }}' }">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Accordion Content (Tickets Table) --}}
                <div x-show="activeAccordion === '{{ $period->id }}'" 
                     x-collapse 
                     style="display: none;">
                    <div class="p-6 bg-slate-50/50 border-t border-slate-50">
                        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-slate-50 uppercase tracking-widest text-[9px] font-black text-slate-400 border-b border-slate-100">
                                        <th class="px-6 py-4">Nama Tiket</th>
                                        <th class="px-6 py-4 text-center">Kategori</th>
                                        <th class="px-6 py-4 text-center">Harga</th>
                                        <th class="px-6 py-4 text-center">Stok</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @php $periodTickets = $tickets->where('period_id', $period->id); @endphp
                                    @forelse ($periodTickets as $ticket)
                                    <tr class="hover:bg-slate-50/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800 uppercase tracking-tight">{{ $ticket->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-[2px] mt-0.5 opacity-60 italic">UID: #{{ $ticket->id }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                                                {{ $ticket->category->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="text-xs font-black text-slate-800 tracking-tighter">Rp{{ number_format($ticket->price, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="text-[10px] font-bold text-slate-700">{{ $ticket->qty }} Units</span>
                                                <div class="w-12 h-1 bg-slate-100 rounded-full mt-1.5 overflow-hidden">
                                                    <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-600" style="width: 70%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-1">
                                                <button class="p-2 text-slate-300 hover:text-blue-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </button>
                                                <button class="p-2 text-slate-300 hover:text-rose-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic text-[10px] font-bold uppercase tracking-widest">Tidak ada tiket di periode ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if(count($periods) === 0)
            <div class="bg-white p-20 rounded-[40px] border border-dashed border-slate-200 text-center">
                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="text-slate-800 font-black uppercase tracking-tight">Belum Ada Periode</h4>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Silakan inisialisasi data periode terlebih dahulu.</p>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
    @endpush
</x-layouts.admin>
