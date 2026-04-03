<x-layouts.app title="Coming Soon">
    <div class="min-h-screen flex flex-col items-center justify-center bg-white selection:bg-blue-50">
        {{-- Soft Decorative Elements --}}
        <div class="fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-[#003366] via-blue-400 to-[#003366]"></div>
        <div class="absolute top-0 right-0 p-12 opacity-5 hidden md:block">
            <img src="{{ asset('assets/images/logo_ipbrun2026.png') }}" alt="" class="h-64 grayscale">
        </div>

        <main class="relative z-10 max-w-2xl px-6 text-center">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-[#003366] rounded-full mb-10 border border-blue-100">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#003366] opacity-30"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#003366]"></span>
                </span>
                <span class="text-[9px] font-black uppercase tracking-[0.3em] italic">Official Pre-Launch</span>
            </div>

            {{-- Main Content --}}
            <div class="mb-12">
                <img src="{{ asset('assets/images/logo_ipbrun2026.png') }}" alt="IPB Run 2026" class="h-24 md:h-32 mx-auto mb-10 drop-shadow-sm">
                <h1 class="text-4xl md:text-6xl font-black text-[#003366] tracking-tight leading-[1.1] mb-6 uppercase">
                    COMING SOON
                </h1>
                <p class="text-slate-500 text-lg font-medium leading-relaxed max-w-lg mx-auto">
                    Pendaftaran IPB Run 2026 akan segera dibuka. <br class="hidden md:block"> Ikuti perkembangan terbaru di media sosial kami.
                </p>
            </div>

            {{-- Action & Social --}}
            <div class="flex flex-col items-center gap-10">
                <div class="flex flex-wrap justify-center gap-4">
                    <x-button variant="navy" href="https://www.instagram.com/ipb_halfmarathon/" target="_blank" class="px-10 py-4 rounded-full">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.76-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.76 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        Follow Instagram
                    </x-button>
                </div>
                
                <div class="space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400">Official Partner & Management</p>
                    <div class="flex items-center justify-center gap-4 opacity-30">
                        {{-- Small Logo IPB Placeholder if exists --}}
                        <div class="h-8 w-px bg-slate-300"></div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">IPB University</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-layouts.app>
