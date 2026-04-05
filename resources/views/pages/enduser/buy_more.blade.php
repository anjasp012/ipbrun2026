<x-layouts.app title="Konfirmasi Pendaftaran Tambahan - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f4f6f9] z-[-2]"></div>
    
    <div class="min-h-screen py-20 px-6 sm:px-12 flex items-center justify-center">
        <div class="max-w-[550px] w-full bg-white rounded-[3rem] border border-slate-100 shadow-2xl p-10 md:p-14 text-center">
            
            <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-[2rem] flex items-center justify-center mx-auto mb-10 border border-blue-100 shadow-xl shadow-blue-50">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>

            <h2 class="text-xs font-black text-[#E8630A] uppercase tracking-[5px] mb-4">Pendaftaran Tambahan</h2>
            <h1 class="text-4xl font-[900] text-[#003366] uppercase tracking-tighter mb-8 italic">Konfirmasi Kategori</h1>

            <div class="p-8 bg-slate-50/80 border border-slate-100 rounded-[2.5rem] mb-12 space-y-6">
                <div class="flex justify-between items-center text-left">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 opacity-70">Nama Peserta</p>
                        <p class="text-lg font-black text-[#003366] uppercase tracking-tight">{{ $participant->name }}</p>
                    </div>
                </div>
                
                <div class="h-px bg-slate-200 border-dashed border-t"></div>

                <div class="flex justify-between items-center text-left">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 opacity-70">Kategori Baru</p>
                        <p class="text-3xl font-[900] text-[#E8630A] uppercase tracking-tighter mb-1">{{ $ticket->category->name }}</p>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ $ticket->name ?: strtoupper($ticket->type) }} Period</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 opacity-70">Harga Tiket</p>
                        <p class="text-xl font-black text-[#003366] tracking-tighter">Rp {{ number_format($ticket->price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="h-px bg-slate-200 border-dashed border-t"></div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Biaya Layanan</span>
                        <span class="text-xs font-black text-[#003366]">Rp 4.500</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                        <span class="text-xs font-black text-[#003366] uppercase tracking-[3px]">Total Bayar</span>
                        <span class="text-2xl font-[900] text-[#003366] tracking-tighter">Rp {{ number_format($ticket->price + 4500, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('participant.buy-more.process', $ticket->id) }}" method="POST" class="space-y-6">
                @csrf
                <button type="submit" class="w-full h-16 bg-[#003366] text-white rounded-2xl font-black uppercase tracking-[4px] text-xs hover:bg-blue-900 transition-all shadow-xl shadow-blue-950/20 active:scale-[0.98]">
                    Lanjutkan ke Pembayaran
                </button>
                <a href="{{ url('/dashboard') }}" class="block text-[11px] font-black text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-all">
                    Kembali ke Dashboard
                </a>
            </form>

            <div class="mt-14 p-6 bg-blue-50 border border-blue-100 rounded-3xl text-left flex gap-4">
                <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-[10px] font-bold text-blue-600/80 leading-relaxed uppercase tracking-widest italic">
                    Data pendaftaran (Jersey, NIK, dll) akan disesuaikan dengan data pendaftaran pertama Anda.
                </p>
            </div>

        </div>
    </div>
</x-layouts.app>
