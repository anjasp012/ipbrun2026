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

        {{-- Countdown Overlay --}}
        @if($ticketSaleStart)
        <div class="relative z-10 w-full max-w-lg px-6 flex flex-col items-center gap-10">
            <div id="countdown" class="flex gap-4 md:gap-8 justify-center items-center backdrop-blur-md bg-white/10 rounded-[3rem] p-8 border border-white/20 shadow-2xl">
                <div class="flex flex-col items-center">
                    <span id="days" class="text-4xl md:text-6xl font-black font-outfit tabular-nums text-white drop-shadow-lg">00</span>
                    <span class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.3em] overflow-hidden whitespace-nowrap text-white/50">Days</span>
                </div>
                <div class="text-2xl md:text-3xl font-black text-white/20 mb-6">:</div>
                <div class="flex flex-col items-center">
                    <span id="hours" class="text-4xl md:text-6xl font-black font-outfit tabular-nums text-white drop-shadow-lg">00</span>
                    <span class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.3em] overflow-hidden whitespace-nowrap text-white/50">Hours</span>
                </div>
                <div class="text-2xl md:text-3xl font-black text-white/20 mb-6">:</div>
                <div class="flex flex-col items-center">
                    <span id="minutes" class="text-4xl md:text-6xl font-black font-outfit tabular-nums text-white drop-shadow-lg">00</span>
                    <span class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.3em] overflow-hidden whitespace-nowrap text-white/50">Mins</span>
                </div>
                <div class="text-2xl md:text-3xl font-black text-white/20 mb-6">:</div>
                <div class="flex flex-col items-center">
                    <span id="seconds" class="text-4xl md:text-6xl font-black font-outfit tabular-nums text-[#FF7A21] drop-shadow-[0_0_15px_rgba(255,122,33,0.5)]">00</span>
                    <span class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.3em] overflow-hidden whitespace-nowrap text-[#FF7A21]/70">Secs</span>
                </div>
            </div>

            <div class="inline-flex items-center gap-3 bg-white/5 border border-white/10 px-6 py-2.5 rounded-full backdrop-blur-sm">
                <div class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FF7A21] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#FF7A21]"></span>
                </div>
                <span class="text-[9px] font-black text-white/80 uppercase tracking-[0.4em] italic leading-none">Registration Opens Soon</span>
            </div>
        </div>
        @endif
    </div>

    <script>
        @if($ticketSaleStart)
        const targetDate = new Date("{{ $ticketSaleStart }}").getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                const el = document.getElementById("countdown");
                if (el) el.innerHTML = "<div class='text-2xl md:text-3xl font-black text-white uppercase tracking-[0.2em] px-4'>PENDAFTARAN DIBUKA!</div>";
                
                // Optional: Auto reload if exactly on target
                if (Math.abs(distance) < 2000) {
                    setTimeout(() => window.location.reload(), 2000);
                }
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("days").innerText = days.toString().padStart(2, '0');
            document.getElementById("hours").innerText = hours.toString().padStart(2, '0');
            document.getElementById("minutes").innerText = minutes.toString().padStart(2, '0');
            document.getElementById("seconds").innerText = seconds.toString().padStart(2, '0');
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();
        @endif
    </script>
</x-layouts.app>
