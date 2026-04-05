<x-layouts.app title="Coming Soon">
    <div class="fixed inset-0 w-full h-full bg-[#003366] overflow-hidden flex flex-col items-center justify-end pb-20">
        {{-- Desktop Background --}}
        <div class="absolute inset-0 hidden md:block w-full h-full bg-cover bg-center bg-no-repeat" 
             style="background-image: url('{{ asset('assets/images/cooming_soon_desktop.png') }}')">
        </div>
        
        {{-- Mobile Background --}}
        <div class="absolute inset-0 block md:hidden w-full h-full bg-cover bg-center bg-no-repeat" 
             style="background-image: url('{{ asset('assets/images/cooming_soon_mobile.png') }}')">
        </div>

        {{-- Maintenance Overlay --}}
        <div class="relative z-10 w-full max-w-lg px-6 flex flex-col items-center gap-10">
            <div class="backdrop-blur-md bg-white/10 rounded-[3rem] p-8 md:p-12 border border-white/20 shadow-2xl text-center">
                <img src="{{ asset('assets/images/logo_ipbrun2026.png') }}" alt="IPB Run 2026" class="h-16 mx-auto mb-8 brightness-0 invert opacity-80">
                <h2 class="text-2xl md:text-4xl font-black text-white uppercase tracking-widest px-4 leading-tight mb-4">MAINTENANCE</h2>
                <div class="w-12 h-1 bg-[#FF7A21] mx-auto mb-6 rounded-full"></div>
                <p class="text-[10px] md:text-xs font-bold text-white/60 uppercase tracking-[0.3em] leading-relaxed">
                    Kami sedang mempersiapkan sistem pendaftaran yang lebih baik. <br> Mohon kembali lagi dalam beberapa saat.
                </p>
            </div>
            
            <div class="inline-flex items-center gap-3 bg-white/5 border border-white/10 px-6 py-2.5 rounded-full backdrop-blur-sm">
                <div class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></div>
                <span class="text-[9px] font-black text-white/40 uppercase tracking-[0.4em] italic leading-none">Official Management</span>
            </div>
        </div>
    </div>
</x-layouts.app>
