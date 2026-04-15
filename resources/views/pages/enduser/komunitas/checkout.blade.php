<x-layouts.app title="Checkout Komunitas - IPB RUN 2026">
    <div class="bg-slate-50 min-h-screen py-16 px-6 sm:px-12">
        <div class="max-w-4xl mx-auto">
            <div class="mb-12">
                <a href="{{ url('/komunitas') }}" class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-[#003366] transition-colors mb-6 group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali Pilih Kategori
                </a>
                <h1 class="text-3xl font-black text-[#003366] uppercase tracking-tighter">Formulir Pendaftaran Komunitas</h1>
                <p class="text-slate-500 font-bold uppercase tracking-widest text-[11px] mt-2">Kategori: {{ $ticket->category->name }} ({{ $ticket->type === 'ipb' ? 'IPB Family' : 'Umum' }})</p>
            </div>

            <form action="{{ route('komunitas.register') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                @csrf
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                
                <div class="lg:col-span-2 space-y-10">
                    {{-- Section: Personal Data --}}
                    <div class="bg-white rounded-[2.5rem] p-10 shadow-xl shadow-blue-900/5 border border-slate-100">
                        <h3 class="text-lg font-black text-[#003366] uppercase tracking-tight mb-8 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">01</span>
                            Data Pribadi
                        </h3>
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-label for="name" value="Nama Lengkap" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                    <x-input id="name" name="name" :value="old('name')" required class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4" />
                                    @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-2">
                                    <x-label for="email" value="Alamat Email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                    <x-input id="email" type="email" name="email" :value="old('email')" required class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4" />
                                    @error('email') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-label for="phone_number" value="Nomor WhatsApp" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                    <x-input id="phone_number" name="phone_number" :value="old('phone_number')" required class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4" />
                                    @error('phone_number') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-2">
                                    <x-label for="nik" value="NIK KTP (16 Digit)" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                    <x-input id="nik" name="nik" :value="old('nik')" required maxlength="16" class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4" />
                                    @error('nik') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-label for="date_birth" value="Tanggal Lahir" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                    <x-input id="date_birth" type="date" name="date_birth" :value="old('date_birth')" required class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4" />
                                    @error('date_birth') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-2">
                                    <x-label for="sex" value="Jenis Kelamin" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                    <select name="sex" required class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4 font-bold text-slate-700">
                                        <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-label for="blood_type" value="Golongan Darah" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                    <select name="blood_type" required class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4 font-bold text-slate-700">
                                        @foreach(['A','A+','A-','B','B+','B-','AB','AB+','AB-','O','O+','O-','-'] as $bt)
                                            <option value="{{ $bt }}" {{ old('blood_type') == $bt ? 'selected' : '' }}>{{ $bt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <x-label for="jersey_size" value="Ukuran Jersey" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                    <select name="jersey_size" required class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4 font-bold text-slate-700">
                                        @foreach(['XS','S','M','L','XL','2XL','3XL','4XL','5XL'] as $size)
                                            <option value="{{ $size }}" {{ old('jersey_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <x-label for="nationality" value="Kewarganegaraan" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                <x-input id="nationality" name="nationality" :value="old('nationality', 'Indonesia')" required class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4" />
                            </div>
                            <div class="space-y-2">
                                <x-label for="address" value="Alamat Lengkap" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                <textarea name="address" required class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 min-h-[100px] font-bold text-slate-700">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Emergency Contact & Others --}}
                    <div class="bg-white rounded-[2.5rem] p-10 shadow-xl shadow-blue-900/5 border border-slate-100">
                        <h3 class="text-lg font-black text-[#003366] uppercase tracking-tight mb-8 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center text-sm">02</span>
                            Data Kontak Darurat & Lari
                        </h3>
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-label for="emergency_contact_name" value="Nama Kontak Darurat" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                    <x-input id="emergency_contact_name" name="emergency_contact_name" :value="old('emergency_contact_name')" required class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4" />
                                </div>
                                <div class="space-y-2">
                                    <x-label for="emergency_contact_phone_number" value="Nomor HP Darurat" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                    <x-input id="emergency_contact_phone_number" name="emergency_contact_phone_number" :value="old('emergency_contact_phone_number')" required class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4" />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <x-label for="emergency_contact_relationship" value="Hubungan" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                <x-input id="emergency_contact_relationship" name="emergency_contact_relationship" :value="old('emergency_contact_relationship')" required class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4" />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                                <div class="space-y-2">
                                    <x-label for="running_community" value="Komunitas Lari" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                    <x-input id="running_community" name="running_community" :value="old('running_community')" placeholder="Opsional" class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4" />
                                </div>
                                <div class="space-y-2">
                                    <x-label for="shuttle_bus" value="Shuttle Bus (Pilih Lokasi)" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                    <select name="shuttle_bus" class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl px-4 font-bold text-slate-700">
                                        <option value="">Tidak Menggunakan</option>
                                        <option value="Botani Square (Jalan Pajajaran, Bogor)">Botani Square (Bogor)</option>
                                        <option value="Gedung Alumni IPB (Baranangsiang, Bogor)">Gedung Alumni IPB (Bogor)</option>
                                        <option value="FX Sudirman (Jakarta Selatan)">FX Sudirman (Jakarta)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <x-label for="medical_condition" value="Kondisi Medis" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                                <textarea name="medical_condition" placeholder="Punya riwayat asma, jantung, alergi, atau kondisi medis lainnya? (Isi - jika tidak ada)" class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 min-h-[100px] font-bold text-slate-700">{{ old('medical_condition') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    {{-- Voucher Card --}}
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-blue-900/5 border border-slate-100">
                        <h3 class="text-sm font-black text-[#003366] uppercase tracking-widest mb-6">Kode Voucher</h3>
                        <div class="flex gap-2">
                            <input type="text" id="voucher_input" name="voucher_code" placeholder="KODE VOUCHER"
                                class="flex-1 h-12 bg-slate-50 border-slate-100 rounded-xl px-4 font-black text-slate-700 text-sm placeholder:text-slate-300 uppercase">
                            <button type="button" id="apply_voucher"
                                class="px-6 h-12 bg-emerald-600 text-white font-black rounded-xl uppercase text-[10px] tracking-widest hover:bg-emerald-700 transition-all active:scale-95">
                                Cek
                            </button>
                        </div>
                        <div id="voucher_message" class="mt-3 ml-1"></div>
                    </div>

                    {{-- Summary Card --}}
                    <div class="bg-gradient-to-br from-[#003366] to-blue-900 rounded-[2.5rem] p-10 shadow-2xl shadow-blue-900/20 text-white sticky top-24">
                        <h3 class="text-sm font-black uppercase tracking-widest mb-8 opacity-60">Ringkasan Pesanan</h3>
                        <div class="space-y-5 border-b border-white/10 pb-8 mb-8">
                            <div class="flex justify-between items-center">
                                <span class="text-[11px] font-bold uppercase tracking-widest opacity-60 text-white/80">Kategori Tiket</span>
                                <span class="text-xs font-black uppercase tracking-wider">{{ $ticket->category->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[11px] font-bold uppercase tracking-widest opacity-60 text-white/80">Harga Tiket</span>
                                <span class="text-sm font-black">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-emerald-400" id="discount_row" style="display: none;">
                                <span class="text-[11px] font-bold uppercase tracking-widest">Diskon Voucher</span>
                                <span class="text-sm font-black">- Rp <span id="discount_amount">0</span></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[11px] font-bold uppercase tracking-widest opacity-60 text-white/80">Biaya Layanan</span>
                                <span class="text-sm font-black">Rp 4.500</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-end mb-10">
                            <div>
                                <span class="block text-[10px] font-black uppercase tracking-[3px] opacity-40 mb-1">Total Pembayaran</span>
                                <span class="text-3xl font-black tracking-tighter">Rp <span id="final_total">{{ number_format($ticket->price + 4500, 0, ',', '.') }}</span></span>
                            </div>
                        </div>
                        <button type="submit" class="w-full h-16 bg-orange-500 hover:bg-orange-600 active:scale-[0.98] rounded-2xl font-black uppercase tracking-[3px] text-sm shadow-xl shadow-orange-950/20 transition-all flex items-center justify-center gap-4">
                            Daftar Sekarang
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const originalPrice = {{ $ticket->price }};
        const adminFee = 4500;
        let discount = 0;

        document.getElementById('apply_voucher').addEventListener('click', async function() {
            const code = document.getElementById('voucher_input').value;
            const messageEl = document.getElementById('voucher_message');
            const discountRow = document.getElementById('discount_row');
            const discountAmountEl = document.getElementById('discount_amount');
            const finalTotalEl = document.getElementById('final_total');
            const btn = this;

            if (!code) return;

            btn.disabled = true;
            btn.innerHTML = '...';
            messageEl.innerHTML = '';

            try {
                const response = await fetch('{{ route("komunitas.check-voucher") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ code: code, price: originalPrice })
                });
                const data = await response.json();

                if (data.valid) {
                    discount = data.discount;
                    discountRow.style.display = 'flex';
                    discountAmountEl.innerText = discount.toLocaleString('id-ID');
                    const total = (originalPrice - discount) + adminFee;
                    finalTotalEl.innerText = total.toLocaleString('id-ID');
                    messageEl.innerHTML = `<span class="text-[10px] font-black uppercase text-emerald-600 tracking-widest">Voucher berhasil diterapkan! (-${data.type === 'nominal' ? 'Rp ' + data.value.toLocaleString('id-ID') : data.value + '%'})</span>`;
                } else {
                    discount = 0;
                    discountRow.style.display = 'none';
                    const total = originalPrice + adminFee;
                    finalTotalEl.innerText = total.toLocaleString('id-ID');
                    messageEl.innerHTML = `<span class="text-[10px] font-black uppercase text-rose-500 tracking-widest animate-shake text-left block">${data.message}</span>`;
                }
            } catch (error) {
                console.error(error);
                messageEl.innerHTML = '<span class="text-[10px] font-black uppercase text-rose-500 tracking-widest">Gagal mengecek voucher. Silakan coba lagi.</span>';
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Cek';
            }
        });
    </script>
    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
            20%, 40%, 60%, 80% { transform: translateX(2px); }
        }
        .animate-shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
    </style>
    @endpush
</x-layouts.app>
