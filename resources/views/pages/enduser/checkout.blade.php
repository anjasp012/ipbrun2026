<x-layouts.app title="Registration Form - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <!-- Main Form Container: Aligned with Index Card Style -->
        <div
            class="max-w-[1000px] w-full bg-white border border-slate-100 rounded-2xl shadow-sm md:p-14 p-8 transition-all duration-300">

            <div class="mb-10 text-center">
                <h1 class="text-3xl font-[800] text-[#003366] uppercase tracking-tight">Formulir Data Pelari</h1>
                
                <!-- Ticket Card Head (Top Part of Index Card Style) -->
                <div class="mt-10 relative bg-white border border-slate-100 rounded-t-2xl overflow-hidden">
                    <div class="p-6 text-center">
                        <div
                            class="text-[17px] font-[800] text-[#003366] leading-tight font-['Plus_Jakarta_Sans'] uppercase mb-1">
                            {{ $ticket->category->name }} {{ $ticket->name }}
                        </div>
                        <div class="text-[11px] text-[#E8630A] font-[800] uppercase tracking-[0.5px] opacity-80">
                            {{ $ticket->period->name ?? 'Standard' }}
                        </div>
                    </div>

                    <!-- Perforation Detail (Matched with Index) -->
                    <div class="relative flex items-center py-1 overflow-hidden pointer-events-none">
                        <div
                            class="absolute -left-3 w-6 h-6 bg-white rounded-full shadow-inner ring-1 ring-inset ring-slate-100/30">
                        </div>
                        <div
                            class="absolute -right-3 w-6 h-6 bg-white rounded-full shadow-inner ring-1 ring-inset ring-slate-100/30">
                        </div>
                        <div class="w-full border-t border-dashed border-slate-200 mx-5"></div>
                    </div>

                    <!-- Price Part (Bottom Part of Index Card Style) -->
                    <div class="p-5 bg-slate-50/40 rounded-b-2xl">
                        <span class="text-[10px] text-slate-400 font-[800] uppercase tracking-wider block mb-1">Entry
                            Fee</span>
                        <div class="text-[22px] font-[900] text-[#003366] leading-none font-['Plus_Jakarta_Sans']">Rp
                            {{ number_format($ticket->price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <form id="registrationForm" action="{{ route('register') }}" method="POST">
                @csrf
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                <!-- Form Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <x-label for="name">Nama Lengkap (Sesuai KTP) *</x-label>
                        <x-input id="name" name="name" placeholder="Ketik nama lengkap Anda" required value="{{ old('name') }}" class="{{ $errors->has('name') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('name')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-label for="email">Alamat Email *</x-label>
                        <x-input type="email" id="email" name="email" placeholder="nama@email.com" required value="{{ old('email') }}" class="{{ $errors->has('email') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('email')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@else<p class="mt-2 text-[10px] text-[#E8630A] font-bold uppercase tracking-wider leading-tight">Gunakan email asli untuk mendapatkan notifikasi invoice</p>@enderror
                    </div>
                    <div>
                        <x-label for="email_confirmation">Konfirmasi Alamat Email *</x-label>
                        <x-input type="email" id="email_confirmation" name="email_confirmation" placeholder="Ketik ulang email Anda" required value="{{ old('email_confirmation') }}" class="{{ $errors->has('email_confirmation') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('email_confirmation')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-label for="phone_number">Nomor WhatsApp *</x-label>
                        <x-input id="phone_number" name="phone_number" placeholder="08xxxxxxxxx" required
                            :numeric="true" maxlength="14" value="{{ old('phone_number') }}" class="{{ $errors->has('phone_number') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('phone_number')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-label for="nik">NIK KTP *</x-label>
                        <x-input id="nik" name="nik" placeholder="16 digit NIK" required :numeric="true"
                            maxlength="16" value="{{ old('nik') }}" class="{{ $errors->has('nik') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('nik')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-label for="date_birth">Tanggal Lahir *</x-label>
                        <x-input id="date_birth" name="date_birth" class="datepicker bg-white {{ $errors->has('date_birth') ? '!border-red-500 ring-4 ring-red-50' : '' }}" placeholder="DD-MM-YYYY"
                            readonly required value="{{ old('date_birth') }}" />
                        @error('date_birth')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-label for="sex">Jenis Kelamin *</x-label>
                        <x-select id="sex" name="sex" required :options="['male' => 'Laki-laki', 'female' => 'Perempuan']" :selected="old('sex')" class="{{ $errors->has('sex') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('sex')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-label for="blood_type">Golongan Darah *</x-label>
                        <x-select id="blood_type" name="blood_type" required :options="['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O', '-' => 'N/A']" :selected="old('blood_type')" class="{{ $errors->has('blood_type') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('blood_type')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-label for="jersey_size">Ukuran Jersey *</x-label>
                        <x-select id="jersey_size" name="jersey_size" required :options="['S' => 'S', 'M' => 'M', 'L' => 'L', 'XL' => 'XL', 'XXL' => 'XXL']" :selected="old('jersey_size')" class="{{ $errors->has('jersey_size') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('jersey_size')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-label for="nim_nrp">NIM/NRP (Optional)</x-label>
                        <x-input id="nim_nrp" name="nim_nrp" placeholder="Untuk kategori IPB" value="{{ old('nim_nrp') }}" class="{{ $errors->has('nim_nrp') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('nim_nrp')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-label for="nationality">Kewarganegaraan</x-label>
                        <x-input id="nationality" name="nationality" value="{{ old('nationality', 'WNI') }}" required class="{{ $errors->has('nationality') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('nationality')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <x-label for="address">Alamat Lengkap *</x-label>
                        <x-textarea id="address" name="address" rows="2"
                            placeholder="Alamat pengiriman/domisili" required class="{{ $errors->has('address') ? '!border-red-500 ring-4 ring-red-50' : '' }}">{{ old('address') }}</x-textarea>
                        @error('address')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-label for="emergency_contact_name">Kontak Darurat *</x-label>
                        <x-input id="emergency_contact_name" name="emergency_contact_name"
                            placeholder="Nama keluarga/kerabat" required value="{{ old('emergency_contact_name') }}" class="{{ $errors->has('emergency_contact_name') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('emergency_contact_name')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-label for="emergency_contact_phone_number">HP Darurat *</x-label>
                        <x-input id="emergency_contact_phone_number" name="emergency_contact_phone_number"
                            placeholder="08xxxxxxxxx" required :numeric="true" value="{{ old('emergency_contact_phone_number') }}" class="{{ $errors->has('emergency_contact_phone_number') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('emergency_contact_phone_number')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <x-label for="emergency_contact_relationship">Hubungan Kontak *</x-label>
                        <x-input id="emergency_contact_relationship" name="emergency_contact_relationship"
                            placeholder="Misal: Orang Tua, Pasangan" required value="{{ old('emergency_contact_relationship') }}" class="{{ $errors->has('emergency_contact_relationship') ? '!border-red-500 ring-4 ring-red-50' : '' }}" />
                        @error('emergency_contact_relationship')<p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Donation Section -->
                <div id="donateSection" class="hidden mt-10">
                    <h3
                        class="text-sm font-black text-[#003366] uppercase tracking-[2px] mb-6 pb-2 border-b border-slate-100">
                        Donation (Optional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="donation_event">Donasi Event</x-label>
                            <x-select id="donation_event" name="donation_event" :options="[
                                '0' => 'Tidak, Terima Kasih',
                                '50000' => 'Rp 50.000',
                                '100000' => 'Rp 100.000',
                                '250000' => 'Rp 250.000',
                                '500000' => 'Rp 500.000',
                            ]" :selected="old('donation_event')" />
                        </div>
                        <div>
                            <x-label for="donation_scholarship">Donasi Beasiswa</x-label>
                            <x-select id="donation_scholarship" name="donation_scholarship" :options="[
                                '0' => 'Tidak, Terima Kasih',
                                '50000' => 'Rp 50.000',
                                '100000' => 'Rp 100.000',
                                '250000' => 'Rp 250.000',
                                '500000' => 'Rp 500.000',
                            ]" :selected="old('donation_scholarship')" />
                        </div>
                    </div>
                </div>

                <!-- Payment Summary (Consistent with Index Card Style) -->
                <div
                    class="mt-12 bg-white border border-slate-100 rounded-2xl overflow-hidden transition-all duration-300">
                    <div class="p-8 pb-1">
                        <h3
                            class="text-[15px] font-[800] text-[#003366] leading-tight font-['Plus_Jakarta_Sans'] uppercase tracking-tight mb-2">
                            Rekapitulasi Pembayaran
                        </h3>
                        <p class="text-[10px] text-[#E8630A] font-[800] uppercase tracking-[0.5px] opacity-80 mb-4">
                            Total tagihan yang harus dibayarkan</p>
                    </div>

                    <!-- Perforation Detail (Matched with Index) -->
                    <div class="relative flex items-center py-2 overflow-hidden pointer-events-none">
                        <div
                            class="absolute -left-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-100/30">
                        </div>
                        <div
                            class="absolute -right-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-100/30">
                        </div>
                        <div class="w-full border-t-2 border-dashed border-slate-200 mx-5"></div>
                    </div>

                    <!-- Summary Content -->
                    <div class="p-8 pt-4 bg-slate-50/40 space-y-4">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium italic">Tiket {{ $ticket->name }}</span>
                                <span class="text-[#003366] font-bold">Rp
                                    {{ number_format($ticket->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium italic">Biaya Layanan</span>
                                <span class="text-[#003366] font-bold">Rp 4.500</span>
                            </div>
                            <div id="row_donation_event" class="hidden flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium italic">Donasi Event</span>
                                <span id="lbl_donation_event" class="text-[#E8630A] font-bold">Rp 0</span>
                            </div>
                            <div id="row_donation_scholarship"
                                class="hidden flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium italic">Donasi Beasiswa</span>
                                <span id="lbl_donation_scholarship" class="text-[#E8630A] font-bold">Rp 0</span>
                            </div>
                        </div>

                        <div
                            class="pt-5 border-t border-dashed border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <div class="flex flex-col">
                                <span
                                    class="text-[10px] text-slate-400 font-[800] uppercase tracking-wider block mb-1">Total
                                    Bayar</span>
                                <div id="lbl_total"
                                    class="text-[28px] font-[900] text-[#003366] leading-none font-['Plus_Jakarta_Sans']">
                                    Rp 0</div>
                            </div>
                            <x-button type="submit" id="btn_submit"
                                class="w-full md:w-auto px-10 py-3.5 bg-[#003366] text-white rounded-xl font-[800] text-[15px] transition-all active:scale-95 hover:bg-[#002244] flex items-center justify-center">
                                Lanjut Pembayaran
                            </x-button>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ url('/') }}"
                        class="inline-flex items-center gap-2 text-slate-400 font-bold text-sm hover:text-[#003366] transition-all">
                        ← Batal & Kembali ke Beranda
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Pendaftaran Gagal',
                    text: 'Silakan periksa kembali isian formulir Anda yang berwarna merah.',
                    confirmButtonColor: '#003366',
                    confirmButtonText: 'OKE, SAYA MENGERTI'
                });
            @endif

            flatpickr(".datepicker", {
                locale: "id",
                dateFormat: "d-m-Y",
                maxDate: "today",
                disableMobile: "true"
            });

            const ticketName = "{{ $ticket->name }}";
            const isIPB = ticketName.toUpperCase().includes("IPB");
            const ticketPrice = {{ $ticket->price }};
            const adminFee = 4500;

            if (isIPB) {
                document.getElementById('donateSection').classList.remove('hidden');
            }

            function updateTotal() {
                let donEvent = parseInt(document.getElementById('donation_event')?.value || 0);
                let donScholar = parseInt(document.getElementById('donation_scholarship')?.value || 0);

                const summaryEvent = document.getElementById('row_donation_event');
                const lblEvent = document.getElementById('lbl_donation_event');
                if (donEvent > 0) {
                    summaryEvent.classList.remove('hidden');
                    lblEvent.innerText = 'Rp ' + donEvent.toLocaleString('id-ID');
                } else {
                    summaryEvent.classList.add('hidden');
                }

                const summaryScholar = document.getElementById('row_donation_scholarship');
                const lblScholar = document.getElementById('lbl_donation_scholarship');
                if (donScholar > 0) {
                    summaryScholar.classList.remove('hidden');
                    lblScholar.innerText = 'Rp ' + donScholar.toLocaleString('id-ID');
                } else {
                    summaryScholar.classList.add('hidden');
                }

                let total = ticketPrice + adminFee + donEvent + donScholar;
                const formattedTotal = 'Rp ' + total.toLocaleString('id-ID');
                document.getElementById('lbl_total').innerText = formattedTotal;
            }

            document.getElementById('donation_event')?.addEventListener('change', updateTotal);
            document.getElementById('donation_scholarship')?.addEventListener('change', updateTotal);
            updateTotal();
        });
    </script>
</x-app>
