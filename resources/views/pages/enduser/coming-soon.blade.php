<x-layouts.app :withoutNavbar="true">
    <div class="fixed inset-0 w-full h-full bg-[#003366] overflow-hidden flex flex-col items-center justify-end pb-20" x-data="startTool()">
        {{-- Desktop Background --}}
        <div class="absolute inset-0 hidden md:block w-full h-full bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('assets/images/cooming_soon_desktop.png') }}')">
        </div>
        {{-- Mobile Background --}}
        <div class="absolute inset-0 block md:hidden w-full h-full bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('assets/images/cooming_soon_mobile.png') }}')">
        </div>

        <!-- Secret Trigger (Invisible button on top left) -->
        <div @click="state = 'password'" class="absolute top-0 left-0 w-20 h-20 z-50 cursor-default"></div>

        <!-- Start Tool Overlay -->
        <template x-if="state !== 'coming'">
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-[#001A33]/90 backdrop-blur-xl">
                <div class="relative w-full max-w-lg">
                    <!-- State: Password Input -->
                    <template x-if="state === 'password'">
                        <div class="max-w-md mx-auto text-center space-y-10" x-transition:enter="transition ease-out duration-300">
                            <img src="{{ asset('assets/images/logo_ipbrun2026.png') }}" class="h-20 mx-auto" alt="">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#FF7A21] uppercase tracking-[5px] block">SECURITY CLEARANCE</label>
                                <input type="password" x-model="password" 
                                    @focus="playStandby()"
                                    @keyup.enter="checkPass()"
                                    placeholder="••••••••••••"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl h-16 text-center text-white text-xl font-bold tracking-[4px] focus:outline-none focus:border-[#FF7A21]/50 focus:bg-white/10 transition-all">
                            </div>
                            <div class="flex flex-col gap-3">
                                <button @click="checkPass()" 
                                    class="w-full h-16 bg-[#FF7A21] text-white rounded-2xl font-black text-xs uppercase tracking-[4px] shadow-lg shadow-orange-900/20 active:scale-95 transition-all">
                                    Verify Access
                                </button>
                                <button @click="state = 'coming'" class="text-white/20 text-[10px] font-bold uppercase tracking-widest">Cancel</button>
                            </div>
                        </div>
                    </template>

                    <!-- State: Ready Button -->
                    <template x-if="state === 'ready'">
                        <div class="text-center space-y-12" x-transition:enter="transition ease-out duration-500">
                            <div class="space-y-4">
                                <h2 class="text-white text-3xl font-[900] uppercase tracking-tighter italic text-center">LAUNCH READY</h2>
                                <p class="text-[11px] font-bold text-white/40 uppercase tracking-[4px]">INITIATE FINAL SEQUENCE</p>
                            </div>
                            <button @click="startCountdown()" 
                                class="relative w-48 h-48 bg-red-600 text-white rounded-full font-black text-2xl uppercase tracking-[2px] shadow-2xl shadow-red-950/50 border-8 border-red-500/50 hover:bg-red-500 hover:scale-105 transition-all animate-pulse mx-auto block">
                                START
                            </button>
                        </div>
                    </template>

                    <!-- State: Countdown -->
                    <template x-if="state === 'count'">
                        <div class="text-center">
                            <div class="text-[200px] leading-none font-black text-[#FF7A21] drop-shadow-[0_0_50px_rgba(255,122,33,0.4)] animate-bounce" x-text="count"></div>
                            <p class="text-white font-[900] text-3xl uppercase tracking-[15px] opacity-40 mt-8">INITIATING</p>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <script>
        function startTool() {
            return {
                state: 'coming',
                password: '',
                count: 7,
                sounds: {
                    standby: new Audio("{{ asset('assets/sounds/standby.mpeg') }}"),
                    start: new Audio("{{ asset('assets/sounds/start.mpeg') }}"),
                },
                init() {
                    this.sounds.standby.loop = true;
                    document.addEventListener('click', () => { this.playStandby(); }, { once: true });
                },
                playStandby() { this.sounds.standby.play().catch(e => {}); },
                checkPass() {
                    if (this.password === 'IpbRun2026#') {
                        this.state = 'ready';
                    } else {
                        alert('Invalid Credentials');
                        this.password = '';
                    }
                },
                startCountdown() {
                    this.sounds.standby.pause();
                    this.sounds.start.play();
                    this.state = 'count';
                    const timer = setInterval(() => {
                        this.count--;
                        if (this.count <= 0) {
                            clearInterval(timer);
                            this.triggerAdminStart();
                        }
                    }, 1000);
                },
                async triggerAdminStart() {
                    try {
                        const response = await fetch("{{ route('trigger.start') }}", {
                            method: "POST",
                            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                            body: JSON.stringify({ password: this.password })
                        });
                        const result = await response.json();
                        if (result.success) {
                            window.location.href = '/';
                        } else {
                            alert(result.message);
                            window.location.reload();
                        }
                    } catch (e) {
                        window.location.reload();
                    }
                }
            }
        }
    </script>
</x-layouts.app>
