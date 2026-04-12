<x-layouts.app>
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="min-h-screen py-16 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <!-- Header Section -->
        <div class="max-w-4xl w-full text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-[900] text-white uppercase tracking-tight mb-4 drop-shadow-md">
                Rules & <span class="text-[#FF7A21]">Regulations</span>
            </h1>
            <p class="text-white/80 font-bold uppercase tracking-[3px] text-xs md:text-sm">
                Peraturan dan Ketentuan Resmi IPB RUN 2026
            </p>
        </div>

        <!-- TOC & Content Wrapper -->
        <div class="max-w-7xl w-full grid grid-cols-1 lg:grid-cols-4 gap-8 px-2 md:px-0">
            
            <!-- Sidebar Navigation (Desktop) -->
            <div class="hidden lg:block lg:col-span-1">
                <div class="sticky top-28 space-y-2 p-6 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20">
                    <p class="text-[10px] font-black text-white/60 uppercase tracking-[3px] mb-4 pl-2">Navigasi Halaman</p>
                    @php
                        $sections = [
                            ['id' => 'sec1', 'title' => 'Informasi Umum'],
                            ['id' => 'sec2', 'title' => 'Pendaftaran'],
                            ['id' => 'sec3', 'title' => 'Race Pack (RPC)'],
                            ['id' => 'sec4', 'title' => 'Ketentuan Umum'],
                            ['id' => 'sec5', 'title' => 'Peraturan Lomba'],
                            ['id' => 'sec6', 'title' => 'Pemenang'],
                            ['id' => 'sec7', 'title' => 'Keselamatan'],
                            ['id' => 'sec8', 'title' => 'Diskualifikasi'],
                            ['id' => 'sec9', 'title' => 'Kewenangan'],
                        ];
                    @endphp
                    @foreach($sections as $sec)
                        <a href="#{{ $sec['id'] }}" class="block px-4 py-3 rounded-xl text-[11px] font-black text-white/80 hover:bg-white/10 hover:text-white uppercase tracking-widest transition-all">
                            {{ $sec['title'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Content Container -->
            <div class="lg:col-span-3 space-y-8">
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                    
                    <!-- Top Intro -->
                    <div class="p-8 md:p-12 border-b border-slate-50">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-[#003366] rounded-full text-[10px] font-black uppercase tracking-widest mb-6 border border-blue-100/50">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Official Rules
                        </div>
                        <p class="text-slate-500 font-medium leading-relaxed italic md:text-lg">
                            "Dengan mendaftar dan mengikuti lomba ini, peserta dianggap telah membaca, memahami, dan menyetujui seluruh peraturan."
                        </p>
                    </div>

                    <div class="p-8 md:p-12 pt-4">
                        <!-- Section 1 -->
                        <div id="sec1" class="mb-12 scroll-mt-28">
                            <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight flex items-center gap-4 mb-6">
                                <span class="flex-shrink-0 w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-lg">01</span>
                                Informasi Umum
                            </h2>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-4">
                                    <span class="text-slate-400 font-black text-xs pt-1 w-6">1.1</span>
                                    <div class="flex-1">
                                        <p class="text-[13px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Nama Kegiatan</p>
                                        <p class="text-slate-700 font-bold">IPB RUN HALF MARATHON 2026</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <span class="text-slate-400 font-black text-xs pt-1 w-6">1.2</span>
                                    <div class="flex-1">
                                        <p class="text-[13px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Jenis Kegiatan</p>
                                        <p class="text-slate-700 font-bold">Perlombaan lari</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <span class="text-slate-400 font-black text-xs pt-1 w-6">1.3</span>
                                    <div class="flex-1">
                                        <p class="text-[13px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Jarak Rute Lomba</p>
                                        <p class="text-slate-700 font-bold">5K, 10K, 21K & 42K (FM)</p>
                                    </div>
                                </li>
                                <li class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-50 mt-4">
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Lokasi</p>
                                        <p class="text-slate-700 font-bold">Kampus Dramaga, IPB University</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Tanggal</p>
                                        <p class="text-slate-700 font-bold">6-7 Juni 2026</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Penyelenggara</p>
                                        <p class="text-slate-700 font-bold">Himpunan Alumni IPB University</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Kapasitas</p>
                                        <p class="text-slate-700 font-bold">20.000 Peserta</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Section 2 -->
                        <div id="sec2" class="mb-12 scroll-mt-28">
                            <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight flex items-center gap-4 mb-6">
                                <span class="flex-shrink-0 w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-lg">02</span>
                                Peraturan Pendaftaran
                            </h2>
                            <div class="bg-blue-50/50 rounded-2xl p-6 space-y-4">
                                @foreach([
                                    'Pendaftaran dan pembayaran hanya dapat dilakukan melalui website resmi: www.ipbrun2026.id',
                                    'Peserta adalah WNI atau WNA dengan identitas resmi seperti KTP, SIM, Paspor, atau Kartu Keluarga.',
                                    'Peserta dari keluarga besar IPB (mahasiswa, alumni, dosen, tenaga kependidikan) wajib mengisi NIM/NRP pada formulir pendaftaran.',
                                    'Pendaftaran tidak dapat dilakukan melalui pihak ketiga dan tidak dapat dialihkan kepada orang lain.',
                                    'Peserta wajib mengisi formulir pendaftaran dengan data yang benar, akurat, terkini, dan lengkap.',
                                    'Nomor lomba (BIB number) bersifat final, tidak dapat dialihkan, dijual, atau digunakan oleh orang lain.',
                                    'Penyelesaian formulir pendaftaran dan pembayaran menandakan persetujuan penuh peserta terhadap seluruh ketentuan lomba.',
                                    'Konfirmasi pendaftaran akan dikirim melalui email setelah pendaftaran selesai.',
                                    'Pembatalan pendaftaran dan pengembalian biaya pendaftaran tidak dilayani (Non-Refundable).',
                                    'Panitia berhak menutup pendaftaran lebih awal jika kuota telah terpenuhi.',
                                ] as $rule)
                                    <div class="flex gap-4">
                                        <svg class="w-5 h-5 text-[#003366] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-[13px] md:text-sm text-slate-600 font-medium leading-relaxed">{{ $rule }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Section 3 -->
                        <div id="sec3" class="mb-12 scroll-mt-28">
                            <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight flex items-center gap-4 mb-6">
                                <span class="flex-shrink-0 w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-lg">03</span>
                                Pengambilan Race Pack (RPC)
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="p-6 border-2 border-slate-50 rounded-2xl">
                                    <p class="text-[11px] font-black text-[#003366] uppercase tracking-widest mb-4">Materi Race Pack</p>
                                    <ul class="space-y-3">
                                        @foreach(['Running Jersey', 'BIB Number', 'Goodie Bag', 'Produk Sponsor'] as $item)
                                            <li class="flex items-center gap-3 text-sm text-slate-500 font-bold">
                                                <div class="w-1.5 h-1.5 rounded-full bg-orange-400"></div> {{ $item }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="p-6 bg-slate-50/50 rounded-2xl">
                                    <p class="text-[11px] font-black text-[#003366] uppercase tracking-widest mb-4">Informasi Penting</p>
                                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                                        Waktu dan lokasi akan diumumkan melalui website & media sosial. Pengambilan dapat diwakilkan dengan menyertakan Surat Kuasa (tanpa materai).
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4 -->
                        <div id="sec4" class="mb-12 scroll-mt-28">
                            <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight flex items-center gap-4 mb-6">
                                <span class="flex-shrink-0 w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-lg">04</span>
                                Ketentuan Umum & Peserta
                            </h2>
                            <div class="space-y-4 text-sm text-slate-600 font-medium leading-relaxed">
                                <p>• Peserta wajib hadir minimal 1 jam sebelum start dengan BIB dipasang di dada.</p>
                                <p>• Dilarang keras membawa atribut SARA, politik, senjata tajam, atau merokok di area lomba.</p>
                                <p>• Layanan penitipan barang (non-berharga) tersedia pukul 04.00 - 10.00 WIB.</p>
                                <p>• Peserta bertanggung jawab penuh atas kondisi fisik dan keselamatan dirinya masing-masing.</p>
                            </div>
                        </div>

                        <!-- Section 5 -->
                        <div id="sec5" class="mb-12 scroll-mt-28">
                            <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight flex items-center gap-4 mb-6">
                                <span class="flex-shrink-0 w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-lg">05</span>
                                Peraturan Lomba & COT
                            </h2>
                            <div class="overflow-x-auto rounded-2xl border border-slate-100 mb-6">
                                <table class="w-full text-center">
                                    <thead class="bg-[#003366] text-white text-[10px] font-black uppercase tracking-widest">
                                        <tr>
                                            <th class="px-6 py-4">Kategori / Jarak</th>
                                            <th class="px-6 py-4">Cut Off Time (COT)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm font-black text-slate-700 divide-y divide-slate-50">
                                        <tr><td class="px-6 py-4 bg-slate-50/50">5K</td><td class="px-6 py-4">1 Jam</td></tr>
                                        <tr><td class="px-6 py-4 bg-slate-50/50">10K</td><td class="px-6 py-4">2 Jam</td></tr>
                                        <tr><td class="px-6 py-4 bg-slate-50/50">21K (Half Marathon)</td><td class="px-6 py-4 text-[#E8630A]">4 Jam</td></tr>
                                        <tr><td class="px-6 py-4 bg-slate-50/50">42K (Full Marathon)</td><td class="px-6 py-4 text-[#E8630A]">7 Jam</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-xs text-slate-400 italic font-medium leading-loose">
                                * Peserta yang melewati batas COT tidak berhak menerima medali penamat. Segala bentuk kecurangan atau memulai sebelum flag-off akan didiskualifikasi.
                            </p>
                        </div>

                        <!-- Section 6 -->
                        <div id="sec6" class="mb-12 scroll-mt-28">
                            <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight flex items-center gap-4 mb-6">
                                <span class="flex-shrink-0 w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-lg">06</span>
                                Penentuan Pemenang
                            </h2>
                            <div class="space-y-4">
                                <div class="p-5 bg-orange-50 border border-orange-100 rounded-2xl">
                                    <p class="text-sm font-bold text-[#E8630A] leading-relaxed">
                                        "Gun Time" akan digunakan untuk penentuan para pemenang. Podium berdasarkan pelari pertama yang menyentuh garis finis secara sah.
                                    </p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-bold text-slate-500 uppercase tracking-widest">
                                    <div class="p-4 bg-slate-50 rounded-xl flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-[#003366]"></div> WNI Berhak atas Podium 1, 2, 3
                                    </div>
                                    <div class="p-4 bg-slate-50 rounded-xl flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-[#003366]"></div> Juri & RD bersifat Final
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 7 -->
                        <div id="sec7" class="mb-12 scroll-mt-28">
                            <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight flex items-center gap-4 mb-6">
                                <span class="flex-shrink-0 w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-lg">07</span>
                                Keselamatan & Evakuasi
                            </h2>
                            <p class="text-sm text-slate-600 leading-relaxed font-medium">
                                Pos medis tersedia di rute. Panitia berhak melarang peserta melanjutkan lomba jika kondisi fisik membahayakan. Lomba dapat ditunda atau dibatalkan jika terjadi Force Majeure.
                            </p>
                        </div>

                        <!-- Section 8 -->
                        <div id="sec8" class="mb-12 scroll-mt-28">
                            <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight flex items-center gap-4 mb-6 text-red-600">
                                <span class="flex-shrink-0 w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-lg">08</span>
                                Diskualifikasi
                            </h2>
                            <div class="p-6 border-2 border-red-50 rounded-2xl">
                                <ul class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-8">
                                    @foreach([
                                        'Data pendaftaran tidak benar',
                                        'BIB bukan milik sendiri',
                                        'Tidak melewati check point',
                                        'Memotong rute lomba',
                                        'Menggunakan alat bantu transpor',
                                        'Didampingi non-peserta',
                                        'Membuang sampah sembarangan',
                                        'Kecurangan dalam bentuk apapun'
                                    ] as $reason)
                                        <li class="flex items-center gap-3 text-[13px] text-slate-500 font-black uppercase tracking-tight">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            {{ $reason }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <!-- Section 9 -->
                        <div id="sec9" class="mb-12 scroll-mt-28">
                            <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight flex items-center gap-4 mb-6">
                                <span class="flex-shrink-0 w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-lg">09</span>
                                Kewenangan Penyelenggara
                            </h2>
                            <p class="text-sm text-slate-600 font-bold leading-relaxed bg-slate-50 p-6 rounded-2xl border-l-4 border-[#003366]">
                                Race Director memiliki kewenangan mutlak untuk mengubah, menambah, atau menafsirkan peraturan demi menjaga integritas dan keamanan lomba. Keputusan Race Director bersifat FINAL.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="mt-8 text-center pb-20">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3 px-8 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-md text-white rounded-2xl font-[800] text-sm uppercase tracking-widest transition-all active:scale-95 border border-white/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
