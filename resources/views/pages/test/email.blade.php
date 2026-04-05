<x-layouts.app title="Test Email IPB RUN">
    <div class="min-h-screen flex items-center justify-center p-6 bg-slate-50">
        <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-slate-100 shadow-2xl">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Test Delivery Email</h1>
                <p class="text-slate-500 text-sm mt-1">Gunakan form ini untuk mengetes SMTP & PDF Invoice.</p>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 text-sm font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-50 text-rose-700 rounded-2xl border border-rose-100 text-sm font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ url('/test-email') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[2px] mb-3">Email Peserta / Tujuan</label>
                    <x-input type="email" name="email" placeholder="nama@email.com" required value="{{ old('email') }}" />
                </div>

                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 italic text-[11px] text-slate-500 leading-relaxed">
                    Sistem akan otomatis mencari data jika email di atas terdaftar. Jika tidak terdaftar, sistem akan mengambil data pendaftar terakhir di database sebagai mock data.
                </div>

                <x-button type="submit" class="w-full h-14 rounded-2xl">
                    KIRIM TEST EMAIL SEKARANG
                </x-button>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ url('/') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 uppercase tracking-wider transition-colors">
                    &larr; Kembali ke Home
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
