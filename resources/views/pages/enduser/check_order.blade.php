<x-layouts.app>
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="py-12 flex items-center justify-center p-4 min-h-screen">
        <div class="max-w-4xl mx-auto w-full">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <img src="{{ asset('assets/images/logo_ipbrun2026.png') }}" alt="IPB Run 2026"
                    class="h-20 mx-auto mb-6 drop-shadow-xl">
                <h1 class="text-3xl font-[900] text-white font-['Plus_Jakarta_Sans'] tracking-tight uppercase mb-2">
                    Cek Status Pesanan
                </h1>
                <p class="text-white/80 font-medium">Masukkan kode order Anda untuk melihat detail pendaftaran</p>
            </div>

            <!-- Search Box -->
            <div class="bg-white/95 rounded-3xl shadow-2xl border border-white/20 p-6 md:p-8 mb-8 backdrop-blur-sm">
                <form action="{{ route('check.order') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="order_code" value="{{ $orderCode }}"
                            class="block w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-[#003366] font-bold placeholder-slate-400 focus:ring-2 focus:ring-[#00ACB1] focus:border-[#00ACB1] transition-all uppercase tracking-widest"
                            placeholder="CONTOH: IPBR26-XXXXXX" required>
                    </div>
                    <button type="submit"
                        class="bg-[#003366] hover:bg-[#002244] text-white px-8 py-4 rounded-2xl font-[800] uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-blue-900/20">
                        Cek Status
                    </button>
                </form>
            </div>

            <!-- Results Section -->
            @if ($order)
                <div
                    class="bg-white rounded-3xl shadow-2xl border border-white/20 overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <!-- Order Header -->
                    <div
                        class="bg-[#003366] p-6 text-white flex flex-col md:flex-row justify-between items-center gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[3px] opacity-70 mb-1">Kode Order</p>
                            <h2 class="text-2xl font-black tracking-tight">{{ $order->order_code }}</h2>
                        </div>
                        <div class="text-center md:text-right">
                            <p class="text-xs font-bold uppercase tracking-[3px] opacity-70 mb-1">Status Pembayaran</p>
                            @if ($order->status === 'paid')
                                <span
                                    class="inline-flex px-4 py-1.5 rounded-full bg-emerald-500 text-white text-xs font-black uppercase tracking-widest shadow-lg shadow-emerald-500/30">BERHASIL
                                    (PAID)</span>
                            @elseif($order->status === 'pending')
                                <span
                                    class="inline-flex px-4 py-1.5 rounded-full bg-amber-500 text-white text-xs font-black uppercase tracking-widest shadow-lg shadow-amber-500/30">MENUNGGU
                                    (PENDING)</span>
                            @else
                                <span
                                    class="inline-flex px-4 py-1.5 rounded-full bg-red-500 text-white text-xs font-black uppercase tracking-widest shadow-lg shadow-red-500/30">GAGAL
                                    / EXPIRED</span>
                            @endif
                        </div>
                    </div>

                    <!-- Details Content -->
                    <div class="p-6 md:p-8">
                        <div class="grid md:grid-cols-2 gap-8">
                            <!-- Participant Info -->
                            <div>
                                <h3
                                    class="text-sm font-black text-[#003366] uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="w-1 h-4 bg-[#00ACB1] rounded-full"></span>
                                    Data Peserta
                                </h3>
                                <div class="space-y-4">
                                    <div
                                        class="bg-slate-50 p-4 rounded-2xl border border-slate-100 transition-hover hover:border-[#00ACB1]/30">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                            Nama Lengkap</p>
                                        <p class="font-bold text-[#003366]">{{ $order->participant->name }}</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                            Email</p>
                                        <p class="font-bold text-[#003366]">{{ $order->participant->email }}</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                            Nomor WhatsApp</p>
                                        <p class="font-bold text-[#003366]">{{ $order->participant->phone_number }}</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                            NIK</p>
                                        <p class="font-bold text-[#003366]">
                                            {{ Str::mask($order->participant->nik, '*', 6, 8) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Ticket Info -->
                            <div>
                                <h3
                                    class="text-sm font-black text-[#003366] uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="w-1 h-4 bg-[#00ACB1] rounded-full"></span>
                                    Detail Tiket
                                </h3>
                                <div class="space-y-4">
                                    @foreach ($order->raceEntries as $entry)
                                        <div
                                            class="relative bg-[#003366]/5 p-5 rounded-3xl border-2 border-dashed border-[#003366]/20">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <p
                                                        class="text-[10px] font-bold text-[#00ACB1] uppercase tracking-[2px] mb-1">
                                                        Kategori</p>
                                                    <p class="text-xl font-black text-[#003366] leading-none">
                                                        {{ $entry->ticket->category->name }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">BIB
                                                        Number</p>
                                                    <p class="font-black text-[#003366]">
                                                        {{ $entry->bib_number ?? 'TBA' }}</p>
                                                </div>
                                            </div>
                                            <div
                                                class="mt-4 pt-4 border-t border-[#003366]/10 flex justify-between items-end">
                                                <div>
                                                    <p
                                                        class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                                        Jersey Size</p>
                                                    <p class="font-bold text-[#003366]">
                                                        {{ $order->participant->jersey_size }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p
                                                        class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                                        Type</p>
                                                    <p class="font-bold text-[#003366] uppercase">
                                                        {{ $entry->ticket->type }}</p>
                                                </div>
                                            </div>
                                            <!-- Ticket Cut Decoration -->
                                            <div
                                                class="absolute -left-2 top-1/2 -translate-y-1/2 w-4 h-8 bg-white rounded-r-full">
                                            </div>
                                            <div
                                                class="absolute -right-2 top-1/2 -translate-y-1/2 w-4 h-8 bg-white rounded-l-full">
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Payment Info Footer -->
                                    <div class="mt-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-xs font-bold text-slate-400 uppercase">Total Bayar</span>
                                            <span class="text-lg font-black text-[#003366]">Rp
                                                {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        </div>
                                        @if ($order->status === 'pending' && $order->payment_url)
                                            <a href="{{ $order->payment_url }}" target="_blank"
                                                class="block w-full text-center bg-[#00ACB1] hover:bg-[#009da1] text-white py-3 rounded-xl font-bold uppercase tracking-widest text-xs transition-all mt-2">
                                                Lanjutkan Pembayaran
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($error)
                <div class="bg-white/95 rounded-3xl p-12 text-center shadow-2xl border border-white/20">
                    <div
                        class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-[#003366] uppercase tracking-tight mb-2">Data Tidak Ditemukan
                    </h3>
                    <p class="text-slate-500 font-medium mb-6">{{ $error }}</p>
                    <a href="{{ route('check.order') }}"
                        class="inline-block text-[#00ACB1] font-bold uppercase tracking-widest text-xs hover:underline">
                        Coba Lagi
                    </a>
                </div>
            @endif

            <!-- Back Link -->
            <div class="text-center mt-8">
                <a href="/"
                    class="text-white/60 hover:text-white font-bold uppercase tracking-[4px] text-[10px] transition-all flex items-center justify-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali Ke Beranda
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
