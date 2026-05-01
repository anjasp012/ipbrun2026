<x-layouts.app withoutNavbar="true">
    <div class="fixed inset-0 bg-[#0a0f18] z-[-2]"></div>
    
    <div class="min-h-screen flex flex-col lg:flex-row overflow-hidden">
        
        <!-- Left Side: Visual & Summary -->
        <div class="lg:w-[45%] relative bg-slate-900 flex flex-col p-12 lg:p-24 overflow-hidden border-r border-white/5">
            <div class="absolute inset-0 bg-cover bg-center opacity-20 scale-110 blur-sm" style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/40 via-transparent to-orange-900/20"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <a href="{{ route('sponsorship.index') }}" class="inline-flex items-center gap-3 text-white/50 hover:text-white transition-colors text-[10px] font-black uppercase tracking-[3px] mb-16">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    Back to Packages
                </a>

                @php
                    $package = request('package', 'platinum');
                    $packages = [
                        'platinum' => ['name' => 'Platinum', 'price' => '100.000.000', 'color' => 'from-blue-500 to-indigo-600', 'tag' => 'Elite Partnership'],
                        'gold' => ['name' => 'Gold', 'price' => '50.000.000', 'color' => 'from-orange-400 to-red-600', 'tag' => 'Premium Partnership'],
                        'silver' => ['name' => 'Silver', 'price' => '25.000.000', 'color' => 'from-slate-400 to-slate-600', 'tag' => 'Standard Partnership'],
                        'bronze' => ['name' => 'Bronze', 'price' => '10.000.000', 'color' => 'from-amber-600 to-amber-800', 'tag' => 'Basic Partnership'],
                    ];
                    $selected = $packages[$package] ?? $packages['platinum'];
                @endphp

                <div class="mt-auto">
                    <div class="inline-block px-4 py-1.5 bg-white/5 border border-white/10 rounded-full text-white/40 text-[9px] font-black uppercase tracking-[3px] mb-6">
                        {{ $selected['tag'] }}
                    </div>
                    <h2 class="text-5xl md:text-7xl font-black text-white font-['Outfit'] tracking-tighter uppercase leading-none mb-4">
                        {{ $selected['name'] }}<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r {{ $selected['color'] }}">Package</span>
                    </h2>
                    <div class="flex items-baseline gap-2 mb-12">
                        <span class="text-xl font-bold text-white/30 tracking-tight">IDR</span>
                        <span class="text-5xl font-black text-white tracking-tighter">{{ $selected['price'] }}</span>
                    </div>

                    <div class="p-8 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-xl">
                        <div class="text-[10px] font-black text-white/40 uppercase tracking-[3px] mb-6">Partnership Benefits</div>
                        <div class="space-y-4">
                            @php
                                $benefits = [
                                    'platinum' => ['Main Stage Exposure', 'Jersey Front Branding', 'VIP Networking Access'],
                                    'gold' => ['Backdrop Logo Placement', 'Jersey Sleeve Branding', 'Premium Booth Space'],
                                    'silver' => ['Official Website Listing', 'Social Media Integration', 'Standard Booth Space'],
                                    'bronze' => ['Logo on Social Media', 'Participant Kit Inclusion', 'Basic Booth Space'],
                                ][$package] ?? ['Exclusive Branding', 'Network Access', 'Event Promotion'];
                            @endphp
                            @foreach($benefits as $b)
                                <div class="flex items-center gap-4 text-white/70 text-sm font-semibold">
                                    <div class="w-2 h-2 rounded-full bg-gradient-to-r {{ $selected['color'] }}"></div>
                                    {{ $b }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="flex-1 bg-[#f8fafc] p-8 lg:p-24 overflow-y-auto custom-scrollbar">
            <div class="max-w-2xl">
                <div class="mb-16">
                    <h1 class="text-3xl font-black text-[#0f172a] uppercase tracking-tight mb-4">Formulir Kemitraan</h1>
                    <p class="text-slate-500 font-medium">Lengkapi rincian berikut untuk memulai proses kerjasama strategis Anda bersama kami.</p>
                </div>

                <form action="#" method="POST" class="space-y-12">
                    @csrf
                    <input type="hidden" name="package" value="{{ $package }}">

                    <!-- Company Info -->
                    <section class="space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#0f172a] text-white flex items-center justify-center text-[10px] font-black">01</span>
                            <h3 class="text-xs font-black text-[#0f172a] uppercase tracking-[4px]">Company Identification</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-8">
                            <div class="group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-3 group-focus-within:text-blue-600 transition-colors">Company / Brand Name</label>
                                <input type="text" name="company_name" required placeholder="PT. Nama Perusahaan" class="w-full bg-transparent border-b-2 border-slate-200 py-4 font-black text-slate-800 placeholder:text-slate-200 focus:outline-none focus:border-blue-600 transition-all text-xl">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="group">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-3 group-focus-within:text-blue-600 transition-colors">PIC Name</label>
                                    <input type="text" name="pic_name" required placeholder="Nama Lengkap" class="w-full bg-transparent border-b-2 border-slate-200 py-3 font-bold text-slate-800 placeholder:text-slate-200 focus:outline-none focus:border-blue-600 transition-all">
                                </div>
                                <div class="group">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-3 group-focus-within:text-blue-600 transition-colors">Position</label>
                                    <input type="text" name="pic_position" required placeholder="Contoh: Manager Marketing" class="w-full bg-transparent border-b-2 border-slate-200 py-3 font-bold text-slate-800 placeholder:text-slate-200 focus:outline-none focus:border-blue-600 transition-all">
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Contact Info -->
                    <section class="space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#0f172a] text-white flex items-center justify-center text-[10px] font-black">02</span>
                            <h3 class="text-xs font-black text-[#0f172a] uppercase tracking-[4px]">Communication Channels</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-3 group-focus-within:text-blue-600 transition-colors">Business Email</label>
                                <input type="email" name="email" required placeholder="name@company.com" class="w-full bg-transparent border-b-2 border-slate-200 py-3 font-bold text-slate-800 placeholder:text-slate-200 focus:outline-none focus:border-blue-600 transition-all">
                            </div>
                            <div class="group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-3 group-focus-within:text-blue-600 transition-colors">WhatsApp Number</label>
                                <input type="text" name="phone" required placeholder="08xxxxxxxxxx" class="w-full bg-transparent border-b-2 border-slate-200 py-3 font-bold text-slate-800 placeholder:text-slate-200 focus:outline-none focus:border-blue-600 transition-all">
                            </div>
                        </div>
                    </section>

                    <!-- Address & Notes -->
                    <section class="space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#0f172a] text-white flex items-center justify-center text-[10px] font-black">03</span>
                            <h3 class="text-xs font-black text-[#0f172a] uppercase tracking-[4px]">Office & Additional Details</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-8">
                            <div class="group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-3 group-focus-within:text-blue-600 transition-colors">Office Address</label>
                                <textarea name="address" required rows="2" placeholder="Alamat lengkap perusahaan..." class="w-full bg-transparent border-b-2 border-slate-200 py-3 font-bold text-slate-800 placeholder:text-slate-200 focus:outline-none focus:border-blue-600 transition-all resize-none"></textarea>
                            </div>
                            <div class="group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-3 group-focus-within:text-blue-600 transition-colors">Message / Request (Optional)</label>
                                <textarea name="notes" rows="3" placeholder="Sebutkan jika ada penyesuaian khusus..." class="w-full bg-transparent border-b-2 border-slate-200 py-3 font-bold text-slate-800 placeholder:text-slate-200 focus:outline-none focus:border-blue-600 transition-all resize-none"></textarea>
                            </div>
                        </div>
                    </section>

                    <div class="pt-8">
                        <button type="submit" class="w-full py-6 bg-[#0f172a] text-white rounded-2xl font-black text-[12px] uppercase tracking-[4px] shadow-2xl shadow-blue-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Submit Partnership Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</x-layouts.app>
