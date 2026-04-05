<x-layouts.app>
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center">
        <div
            class="max-w-[500px] w-full bg-white border border-slate-100 rounded-2xl shadow-2xl transition-all duration-300 overflow-hidden">

            <div class="p-8 text-center bg-white">
                @if ($status == 'settlement' || $status == 'capture')
                    <!-- SUCCESS -->
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-50 rounded-full mb-6">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-[900] text-[#003366] uppercase tracking-tight mb-2">Pembayaran Berhasil!
                    </h1>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Terima kasih! Pembayaran Anda telah kami terima. E-Tiket dan detail akun telah dikirimkan ke
                        email <strong>{{ $email ?? 'Anda' }}</strong>.
                    </p>
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100 inline-block">
                        <p class="text-[10px] text-blue-700 font-bold uppercase tracking-wider">Silakan cek folder Inbox
                            atau Spam email Anda.</p>
                    </div>
                @elseif($status == 'pending')
                    <!-- PENDING -->
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-50 rounded-full mb-6">
                        <svg class="w-10 h-10 text-amber-500 animate-pulse" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-[900] text-[#003366] uppercase tracking-tight mb-2">Menunggu Pembayaran
                    </h1>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Transaksi Anda sedang diproses. Segera selesaikan pembayaran sesuai instruksi di Midtrans agar
                        slot Anda aman.
                    </p>
                @elseif($status == 'expire' || $status == 'cancel' || $status == 'deny')
                    <!-- FAILED / EXPIRED -->
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-50 rounded-full mb-6">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-[900] text-[#003366] uppercase tracking-tight mb-2">Pembayaran Gagal</h1>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Maaf, transaksi Anda gagal, dibatalkan, atau telah kadaluarsa. Silakan coba lakukan pendaftaran
                        ulang.
                    </p>
                @else
                    <!-- UNKNOWN -->
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-50 rounded-full mb-6">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-[900] text-[#003366] uppercase tracking-tight mb-2">Status Transaksi</h1>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Sistem sedang memverifikasi status pembayaran Anda. Mohon cek email Anda secara berkala.
                    </p>
                @endif
            </div>

            <!-- Perforation Detail -->
            <div class="relative flex items-center py-1 overflow-hidden pointer-events-none">
                <div
                    class="absolute -left-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-100/20">
                </div>
                <div
                    class="absolute -right-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-100/20">
                </div>
                <div class="w-full border-t border-dashed border-slate-200 mx-5"></div>
            </div>

            <div class="p-8 bg-slate-50/40 text-center">
                <div class="space-y-1 mb-8">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Order ID</p>
                    <p class="text-sm text-[#003366] font-extrabold">{{ $order_id ?? '-' }}</p>
                </div>

                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center w-full bg-[#003366] text-white py-4 rounded-xl font-[800] text-[15px] shadow-xl shadow-[#003366]/20 transition-all active:scale-95 hover:bg-[#002244] gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <p class="mt-8 text-slate-400 font-medium text-[11px] leading-relaxed text-center italic">
            Jika dalam 1x24 jam e-tiket belum masuk, silakan hubungi <br>
            panitia melalui WhatsApp official IPB RUN 2026.
        </p>
    </div>
    </x-app>
