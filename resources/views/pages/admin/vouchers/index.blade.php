<x-layouts.admin title="Voucher Management">
    <div class="space-y-8" x-data="{ showModal: false, showDetailModal: false, detailVoucher: {} }">
        {{-- Header & Stats --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-[#003366] uppercase tracking-tight">Voucher Management</h1>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-1">Kelola kode voucher dan klaim peserta</p>
            </div>
            
            <button @click="showModal = true"
                class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#003366] text-white font-black rounded-2xl hover:bg-blue-900 active:scale-95 transition-all text-xs uppercase tracking-widest shadow-lg shadow-blue-900/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Generate New Vouchers
            </button>
        </div>

        {{-- Voucher List Table --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[2.5px]">Active Vouchers List</h3>
                <div class="px-4 py-1.5 bg-white border border-slate-100 rounded-full text-[10px] font-black text-[#003366] uppercase tracking-wider shadow-sm">
                    Total: {{ $vouchers->total() }}
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="uppercase tracking-widest text-[10px] font-black text-slate-400 border-b border-slate-50">
                            <th class="px-8 py-5">Kode Voucher</th>
                            <th class="px-8 py-5 text-center">Tipe</th>
                            <th class="px-8 py-5">Nilai Diskon</th>
                            <th class="px-8 py-5">Status (Terpakai/Limit)</th>
                            <th class="px-8 py-5">Penggunaan</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($vouchers as $voucher)
                            <tr class="hover:bg-slate-50/40 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <span class="font-black text-[#003366] font-mono text-base uppercase bg-blue-50/50 px-4 py-2 rounded-xl border border-blue-100/50 group-hover:border-blue-200 transition-all">
                                            {{ $voucher->code }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span @class([
                                        'text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest',
                                        'bg-emerald-50 text-emerald-700' => $voucher->type === 'nominal',
                                        'bg-purple-50 text-purple-700' => $voucher->type === 'percentage',
                                    ])>
                                        {{ $voucher->type === 'nominal' ? 'NOMINAL' : 'PERSENTASE' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    @if($voucher->type === 'nominal')
                                        <span class="font-black text-slate-700 text-sm">Rp {{ number_format($voucher->value, 0, ',', '.') }}</span>
                                    @else
                                        <span class="font-black text-slate-700 text-sm">{{ $voucher->value }}%</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-[#003366]/60">
                                            <span>Terpakai</span>
                                            <span>{{ $voucher->used_count }} / {{ $voucher->usage_limit }}</span>
                                        </div>
                                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden flex">
                                            <div class="h-full bg-emerald-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.3)] transition-all duration-700"
                                                style="width: {{ min(100, ($voucher->used_count / $voucher->usage_limit) * 100) }}%"></div>
                                        </div>
                                        @if($voucher->used_count >= $voucher->usage_limit)
                                            <span class="inline-flex items-center gap-1.5 text-[8px] font-black text-rose-600 uppercase tracking-widest mt-0.5">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                                Limit Tercapai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-[8px] font-black text-emerald-600 uppercase tracking-widest mt-0.5 animate-pulse">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full shadow-[0_0_6px_rgba(16,185,129,0.5)]"></span>
                                                Tersedia
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                @if($voucher->used_count > 0)
                                        <button @click="detailVoucher = {{ json_encode([
                                            'code' => $voucher->code,
                                            'participants' => $voucher->usages->map(fn($u) => [
                                                'name' => $u->participant->name,
                                                'nik' => $u->participant->nik,
                                                'order_code' => $u->order->order_code,
                                                'date' => $u->created_at->format('d M Y, H:i'),
                                                'status' => strtoupper($u->order->status)
                                            ])
                                        ]) }}; showDetailModal = true"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-[#003366] font-black rounded-xl hover:bg-blue-100 transition-all text-[10px] uppercase tracking-widest border border-blue-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Lihat {{ $voucher->used_count }} Peserta
                                        </button>
                                    @else
                                        <span class="text-slate-200 font-black text-[10px] uppercase tracking-widest italic text-center block">Belum digunakan</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    @if($voucher->used_count == 0)
                                        <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Hapus voucher ini?')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-3 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-2xl transition-all border border-transparent hover:border-rose-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <div class="p-3 opacity-20 inline-block">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 font-bold text-sm uppercase tracking-widest leading-loose">Belum ada voucher yang aktif.<br><button @click="showModal = true" class="text-[#003366] underline">Generate Sekarang</button></p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($vouchers->hasPages())
                <div class="px-8 py-6 bg-slate-50/30 border-t border-slate-100">
                    {{ $vouchers->links() }}
                </div>
            @endif
        </div>

        {{-- Generate Modal --}}
        <div x-show="showModal" 
            class="fixed inset-0 z-[100] overflow-y-auto" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>

                <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    
                    {{-- Modal Header --}}
                    <div class="bg-[#003366] p-10 text-center relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                        
                        <h2 class="text-2xl font-black text-white uppercase tracking-tight relative z-10">Generate Vouchers</h2>
                        <p class="text-white/60 font-bold text-[10px] uppercase tracking-[3px] mt-2 relative z-10">Sistem Pembuatan Voucher Massal</p>
                    </div>

                    {{-- Modal Body --}}
                    <form action="{{ route('admin.vouchers.store') }}" method="POST" class="p-10 space-y-8">
                        @csrf
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jumlah Voucher</label>
                                <input type="number" name="count" value="1" min="1" max="500" required
                                    class="w-full h-14 bg-slate-50 border-slate-100 rounded-2xl px-5 font-black text-[#003366] focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-lg">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tipe Potongan</label>
                                <select name="type" required
                                    class="w-full h-14 bg-slate-50 border-slate-100 rounded-2xl px-5 font-black text-[#003366] focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none uppercase tracking-wider text-xs">
                                    <option value="nominal">Nominal (Rp)</option>
                                    <option value="percentage" selected>Persentase (%)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nilai Potongan</label>
                                <input type="number" name="value" placeholder="100000 atau 10" required
                                    class="w-full h-14 bg-slate-50 border-slate-100 rounded-2xl px-5 font-black text-[#003366] focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-lg">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Limit Pemakaian</label>
                                <input type="number" name="usage_limit" value="1" min="1" required
                                    class="w-full h-14 bg-emerald-50 border-emerald-100 rounded-2xl px-5 font-black text-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none text-lg">
                            </div>
                        </div>

                        <div class="space-y-4 p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-2 h-2 bg-[#003366] rounded-full"></span>
                                <label class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Pengaturan Lanjutan (Opsional)</label>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Prefix Kode</label>
                                <input type="text" name="prefix" placeholder="Misal: KOMUNITAS" maxlength="10"
                                    class="w-full h-12 bg-white border border-slate-100 rounded-xl px-5 font-black text-[#003366] focus:ring-4 focus:ring-blue-500/10 transition-all outline-none uppercase tracking-widest placeholder:text-slate-200 text-xs">
                            </div>
                        </div>

                        <div class="pt-6 flex gap-4">
                            <button type="button" @click="showModal = false"
                                class="flex-1 h-14 bg-slate-100 text-slate-500 font-black rounded-2xl uppercase text-[11px] tracking-widest hover:bg-slate-200 transition-all">
                                Batalkan
                            </button>
                            <button type="submit"
                                class="flex-[2] h-14 bg-[#003366] text-white font-black rounded-2xl uppercase text-[11px] tracking-widest hover:bg-blue-900 shadow-xl shadow-blue-900/20 active:scale-95 transition-all">
                                Generate Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Data Detail Modal --}}
        <div x-show="showDetailModal" 
            class="fixed inset-0 z-[110] overflow-y-auto" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-md" @click="showDetailModal = false"></div>

                <div class="relative bg-white w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    
                    <div class="bg-[#003366] p-8 flex justify-between items-center relative overflow-hidden">
                         <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                         <div>
                            <h2 class="text-xl font-black text-white uppercase tracking-tight relative z-10">Daftar Klaim Voucher</h2>
                            <p class="text-white/60 font-black text-[10px] uppercase tracking-[3px] mt-1 relative z-10">KODE: <span x-text="detailVoucher.code" class="text-white font-mono"></span></p>
                         </div>
                         <button @click="showDetailModal = false" class="text-white/50 hover:text-white transition-colors relative z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                         </button>
                    </div>

                    <div class="p-8 max-h-[60vh] overflow-y-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    <th class="pb-4">Nama Peserta</th>
                                    <th class="pb-4">NIK</th>
                                    <th class="pb-4">Kode Order</th>
                                    <th class="pb-4">Tanggal Pakai</th>
                                    <th class="pb-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <template x-for="item in detailVoucher.participants" :key="item.order_code">
                                    <tr class="text-xs group">
                                        <td class="py-4">
                                            <div class="font-black text-slate-700 uppercase" x-text="item.name"></div>
                                        </td>
                                        <td class="py-4 font-mono font-bold text-slate-400" x-text="item.nik"></td>
                                        <td class="py-4">
                                            <span class="px-2 py-1 bg-slate-50 border border-slate-100 rounded text-[10px] font-black text-[#003366]" x-text="item.order_code"></span>
                                        </td>
                                        <td class="py-4 text-slate-400 font-bold" x-text="item.date"></td>
                                        <td class="py-4 text-right">
                                            <span class="text-[9px] font-black px-2.5 py-1 rounded uppercase tracking-widest"
                                                :class="{
                                                    'bg-emerald-50 text-emerald-600': item.status === 'PAID',
                                                    'bg-amber-50 text-amber-600': item.status === 'PENDING',
                                                    'bg-rose-50 text-rose-600': item.status === 'EXPIRED' || item.status === 'FAILED'
                                                }"
                                                x-text="item.status">
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-8 bg-slate-50 border-t border-slate-100 text-center">
                        <button @click="showDetailModal = false" class="px-10 py-3.5 bg-white border border-slate-200 text-[#003366] font-black rounded-2xl text-[10px] uppercase tracking-widest hover:bg-white active:scale-95 transition-all shadow-sm">
                            Tutup Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
