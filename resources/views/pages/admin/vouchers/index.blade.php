<x-layouts.admin title="Voucher Management">
    <div class="space-y-8" x-data="{ showModal: false, showDetailModal: false, detailVoucher: {}, ticketType: 'UMUM', categoryId: '', categoryName: '', customCode: '', isUnlimitedUsage: false, isUnlimitedDate: true }">
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
                            <th class="px-8 py-5">Kedaluwarsa</th>
                            <th class="px-8 py-5">Penggunaan</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($vouchers as $voucher)
                            <tr class="hover:bg-slate-50/40 transition-colors group {{ !$voucher->is_active ? 'opacity-50' : '' }}">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3" x-data="{ copied: false }">
                                        <span class="font-black text-[#003366] font-mono text-base uppercase bg-blue-50/50 px-4 py-2 rounded-xl border border-blue-100/50 group-hover:border-blue-200 transition-all">
                                            {{ $voucher->code }}
                                        </span>
                                        @if(!$voucher->is_active)
                                            <span class="px-2 py-0.5 bg-rose-50 text-rose-600 font-black text-[8px] uppercase tracking-widest rounded-lg border border-rose-100">OFF</span>
                                        @endif
                                        <button @click="navigator.clipboard.writeText('{{ $voucher->code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                            class="p-2 text-slate-400 hover:text-[#003366] hover:bg-blue-50 rounded-lg transition-all border border-transparent hover:border-blue-100 active:scale-90"
                                            title="Salin Kode">
                                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                            </svg>
                                            <svg x-show="copied" x-cloak class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
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
                                            <span>
                                                {{ $voucher->used_count }} / {{ $voucher->usage_limit !== null ? $voucher->usage_limit : '∞' }}
                                            </span>
                                        </div>
                                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden flex">
                                            @if($voucher->usage_limit !== null)
                                            <div class="h-full bg-emerald-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.3)] transition-all duration-700"
                                                style="width: {{ min(100, ($voucher->used_count / $voucher->usage_limit) * 100) }}%"></div>
                                            @else
                                            <div class="h-full bg-blue-400 rounded-full w-full opacity-50"></div>
                                            @endif
                                        </div>
                                        @if($voucher->usage_limit !== null && $voucher->used_count >= $voucher->usage_limit)
                                            <span class="inline-flex items-center gap-1.5 text-[8px] font-black text-rose-600 uppercase tracking-widest mt-0.5">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                                Limit Tercapai
                                            </span>
                                        @elseif($voucher->expired_at && now()->isAfter($voucher->expired_at))
                                            <span class="inline-flex items-center gap-1.5 text-[8px] font-black text-rose-600 uppercase tracking-widest mt-0.5">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Kedaluwarsa
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
                                    @if($voucher->expired_at)
                                        <div class="flex flex-col">
                                            <span class="font-black text-slate-700 text-xs">{{ \Carbon\Carbon::parse($voucher->expired_at)->format('d M Y') }}</span>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($voucher->expired_at)->format('H:i') }} WIB</span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-600 font-bold text-[9px] uppercase tracking-widest border border-blue-100/50">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            Tanpa Batas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                @if($voucher->used_count > 0)
                                        <button @click="detailVoucher = {{ json_encode([
                                            'code' => $voucher->code,
                                            'participants' => $voucher->usages->groupBy('participant_id')->map(fn($group) => [
                                                'name' => $group->first()->participant->name,
                                                'nik' => $group->first()->participant->nik,
                                                'order_codes' => $group->map(fn($u) => $u->order->order_code)->toArray(),
                                                'dates' => $group->map(fn($u) => $u->created_at->format('d/m/Y'))->toArray(),
                                                'statuses' => $group->map(fn($u) => strtoupper($u->order->status))->toArray()
                                            ])->values()
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
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Toggle ON/OFF --}}
                                        <form action="{{ route('admin.vouchers.toggle', $voucher) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="{{ $voucher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                class="p-3 rounded-2xl transition-all border {{ $voucher->is_active ? 'text-emerald-600 bg-emerald-50 border-emerald-100 hover:bg-emerald-100' : 'text-slate-400 bg-slate-50 border-slate-100 hover:bg-slate-100' }}">
                                                @if($voucher->is_active)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>

                                        {{-- Delete --}}
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
                                    </div>
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
                                <div class="flex items-center justify-between ml-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Limit Pemakaian</label>
                                    <label class="flex items-center cursor-pointer gap-2">
                                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Unlimited?</span>
                                        <input type="checkbox" name="is_unlimited_usage" value="1" x-model="isUnlimitedUsage" class="sr-only peer">
                                        <div class="w-8 h-4 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-emerald-500 relative"></div>
                                    </label>
                                </div>
                                <input type="number" name="usage_limit" value="1" min="1" :required="!isUnlimitedUsage" :disabled="isUnlimitedUsage" :class="isUnlimitedUsage ? 'opacity-50 cursor-not-allowed' : ''"
                                    class="w-full h-14 bg-emerald-50 border-emerald-100 rounded-2xl px-5 font-black text-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none text-lg">
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

                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nilai Potongan</label>
                                <input type="number" name="value" placeholder="100000 atau 10" required
                                    class="w-full h-14 bg-slate-50 border-slate-100 rounded-2xl px-5 font-black text-[#003366] focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-lg">
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between ml-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Kedaluwarsa</label>
                                    <label class="flex items-center cursor-pointer gap-2">
                                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Tanpa Expired?</span>
                                        <input type="checkbox" name="is_unlimited_date" value="1" x-model="isUnlimitedDate" class="sr-only peer">
                                        <div class="w-8 h-4 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-emerald-500 relative"></div>
                                    </label>
                                </div>
                                <input type="datetime-local" name="expired_at" :required="!isUnlimitedDate" :disabled="isUnlimitedDate" :class="isUnlimitedDate ? 'opacity-50 cursor-not-allowed' : ''"
                                    class="w-full h-14 bg-slate-50 border-slate-100 rounded-2xl px-5 font-black text-[#003366] focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-xs">
                            </div>
                        </div>

                        <div class="space-y-4 p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-2 h-2 bg-[#003366] rounded-full"></span>
                                <label class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Pengaturan Format Kode Voucher</label>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Tipe Tiket</label>
                                    <select name="ticket_type" required x-model="ticketType"
                                        class="w-full h-12 bg-white border border-slate-100 rounded-xl px-5 font-black text-[#003366] focus:ring-4 focus:ring-blue-500/10 transition-all outline-none uppercase tracking-widest text-xs">
                                        <option value="UMUM">Public (Umum)</option>
                                        <option value="IPB">IPB Family</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori</label>
                                    <select name="category_id" required x-model="categoryId" @change="categoryName = $event.target.options[$event.target.selectedIndex].dataset.name"
                                        class="w-full h-12 bg-white border border-slate-100 rounded-xl px-5 font-black text-[#003366] focus:ring-4 focus:ring-blue-500/10 transition-all outline-none uppercase tracking-widest text-xs">
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" data-name="{{ $cat->name }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-2 mt-4">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Akhiran Bebas (Custom String)</label>
                                <input type="text" name="custom_code" placeholder="Misal: FAHRIL" maxlength="15" required x-model="customCode"
                                    class="w-full h-12 bg-white border border-slate-100 rounded-xl px-5 font-black text-[#003366] focus:ring-4 focus:ring-blue-500/10 transition-all outline-none uppercase tracking-widest placeholder:text-slate-200 text-xs">
                            </div>
                            
                            <div class="mt-4 p-4 border border-blue-100 bg-blue-50/50 rounded-xl">
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Preview Hasil Akhir:</p>
                                <p class="text-base font-black text-[#003366] mt-1 break-words">
                                    <span x-text="ticketType || 'UMUM'"></span>-<span x-text="categoryName || '5K'"></span>-<span x-text="(customCode || '').toUpperCase().replace(/[^a-zA-Z0-9]/g, '')"></span>
                                </p>
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
                                    <th class="pb-4 text-center">Tanggal Pakai</th>
                                    <th class="pb-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <template x-for="person in detailVoucher.participants" :key="person.nik">
                                    <tr class="text-xs group">
                                        <td class="py-4">
                                            <div class="font-black text-slate-700 uppercase" x-text="person.name"></div>
                                        </td>
                                        <td class="py-4 font-mono font-bold text-slate-400" x-text="person.nik"></td>
                                        <td class="py-4">
                                            <div class="flex flex-wrap gap-2">
                                                <template x-for="(code, idx) in person.order_codes">
                                                    <div class="flex items-center gap-1">
                                                        <span class="px-2 py-0.5 bg-slate-50 border border-slate-100 rounded text-[9px] font-black text-[#003366]" x-text="code"></span>
                                                        <template x-if="idx < person.order_codes.length - 1">
                                                            <span class="text-slate-300 font-bold">|</span>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="py-4 text-center">
                                             <div class="flex flex-wrap justify-center gap-2">
                                                <template x-for="(date, idx) in person.dates">
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-slate-400 font-bold" x-text="date"></span>
                                                        <template x-if="idx < person.dates.length - 1">
                                                            <span class="text-slate-300 font-bold">|</span>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="py-4 text-right">
                                            <div class="flex flex-wrap justify-end gap-2">
                                                <template x-for="(status, idx) in person.statuses">
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[8px] font-black px-2 py-0.5 rounded uppercase tracking-widest"
                                                            :class="{
                                                                'bg-emerald-50 text-emerald-600': status === 'PAID',
                                                                'bg-amber-50 text-amber-600': status === 'PENDING',
                                                                'bg-rose-50 text-rose-600': ['EXPIRED', 'FAILED'].includes(status)
                                                            }"
                                                            x-text="status">
                                                        </span>
                                                        <template x-if="idx < person.statuses.length - 1">
                                                            <span class="text-slate-300 font-bold">|</span>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
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
