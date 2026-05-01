<x-layouts.app>
    <div class="fixed inset-0 bg-[#0a0f18] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-20"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    
    <!-- Animated Gradient Background -->
    <div class="fixed inset-0 z-[-1] overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-orange-600/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="py-20 flex items-center justify-center p-4">
        <div class="max-w-7xl mx-auto w-full relative">
            
            <!-- Hero Section -->
            <div class="text-center mb-24 relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-full backdrop-blur-md mb-8">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                    </span>
                    <span class="text-white/70 text-[10px] font-black uppercase tracking-[4px]">Exclusive Partnership</span>
                </div>
                
                <h1 class="text-5xl md:text-8xl font-black text-white font-['Outfit'] tracking-tighter uppercase leading-[0.9] mb-8">
                    IPB RUN <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">2026</span><br>
                    <span class="text-3xl md:text-5xl opacity-50">Sponsorship Program</span>
                </h1>
                
                <p class="text-slate-400 max-w-2xl mx-auto text-base md:text-lg font-medium leading-relaxed mb-12">
                    Bergabunglah sebagai mitra strategis dalam event lari paling prestisius tahun ini. Tingkatkan eksposur brand Anda di hadapan ribuan peserta dan komunitas pelari.
                </p>

                <div class="flex flex-wrap justify-center gap-6">
                    <a href="#packages" class="px-10 py-5 bg-white text-black rounded-2xl font-black text-xs uppercase tracking-[2px] hover:scale-105 transition-all shadow-[0_0_40px_rgba(255,255,255,0.1)]">
                        Lihat Paket Kemitraan
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" class="px-10 py-5 bg-white/5 border border-white/10 text-white rounded-2xl font-black text-xs uppercase tracking-[2px] hover:bg-white/10 transition-all backdrop-blur-md">
                        Konsultasi Khusus
                    </a>
                </div>
            </div>

            <!-- Stats/Highlights -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 mb-32">
                @php
                    $stats = [
                        ['label' => 'Total Peserta', 'value' => '10.000+', 'icon' => 'users'],
                        ['label' => 'Media Reach', 'value' => '1M+', 'icon' => 'globe'],
                        ['label' => 'Community', 'value' => '50+', 'icon' => 'hash'],
                        ['label' => 'Brand Impact', 'value' => 'High', 'icon' => 'trending-up'],
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div class="p-8 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-sm text-center group hover:border-orange-500/50 transition-all">
                        <div class="text-3xl md:text-4xl font-black text-white mb-2 group-hover:text-orange-400 transition-colors">{{ $stat['value'] }}</div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-[2px]">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Packages Grid -->
            <div id="packages" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-24">
                @php
                    $packages = [
                        [
                            'name' => 'Platinum',
                            'price' => '100.000.000',
                            'color' => 'from-blue-600 to-indigo-600',
                            'glow' => 'rgba(37, 99, 235, 0.3)',
                            'features' => ['Main Stage Branding', 'Jersey Primary Logo', '100 Free Slots', 'Exclusive VIP Lounge', 'Booth 3x3 Prime', 'All Media Logo'],
                            'popular' => true
                        ],
                        [
                            'name' => 'Gold',
                            'price' => '50.000.000',
                            'color' => 'from-orange-500 to-red-600',
                            'glow' => 'rgba(249, 115, 22, 0.3)',
                            'features' => ['Backdrop Logo', 'Jersey Sleeve Logo', '50 Free Slots', 'Booth 2x2 Area A', 'Social Media Post'],
                            'popular' => false
                        ],
                        [
                            'name' => 'Silver',
                            'price' => '25.000.000',
                            'color' => 'from-slate-600 to-slate-800',
                            'glow' => 'rgba(71, 85, 105, 0.3)',
                            'features' => ['Jersey Back Logo', '25 Free Slots', 'Booth 2x2 Area B', 'Social Media Shoutout'],
                            'popular' => false
                        ],
                        [
                            'name' => 'Bronze',
                            'price' => '10.000.000',
                            'color' => 'from-amber-700 to-amber-900',
                            'glow' => 'rgba(180, 83, 9, 0.3)',
                            'features' => ['Social Media Logo', '10 Free Slots', 'Small Booth Space'],
                            'popular' => false
                        ]
                    ];
                @endphp

                @foreach($packages as $pkg)
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-br {{ $pkg['color'] }} rounded-[2rem] opacity-0 group-hover:opacity-100 blur-2xl transition-all duration-500" style="background-color: {{ $pkg['glow'] }}"></div>
                        
                        <div class="relative h-full bg-slate-900/80 border border-white/10 rounded-[2rem] p-10 flex flex-col backdrop-blur-xl transition-all duration-500 group-hover:-translate-y-4 group-hover:border-white/20">
                            @if($pkg['popular'])
                                <div class="absolute top-0 right-10 -translate-y-1/2 bg-orange-500 text-white text-[9px] font-black uppercase tracking-[2px] px-4 py-2 rounded-full shadow-xl">
                                    Most Wanted
                                </div>
                            @endif

                            <div class="mb-12">
                                <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-4">{{ $pkg['name'] }}</h3>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-xs font-bold text-slate-500">IDR</span>
                                    <span class="text-4xl font-black text-white tracking-tighter">{{ $pkg['price'] }}</span>
                                </div>
                            </div>

                            <div class="space-y-5 mb-12 flex-grow">
                                @foreach($pkg['features'] as $feature)
                                    <div class="flex items-start gap-4">
                                        <div class="mt-1 w-5 h-5 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 group-hover:border-orange-500/50 transition-colors">
                                            <svg class="w-3 h-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-400 leading-tight group-hover:text-white transition-colors">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <a href="{{ route('sponsorship.checkout', ['package' => strtolower($pkg['name'])]) }}" 
                               class="w-full py-5 rounded-2xl text-center text-[11px] font-black uppercase tracking-[3px] transition-all duration-300 relative overflow-hidden group/btn">
                                <div class="absolute inset-0 bg-gradient-to-r {{ $pkg['color'] }} opacity-100 group-hover/btn:scale-110 transition-transform"></div>
                                <span class="relative text-white">Pilih Paket</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer Links -->
            <div class="text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-slate-500 font-bold text-xs uppercase tracking-[4px] hover:text-white transition-all">
                    ← Back to Official Site
                </a>
            </div>
        </div>
    </div>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</x-layouts.app>
