<x-layouts.admin title="Voucher Management">
    <div class="space-y-8">
        {{-- Generate Voucher Card --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
            <h3 class="text-xl font-black text-[#003366] uppercase tracking-tight mb-6">Generate New Vouchers</h3>
            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Jumlah Voucher</label>
                        <input type="number" name="count" value="1" min="1" max="100" required
                            class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4 font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tipe Potongan</label>
                        <select name="type" required
                            class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4 font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                            <option value="nominal">Nominal (Rp)</option>
                            <option value="percentage">Persentase (%)</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nilai Potongan</label>
                        <input type="number" name="value" placeholder="Contoh: 50000 atau 10" required
                            class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4 font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Prefix Kode (Opsional)</label>
                        <div class="flex gap-3">
                            <input type="text" name="prefix" placeholder="COM" maxlength="10"
                                class="flex-1 h-12 bg-slate-50 border-slate-100 rounded-xl px-4 font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all outline-none uppercase">
                            <button type="submit"
                                class="h-12 px-6 bg-[#003366] text-white font-black rounded-xl hover:bg-blue-900 active:scale-95 transition-all text-xs uppercase tracking-widest leading-none">
                                Generate
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Voucher List Table --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                <h3 class="text-xl font-black text-[#003366] uppercase tracking-tight">Active Vouchers</h3>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Total: {{ $vouchers->total() }}</div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 uppercase tracking-widest text-[11px] font-black text-slate-400 border-b border-slate-100">
                            <th class="px-8 py-5">KODE VOUCHER</th>
                            <th class="px-8 py-5">TIPE</th>
                            <th class="px-8 py-5">NILAI</th>
                            <th class="px-8 py-5">STATUS</th>
                            <th class="px-8 py-5">DIGUNAKAN OLEH</th>
                            <th class="px-8 py-5 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($vouchers as $voucher)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-8 py-5">
                                    <span class="font-black text-slate-700 font-mono text-base uppercase bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                                        {{ $voucher->code }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <span @class([
                                        'text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest',
                                        'bg-blue-100 text-blue-700' => $voucher->type === 'nominal',
                                        'bg-purple-100 text-purple-700' => $voucher->type === 'percentage',
                                    ])>
                                        {{ $voucher->type === 'nominal' ? 'NOMINAL' : 'PERSENTASE' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 font-bold text-slate-700">
                                    {{ $voucher->type === 'nominal' ? 'Rp ' . number_format($voucher->value, 0, ',', '.') : $voucher->value . '%' }}
                                </td>
                                <td class="px-8 py-5">
                                    @if($voucher->is_used)
                                        <span class="inline-flex items-center gap-2 text-[10px] font-black text-rose-600 uppercase tracking-widest bg-rose-50 px-3 py-1.5 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                            Terpakai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 px-3 py-1.5 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            Tersedia
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    @if($voucher->participant)
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-700 text-sm">{{ $voucher->participant->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium">{{ $voucher->used_at->format('d M Y, H:i') }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-300 font-bold text-[10px] uppercase tracking-widest">—</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    @if(!$voucher->is_used)
                                        <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Hapus voucher ini?')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                        </svg>
                                        <p class="text-slate-400 font-bold text-sm uppercase tracking-widest">Belum ada voucher yang digenerate</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($vouchers->hasPages())
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                    {{ $vouchers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
