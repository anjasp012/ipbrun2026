<x-layouts.app title="Status Pembayaran - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="min-h-screen py-12 px-4 flex flex-col items-center justify-center">
        <div class="max-w-[550px] w-full bg-white/95 rounded-[2.5rem] shadow-2xl border border-white/20 overflow-hidden backdrop-blur-sm animate-in fade-in zoom-in-95 duration-500">
            <!-- Header Status -->
            <div class="p-10 text-center relative">
                @if ($status == 'settlement' || $status == 'capture')
                    <!-- SUCCESS -->
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-emerald-50 rounded-3xl mb-8 shadow-inner border border-emerald-100 animate-bounce">
                        <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black text-[#003366] uppercase tracking-tight mb-4">Pembayaran Berhasil!</h1>
                    <p class="text-[13px] text-slate-500 font-bold leading-relaxed uppercase tracking-wider italic opacity-80">
                        Terima kasih! Pembayaran Anda telah kami terima. E-Tiket dan detail akun telah dikirimkan ke email 
                        <span class="text-[#00ACB1]">{{ $email ?? 'Anda' }}</span>.
                    </p>
                @elseif($status == 'pending')
                    <!-- PENDING -->
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-amber-50 rounded-3xl mb-8 shadow-inner border border-amber-100">
                        <svg class="w-12 h-12 text-amber-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black text-[#003366] uppercase tracking-tight mb-4">Menunggu Pembayaran</h1>
                    <p class="text-[13px] text-slate-500 font-bold leading-relaxed uppercase tracking-wider italic opacity-80">
                        Transaksi Anda sedang diproses. Segera selesaikan pembayaran sesuai instruksi agar slot Anda aman.
                    </p>
                @else
                    <!-- FAILED / EXPIRED / CANCEL -->
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-red-50 rounded-3xl mb-8 shadow-inner border border-red-100">
                        <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black text-[#003366] uppercase tracking-tight mb-4">Pembayaran Gagal</h1>
                    <p class="text-[13px] text-slate-500 font-bold leading-relaxed uppercase tracking-wider italic opacity-80">
                        Maaf, transaksi Anda gagal atau dibatalkan. Jangan khawatir, Anda dapat mencoba mendaftar kembali atau hubungi panitia.
                    </p>
                @endif
            </div>

            <!-- Ticket-style Divider -->
            <div class="relative flex items-center py-2 overflow-hidden pointer-events-none">
                <div class="absolute -left-4 w-8 h-8 bg-[#f1f5f9] rounded-full border border-slate-200"></div>
                <div class="absolute -right-4 w-8 h-8 bg-[#f1f5f9] rounded-full border border-slate-200"></div>
                <div class="w-full border-t-2 border-dashed border-slate-100 mx-8"></div>
            </div>

            <!-- Order Summary Section -->
            <div class="p-10 bg-slate-50/50">
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm mb-8">
                    <div class="flex flex-col gap-4">
                        <div class="flex justify-between items-center pb-4 border-b border-slate-50">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Order ID</span>
                            <span class="text-sm font-black text-[#003366] tracking-tighter">{{ $order_id ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Platform Status</span>
                            <span class="text-[10px] font-black px-3 py-1 bg-slate-100 text-slate-500 rounded-full uppercase tracking-widest">
                                {{ strtoupper($status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    @if ($status == 'settlement' || $status == 'capture')
                         <a href="{{ route('login') }}" class="w-full h-16 bg-[#003366] text-white rounded-2xl flex items-center justify-center font-black text-xs uppercase tracking-[3px] shadow-xl shadow-blue-950/20 hover:bg-[#002244] transition-all active:scale-95">
                            Lihat Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('check.order', ['order_code' => $order_id]) }}" class="w-full h-16 bg-[#00ACB1] text-white rounded-2xl flex items-center justify-center font-black text-xs uppercase tracking-[3px] shadow-xl shadow-[#00ACB1]/20 hover:bg-[#009da1] transition-all active:scale-95">
                            Cek Rincian Pesanan
                        </a>
                        <a href="{{ url('/') }}" class="w-full h-16 bg-white border-2 border-slate-100 text-slate-500 rounded-2xl flex items-center justify-center font-black text-xs uppercase tracking-[3px] hover:border-[#003366]/20 hover:text-[#003366] transition-all active:scale-95">
                            Kembali ke Beranda
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-12 text-center space-y-2">
            <p class="text-white/40 font-black text-[10px] uppercase tracking-[4px]">IPB RUN 2026 • LESTARI UNTUK BUMI</p>
            <p class="text-white/30 text-[9px] italic font-bold uppercase tracking-widest">Bantuan? Hubungi WhatsApp Official Panitia</p>
        </div>
    </div>
</x-layouts.app>
