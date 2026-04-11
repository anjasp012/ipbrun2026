<x-layouts.app>
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="min-h-screen py-16 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <!-- Header Section -->
        <div class="max-w-4xl w-full text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-[900] text-white uppercase tracking-tight mb-4 drop-shadow-md">
                Frequently Asked <span class="text-[#FF7A21]">Questions</span>
            </h1>
            <p class="text-white/80 font-bold uppercase tracking-[3px] text-xs md:text-sm">
                Temukan jawaban untuk pertanyaan yang sering diajukan seputar IPB RUN 2026
            </p>
        </div>

        <!-- FAQ Content Container -->
        <div class="max-w-4xl w-full">
            <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                <div class="p-1 md:p-2">
                    <div x-data="{ activeAccordion: null }" class="space-y-1">
                        
                        @php
                            $faqs = [
                                [
                                    'q' => 'Kapan IPB RUN HM 2026 akan diadakan?',
                                    'a' => 'Sabtu & Minggu, 6-7 Juni 2026'
                                ],
                                [
                                    'q' => 'Kategori apa saja yang diperlombakan?',
                                    'a' => '5K, Individu/Putra/Putri/Umum/Keluarga IPB University<br>
                                            10K, Individu/Putra/Putri/Umum/Keluarga IPB University<br>
                                            21K, Individu/Putra/Putri/Umum/Keluarga IPB University<br>
                                            42K, Individu/Putra/Putri/Umum/Keluarga IPB University'
                                ],
                                [
                                    'q' => 'Apa saja yang didapatkan peserta dengan pendaftaran di lomba ini?',
                                    'a' => '<ul class="list-disc pl-5 space-y-1">
                                                <li>Jersey Event</li>
                                                <li>Medali</li>
                                                <li>BIB chip time</li>
                                                <li>Goodie Bag</li>
                                                <li>Refreshment</li>
                                                <li>Finisher tee untuk kategori 21K & 42K</li>
                                            </ul>'
                                ],
                                [
                                    'q' => 'Bagaimana saya mengetahui bahwa pendaftaran saya telah diterima?',
                                    'a' => 'Ketika pendaftaran dan pembayaran telah sukses, peserta akan menerima konfirmasi melalui Whatsapp dan Email yang didaftarkan. Pastikan Whatsapp dan Email yang benar dan aktif.'
                                ],
                                [
                                    'q' => 'Apakah saya dapat mengubah kategori setelah saya terdaftar?',
                                    'a' => 'Tidak bisa. Anda tidak diperkenankan mengganti kategori jika sudah terdaftar. Jika ingin berlomba di kategori lain, silakan mendaftar kembali di kategori yang diinginkan.'
                                ],
                                [
                                    'q' => 'Jika saya sudah terdaftar, bolehkah saya memutuskan untuk tidak berpartisipasi di lomba ini karena satu dan lain hal?',
                                    'a' => 'Diperbolehkan. Namun, dana pendaftaran tidak dapat dikembalikan (dengan alasan apa pun) dan peserta tetap berhak mendapatkan paket lomba.'
                                ],
                                [
                                    'q' => 'Bagaimana kebijakan pengunduran diri dari lomba?',
                                    'a' => 'Slot lomba tidak dapat dipindahtangankan ke orang lain dengan alasan apa pun.'
                                ],
                                [
                                    'q' => 'Metode pembayaran apa yang digunakan untuk pendaftaran?',
                                    'a' => 'Pembayaran pendaftaran dapat menggunakan transfer bank & QRIS.'
                                ],
                                [
                                    'q' => 'Di mana dan kapan saya dapat mengambil paket lomba (race pack collection)?',
                                    'a' => 'Waktu dan lokasi akan diumumkan kemudian.'
                                ],
                                [
                                    'q' => 'Apakah paket lomba dapat dikirimkan kepada peserta melalui jasa kurir?',
                                    'a' => 'Tidak bisa.'
                                ],
                                [
                                    'q' => 'Dapatkah saya menitipkan paket lomba kepada orang lain?',
                                    'a' => 'Ya, Anda dapat menitipkan kepada orang lain. Anda harus menyertakan surat kuasa (tanpa materai) dan salinan tanda bukti diri berupa KTP/SIM/Paspor/KITAS. Orang yang mewakili Anda harus menunjukkan surat kuasa yang sudah ditandatangani, salinan konfirmasi pengambilan, dan salinan tanda bukti diri.'
                                ],
                                [
                                    'q' => 'Apakah ada pendaftaran on the spot?',
                                    'a' => 'Tidak ada, semua pendaftaran dilakukan secara online.'
                                ],
                                [
                                    'q' => 'Bagaimana jika saya terlambat mengambil race pack?',
                                    'a' => 'Peserta yang terlambat tidak bisa mengambil race pack dan tetap tidak dapat mengganti nomor lomba.'
                                ],
                                [
                                    'q' => 'Bagaimana jika terjadi cedera saat lomba?',
                                    'a' => 'Panitia menyediakan layanan medis di titik-titik tertentu, namun peserta wajib bertanggung jawab atas keselamatan dirinya sendiri.'
                                ],
                                [
                                    'q' => 'Apakah boleh membawa hewan peliharaan?',
                                    'a' => 'Tidak diperbolehkan.'
                                ],
                                [
                                    'q' => 'Apakah disediakan tempat parkir untuk peserta?',
                                    'a' => 'Ya, tersedia area parkir untuk peserta dan keluarga.'
                                ],
                                [
                                    'q' => 'Apakah ada fasilitas loker untuk peserta?',
                                    'a' => 'Tersedia fasilitas penitipan barang di lokasi lomba.'
                                ],
                                [
                                    'q' => 'Bagaimana cara menghubungi panitia jika ada pertanyaan lebih lanjut?',
                                    'a' => 'Peserta bisa menghubungi panitia melalui email resmi IPB RUN HM 2026 atau kontak yang tersedia di website resmi.'
                                ],
                            ];
                        @endphp

                        @foreach ($faqs as $index => $faq)
                            <div class="group border-b border-slate-100 last:border-0 overflow-hidden">
                                <button 
                                    @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}"
                                    class="w-full flex items-center justify-between p-6 text-left transition-all duration-300 hover:bg-slate-50"
                                    :class="{ 'bg-slate-50/80': activeAccordion === {{ $index }} }"
                                >
                                    <span class="text-[15px] md:text-lg font-[800] text-[#003366] leading-snug group-hover:translate-x-1 transition-transform duration-300">
                                        {{ $faq['q'] }}
                                    </span>
                                    <div 
                                        class="flex-shrink-0 ml-4 w-8 h-8 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all duration-300"
                                        :class="{ 'rotate-180 bg-[#003366] border-[#003366] text-white': activeAccordion === {{ $index }}, 'text-slate-400': activeAccordion !== {{ $index }} }"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </button>
                                
                                <div 
                                    x-show="activeAccordion === {{ $index }}"
                                    x-cloak
                                    x-collapse
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 -translate-y-4"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="bg-white"
                                >
                                    <div class="px-6 pb-8 pt-2 text-slate-600 text-[14px] md:text-base font-medium leading-relaxed">
                                        {!! $faq['a'] !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="mt-12 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 px-8 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-md text-white rounded-2xl font-[800] text-sm uppercase tracking-widest transition-all active:scale-95 border border-white/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
