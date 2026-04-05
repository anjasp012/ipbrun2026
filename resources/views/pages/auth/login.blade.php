<x-layouts.app title="Sign In - IPB RUN 2026">
    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/40 z-[-1]"></div>

    <div class="min-h-screen flex flex-col items-center justify-center p-6 sm:p-12">
        <!-- Login Card -->
        <div class="max-w-[480px] w-full bg-white/95 backdrop-blur-xl rounded-[2.5rem] shadow-[0_40px_80px_-15px_rgba(0,0,0,0.3)] border border-white/50 p-10 md:p-14 relative overflow-hidden group transition-all duration-500 hover:shadow-[0_50px_100px_-20px_rgba(0,0,0,0.35)]">
            
            <!-- Glow Effect -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-orange-500/10 rounded-full blur-3xl group-hover:bg-orange-500/20 transition-all duration-700"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-600/10 rounded-full blur-3xl group-hover:bg-blue-600/20 transition-all duration-700"></div>

            <div class="relative z-10">
                <!-- Brand/Logo Area -->
                <div class="text-center mb-10 text-left">
                    <div class="flex flex-col items-center gap-3">
                         <span class="text-[12px] font-black text-blue-600 uppercase tracking-[4px] opacity-80">IPB RUN 2026</span>
                         <h2 class="text-4xl font-[900] text-[#003366] leading-none uppercase tracking-tighter">Sign In</h2>
                         <div class="h-1.5 w-12 bg-gradient-to-r from-orange-500 to-amber-400 rounded-full mt-4"></div>
                    </div>
                </div>

                <!-- Admin/Support Notice -->
                <div class="mb-10 p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center leading-relaxed">
                        Silakan masukkan email dan password yang Anda terima melalui WhatsApp/Email.
                    </p>
                </div>

                <form id="loginForm" action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="email" class="block text-[11px] font-black text-slate-400 uppercase tracking-[2px] ml-1">Email Address</label>
                        <div class="relative">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full h-14 bg-slate-50/50 border border-slate-100 rounded-2xl px-6 font-bold text-[#003366] placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-200 focus:bg-white transition-all text-left">
                            <svg class="absolute right-5 top-[18px] w-5 h-5 text-slate-300 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-12H12"></path></svg>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-[10px] font-black uppercase italic mt-2 ml-1 tracking-wider animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2 text-left">
                        <div class="flex justify-between items-center ml-1">
                            <label for="password" class="text-[11px] font-black text-slate-400 uppercase tracking-[2px]">Password</label>
                        </div>
                        <div class="relative">
                            <input id="password" type="password" name="password" required 
                                class="w-full h-14 bg-slate-50/50 border border-slate-100 rounded-2xl px-6 font-bold text-[#003366] placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-200 focus:bg-white transition-all text-left">
                            <svg class="absolute right-5 top-[18px] w-5 h-5 text-slate-300 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-[10px] font-black uppercase italic mt-2 ml-1 tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pb-2 ml-1 flex-row text-left">
                        <label class="flex items-center gap-3 cursor-pointer group">
                             <div class="relative">
                                <input type="checkbox" name="remember" class="sr-only peer">
                                <div class="w-5 h-5 bg-slate-100 border border-slate-200 rounded-lg peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                             </div>
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">Remember device</span>
                        </label>
                    </div>

                    <button type="submit" 
                        class="w-full h-16 bg-[#003366] hover:bg-[#002244] text-white rounded-2xl font-black text-sm uppercase tracking-[3px] shadow-xl shadow-blue-900/10 hover:shadow-blue-900/20 active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                        Masuk Sekarang
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>
                </form>

                <div class="mt-12 text-center text-left">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[2px] leading-relaxed">
                        Belum mendaftar? <br>
                        <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 transition-colors border-b-2 border-blue-100 hover:border-blue-300 pb-0.5">Daftar Tiket Disini</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer Copyright -->
        <p class="mt-12 text-[10px] font-black text-white/50 uppercase tracking-[4px]">© IPB RUN 2026 EXHIBITION</p>
    </div>
</x-layouts.app>
