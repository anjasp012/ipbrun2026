<x-layouts.app>
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <!-- Main Form Container -->
        <div
            class="max-w-[1000px] w-full bg-white border border-slate-100 rounded-2xl shadow-sm md:p-14 p-8 transition-all duration-300">
            <div class="mb-10 text-center">
                <h1 class="text-3xl font-[800] text-[#003366] uppercase tracking-tight">Formulir Data Pelari
                    (Sponsorship)</h1>
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
            <form id="registrationForm" action="{{ route('sponsorship.register') }}" method="POST"> @csrf
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                @if ($errors->any())
                    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xs font-bold text-red-700 uppercase tracking-tight">
                                Terjadi kesalahan pada pengisian form. Silakan cek kembali.
                            </p>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2"> <x-label for="name">Nama Lengkap (Sesuai KTP) *</x-label> <x-input
                            id="name" name="name" placeholder="Ketik nama lengkap Anda" required
                            value="{{ old('name') }}" />
                        @error('name')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div> <x-label for="email">Alamat Email *</x-label> <x-input type="email" id="email"
                            name="email" placeholder="nama@email.com" required value="{{ old('email') }}" />
                        @error('email')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div> <x-label for="email_confirmation">Konfirmasi Alamat Email *</x-label> <x-input type="email"
                            id="email_confirmation" name="email_confirmation" placeholder="Ketik ulang email Anda"
                            required value="{{ old('email_confirmation') }}" />
                    </div>
                    <div> <x-label for="phone_number">Nomor WhatsApp *</x-label> <x-input id="phone_number"
                            name="phone_number" placeholder="08xxxxxxxxx" required :numeric="true" maxlength="14"
                            value="{{ old('phone_number') }}" />
                        @error('phone_number')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div> <x-label for="nik">NIK KTP *</x-label> <x-input id="nik" name="nik"
                            placeholder="16 digit NIK" required :numeric="true" maxlength="16"
                            value="{{ old('nik') }}" />
                        @error('nik')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div> <x-label for="date_birth">Tanggal Lahir *</x-label> <x-input id="date_birth" name="date_birth"
                            class="datepicker" placeholder="DD-MM-YYYY" required value="{{ old('date_birth') }}" />
                        @error('date_birth')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div> <x-label for="sex">Jenis Kelamin *</x-label> <x-select id="sex" name="sex"
                            required :options="['male' => 'Laki-laki', 'female' => 'Perempuan']" :selected="old('sex')" />
                        @error('sex')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div> <x-label for="blood_type">Golongan Darah *</x-label> <x-select id="blood_type"
                            name="blood_type" required :options="['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O']" :selected="old('blood_type')" />
                    </div>
                    <div> <x-label for="jersey_size">Ukuran Jersey *</x-label> <x-select id="jersey_size"
                            name="jersey_size" required :options="[
                                'XS' => 'XS',
                                'S' => 'S',
                                'M' => 'M',
                                'L' => 'L',
                                'XL' => 'XL',
                                '2XL' => '2XL',
                                '3XL' => '3XL',
                                '4XL' => '4XL',
                                '5XL' => '5XL',
                            ]" :selected="old('jersey_size')" />
                    </div>
                    <div> <x-label for="nationality">Kewarganegaraan</x-label> <x-input id="nationality"
                            name="nationality" value="{{ old('nationality', 'WNI') }}" required /> </div>
                    <div class="md:col-span-2"> <x-label for="address">Alamat Lengkap *</x-label> <x-textarea
                            id="address" name="address" rows="2" placeholder="Alamat pengiriman/domisili"
                            required>{{ old('address') }}</x-textarea>
                    </div>
                    <div class="md:col-span-2 mt-4">
                        <h3
                            class="text-sm font-black text-[#003366] uppercase tracking-[2px] pb-2 border-b border-slate-100">
                            Emergency Contact</h3>
                    </div>
                    <div> <x-label for="emergency_contact_name">Kontak Darurat *</x-label> <x-input
                            id="emergency_contact_name" name="emergency_contact_name"
                            placeholder="Nama keluarga/kerabat" required
                            value="{{ old('emergency_contact_name') }}" />
                    </div>
                    <div> <x-label for="emergency_contact_phone_number">Nomor Darurat *</x-label> <x-input
                            id="emergency_contact_phone_number" name="emergency_contact_phone_number"
                            placeholder="08xxxxxxxxx" required :numeric="true"
                            value="{{ old('emergency_contact_phone_number') }}" />
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
                            ]" :selected="old('emergency_contact_relationship')" />
                    </div>
                </div>



                <!-- Disclaimer Section -->
                <div class="mt-8">
                    <h3
                        class="text-sm font-black text-[#003366] uppercase tracking-[2px] mb-4 pb-2 border-b border-slate-100">
                        Persetujuan & Disclaimer
                    </h3>
                    <div class="space-y-0">
                        <label class="flex items-start gap-4 cursor-pointer group py-4 bg-slate-50/50 transition-all">
                            <div class="flex-shrink-0 mt-0.5">
                                <input type="checkbox" name="disclaimer_1" required
                                    class="w-5 h-5 rounded border-slate-300 text-[#003366] focus:ring-[#003366] cursor-pointer disclaimer-cb">
                            </div>
                            <span class="text-xs text-slate-600 leading-relaxed font-medium">
                                Saya sebagai peserta IPB RUN 2026 akan mematuhi ketentuan lomba & memahami kegiatan
                                outdoor ini memliliki risiko kematian, cidera dll. Dan risiko yang timbul selama
                                mengikuti kegiatan ini akibat tindakan yang tidak sesuai dengan aturan, ketentuan, dan
                                arahan panitia menjadi tanggung jawab saya pribadi. Panitia penyelenggara dibebaskan
                                dari segala tuntutan atas kejadian tersebut. <span
                                    class="text-red-500 font-black">*</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-4 cursor-pointer group py-4 bg-slate-50/50 transition-all">
                            <div class="flex-shrink-0 mt-0.5">
                                <input type="checkbox" name="disclaimer_2" required
                                    class="w-5 h-5 rounded border-slate-300 text-[#003366] focus:ring-[#003366] cursor-pointer disclaimer-cb">
                            </div>
                            <span class="text-xs text-slate-600 leading-relaxed font-medium">
                                Saya memberikan hak penuh kepada panitia untuk menggunakan foto atau video peserta
                                selama acara untuk keperluan resmi tanpa tuntutan kompensasi. <span
                                    class="text-red-500 font-black">*</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-4 cursor-pointer group py-4 bg-slate-50/50 transition-all">
                            <div class="flex-shrink-0 mt-0.5">
                                <input type="checkbox" name="disclaimer_3" required
                                    class="w-5 h-5 rounded border-slate-300 text-[#003366] focus:ring-[#003366] cursor-pointer disclaimer-cb">
                            </div>
                            <span class="text-xs text-slate-600 leading-relaxed font-medium">
                                Saya menjamin bahwa seluruh data yang telah saya isikan pada formulir di atas adalah
                                benar dan akurat. <span class="text-red-500 font-black">*</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div
                    class="mt-12 bg-white border border-slate-100 rounded-2xl overflow-hidden transition-all duration-300">
                    <div class="p-8 pb-1">
                        <h3
                            class="text-[15px] font-[800] text-[#003366] leading-tight font-['Plus_Jakarta_Sans'] uppercase tracking-tight mb-2">
                            Rekapitulasi Pembayaran </h3>
                    </div>
                    <div class="relative flex items-center py-2 overflow-hidden pointer-events-none">
                        <div
                            class="absolute -left-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-100/30">
                        </div>
                        <div
                            class="absolute -right-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-100/30">
                        </div>
                        <div class="w-full border-t-2 border-dashed border-slate-200 mx-5"></div>
                    </div>
                    <div class="p-8 pt-4 bg-slate-50/40 space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium italic">Tiket {{ $ticket->category->name }}</span>
                            <span class="text-[#003366] font-bold">Rp
                                {{ number_format($ticket->price, 0, ',', '.') }}</span>
                        </div>

                    </div>
                    <div
                        class="p-5 border-t border-dashed border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="flex flex-col">
                            <span
                                class="text-[10px] text-slate-400 font-[800] uppercase tracking-wider block mb-1">Total
                                Bayar</span>
                            <div id="lbl_total" class="text-[28px] font-[900] text-[#003366] leading-none font-['Plus_Jakarta_Sans']">
                                Rp {{ number_format($ticket->price, 0, ',', '.') }}
                            </div>
                        </div>
                        <x-button type="submit"
                            class="w-full md:w-auto px-10 py-3.5 bg-[#003366] text-white rounded-xl font-[800] text-[15px]">
                            Lanjut Pembayaran
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
        <div class="mt-8 text-center"> <a href="{{ url('/sponsorship') }}"
                class="inline-flex items-center gap-2 text-slate-400 font-bold text-sm hover:text-[#003366] transition-all">
                ← Batal & Kembali </a> </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".datepicker", {
                dateFormat: "d-m-Y",
                maxDate: "today",
                allowInput: true
            });


        });
    </script>
</x-layouts.app>
