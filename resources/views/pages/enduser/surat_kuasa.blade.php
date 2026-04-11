<x-layouts.app>
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="min-h-screen py-16 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <!-- Header Section -->
        <div class="max-w-4xl w-full text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-[900] text-white uppercase tracking-tight mb-4 drop-shadow-md">
                Surat <span class="text-[#FF7A21]">Kuasa</span>
            </h1>
            <p class="text-white/80 font-bold uppercase tracking-[3px] text-xs md:text-sm">
                Informasi & Dokumen Pengambilan Race Pack Perwakilan
            </p>
        </div>

        <!-- Main Content -->
        <div class="max-w-3xl w-full">
            <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                <div class="md:flex">
                    <!-- Preview/Icon Side -->
                    <div class="md:w-1/3 bg-[#003366] p-10 flex flex-col items-center justify-center text-center text-white">
                        <div class="w-24 h-24 bg-white/10 rounded-2xl flex items-center justify-center mb-6 border border-white/20">
                            <svg class="w-12 h-12 text-[#FF7A21]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black uppercase tracking-widest leading-tight">Template Surat Kuasa</h3>
                        <p class="mt-4 text-[10px] font-bold text-white/60 uppercase tracking-widest">Format: .DOCX</p>
                    </div>

                    <!-- Instructions Side -->
                    <div class="md:w-2/3 p-8 md:p-12">
                        <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight mb-6">Instruksi Pengambilan</h2>
                        <div class="space-y-6">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-100 text-[#E8630A] flex items-center justify-center font-black text-sm">1</div>
                                <div>
                                    <p class="text-sm font-black text-slate-700 uppercase tracking-wide mb-1">Unduh & Isi</p>
                                    <p class="text-[13px] text-slate-500 font-medium leading-relaxed">
                                        Unduh template dokumen di bawah ini dan isi data pemberi kuasa serta penerima kuasa dengan benar.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-100 text-[#E8630A] flex items-center justify-center font-black text-sm">2</div>
                                <div>
                                    <p class="text-sm font-black text-slate-700 uppercase tracking-wide mb-1">Siapkan Identitas</p>
                                    <p class="text-[13px] text-slate-500 font-medium leading-relaxed">
                                        Lampirkan salinan (fotokopi/print) tanda bukti diri berupa KTP/SIM/Paspor/KITAS pemberi dan penerima kuasa.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-100 text-[#E8630A] flex items-center justify-center font-black text-sm">3</div>
                                <div>
                                    <p class="text-sm font-black text-slate-700 uppercase tracking-wide mb-1">Tunjukkan Bukti</p>
                                    <p class="text-[13px] text-slate-500 font-medium leading-relaxed">
                                        Orang yang mewakili wajib menunjukkan surat kuasa yang sudah ditandatangani dan email konfirmasi pendaftaran.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 pt-8 border-t border-slate-100">
                            <a href="{{ asset('assets/docs/surat Kuasa IPB RUN 2026.docx') }}" 
                               class="flex items-center justify-center gap-3 w-full py-4 bg-[#FF7A21] hover:bg-[#E8630A] text-white rounded-2xl font-black text-sm uppercase tracking-[2px] shadow-lg shadow-orange-500/20 transition-all active:scale-95 group"
                               download>
                                <svg class="w-5 h-5 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Surat Kuasa
                            </a>
                            <p class="mt-4 text-center text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">
                                * Surat kuasa tidak wajib menggunakan materai.
                            </p>
                        </div>
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
