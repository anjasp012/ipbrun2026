<x-layouts.admin title="Blast Email RPC">
    <div class="space-y-8">
        {{-- Header Banner --}}
        <div class="bg-gradient-to-br from-[#003366] to-[#001c38] rounded-2xl p-8 flex items-center justify-between relative overflow-hidden shadow-2xl shadow-blue-950/20">
            <div class="absolute -right-10 -top-10 w-56 h-56 bg-white/5 rounded-full"></div>
            <div class="absolute -left-6 -bottom-10 w-40 h-40 bg-[#E8630A]/10 rounded-full"></div>
            <div class="relative z-10">
                <p class="text-[11px] font-black text-[#E8630A] uppercase tracking-[5px] mb-2">Admin Panel</p>
                <h2 class="text-3xl font-black text-white uppercase tracking-tight">Blast Email RPC</h2>
                <p class="text-white/50 text-sm font-bold mt-2 tracking-wide">Kirim pengingat jadwal dan instruksi pengambilan Race Pack ke beberapa peserta sekaligus.</p>
            </div>
            <div class="relative z-10 hidden md:flex items-center gap-3">
                <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="p-6 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl flex items-start gap-4">
                <svg class="w-6 h-6 flex-shrink-0 mt-0.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-widest mb-1">Berhasil</h4>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="p-6 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl flex items-start gap-4">
                <svg class="w-6 h-6 flex-shrink-0 mt-0.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-widest mb-1">Gagal</h4>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Form Area --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-8">
            <form action="{{ route('admin.scan-rpc.blast.send') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="emails" class="block text-sm font-black text-slate-800 uppercase tracking-widest mb-2">
                            Daftar Email Peserta
                        </label>
                        <p class="text-xs text-slate-500 mb-4">
                            Masukkan daftar email peserta yang ingin Anda kirimi blast (pisahkan dengan koma, spasi, atau baris baru/enter).
                        </p>
                        <textarea 
                            name="emails" 
                            id="emails" 
                            rows="10" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:border-[#003366] focus:ring-2 focus:ring-[#003366]/20 transition-all font-mono"
                            placeholder="email1@gmail.com&#10;email2@yahoo.com&#10;email3@ipb.ac.id"
                            required></textarea>
                    </div>

                    <div class="flex items-center gap-4 bg-amber-50 p-4 border border-amber-100 rounded-xl text-amber-800">
                        <svg class="w-6 h-6 flex-shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="text-xs">
                            <strong>Perhatian:</strong> Sistem akan mengantrekan email ini (queue). Pengiriman memakan waktu tergantung jumlah email.
                        </p>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengirim email blast ke seluruh email yang dimasukkan?')"
                            class="h-12 px-8 bg-[#E8630A] text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-orange-600 transition-all shadow-lg shadow-orange-900/20 transform hover:scale-[1.02] active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Kirim Email Blast
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
