<x-layouts.app title="Payment - IPB RUN 2026">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center">
        <div class="max-w-[500px] w-full bg-white border border-slate-100 rounded-2xl shadow-2xl transition-all duration-300 overflow-hidden">
            
            <!-- Header Ticket -->
            <div class="p-8 text-center bg-white">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-orange-50 rounded-full mb-4">
                    <svg class="w-6 h-6 text-[#E8630A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h1 class="text-xl font-[800] text-[#003366] uppercase tracking-tight mb-1">Menunggu Pembayaran</h1>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest">Selesaikan pembayaran Anda segera</p>
            </div>

            <!-- Perforation Detail -->
            <div class="relative flex items-center py-1 overflow-hidden pointer-events-none">
                <div class="absolute -left-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-100/20"></div>
                <div class="absolute -right-3 w-6 h-6 bg-[#f1f5f9] rounded-full shadow-inner ring-1 ring-inset ring-slate-100/20"></div>
                <div class="w-full border-t border-dashed border-slate-200 mx-5"></div>
            </div>

            <!-- Content -->
            <div class="p-8 bg-slate-50/40">
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Peserta</span>
                        <span class="text-[#003366] font-bold">{{ $participant->name }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Kategori</span>
                        <span class="text-[#003366] font-bold">{{ $participant->ticket->category->name }} ({{ $participant->ticket->name }})</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Nomor WhatsApp</span>
                        <span class="text-[#003366] font-bold">{{ $participant->phone_number }}</span>
                    </div>
                    
                    <div class="pt-4 border-t border-dashed border-slate-200">
                        <div class="flex justify-between items-end">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-slate-400 font-[800] uppercase tracking-wider block mb-1">Total Pembayaran</span>
                                <div class="text-[32px] font-[900] text-[#003366] leading-none font-['Plus_Jakarta_Sans']">
                                    Rp {{ number_format($participant->total_price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button id="pay-button" class="w-full bg-[#003366] text-white py-4 rounded-xl font-[800] text-[15px] shadow-xl shadow-[#003366]/20 transition-all active:scale-95 hover:bg-[#002244] flex items-center justify-center gap-3">
                    <span>Bayar Sekarang</span>
                    <svg class="w-5 h-5 animate-bounce-x" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>

                <p class="mt-6 text-[10px] text-slate-400 text-center leading-relaxed">
                    Klik tombol di atas untuk menyelesaikan pembayaran melalui Midtrans.<br>
                    Status pendaftaran akan otomatis diperbarui setelah pembayaran berhasil.
                </p>
            </div>
        </div>

        <a href="{{ url('/') }}" class="mt-8 text-slate-400 font-bold text-[11px] uppercase tracking-widest hover:text-[#003366] transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Beranda
        </a>
    </div>

    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            window.snap.pay('{{ $participant->snap_token }}', {
                onSuccess: function (result) {
                    /* You may add your own implementation here */
                    window.location.href = "{{ url('/') }}";
                },
                onPending: function (result) {
                    /* You may add your own implementation here */
                    location.reload();
                },
                onError: function (result) {
                    /* You may add your own implementation here */
                    alert("Pembayaran gagal!");
                },
                onClose: function () {
                    /* You may add your own implementation here */
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                }
            });
        });
    </script>

    <style>
        @keyframes bounce-x {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(5px); }
        }
        .animate-bounce-x {
            animation: bounce-x 1s infinite;
        }
    </style>
</x-app>
