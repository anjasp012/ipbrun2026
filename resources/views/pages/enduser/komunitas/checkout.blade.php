<x-layouts.app title="Checkout Komunitas - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <!-- Main Form Container: Aligned with Index Card Style -->
        <div
            class="max-w-[1000px] w-full bg-white border border-slate-100 rounded-2xl shadow-sm md:p-14 p-8 transition-all duration-300">
            <div class="mb-10 text-center">
                <h1 class="text-3xl font-[800] text-[#003366] uppercase tracking-tight">Pendaftaran Komunitas</h1>
                <!-- Ticket Card Head -->
                <div class="mt-10 relative bg-white border border-slate-100 rounded-t-2xl overflow-hidden">
                    <div class="p-6 text-center">
                        <div
                            class="text-[17px] font-[800] text-[#003366] leading-tight font-['Plus_Jakarta_Sans'] uppercase mb-1">
                            {{ $ticket->category->name }} {{ $ticket->name ?: strtoupper($ticket->type) }} </div>
                        <div class="text-[11px] text-[#E8630A] font-[800] uppercase tracking-[0.5px] opacity-80">
                            {{ $ticket->period->name ?? 'Standard' }} </div>
                    </div> <!-- Perforation Detail -->
                    <div class="relative flex items-center py-1 overflow-hidden pointer-events-none">
                        <div
                            class="absolute -left-3 w-6 h-6 bg-white rounded-full shadow-inner ring-1 ring-inset ring-slate-100/30">
                        </div>
                        <div
                            class="absolute -right-3 w-6 h-6 bg-white rounded-full shadow-inner ring-1 ring-inset ring-slate-100/30">
                        </div>
                        <div class="w-full border-t border-dashed border-slate-200 mx-5"></div>
                    </div> <!-- Price Part -->
                    <div class="p-5 bg-slate-50/40 rounded-b-2xl"> <span
                            class="text-[10px] text-slate-400 font-[800] uppercase tracking-wider block mb-1">Entry
                            Fee</span>
                        <div class="text-[22px] font-[900] text-[#003366] leading-none font-['Plus_Jakarta_Sans']">Rp
                            {{ number_format($ticket->price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <form id="registrationForm" action="{{ route('komunitas.register') }}" method="POST"> @csrf 
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}"> 
                
                <!-- General Error Alert -->
                @if ($errors->any())
                    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                        <div class="flex items-center gap-3"> <svg class="w-5 h-5 text-red-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xs font-bold text-red-700 uppercase tracking-tight">
                                Silakan periksa kembali isian formulir Anda.
                            </p>
                        </div>
                    </div>
                @endif 

                <!-- Form Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2"> <x-label for="name">Nama Lengkap (Sesuai KTP) *</x-label> <x-input
                            id="name" name="name" placeholder="Ketik nama lengkap Anda" required
                            value="{{ old('name') }}"
                            class="{{ $errors->has('name') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('name')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div> <x-label for="email">Alamat Email (Untuk Notifikasi) *</x-label> <x-input type="email" id="email"
                            name="email" placeholder="nama@email.com" required value="{{ old('email') }}"
                            class="{{ $errors->has('email') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('email')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                        </p>@else<p
                                class="mt-2 text-[10px] text-[#E8630A] font-bold uppercase tracking-wider leading-tight">
                                Digunakan untuk pengiriman invoice & info lomba</p>
                        @enderror
                    </div>
                    <div> <x-label for="phone_number">Nomor WhatsApp *</x-label> <x-input id="phone_number"
                            name="phone_number" placeholder="08xxxxxxxxx" required :numeric="true" maxlength="14"
                            value="{{ old('phone_number') }}"
                            class="{{ $errors->has('phone_number') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('phone_number')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div> <x-label for="nik">NIK KTP *</x-label> <x-input id="nik" name="nik"
                            placeholder="16 digit NIK" required :numeric="true" maxlength="16"
                            value="{{ old('nik') }}"
                            class="{{ $errors->has('nik') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('nik')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @else<p
                                class="mt-2 text-[10px] text-[#E8630A] font-bold uppercase tracking-wider leading-tight">
                                NIK akan digunakan sebagai Username & Password login pembatalan/dashboard</p>
                        @enderror
                    </div>
                    <div>
                        <x-label for="date_birth">Tanggal Lahir *</x-label>
                        <div class="relative group">
                            <x-input id="date_birth" name="date_birth"
                                class="datepicker block w-full bg-white pr-12 {{ $errors->has('date_birth') ? '!border-red-500 ring-4 ring-red-50' : '' }}"
                                placeholder="DD-MM-YYYY" required value="{{ old('date_birth') }}" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 cursor-pointer text-slate-400 hover:text-[#003366] transition-colors"
                                onclick="document.getElementById('date_birth')._flatpickr.open()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        @error('date_birth')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div> <x-label for="sex">Jenis Kelamin *</x-label> <x-select id="sex" name="sex"
                            required :options="['male' => 'Laki-laki', 'female' => 'Perempuan']" :selected="old('sex')"
                            class="{{ $errors->has('sex') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                    </div>
                    <div> <x-label for="blood_type">Golongan Darah *</x-label> <x-select id="blood_type"
                            name="blood_type" required :options="['A'=>'A','A+'=>'A+','A-'=>'A-','B'=>'B','B+'=>'B+','B-'=>'B-','AB'=>'AB','AB+'=>'AB+','AB-'=>'AB-','O'=>'O','O+'=>'O+','O-'=>'O-','-'=>'-']" :selected="old('blood_type')"
                            class="{{ $errors->has('blood_type') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <x-label for="jersey_size" class="!mb-0">Ukuran Jersey *</x-label>
                            <button type="button" onclick="showSizeChart()"
                                class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Size Chart
                            </button>
                        </div>
                        <x-select id="jersey_size" name="jersey_size" required :options="['XS'=>'XS','S'=>'S','M'=>'M','L'=>'L','XL'=>'XL','2XL'=>'2XL','3XL'=>'3XL','4XL'=>'4XL','5XL'=>'5XL']" :selected="old('jersey_size')"
                            class="{{ $errors->has('jersey_size') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                    </div>
                    
                    @if($ticket->type === 'ipb')
                    <div> <x-label for="nim_nrp">NIM/NRP *</x-label>
                        <x-input id="nim_nrp" name="nim_nrp" placeholder="Khusus Mahasiswa/Alumni IPB"
                            required value="{{ old('nim_nrp') }}" minlength="6"
                            class="{{ $errors->has('nim_nrp') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('nim_nrp')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <div> <x-label for="nationality">Kewarganegaraan</x-label> <x-input id="nationality"
                            name="nationality" value="{{ old('nationality', 'Indonesia') }}" required
                            class="{{ $errors->has('nationality') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                    </div>
                    <div class="md:col-span-2"> <x-label for="address">Alamat Lengkap *</x-label> <x-textarea
                            id="address" name="address" rows="2" placeholder="Alamat pengiriman/domisili"
                            required
                            class="{{ $errors->has('address') ? '!border-red-500 ring-4 ring-red-50' : '' }}">{{ old('address') }}</x-textarea>
                    </div>

                    <div class="md:col-span-2 mt-4 text-[#003366] font-black text-sm uppercase tracking-widest border-b border-slate-100 pb-2">Optional Running Data</div>
                    <div> <x-label for="running_community">Komunitas Lari</x-label> <x-input id="running_community" name="running_community" value="{{ old('running_community') }}" /> </div>
                    <div> <x-label for="shuttle_bus">Shuttle Bus</x-label>
                        <x-select id="shuttle_bus" name="shuttle_bus" :options="[''=>'Tidak Menggunakan','Botani Square (Bogor)'=>'Botani Square (Bogor)','Gedung Alumni IPB (Bogor)'=>'Gedung Alumni IPB (Bogor)','FX Sudirman (Jakarta)'=>'FX Sudirman (Jakarta)']" :selected="old('shuttle_bus')" />
                    </div>
                    <div class="md:col-span-2"> <x-label for="medical_condition">Kondisi Medis</x-label> <x-textarea id="medical_condition" name="medical_condition" rows="2" placeholder="Sebutkan (jika ada)">{{ old('medical_condition') }}</x-textarea> </div>

                    <div class="md:col-span-2 mt-4 text-[#003366] font-black text-sm uppercase tracking-widest border-b border-slate-100 pb-2">Emergency Contact</div>
                    <div> <x-label for="emergency_contact_name">Nama Kontak *</x-label> <x-input id="emergency_contact_name" name="emergency_contact_name" required value="{{ old('emergency_contact_name') }}" /> </div>
                    <div> <x-label for="emergency_contact_phone_number">Nomor HP *</x-label> <x-input id="emergency_contact_phone_number" name="emergency_contact_phone_number" required :numeric="true" value="{{ old('emergency_contact_phone_number') }}" /> </div>
                    <div class="md:col-span-2"> <x-label for="emergency_contact_relationship">Hubungan *</x-label> <x-input id="emergency_contact_relationship" name="emergency_contact_relationship" required value="{{ old('emergency_contact_relationship') }}" /> </div>
                </div> 

                <!-- Price / Other Race Interest (Upsell) -->
                @php
                    $categoryName = strtoupper($ticket->category->name ?? '');
                    $pairCategoryName = '';
                    if (str_contains($categoryName, '5K') || str_contains($categoryName, '42K')) {
                        $pairCategoryName = '10K (Minggu)';
                    } elseif (str_contains($categoryName, '10K') || str_contains($categoryName, '21K')) {
                        $pairCategoryName = '5K (Sabtu)';
                    }
                @endphp
                @if ($pairCategoryName && $pairTicket)
                    <div class="mt-8 bg-orange-50/50 border border-orange-100 p-8 rounded-2xl">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="space-y-1">
                                <p class="text-[11px] font-bold text-[#E8630A]/80 uppercase tracking-widest leading-loose">
                                    APAKAH ANDA INGIN MENGIKUTI KATEGORI <span class="text-[#E8630A] underline underline-offset-4 decoration-2">{{ $pairCategoryName }}</span> JUGA?
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="other_race_interest" id="cb_second_ticket" value="1" class="sr-only peer">
                                <div class="w-16 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-8 after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#FF7A21] shadow-inner ring-4 ring-slate-100"></div>
                                <span class="ml-4 text-[10px] font-black text-slate-400 peer-checked:text-[#FF7A21] uppercase tracking-widest transition-colors">
                                    <span class="group-peer-checked:hidden">TIDAK</span>
                                    <span class="hidden group-peer-checked:inline">YA, IKUT!</span>
                                </span>
                            </label>
                        </div>
                    </div>
                @endif

                <!-- Persetujuan & Disclaimer -->
                <div class="mt-8 space-y-4">
                    <h3 class="text-sm font-black text-[#003366] uppercase tracking-[2px] mb-4 pb-2 border-b border-slate-100">Persetujuan & Disclaimer</h3>
                    <label class="flex items-start gap-4 cursor-pointer group py-3 px-4 bg-slate-50/50 rounded-xl transition-all">
                        <input type="checkbox" required class="w-5 h-5 rounded border-slate-300 text-[#003366] focus:ring-[#003366] cursor-pointer disclaimer-cb">
                        <span class="text-[11px] text-slate-600 leading-relaxed font-medium">Saya sebagai peserta IPB RUN 2026 akan mematuhi ketentuan lomba & memahami risiko kegiatan ini. Panitia dibebaskan dari segala tuntutan. <span class="text-red-500 font-black">*</span></span>
                    </label>
                    <label class="flex items-start gap-4 cursor-pointer group py-3 px-4 bg-slate-50/50 rounded-xl transition-all">
                        <input type="checkbox" required class="w-5 h-5 rounded border-slate-300 text-[#003366] focus:ring-[#003366] cursor-pointer disclaimer-cb">
                        <span class="text-[11px] text-slate-600 leading-relaxed font-medium">Saya memberikan hak kepada panitia menggunakan dokumentasi foto/video untuk keperluan resmi. <span class="text-red-500 font-black">*</span></span>
                    </label>
                    <label class="flex items-start gap-4 cursor-pointer group py-3 px-4 bg-slate-50/50 rounded-xl transition-all">
                        <input type="checkbox" required class="w-5 h-5 rounded border-slate-300 text-[#003366] focus:ring-[#003366] cursor-pointer disclaimer-cb">
                        <span class="text-[11px] text-slate-600 leading-relaxed font-medium">Saya menjamin seluruh data yang diisi adalah benar dan akurat. <span class="text-red-500 font-black">*</span></span>
                    </label>
                </div>

                <!-- Payment Summary with Voucher Integration -->
                <div class="mt-12 bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                    <div class="p-8 pb-1">
                        <h3 class="text-[15px] font-[800] text-[#003366] leading-tight uppercase tracking-tight mb-2">Rekapitulasi Pembayaran</h3>
                        <p class="text-[10px] text-[#E8630A] font-[800] uppercase tracking-[0.5px] opacity-80 mb-4">Total tagihan yang harus dibayarkan</p>
                    </div> 
                    <!-- Perforation Detail -->
                    <div class="relative flex items-center py-2 overflow-hidden pointer-events-none">
                        <div class="absolute -left-3 w-6 h-6 bg-[#f1f5f9] rounded-full ring-1 ring-inset ring-slate-100/30"></div>
                        <div class="absolute -right-3 w-6 h-6 bg-[#f1f5f9] rounded-full ring-1 ring-inset ring-slate-100/30"></div>
                        <div class="w-full border-t-2 border-dashed border-slate-200 mx-5"></div>
                    </div> 
                    <!-- Summary Content -->
                    <div class="p-8 pt-4 bg-slate-50/40 space-y-4">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm"> 
                                <span class="text-slate-500 font-medium italic">Tiket {{ $ticket->category->name }} ({{ strtoupper($ticket->type) }})</span> 
                                <span class="text-[#003366] font-bold">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span> 
                            </div>
                            <div class="flex justify-between items-center text-sm"> 
                                <span class="text-slate-500 font-medium italic">Biaya Layanan</span> 
                                <span class="text-[#003366] font-bold">Rp 4.500</span> 
                            </div>
                            @if($pairTicket)
                                <div id="row_second_ticket" class="hidden flex justify-between items-center text-sm ring-2 ring-orange-100 bg-orange-50/30 p-2 rounded-lg">
                                    <span class="text-[#E8630A] font-bold italic">Paket Tambahan: {{ $pairTicket->category->name }}</span>
                                    <span class="text-[#E8630A] font-black">Rp {{ number_format($pairTicket->price, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <!-- Voucher Row -->
                            <div id="row_voucher" class="hidden flex justify-between items-center text-sm ring-2 ring-emerald-100 bg-emerald-50/30 p-2 rounded-lg">
                                <span class="text-emerald-600 font-bold italic">Potongan Voucher (<span id="txt_voucher_code"></span>)</span>
                                <span class="text-emerald-700 font-black">- Rp <span id="lbl_discount">0</span></span>
                            </div>
                        </div>

                        <!-- Voucher Input Area -->
                        <div class="py-4 border-y border-dashed border-slate-200">
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Punya Kode Voucher Komunitas?</label>
                                <div class="flex gap-2">
                                    <input type="text" id="voucher_input" name="voucher_code" placeholder="MASUKKAN KODE" value="{{ old('voucher_code') }}"
                                        class="flex-1 h-11 bg-white border-slate-200 rounded-xl px-4 font-black text-slate-700 text-xs placeholder:text-slate-300 uppercase focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all">
                                    <button type="button" id="btn_apply_voucher"
                                        class="px-6 h-11 bg-emerald-600 text-white font-black rounded-xl uppercase text-[10px] tracking-widest hover:bg-emerald-700 transition-all active:scale-95">
                                        Cek
                                    </button>
                                </div>
                                <div id="voucher_message" class="mt-1 ml-1"></div>
                            </div>
                        </div>

                        <div class="pt-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <div class="flex flex-col"> 
                                <span class="text-[10px] text-slate-400 font-[800] uppercase tracking-wider block mb-1">Total Bayar</span>
                                <div id="lbl_total" class="text-[32px] font-[900] text-[#003366] leading-none font-['Plus_Jakarta_Sans']">
                                    Rp {{ number_format($ticket->price + 4500, 0, ',', '.') }}
                                </div>
                            </div> 
                            <x-button type="submit" id="btn_submit" disabled
                                class="w-full md:w-auto px-10 py-4 bg-[#003366] text-white rounded-xl font-[800] text-[15px] transition-all opacity-50 cursor-not-allowed uppercase tracking-widest">
                                Daftar Sekarang </x-button>
                        </div>
                    </div>
                </div>
                <div class="mt-8 text-center"> <a href="{{ url('/komunitas') }}"
                        class="inline-flex items-center gap-2 text-slate-400 font-bold text-[11px] uppercase tracking-widest hover:text-[#003366] transition-all">
                        ← Batal & Pilih Kategori Lain </a> </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showSizeChart() {
            Swal.fire({
                title: '<span class="text-[#003366] font-black uppercase text-2xl">Size Chart</span>',
                html: `<div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 mt-4">Tabel ukuran jersey... (Sesuai Standar)</div>`,
                confirmButtonText: 'OKE',
                confirmButtonColor: '#003366',
                customClass: { popup: 'rounded-3xl', confirmButton: 'rounded-xl px-8 font-black' }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const ticketPrice = {{ $ticket->price }};
            const adminFee = 4500;
            const pairTicketPrice = {{ $pairTicket->price ?? 0 }};
            let currentDiscount = 0;
            let currentVoucher = { type: null, value: 0 };

            const flatpickrInstance = flatpickr(".datepicker", {
                locale: "id",
                dateFormat: "d-m-Y",
                maxDate: "today",
                allowInput: true,
                disableMobile: "true"
            });

            // Input Masking for Date
            const birthInput = document.getElementById('date_birth');
            if (birthInput) {
                birthInput.addEventListener('input', function(e) {
                    let v = e.target.value.replace(/\D/g, '').slice(0, 8);
                    if (v.length > 2) v = v.slice(0, 2) + '-' + v.slice(2);
                    if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5);
                    e.target.value = v;
                });
            }

            function updateTotal() {
                const isSecondChecked = document.getElementById('cb_second_ticket')?.checked || false;
                const secondPrice = isSecondChecked ? pairTicketPrice : 0;
                
                const summarySecond = document.getElementById('row_second_ticket');
                if (summarySecond) {
                    if (isSecondChecked) summarySecond.classList.remove('hidden');
                    else summarySecond.classList.add('hidden');
                }

                // Recalculate discount if voucher exists
                let subtotalBeforeDiscount = ticketPrice + adminFee + secondPrice;
                if (currentVoucher.type === 'nominal') {
                    currentDiscount = Math.min(currentVoucher.value, subtotalBeforeDiscount);
                } else if (currentVoucher.type === 'percentage') {
                    currentDiscount = Math.floor(subtotalBeforeDiscount * (currentVoucher.value / 100));
                }

                if (currentDiscount > 0) {
                    document.getElementById('row_voucher')?.classList.remove('hidden');
                    document.getElementById('lbl_discount').innerText = currentDiscount.toLocaleString('id-ID');
                } else {
                    document.getElementById('row_voucher')?.classList.add('hidden');
                }

                const total = subtotalBeforeDiscount - currentDiscount;
                document.getElementById('lbl_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
            }

            document.getElementById('cb_second_ticket')?.addEventListener('change', updateTotal);

            // Auto-check voucher on NIK input
            const nikInput = document.getElementById('nik');
            if (nikInput) {
                nikInput.addEventListener('input', function() {
                    if (this.value.length === 16) {
                        applyVoucher(null, this.value);
                    }
                });
            }

            async function applyVoucher(code = null, nik = null) {
                const inputEl = document.getElementById('voucher_input');
                const voucherCode = code || inputEl.value;
                const messageEl = document.getElementById('voucher_message');
                const btn = document.getElementById('btn_apply_voucher');

                if (!voucherCode && !nik) return;
                
                btn.disabled = true; btn.innerText = '...'; 
                
                try {
                    const isSecondChecked = document.getElementById('cb_second_ticket')?.checked || false;
                    const secondPrice = isSecondChecked ? pairTicketPrice : 0;
                    const subtotalPrice = ticketPrice + adminFee + secondPrice;

                    const response = await fetch('{{ route("komunitas.check-voucher") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ code: voucherCode, nik: nik, price: subtotalPrice })
                    });
                    const data = await response.json();

                    if (data.valid) {
                        currentVoucher = { type: data.type, value: data.value };
                        document.getElementById('txt_voucher_code').innerText = data.code;
                        
                        if (data.assigned || (!inputEl.value || inputEl.value === '')) {
                            inputEl.value = data.code;
                        }
                        
                        if (data.assigned) {
                            messageEl.innerHTML = '<span class="text-[10px] font-black uppercase text-emerald-600 tracking-widest">Diskon Voucher Otomatis Terpasang!</span>';
                            inputEl.readOnly = true;
                            inputEl.classList.add('bg-slate-50', 'text-slate-400');
                            btn.classList.add('hidden');
                        } else {
                            messageEl.innerHTML = '<span class="text-[10px] font-black uppercase text-emerald-600 tracking-widest">Voucher berhasil dipasang!</span>';
                        }
                        updateTotal();
                    } else if (voucherCode) { // Show error if a code was manually entered or checked
                        currentDiscount = 0;
                        document.getElementById('row_voucher').classList.add('hidden');
                        messageEl.innerHTML = `<span class="text-[10px] font-black uppercase text-rose-500 tracking-widest">${data.message}</span>`;
                        updateTotal();
                    }
                } catch (e) {
                    if (code) messageEl.innerHTML = '<span class="text-[10px] font-black uppercase text-rose-500 tracking-widest">Error mengecek voucher</span>';
                } finally {
                    btn.disabled = false; btn.innerText = 'Cek';
                }
            }

            // Voucher AJAX Click
            document.getElementById('btn_apply_voucher').addEventListener('click', () => applyVoucher());

            const disclaimers = document.querySelectorAll('.disclaimer-cb');
            const submitBtn = document.getElementById('btn_submit');
            function checkDisclaimers() {
                let allChecked = Array.from(disclaimers).every(cb => cb.checked);
                submitBtn.disabled = !allChecked;
                submitBtn.classList.toggle('opacity-50', !allChecked);
                submitBtn.classList.toggle('cursor-not-allowed', !allChecked);
                submitBtn.classList.toggle('hover:bg-[#002244]', allChecked);
                submitBtn.classList.toggle('active:scale-95', allChecked);
            }
            disclaimers.forEach(cb => cb.addEventListener('change', checkDisclaimers));
        });
    </script>
    @endpush
</x-layouts.app>
