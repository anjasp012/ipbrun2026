<x-layouts.app title="Konfirmasi Pendaftaran Tambahan - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f4f6f9] z-[-2]"></div>
    <div class="min-h-screen py-20 px-6 sm:px-12 flex items-center justify-center">
        <div class="max-w-[550px] w-full bg-white rounded-[3rem] border border-slate-100 shadow-2xl p-10 md:p-14 text-center">
            <div
                class="w-20 h-20 bg-blue-50 text-blue-600 rounded-[2rem] flex items-center justify-center mx-auto mb-10 border border-blue-100 shadow-xl shadow-blue-50">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
            </div>
            <h2 class="text-xs font-black text-[#E8630A] uppercase tracking-[5px] mb-4">Pendaftaran Tambahan</h2>
            <h1 class="text-4xl font-[900] text-[#003366] uppercase tracking-tighter mb-8 italic">Konfirmasi Kategori
            </h1>
            <div class="p-8 bg-slate-50/80 border border-slate-100 rounded-[2.5rem] mb-12 space-y-6">
                <div class="flex justify-between items-center text-left">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 opacity-70">Nama
                            Peserta</p>
                        <p class="text-lg font-black text-[#003366] uppercase tracking-tight">{{ $participant->name }}
                        </p>
                    </div>
                </div>
                <div class="h-px bg-slate-200 border-dashed border-t"></div>
                <div class="flex justify-between items-center text-left">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 opacity-70">
                            Kategori Baru</p>
                        <p class="text-3xl font-[900] text-[#E8630A] uppercase tracking-tighter mb-1">
                            {{ $ticket->category->name }}</p>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                            {{ $ticket->name ?: strtoupper($ticket->type) }} Period</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 opacity-70">
                            Harga Tiket</p>
                        <p class="text-xl font-black text-[#003366] tracking-tighter">Rp
                            {{ number_format($ticket->price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div id="voucher_applied_row" class="{{ $discountAmount > 0 ? '' : 'hidden' }} h-px bg-slate-200 border-dashed border-t"></div>
                <div id="voucher_summary_box" class="{{ $discountAmount > 0 ? '' : 'hidden' }} flex justify-between items-center text-left p-4 bg-orange-50 rounded-2xl border border-orange-100">
                    <div>
                        <p class="text-[10px] font-black text-[#E8630A] uppercase tracking-widest mb-1 opacity-70">
                            Voucher Terpasang</p>
                        <p id="lbl_voucher_code" class="text-sm font-black text-[#E8630A] uppercase tracking-tight">{{ $voucher->code ?? '' }}
                        </p>
                        <p id="lbl_voucher_desc" class="text-[9px] font-bold text-[#E8630A]/60 uppercase tracking-widest">
                            @if($voucher)
                                ({{ $voucher->type === 'percentage' ? $voucher->value . '%' : 'Rp ' . number_format($voucher->value, 0, ',', '.') }})
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-[#E8630A] uppercase tracking-widest mb-1 opacity-70">
                            Potongan</p>
                        <p id="lbl_discount_amount" class="text-lg font-black text-[#E8630A] tracking-tighter">- Rp
                            {{ number_format($discountAmount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="h-px bg-slate-200 border-dashed border-t"></div>
                <div class="space-y-4">
                    <div class="flex justify-between items-center"> <span
                            class="text-xs font-bold text-slate-400 uppercase tracking-widest">Biaya Layanan</span> <span
                            class="text-xs font-black text-[#003366]">Rp 4.500</span> </div>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-100"> <span
                            class="text-xs font-black text-[#003366] uppercase tracking-[3px]">Total Bayar</span>
                        <span id="lbl_total" class="text-2xl font-[900] text-[#003366] tracking-tighter">Rp
                            {{ number_format($ticket->price + 4500 - $discountAmount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Voucher Input Section -->
                <div class="pt-6 border-t border-dashed border-slate-200">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-3 text-center">Punya Kode Voucher?</p>
                    <div class="flex gap-2">
                        <input type="text" id="voucher_input" placeholder="Masukkan kode..." 
                            class="flex-1 h-12 bg-white border-2 border-slate-100 rounded-xl px-4 font-black text-[#003366] focus:border-[#FF7A21] outline-none uppercase tracking-widest text-xs">
                        <button type="button" id="btn_apply_voucher"
                            class="h-12 px-6 bg-orange-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-orange-600 transition-all active:scale-95 shadow-lg shadow-orange-900/10">
                            Pasang
                        </button>
                    </div>
                    <div id="voucher_message" class="mt-2 min-h-[14px] text-center"></div>
                </div>
            </div>

            <form action="{{ route('participant.buy-more.process', $ticket->id) }}" method="POST" class="space-y-6">
                @csrf 
                <input type="hidden" id="hidden_voucher_code" name="voucher_code" value="{{ $voucher->code ?? '' }}">
                <button type="submit"
                    class="w-full h-16 bg-[#003366] text-white rounded-2xl font-black uppercase tracking-[4px] text-xs hover:bg-blue-900 transition-all shadow-xl shadow-blue-950/20 active:scale-[0.98]">
                    Lanjutkan ke Pembayaran </button> <a href="{{ url('/dashboard') }}"
                    class="block text-[11px] font-black text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-all">
                    Kembali ke Dashboard </a> </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let ticketPrice = {{ $ticket->price }};
                    let adminFee = 4500;
                    let currentDiscount = {{ $discountAmount }};

                    function updateTotal() {
                        let total = ticketPrice + adminFee - currentDiscount;
                        document.getElementById('lbl_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
                        document.getElementById('lbl_discount_amount').innerText = '- Rp ' + currentDiscount.toLocaleString('id-ID');
                        
                        if (currentDiscount > 0) {
                            document.getElementById('voucher_summary_box').classList.remove('hidden');
                            document.getElementById('voucher_applied_row').classList.remove('hidden');
                        } else {
                            document.getElementById('voucher_summary_box').classList.add('hidden');
                            document.getElementById('voucher_applied_row').classList.add('hidden');
                        }
                    }

                    async function applyVoucher() {
                        const inputEl = document.getElementById('voucher_input');
                        const code = inputEl.value.trim().toUpperCase();
                        const messageEl = document.getElementById('voucher_message');
                        const btn = document.getElementById('btn_apply_voucher');

                        if (!code) return;
                        btn.disabled = true; btn.innerText = '...';

                        try {
                            const response = await fetch('{{ route("voucher.check") }}', {
                                method: 'POST',
                                headers: { 
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ 
                                    code: code, 
                                    price: ticketPrice, 
                                    ticket_id: '{{ $ticket->id }}',
                                    nik: '{{ $participant->nik }}'
                                })
                            });
                            const data = await response.json();

                            if (data.valid) {
                                currentDiscount = data.discount;
                                document.getElementById('lbl_voucher_code').innerText = data.code;
                                document.getElementById('lbl_voucher_desc').innerText = 
                                    data.type === 'percentage' ? `(${data.value}%)` : `(Rp ${data.value.toLocaleString('id-ID')})`;
                                document.getElementById('hidden_voucher_code').value = data.code;
                                
                                messageEl.innerHTML = '<span class="text-[10px] font-black uppercase text-emerald-600 tracking-widest">✓ Voucher berhasil dipasang!</span>';
                                inputEl.value = '';
                                updateTotal();
                            } else {
                                messageEl.innerHTML = `<span class="text-[10px] font-black uppercase text-rose-500 tracking-widest">${data.message}</span>`;
                            }
                        } catch (e) {
                            messageEl.innerHTML = '<span class="text-[10px] font-black uppercase text-rose-500 tracking-widest">Gagal memvalidasi voucher</span>';
                        } finally {
                            btn.disabled = false; btn.innerText = 'Pasang';
                        }
                    }

                    document.getElementById('btn_apply_voucher').addEventListener('click', applyVoucher);
                    document.getElementById('voucher_input').addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') { e.preventDefault(); applyVoucher(); }
                    });
                });
            </script>

            <div class="mt-14 p-6 bg-blue-50 border border-blue-100 rounded-3xl text-left flex gap-4"> <svg
                    class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-[10px] font-bold text-blue-600/80 leading-relaxed uppercase tracking-widest italic"> Data
                    pendaftaran (Jersey, NIK, dll) akan disesuaikan dengan data pendaftaran pertama Anda. </p>
            </div>
        </div>
    </div>
</x-layouts.app>
