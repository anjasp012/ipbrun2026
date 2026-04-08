<x-layouts.app title="Registration Start - Tool" :withoutNavbar="true">
    <div class="fixed inset-0 bg-[#001A33] flex items-center justify-center p-6 select-none overflow-hidden">
        <!-- Background Elements -->
        <div class="fixed inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="absolute inset-x-0 top-1/2 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-y-1/2"></div>
        <div class="absolute inset-y-0 left-1/2 w-px bg-gradient-to-b from-transparent via-[#FF7A21]/30 to-transparent -translate-x-1/2"></div>

        <div class="relative w-full max-w-lg" x-data="startTool()">
            <!-- State 1: Password Input -->
            <template x-if="state === 'password'">
                <div class="max-w-md mx-auto text-center space-y-10" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <img src="{{ asset('assets/images/logo_ipbrun2026.png') }}" class="h-20 mx-auto drop-shadow-xl" alt="">
                    
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-[#FF7A21] uppercase tracking-[5px] block">SECURITY CLEARANCE</label>
                        <input type="password" x-model="password" 
                            @keyup.enter="checkPass()"
                            placeholder="••••••••••••"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl h-16 text-center text-white text-xl font-bold tracking-[4px] focus:outline-none focus:border-[#FF7A21]/50 focus:bg-white/10 transition-all">
                        <template x-if="error">
                            <p class="text-xs font-bold text-red-500 uppercase tracking-widest italic" x-text="error"></p>
                        </template>
                    </div>

                    <button @click="checkPass()" 
                        class="w-full h-16 bg-[#FF7A21] text-white rounded-2xl font-black text-xs uppercase tracking-[4px] shadow-lg shadow-orange-900/20 active:scale-95 transition-all">
                        Verify Control
                    </button>
                </div>
            </template>

            <!-- State 2: Ready Button -->
            <template x-if="state === 'ready'">
                <div class="text-center space-y-12" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                    <div class="space-y-4">
                        <h2 class="text-white text-3xl font-[900] uppercase tracking-tighter italic">SYSTEM READY</h2>
                        <p class="text-[11px] font-bold text-white/40 uppercase tracking-[4px]">PRESS START TO TRIGGER COUNTDOWN</p>
                    </div>

                    <div class="relative group">
                        <div class="absolute inset-0 bg-red-600 blur-3xl opacity-20 pointer-events-none group-hover:opacity-40 transition-opacity"></div>
                        <button @click="startCountdown()" 
                            class="relative w-48 h-48 bg-red-600 text-white rounded-full font-black text-2xl uppercase tracking-[2px] shadow-2xl shadow-red-950/50 border-8 border-red-500/50 hover:bg-red-500 hover:scale-105 active:scale-90 transition-all animate-pulse">
                            START
                        </button>
                    </div>
                </div>
            </template>

            <!-- State 3: Countdown -->
            <template x-if="state === 'count'">
                <div class="text-center" x-transition:enter="transition ease-out duration-300">
                    <div class="text-[200px] leading-none font-black text-[#FF7A21] drop-shadow-[0_0_50px_rgba(255,122,33,0.4)] animate-bounce" x-text="count"></div>
                    <p class="text-white font-[900] text-3xl uppercase tracking-[15px] opacity-40 mt-8">INITIATING</p>
                </div>
            </template>
        </div>
    </div>

    @push('scripts')
    <script>
        function startTool() {
            return {
                state: 'password', // password, ready, count, finish
                password: '',
                error: '',
                count: 3,

                checkPass() {
                    if (this.password === 'IpbRun2026#') {
                        this.state = 'ready';
                        this.error = '';
                    } else {
                        this.error = 'Invalid Credentials';
                        this.password = '';
                    }
                },

                startCountdown() {
                    this.state = 'count';
                    const timer = setInterval(() => {
                        this.count--;
                        if (this.count <= 0) {
                            clearInterval(timer);
                            this.finish();
                        }
                    }, 1000);
                },

                async finish() {
                    try {
                        const response = await fetch("{{ route('trigger.start') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ password: this.password })
                        });

                        const result = await response.json();
                        if (result.success) {
                            window.location.href = result.redirect;
                        } else {
                            alert(result.message);
                            window.location.reload();
                        }
                    } catch (e) {
                        alert("Error contacting server");
                        window.location.reload();
                    }
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
