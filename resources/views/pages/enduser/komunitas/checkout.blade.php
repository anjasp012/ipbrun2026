<x-layouts.app>
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <!-- Main Form Container: Aligned with Index Card Style -->
        <div
            class="max-w-[1000px] w-full bg-white border border-slate-100 rounded-2xl shadow-sm md:p-14 p-8 transition-all duration-300">
            <div class="mb-10 text-center">
                <h1 class="text-3xl font-[800] text-[#003366] uppercase tracking-tight">Formulir Data Pelari (Komunitas)</h1>
                <!-- Ticket Card Head (Top Part of Index Card Style) -->
                <div class="mt-10 relative bg-white border border-slate-100 rounded-t-2xl overflow-hidden">
                    <div class="p-6 text-center">
                        <div
                            class="text-[17px] font-[800] text-[#003366] leading-tight font-['Plus_Jakarta_Sans'] uppercase mb-1">
                            {{ $ticket->category->name }} {{ $ticket->name ?: strtoupper($ticket->type) }} </div>
                        <div class="text-[11px] text-[#E8630A] font-[800] uppercase tracking-[0.5px] opacity-80">
                            {{ $ticket->period->name ?? 'Standard' }} </div>
                    </div> <!-- Perforation Detail (Matched with Index) -->
                    <div class="relative flex items-center py-1 overflow-hidden pointer-events-none">
                        <div
                            class="absolute -left-3 w-6 h-6 bg-white rounded-full shadow-inner ring-1 ring-inset ring-slate-100/30">
                        </div>
                        <div
                            class="absolute -right-3 w-6 h-6 bg-white rounded-full shadow-inner ring-1 ring-inset ring-slate-100/30">
                        </div>
                        <div class="w-full border-t border-dashed border-slate-200 mx-5"></div>
                    </div> <!-- Price Part (Bottom Part of Index Card Style) -->
                    <div class="p-5 bg-slate-50/40 rounded-b-2xl"> <span
                            class="text-[10px] text-slate-400 font-[800] uppercase tracking-wider block mb-1">Entry
                            Fee</span>
                        <div class="text-[22px] font-[900] text-[#003366] leading-none font-['Plus_Jakarta_Sans']">Rp
                            {{ number_format($ticket->price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <form id="registrationForm" action="{{ route('komunitas.register') }}" method="POST"> @csrf <input type="hidden"
                    name="ticket_id" value="{{ $ticket->id }}"> <!-- General Error Alert (Duplicate, etc) -->
                @if ($errors->has('duplicate') || $errors->has('midtrans'))
                    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                        <div class="flex items-center gap-3"> <svg class="w-5 h-5 text-red-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xs font-bold text-red-700 uppercase tracking-tight">
                                {{ $errors->first('duplicate') ?: $errors->first('midtrans') }} </p>
                        </div>
                    </div>
                @endif <!-- Form Fields -->
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
                    <div> <x-label for="email">Alamat Email *</x-label> <x-input type="email" id="email"
                            name="email" placeholder="nama@email.com" required value="{{ old('email') }}"
                            class="{{ $errors->has('email') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('email')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                        </p>@else<p
                                class="mt-2 text-[10px] text-[#E8630A] font-bold uppercase tracking-wider leading-tight">
                                Gunakan email asli untuk mendapatkan notifikasi invoice</p>
                        @enderror
                    </div>
                    <div> <x-label for="email_confirmation">Konfirmasi Alamat Email *</x-label> <x-input type="email"
                            id="email_confirmation" name="email_confirmation" placeholder="Ketik ulang email Anda"
                            required value="{{ old('email_confirmation') }}"
                            class="{{ $errors->has('email_confirmation') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('email_confirmation')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div> <x-label for="phone_number">Nomor WhatsApp *</x-label> <x-input id="phone_number"
                            name="phone_number" placeholder="08xxxxxxxxx" required :numeric="true" maxlength="14"
                            value="{{ old('phone_number') }}"
                            class="{{ $errors->has('phone_number') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('phone_number')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @else
                            <p class="mt-2 text-[10px] text-[#E8630A] font-bold uppercase tracking-wider leading-tight">
                                Gunakan nomor WhatsApp aktif untuk mendapatkan notifikasi pendaftaran & pembayaran</p>
                        @enderror
                    </div>
                    <div> <x-label for="nik">NIK KTP *</x-label> <x-input id="nik" name="nik"
                            placeholder="16 digit NIK" required :numeric="true" maxlength="16"
                            value="{{ old('nik') }}"
                            class="{{ $errors->has('nik') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('nik')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
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
                        @error('sex')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div> <x-label for="blood_type">Golongan Darah *</x-label> <x-select id="blood_type"
                            name="blood_type" required :options="[
                                'A' => 'A',
                                'A+' => 'A+',
                                'A-' => 'A-',
                                'B' => 'B',
                                'B+' => 'B+',
                                'B-' => 'B-',
                                'AB' => 'AB',
                                'AB+' => 'AB+',
                                'AB-' => 'AB-',
                                'O' => 'O',
                                'O+' => 'O+',
                                'O-' => 'O-',
                            ]" :selected="old('blood_type')"
                            class="{{ $errors->has('blood_type') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('blood_type')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">
                                {{ $message }}
                            </p>
                        @enderror
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
                        <x-select id="jersey_size" name="jersey_size" required :options="[
                            'XS' => 'XS',
                            'S' => 'S',
                            'M' => 'M',
                            'L' => 'L',
                            'XL' => 'XL',
                            '2XL' => '2XL',
                            '3XL' => '3XL',
                            '4XL' => '4XL',
                            '5XL' => '5XL',
                        ]" :selected="old('jersey_size')"
                            class="{{ $errors->has('jersey_size') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('jersey_size')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">
                                {{ $message }}</p>
                        @enderror
                    </div>
                    <div id="nimSection" class="hidden"> <x-label for="nim_nrp">NIM/NRP</x-label>
                        <x-input id="nim_nrp" name="nim_nrp" placeholder="Khusus Mahasiswa/Alumni IPB"
                            value="{{ old('nim_nrp') }}" minlength="6"
                            class="{{ $errors->has('nim_nrp') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('nim_nrp')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">
                                {{ $message }}</p>
                        @else
                            <p class="mt-2 text-[10px] text-[#E8630A] font-bold uppercase tracking-wider leading-tight">
                                Minimal 6 karakter. Contoh: G64150001 atau A123456</p>
                        @enderror
                    </div>
                    <div> <x-label for="nationality">Kewarganegaraan</x-label> <x-input id="nationality"
                            name="nationality" value="{{ old('nationality', 'WNI') }}" required
                            class="{{ $errors->has('nationality') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('nationality')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">
                                {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2"> <x-label for="address">Alamat Lengkap *</x-label> <x-textarea
                            id="address" name="address" rows="2" placeholder="Alamat pengiriman/domisili"
                            required
                            class="{{ $errors->has('address') ? '!border-red-500 ring-4 ring-red-50' : '' }}">{{ old('address') }}</x-textarea>
                        @error('address')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">
                                {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2 mt-4">
                        <h3
                            class="text-sm font-black text-[#003366] uppercase tracking-[2px] pb-2 border-b border-slate-100">
                            Optional Running Data</h3>
                    </div>
                    <div> <x-label for="running_community">Tergabung dalam komunitas lari? Sebutkan.
                            (Optional)</x-label> <x-input id="running_community" name="running_community"
                            placeholder="Nama komunitas (jika ada)" value="{{ old('running_community') }}" /> </div>
                    <div> <x-label for="best_time">Best time yang pernah didapat? (Optional)</x-label> <x-input
                            id="best_time" name="best_time" placeholder="HH:MM:SS (Contoh: 00:55:20)"
                            value="{{ old('best_time') }}" maxlength="8" /> </div>
                    <div class="md:col-span-2"> <x-label for="previous_events">Pernah mengikuti event lari dan
                            kategori apa saja? (Optional)</x-label> <x-textarea id="previous_events"
                            name="previous_events" rows="2"
                            placeholder="Sebutkan event yang pernah diikuti sebelumnya">{{ old('previous_events') }}</x-textarea>
                    </div>
                    <div class="md:col-span-2">
                        <x-label for="medical_condition">Kondisi Medis (Optional)</x-label>
                        <x-textarea id="medical_condition" name="medical_condition" rows="2"
                            placeholder="Sebutkan kondisi medis yang perlu diketahui oleh panitia (jika ada)">{{ old('medical_condition') }}</x-textarea>
                    </div>
                    <div> <x-label for="shuttle_bus">Pilih terminal untuk naik shuttle bus? (Optional)</x-label>
                        <x-select id="shuttle_bus" name="shuttle_bus" :options="[
                            '' => 'Tidak Menggunakan',
                            'Mall Botani Square' => 'Mall Botani Square',
                            'Terminal Bubulak' => 'Terminal Bubulak',
                        ]" :selected="old('shuttle_bus')" />
                    </div>
                    <div class="md:col-span-2 mt-4">
                        <h3
                            class="text-sm font-black text-[#003366] uppercase tracking-[2px] pb-2 border-b border-slate-100">
                            Emergency Contact</h3>
                    </div>
                    <div> <x-label for="emergency_contact_name">Kontak Darurat *</x-label> <x-input
                            id="emergency_contact_name" name="emergency_contact_name"
                            placeholder="Nama keluarga/kerabat" required value="{{ old('emergency_contact_name') }}"
                            class="{{ $errors->has('emergency_contact_name') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('emergency_contact_name')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">
                                {{ $message }}</p>
                        @enderror
                    </div>
                    <div> <x-label for="emergency_contact_phone_number">Nomor Darurat *</x-label> <x-input
                            id="emergency_contact_phone_number" name="emergency_contact_phone_number"
                            placeholder="08xxxxxxxxx" required :numeric="true"
                            value="{{ old('emergency_contact_phone_number') }}"
                            class="{{ $errors->has('emergency_contact_phone_number') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('emergency_contact_phone_number')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">
                                {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <x-label for="emergency_contact_relationship">Hubungan *</x-label>
                        <x-select id="emergency_contact_relationship" name="emergency_contact_relationship" required
                            :options="[
                                'Orang Tua' => 'Orang Tua',
                                'Suami' => 'Suami',
                                'Istri' => 'Istri',
                                'Anak' => 'Anak',
                                'Saudara' => 'Saudara',
                                'Teman' => 'Teman',
                            ]" :selected="old('emergency_contact_relationship')"
                            class="{{ $errors->has('emergency_contact_relationship') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('emergency_contact_relationship')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">
                                {{ $message }}</p>
                        @enderror
                    </div>
                </div> <!-- Other Race Interest Section --> @php
                    $categoryName = strtoupper($ticket->category->name ?? '');
                    $pairCategory = '';
                    if (str_contains($categoryName, '5K') || str_contains($categoryName, '42K')) {
                        $pairCategory = '10K (Minggu)';
                    } elseif (str_contains($categoryName, '10K') || str_contains($categoryName, '21K')) {
                        $pairCategory = '5K (Sabtu)';
                    }
                @endphp @if ($pairTicket)
                    <div class="mt-12 bg-orange-50/50 border border-orange-100 p-8 rounded-2xl {{ $isPairSoldOut ? 'opacity-60' : '' }}">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="space-y-1">
                                <p
                                    class="text-[11px] font-bold text-[#E8630A]/80 uppercase tracking-widest leading-loose">
                                    APAKAH ANDA INGIN MENGIKUTI KATEGORI <span
                                        class="text-[#E8630A] underline underline-offset-4 decoration-2">{{ $pairCategory }}</span>
                                    JUGA? 
                                    @if($isPairSoldOut)
                                        <span class="ml-2 px-2 py-0.5 bg-rose-500 text-white text-[9px] rounded font-black italic">KUOTA HABIS</span>
                                    @endif
                                </p>
                            </div> <label class="relative inline-flex items-center {{ $isPairSoldOut ? 'cursor-not-allowed' : 'cursor-pointer' }} group"> <input
                                    type="checkbox" name="other_race_interest" id="cb_second_ticket"
                                    value="{{ $pairCategory }}" class="sr-only peer"
                                    {{ old('other_race_interest') ? 'checked' : '' }}
                                    {{ $isPairSoldOut ? 'disabled' : '' }}>
                                <div
                                    class="w-20 h-10 {{ $isPairSoldOut ? 'bg-slate-300' : 'bg-slate-200' }} peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-10 after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-8 after:w-8 after:transition-all peer-checked:bg-[#FF7A21] shadow-inner ring-4 ring-slate-100 peer-checked:ring-orange-100">
                                </div> 
                                <span class="ml-4 text-xs font-black text-slate-400 peer-checked:hidden uppercase tracking-widest transition-colors">TIDAK</span>
                                <span class="ml-4 hidden peer-checked:inline text-xs font-black text-[#FF7A21] uppercase tracking-widest transition-colors">YA, IKUT!</span>
                            </label>
                        </div>
                        <div class="mt-6 pt-6 border-t border-orange-100/50">
                            <p
                                class="text-[9px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed italic">
                                * Pilihan ini bersifat opsional. Jika dipilih, data Anda akan tercatat sebagai peminat
                                kategori {{ $pairCategory }}. </p>
                        </div>
                    </div>
                @endif <!-- Donation Section -->
                <div id="donateSection" class="hidden mt-10">
                    <h3
                        class="text-sm font-black text-[#003366] uppercase tracking-[2px] mb-6 pb-2 border-b border-slate-100">
                        Donation (Optional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div> <x-label for="donation_event">Donasi Event</x-label> <x-select id="donation_event"
                                name="donation_event" :options="[
                                    '0' => 'Tidak, Terima Kasih',
                                    '50000' => 'Rp 50.000',
                                    '100000' => 'Rp 100.000',
                                    '250000' => 'Rp 250.000',
                                    '500000' => 'Rp 500.000',
                                ]" :selected="old('donation_event')" /> </div>
                        <div> <x-label for="donation_scholarship">Donasi Beasiswa</x-label> <x-select
                                id="donation_scholarship" name="donation_scholarship" :options="[
                                    '0' => 'Tidak, Terima Kasih',
                                    '50000' => 'Rp 50.000',
                                    '100000' => 'Rp 100.000',
                                    '250000' => 'Rp 250.000',
                                    '500000' => 'Rp 500.000',
                                ]"
                                :selected="old('donation_scholarship')" /> </div>
                    </div>
                </div> 

                <!-- Disclaimer Section -->
                <div class="mt-8">
                    <h3 class="text-sm font-black text-[#003366] uppercase tracking-[2px] mb-4 pb-2 border-b border-slate-100">
                        Persetujuan & Disclaimer
                    </h3>
                    <div class="space-y-0">
                        <label class="flex items-start gap-4 cursor-pointer group py-4 bg-slate-50/50 transition-all">
                            <div class="flex-shrink-0 mt-0.5">
                                <input type="checkbox" name="disclaimer_1" required class="w-5 h-5 rounded border-slate-300 text-[#003366] focus:ring-[#003366] cursor-pointer disclaimer-cb">
                            </div>
                            <span class="text-xs text-slate-600 leading-relaxed font-medium">
                                Saya sebagai peserta IPB RUN 2026 akan mematuhi ketentuan lomba & memahami kegiatan outdoor ini memliliki risiko kematian, cidera dll. Dan risiko yang timbul selama mengikuti kegiatan ini akibat tindakan yang tidak sesuai dengan aturan, ketentuan, dan arahan panitia menjadi tanggung jawab saya pribadi. Panitia penyelenggara dibebaskan dari segala tuntutan atas kejadian tersebut. <span class="text-red-500 font-black">*</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-4 cursor-pointer group py-4 bg-slate-50/50 transition-all">
                            <div class="flex-shrink-0 mt-0.5">
                                <input type="checkbox" name="disclaimer_2" required class="w-5 h-5 rounded border-slate-300 text-[#003366] focus:ring-[#003366] cursor-pointer disclaimer-cb">
                            </div>
                            <span class="text-xs text-slate-600 leading-relaxed font-medium">
                                Saya memberikan hak penuh kepada panitia untuk menggunakan foto atau video peserta selama acara untuk keperluan resmi tanpa tuntutan kompensasi. <span class="text-red-500 font-black">*</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-4 cursor-pointer group py-4 bg-slate-50/50 transition-all">
                            <div class="flex-shrink-0 mt-0.5">
                                <input type="checkbox" name="disclaimer_3" required class="w-5 h-5 rounded border-slate-300 text-[#003366] focus:ring-[#003366] cursor-pointer disclaimer-cb">
                            </div>
                            <span class="text-xs text-slate-600 leading-relaxed font-medium">
                                Saya menjamin bahwa seluruh data yang telah saya isikan pada formulir di atas adalah benar dan akurat. <span class="text-red-500 font-black">*</span>
                            </span>
                        </label>
                    </div>
                </div>



                <!-- Payment Summary (Consistent with Index Card Style) -->
                <div
                    class="mt-12 bg-white border border-slate-100 rounded-2xl overflow-hidden transition-all duration-300">
                    <div class="p-8 pb-1">
                        <h3
                            class="text-[15px] font-[800] text-[#003366] leading-tight font-['Plus_Jakarta_Sans'] uppercase tracking-tight mb-2">
                            Rekapitulasi Pembayaran </h3>
                        <p class="text-[10px] text-[#E8630A] font-[800] uppercase tracking-[0.5px] opacity-80 mb-4">
                            Total tagihan yang harus dibayarkan</p>
                    </div> <!-- Perforation Detail (Matched with Index) -->
                    <div class="relative flex items-center py-2 overflow-hidden pointer-events-none">
                        <div
                            class="absolute -left-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-100/30">
                        </div>
                        <div
                            class="absolute -right-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-100/30">
                        </div>
                        <div class="w-full border-t-2 border-dashed border-slate-200 mx-5"></div>
                    </div> <!-- Summary Content -->
                    <div class="p-8 pt-4 bg-slate-50/40 space-y-4">
                        <div class="space-y-3">
                            <!-- Ticket Row (Dynamic: shows voucher badges + price inline) -->
                            <div id="row_ticket_main" class="flex justify-between items-start text-sm gap-3">
                                <div class="flex-1 min-w-0">
                                    <span class="text-slate-500 font-medium italic block">Tiket {{ $ticket->category->name }}
                                        ({{ $ticket->name ?: strtoupper($ticket->type) }})</span>
                                    <div id="ticket_voucher_tags" class="mt-1.5 flex flex-wrap gap-1"></div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span id="ticket_price_original" class="text-[#003366] font-bold block">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                    <span id="ticket_price_final" class="hidden text-emerald-600 font-black block"></span>
                                </div>
                            </div>

                            @if ($pairTicket)
                                <div id="row_second_ticket"
                                    class="hidden flex justify-between items-start text-sm ring-2 ring-orange-100 bg-orange-50/30 p-2 rounded-lg gap-3">
                                    <div class="flex-1 min-w-0">
                                        <span class="text-[#E8630A] font-bold italic block">Tiket Tambahan:
                                            {{ $pairTicket->category->name }}
                                            ({{ $pairTicket->name ?: strtoupper($pairTicket->type) }})</span>
                                        <div id="ticket2_voucher_tags" class="mt-1.5 flex flex-wrap gap-1"></div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span id="ticket2_price_original" class="text-[#E8630A] font-bold block">Rp {{ number_format($pairTicket->price, 0, ',', '.') }}</span>
                                        <span id="ticket2_price_final" class="hidden text-emerald-600 font-black block"></span>
                                    </div>
                                </div>
                            @endif

                            <div id="row_donation_event" class="hidden flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium italic">Donasi Event</span> <span
                                    id="lbl_donation_event" class="text-[#E8630A] font-bold">Rp 0</span>
                            </div>
                            <div id="row_donation_scholarship"
                                class="hidden flex justify-between items-center text-sm"> <span
                                    class="text-slate-500 font-medium italic">Donasi Beasiswa</span> <span
                                    id="lbl_donation_scholarship" class="text-[#E8630A] font-bold">Rp 0</span>
                            </div>

                            <div class="flex justify-between items-center text-sm"> <span
                                    class="text-slate-500 font-medium italic">Biaya Layanan</span> <span
                                    class="text-[#003366] font-bold">Rp 4.500</span> </div>

                        </div>

                        <!-- Voucher Input Area (Multi Voucher) -->
                        <div class="mt-6 pt-6 border-t border-dashed border-slate-200" id="voucher_section">
                            <!-- Label Slot Voucher -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex flex-col">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Kode Voucher <span id="voucher_limit_label" class="text-emerald-500">(maks. 2 voucher)</span></p>
                                    <p class="text-[9px] font-bold text-orange-500 uppercase tracking-wider mt-0.5">Maks. 1 Voucher untuk 1 Kategori</p>
                                </div>
                                <div id="voucher_slots_indicator" class="flex gap-1">
                                    <div id="slot_dot_1" class="w-2 h-2 rounded-full bg-slate-200 transition-all"></div>
                                    <div id="slot_dot_2" class="w-2 h-2 rounded-full bg-slate-200 transition-all"></div>
                                </div>
                            </div>
                            <input type="hidden" id="applied_voucher_code_1" name="voucher_code">
                            <input type="hidden" id="applied_voucher_code_2" name="voucher_code_2">
                            <div class="flex flex-col md:flex-row gap-3">
                                <div class="flex-1 relative">
                                    <input type="text" id="voucher_input" placeholder="Masukkan kode voucher..." 
                                        class="w-full h-12 bg-white border-2 border-slate-100 rounded-xl px-4 font-black text-[#003366] focus:border-[#FF7A21] focus:ring-4 focus:ring-orange-500/10 transition-all outline-none uppercase tracking-widest text-xs placeholder:normal-case placeholder:font-medium placeholder:text-slate-300">
                                </div>
                                <button type="button" id="btn_apply_voucher"
                                    class="h-12 px-6 bg-[#003366] text-white rounded-xl font-black text-[10px] uppercase tracking-[2px] hover:bg-[#FF7A21] transition-all active:scale-95 shadow-lg shadow-blue-900/10">
                                    Pasang
                                </button>
                            </div>
                            <div id="voucher_message" class="mt-2 min-h-[14px]"></div>
                        </div>
                        <div
                            class="pt-5 border-t border-dashed border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <div class="flex flex-col"> <span
                                    class="text-[10px] text-slate-400 font-[800] uppercase tracking-wider block mb-1">Total
                                    Bayar</span>
                                <div id="lbl_total"
                                    class="text-[28px] font-[900] text-[#003366] leading-none font-['Plus_Jakarta_Sans']">
                                    Rp 0</div>
                            </div> <x-button type="submit" id="btn_submit"
                                class="w-full md:w-auto px-10 py-3.5 bg-[#003366] text-white rounded-xl font-[800] text-[15px] transition-all active:scale-95 hover:bg-[#002244] flex items-center justify-center">
                                Lanjut Pembayaran </x-button>
                        </div>
                    </div>
                </div>
                <div class="mt-8 text-center"> <a href="{{ url('/komunitas') }}"
                        class="inline-flex items-center gap-2 text-slate-400 font-bold text-sm hover:text-[#003366] transition-all">
                        ← Batal & Kembali </a> </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showSizeChart() {
            if (typeof Swal === 'undefined') {
                alert('Sedang memuat sistem... Silakan coba lagi dalam sekejap.');
                return;
            }
            Swal.fire({
                title: '<span class="text-[#003366] font-black uppercase tracking-tight text-2xl">Race Tee Size Chart</span>',
                html: `
                <div class="mt-6 flex flex-col md:flex-row items-center gap-8 px-2 text-left">
                    <div class="flex items-center justify-center bg-slate-50 p-6 rounded-3xl border border-slate-100 flex-1 w-full relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 opacity-[0.03] scale-150">
                            <svg class="w-24 h-24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.18l8 4v8.64l-8 4-8-4V8.18l8-4z"/></svg>
                        </div>
                        <div class="relative w-64 h-64 shrink-0">
                            <svg class="w-full h-full text-[#FF7A21]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12,2L4.5,4.7V9H6V22H18V9H19.5V4.7L12,2Z" />
                            </svg>
                            <div class="absolute top-[45%] left-5 w-[calc(100%-40px)] h-[1px] border-t-2 border-dashed border-white/80">
                                <span class="absolute -top-8 left-[80%] -translate-x-1/2 bg-[#003366] text-white text-[10px] font-black px-2 py-1 rounded-sm shadow-lg whitespace-nowrap">CHEST</span>
                            </div>
                            <div class="absolute top-4 left-1/2 w-[1px] h-[calc(100%-24px)] border-l-2 border-dashed border-white/80">
                                <span class="absolute bottom-0 right-4 bg-[#003366] text-white text-[10px] font-black px-1.5 py-1 rounded-sm shadow-lg whitespace-nowrap" style="writing-mode: vertical-rl; transform: rotate(180deg) translateX(-50%);">BODY LENGTH</span>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-hidden rounded-2xl border border-slate-100 flex-1 w-full bg-white shadow-sm font-['Plus_Jakarta_Sans']">
                        <table class="w-full text-center text-xs">
                            <thead class="bg-[#003366] text-white font-black uppercase tracking-widest leading-none">
                                <tr>
                                    <th class="px-3 py-4 text-[10px]">Size</th>
                                    <th class="px-3 py-4 text-[10px]">Chest (cm)</th>
                                    <th class="px-3 py-4 text-[10px]">Length (cm)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 font-black text-slate-700">
                                <tr><td class="px-3 py-3 bg-slate-50/50">XS</td><td class="px-3 py-3">47</td><td class="px-3 py-3">64</td></tr>
                                <tr class="bg-white"><td class="px-3 py-3 bg-slate-50/50">S</td><td class="px-3 py-3">49</td><td class="px-3 py-3">66</td></tr>
                                <tr><td class="px-3 py-3 bg-slate-50/50">M</td><td class="px-3 py-3">51</td><td class="px-3 py-3">68</td></tr>
                                <tr class="bg-white"><td class="px-3 py-3 bg-slate-50/50">L</td><td class="px-3 py-3">53</td><td class="px-3 py-3">70</td></tr>
                                <tr><td class="px-3 py-3 bg-slate-50/50">XL</td><td class="px-3 py-3">55</td><td class="px-3 py-3">72</td></tr>
                                <tr class="bg-white"><td class="px-3 py-3 bg-slate-50/50">2XL</td><td class="px-3 py-3">57</td><td class="px-3 py-3">74</td></tr>
                                <tr><td class="px-3 py-3 bg-slate-50/50">3XL</td><td class="px-3 py-3">59</td><td class="px-3 py-3">76</td></tr>
                                <tr class="bg-white"><td class="px-3 py-3 bg-slate-50/50">4XL</td><td class="px-3 py-3">61</td><td class="px-3 py-3">78</td></tr>
                                <tr><td class="px-3 py-3 bg-slate-50/50">5XL</td><td class="px-3 py-3">64</td><td class="px-3 py-3">80</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-8 px-4 py-3 bg-blue-50/50 rounded-xl border border-blue-100 flex items-center justify-center gap-3">
                    <svg class="w-4 h-4 text-[#003366] opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-[9.5px] text-[#003366] font-black uppercase tracking-widest opacity-80 italic leading-snug">
                        * Toleransi ukuran ±1-2 cm. Pastikan ukuran yang Anda pilih sudah sesuai.
                    </p>
                </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'KONFIRMASI UKURAN',
                confirmButtonColor: '#003366',
                width: '740px',
                padding: '1.5rem',
                customClass: {
                    popup: 'rounded-[1.5rem] border border-slate-100 shadow-2xl',
                    confirmButton: 'rounded-xl px-12 py-4 font-black uppercase tracking-widest text-[11px] transition-all hover:scale-105'
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                @php
                    $allErrors = $errors->all();
                    $errorMsg = 'Silakan periksa kembali isian formulir Anda yang berwarna merah.';
                    if (count($allErrors) == 1) {
                        $errorMsg = $allErrors[0];
                    }
                @endphp
                Swal.fire({
                    icon: 'error',
                    title: 'Pendaftaran Gagal',
                    text: '{!! $errorMsg !!}',
                    confirmButtonColor: '#003366',
                    confirmButtonText: 'OKE, SAYA MENGERTI'
                }).then(() => {
                    const firstError = document.querySelector('.text-red-500, .border-red-500');
                    if (firstError) firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                });
            @endif

            const birthPicker = flatpickr(".datepicker", {
                locale: "id",
                dateFormat: "d-m-Y",
                maxDate: "today",
                allowInput: true,
                clickOpens: false,
                disableMobile: "true"
            });

            const birthInput = document.getElementById('date_birth');
            if (birthInput) {
                birthInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 8) value = value.slice(0, 8);
                    let formattedValue = '';
                    if (value.length > 0) {
                        formattedValue = value.slice(0, 2);
                        if (value.length > 2) {
                            formattedValue += '-' + value.slice(2, 4);
                            if (value.length > 4) {
                                formattedValue += '-' + value.slice(4, 8);
                            }
                        }
                    }
                    e.target.value = formattedValue;
                });
            }

            const bestTimeInput = document.getElementById('best_time');
            if (bestTimeInput) {
                bestTimeInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 6) value = value.slice(0, 6);
                    let formattedValue = '';
                    if (value.length > 0) {
                        formattedValue = value.slice(0, 2);
                        if (value.length > 2) {
                            formattedValue += ':' + value.slice(2, 4);
                            if (value.length > 4) {
                                formattedValue += ':' + value.slice(4, 6);
                            }
                        }
                    }
                    e.target.value = formattedValue;
                });
            }

            const ticketPrice = {{ $ticket->price }};
            const adminFee = 4500;
            const isIPB = "{{ $ticket->type }}" === "ipb";
            const pairTicketPrice = {{ $pairTicket->price ?? 0 }};
            const pairTicketId = "{{ $pairTicket->id ?? '' }}";
            const nimInput = document.getElementById('nim_nrp');

            // Multi-Voucher State (max 2) — tiap voucher punya targetTicket (1 atau 2)
            let appliedVouchers = []; // { code, type, value, targetTicket, discount }
            let pendingVoucher = null; // voucher tervalidasi menunggu pemilihan target

            if (isIPB) {
                document.getElementById('donateSection')?.classList.remove('hidden');
                document.getElementById('nimSection')?.classList.remove('hidden');
                nimInput?.setAttribute('required', 'required');
            } else {
                if (nimInput) nimInput.value = '';
                nimInput?.removeAttribute('required');
            }

            function getTicketSubtotal() {
                const isSecondChecked = document.getElementById('cb_second_ticket')?.checked || false;
                return ticketPrice + (isSecondChecked ? pairTicketPrice : 0);
            }

            function updateSlotDots() {
                const dot1 = document.getElementById('slot_dot_1');
                const dot2 = document.getElementById('slot_dot_2');
                const limitLabel = document.getElementById('voucher_limit_label');
                const isSecondChecked = document.getElementById('cb_second_ticket')?.checked || false;
                const limit = isSecondChecked ? 2 : 1;

                if (limitLabel) limitLabel.innerText = `(maks. ${limit} voucher)`;

                const count = appliedVouchers.length;
                if (dot1) dot1.className = 'w-2 h-2 rounded-full transition-all ' + (count >= 1 ? 'bg-emerald-500 scale-125' : 'bg-slate-200');
                
                if (dot2) {
                    if (limit === 1) {
                        dot2.classList.add('hidden');
                    } else {
                        dot2.classList.remove('hidden');
                        dot2.className = 'w-2 h-2 rounded-full transition-all ' + (count >= 2 ? 'bg-teal-500 scale-125' : 'bg-slate-200');
                    }
                }
            }

            // Helper: hitung diskon untuk 1 voucher terhadap harga tiket tertentu
            function syncVoucherInputState() {
                const inputEl = document.getElementById('voucher_input');
                const btn = document.getElementById('btn_apply_voucher');
                const messageEl = document.getElementById('voucher_message');
                const isSecondChecked = document.getElementById('cb_second_ticket')?.checked || false;
                const limit = isSecondChecked ? 2 : 1;
                const count = appliedVouchers.length;

                if (count >= limit) {
                    inputEl.disabled = true;
                    inputEl.placeholder = `Maks. ${limit} voucher sudah terpasang`;
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    btn.classList.remove('hover:bg-[#FF7A21]');
                } else {
                    inputEl.disabled = false;
                    inputEl.placeholder = count === 0 ? 'Masukkan kode voucher...' : `Masukkan voucher ke-${count + 1}...`;
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    btn.classList.add('hover:bg-[#FF7A21]');
                    btn.innerText = 'Pasang';
                }
            }

            function calcDisc(v, basePrice, alreadyDiscounted) {
                if (v.type === 'nominal') return Math.min(v.value, Math.max(0, basePrice - alreadyDiscounted));
                if (v.type === 'percentage') return Math.floor(basePrice * (v.value / 100));
                return 0;
            }

            // Helper: buat HTML badge voucher
            function voucherBadgeHTML(v, colorClass) {
                const discLabel = v.type === 'percentage'
                    ? `(${v.value}%) −Rp ${v.discount.toLocaleString('id-ID')}`
                    : `−Rp ${v.discount.toLocaleString('id-ID')}`;
                return `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wide ${colorClass}">
                    <svg class="w-2.5 h-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    ${v.code} ${discLabel}
                </span>`;
            }

            // Helper: render row tiket dengan badge+harga
            function renderTicketRow(tagsId, origId, finalId, basePrice, matchFn) {
                const tagsEl = document.getElementById(tagsId);
                const origEl = document.getElementById(origId);
                const finalEl = document.getElementById(finalId);

                const myVouchers = appliedVouchers.filter(matchFn);
                const badgeColors = ['bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200', 'bg-teal-50 text-teal-700 ring-1 ring-teal-200'];

                // Hitung diskon per tiket
                let disc1 = 0;
                myVouchers.forEach((v, i) => {
                    v.discount = calcDisc(v, basePrice, disc1);
                    disc1 += v.discount;
                });

                if (myVouchers.length > 0 && tagsEl) {
                    tagsEl.innerHTML = myVouchers.map((v, i) => voucherBadgeHTML(v, badgeColors[i])).join('');
                    if (origEl) origEl.innerHTML = `<span class="line-through text-slate-400 font-medium text-xs">Rp ${basePrice.toLocaleString('id-ID')}</span>`;
                    if (finalEl) { finalEl.classList.remove('hidden'); finalEl.innerText = 'Rp ' + (basePrice - disc1).toLocaleString('id-ID'); }
                } else {
                    if (tagsEl) tagsEl.innerHTML = '';
                    if (origEl) origEl.innerHTML = 'Rp ' + basePrice.toLocaleString('id-ID');
                    if (finalEl) finalEl.classList.add('hidden');
                }

                return disc1;
            }

            function updateTotal() {
                let donEvent = parseInt(document.getElementById('donation_event')?.value || 0);
                let donScholar = parseInt(document.getElementById('donation_scholarship')?.value || 0);
                let isSecondChecked = document.getElementById('cb_second_ticket')?.checked || false;

                if (document.getElementById('row_donation_event')) {
                    if (donEvent > 0) {
                        document.getElementById('row_donation_event').classList.remove('hidden');
                        document.getElementById('lbl_donation_event').innerText = 'Rp ' + donEvent.toLocaleString('id-ID');
                    } else {
                        document.getElementById('row_donation_event').classList.add('hidden');
                    }
                }

                if (document.getElementById('row_donation_scholarship')) {
                    if (donScholar > 0) {
                        document.getElementById('row_donation_scholarship').classList.remove('hidden');
                        document.getElementById('lbl_donation_scholarship').innerText = 'Rp ' + donScholar.toLocaleString('id-ID');
                    } else {
                        document.getElementById('row_donation_scholarship').classList.add('hidden');
                    }
                }

                const summarySecond = document.getElementById('row_second_ticket');
                if (isSecondChecked) {
                    summarySecond?.classList.remove('hidden');
                } else {
                    summarySecond?.classList.add('hidden');
                    // Reset vouchers assigned to ticket 2 kalau ticket 2 tidak dipilih
                }

                // Render Tiket 1
                const disc1 = renderTicketRow('ticket_voucher_tags', 'ticket_price_original', 'ticket_price_final',
                    ticketPrice, v => v.targetTicket === 1);

                // Render Tiket 2 (pair ticket)
                let disc2 = 0;
                if (isSecondChecked && pairTicketPrice > 0) {
                    disc2 = renderTicketRow('ticket2_voucher_tags', 'ticket2_price_original', 'ticket2_price_final',
                        pairTicketPrice, v => v.targetTicket === 2);
                }

                updateSlotDots();

                const ticketSubtotal = ticketPrice + (isSecondChecked ? pairTicketPrice : 0);
                const total = ticketSubtotal - disc1 - disc2 + adminFee + donEvent + donScholar;
                document.getElementById('lbl_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
            }

            document.getElementById('donation_event')?.addEventListener('change', updateTotal);
            document.getElementById('donation_scholarship')?.addEventListener('change', updateTotal);
            document.getElementById('cb_second_ticket')?.addEventListener('change', function() {
                if (!this.checked) {
                    appliedVouchers = appliedVouchers.filter(v => v.targetTicket !== 2);
                    
                    document.getElementById('applied_voucher_code_1').value = appliedVouchers.length > 0 ? appliedVouchers[0].code : '';
                    document.getElementById('applied_voucher_code_2').value = appliedVouchers.length > 1 ? appliedVouchers[1].code : '';

                    const messageEl = document.getElementById('voucher_message');
                    if (appliedVouchers.length < (this.checked ? 2 : 1)) {
                        messageEl.innerHTML = '';
                    }
                }
                syncVoucherInputState();
                updateTotal();
            });

            // Voucher Logic (Community Specific - Per Ticket Target)
            const nikInputForVoucher = document.getElementById('nik');
            if (nikInputForVoucher) {
                nikInputForVoucher.addEventListener('input', function() {
                    if (this.value.length === 16 && appliedVouchers.length === 0) {
                        applyVoucher(null, this.value);
                    }
                });
            }

            function commitVoucher(voucherData, targetTicket) {
                appliedVouchers.push({ ...voucherData, targetTicket });
                if (appliedVouchers.length === 1) document.getElementById('applied_voucher_code_1').value = voucherData.code;
                else if (appliedVouchers.length === 2) document.getElementById('applied_voucher_code_2').value = voucherData.code;

                const messageEl = document.getElementById('voucher_message');
                const isSecondChecked = document.getElementById('cb_second_ticket')?.checked || false;
                const limit = isSecondChecked ? 2 : 1;
                const remaining = limit - appliedVouchers.length;

                if (remaining > 0) {
                    messageEl.innerHTML = `<span class="text-[10px] font-black uppercase text-emerald-600 tracking-widest">✓ Terpasang ke Tiket ${targetTicket}! Sisa slot: ${remaining}</span>`;
                } else {
                    messageEl.innerHTML = `<span class="text-[10px] font-black uppercase text-emerald-600 tracking-widest">✓ ${limit} Voucher berhasil dipasang!</span>`;
                }

                syncVoucherInputState();
                updateTotal();
            }

            async function applyVoucher(code = null, nik = null) {
                const isSecondChecked = document.getElementById('cb_second_ticket')?.checked || false;
                const limit = isSecondChecked ? 2 : 1;

                if (appliedVouchers.length >= limit) {
                    document.getElementById('voucher_message').innerHTML = `<span class="text-[10px] font-black uppercase text-amber-500 tracking-widest">Maksimal ${limit} voucher sudah dipasang.</span>`;
                    return;
                }

                const inputEl = document.getElementById('voucher_input');
                const voucherCode = (code || inputEl.value || '').trim().toUpperCase();
                const messageEl = document.getElementById('voucher_message');
                const btn = document.getElementById('btn_apply_voucher');
                const nikValue = nik || nikInputForVoucher?.value;

                if (!voucherCode && !nikValue) return;

                if (voucherCode && appliedVouchers.some(v => v.code === voucherCode)) {
                    messageEl.innerHTML = '<span class="text-[10px] font-black uppercase text-amber-500 tracking-widest">Voucher ini sudah dipasang.</span>';
                    return;
                }

                btn.disabled = true; btn.innerText = '...';

                try {
                    const isSecondChecked = document.getElementById('cb_second_ticket')?.checked || false;
                    
                    // Coba ke Tiket 1 dulu
                    let checkT1 = true;
                    // Jika tiket 1 sudah punya voucher, coba cek tiket 2 dulu jika ada
                    if (appliedVouchers.some(v => v.targetTicket === 1) && isSecondChecked && pairTicketId) {
                        checkT1 = false;
                    }

                    let validData = null;
                    let targetFound = 0;
                    let errorMsg = '';

                    if (checkT1) {
                        const res1 = await fetch('{{ route("komunitas.check-voucher") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ code: voucherCode, nik: nikValue, price: ticketPrice, ticket_id: '{{ $ticket->id }}', existing_codes: appliedVouchers.map(v => v.code) })
                        });
                        const data1 = await res1.json();
                        if (data1.valid) {
                            validData = data1;
                            targetFound = 1;
                        } else {
                            errorMsg = data1.message;
                        }
                    }

                    // Jika gagal t1 atau t1 sudah ada voucher, coba t2
                    if (!targetFound && isSecondChecked && pairTicketId) {
                        const res2 = await fetch('{{ route("komunitas.check-voucher") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ code: voucherCode, nik: nikValue, price: pairTicketPrice, ticket_id: pairTicketId, existing_codes: appliedVouchers.map(v => v.code) })
                        });
                        const data2 = await res2.json();
                        if (data2.valid) {
                            validData = data2;
                            targetFound = 2;
                        } else {
                            if (data2.message.includes('sudah') || !errorMsg) errorMsg = data2.message;
                        }
                    }

                    // Jika masih belum ketemu tapi t1 tadi di-skip, coba t1 sebagai fallback terakhir
                    if (!targetFound && !checkT1) {
                         const res1 = await fetch('{{ route("komunitas.check-voucher") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ code: voucherCode, nik: nikValue, price: ticketPrice, ticket_id: '{{ $ticket->id }}', existing_codes: appliedVouchers.map(v => v.code) })
                        });
                        const data1 = await res1.json();
                        if (data1.valid) {
                            validData = data1;
                            targetFound = 1;
                        } else {
                            errorMsg = data1.message;
                        }
                    } 
                    
                    if (targetFound) {
                        commitVoucher({ code: validData.code, type: validData.type, value: validData.value, discount: 0 }, targetFound);
                    } else {
                        messageEl.innerHTML = `<span class="text-[10px] font-black uppercase text-rose-500 tracking-widest">${errorMsg || 'Voucher tidak cocok untuk tiket yang dipilih.'}</span>`;
                    }

                } catch (e) {
                    messageEl.innerHTML = '<span class="text-[10px] font-black uppercase text-rose-500 tracking-widest">Error mengecek voucher</span>';
                } finally {
                    if (appliedVouchers.length < 2) {
                        btn.disabled = false; btn.innerText = 'Pasang';
                    }
                }
            }

            document.getElementById('btn_apply_voucher')?.addEventListener('click', () => applyVoucher());

            // Enter key on input
            document.getElementById('voucher_input')?.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); applyVoucher(); }
            });

            updateTotal();

            const disclaimers = document.querySelectorAll('.disclaimer-cb');
            const submitBtn = document.getElementById('btn_submit');

            function checkDisclaimers() {
                let allChecked = true;
                disclaimers.forEach(cb => { if (!cb.checked) allChecked = false; });
                if (submitBtn) {
                    if (allChecked) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        submitBtn.classList.add('hover:bg-[#002244]', 'active:scale-95');
                    } else {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        submitBtn.classList.remove('hover:bg-[#002244]', 'active:scale-95');
                    }
                }
            }
            if (disclaimers.length > 0) {
                disclaimers.forEach(cb => cb.addEventListener('change', checkDisclaimers));
                checkDisclaimers();
            }
        });
    </script>
</x-layouts.app>
