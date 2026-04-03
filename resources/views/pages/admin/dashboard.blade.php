<x-layouts.admin title="Overview Dashboard">
    <div class="space-y-8">
        <!-- System Status Alert -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 overflow-hidden relative">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-4">
                    <div @class([
                        'w-12 h-12 rounded-2xl flex items-center justify-center transition-colors',
                        'bg-emerald-50 text-emerald-600' => $stats['is_running'],
                        'bg-rose-50 text-rose-600' => !$stats['is_running']
                    ])>
                        @if($stats['is_running'])
                            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest">Website Status</h2>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                            Currently: 
                            <span @class([
                                'px-2 py-0.5 rounded-full text-[10px]',
                                'bg-emerald-100 text-emerald-700' => $stats['is_running'],
                                'bg-rose-100 text-rose-700' => !$stats['is_running']
                            ])>
                                {{ $stats['is_running'] ? 'Running / Registration Open' : 'Maintenance / Coming Soon' }}
                            </span>
                        </p>
                    </div>
                </div>
                
                <form action="{{ url('/admin/toggle-running') }}" method="POST">
                    @csrf
                    <x-button type="submit" 
                        variant="{{ $stats['is_running'] ? 'danger' : 'success' }}" 
                        class="px-6 py-2.5 rounded-xl">
                        {{ $stats['is_running'] ? 'Set to Maintenance' : 'Open Registration' }}
                    </x-button>
                </form>
            </div>
            <!-- Decorative Bg -->
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-64 h-64 bg-slate-50 rounded-full opacity-50"></div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm transition-all hover:shadow-lg">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Paid Runners</div>
                <div class="flex items-center justify-between">
                    <div class="text-3xl font-black text-[#003366]">{{ number_format($stats['total_participants']) }}</div>
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-2 text-[10px] font-bold text-emerald-600 uppercase tracking-wider">ATHLETES REGISTERED</div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm transition-all hover:shadow-lg">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Revenue</div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-black text-[#003366]">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-2 text-[10px] font-bold text-blue-600 uppercase tracking-wider">SETTLED TRANSACTIONS</div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm transition-all hover:shadow-lg border-l-4 border-l-[#E8630A]">
                <div class="text-[10px] font-black text-[#E8630A] uppercase tracking-widest mb-1">Pending Balance</div>
                <div class="flex items-center justify-between">
                    <div class="text-3xl font-black text-slate-800">{{ number_format($stats['pending_payments']) }}</div>
                    <div class="w-10 h-10 rounded-full bg-orange-50 text-[#E8630A] flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">WAITING FOR PAYMENT</div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm transition-all hover:shadow-lg">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Racepack Picked</div>
                <div class="flex items-center justify-between">
                    <div class="text-3xl font-black text-slate-800">0</div>
                    <div class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">LOGISTICS TRACKING</div>
            </div>
        </div>

        <!-- Charts / Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h3 class="text-sm font-black text-[#003366] uppercase tracking-[3px] mb-8 pb-4 border-b border-slate-50">Registration by Category</h3>
                <div class="space-y-6">
                    @forelse($stats['registrations_by_category'] as $category)
                    <div>
                        <div class="flex justify-between text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                            <span>{{ $category->name }}</span>
                            <span>{{ $category->count }} Runners</span>
                        </div>
                        <div class="w-full bg-slate-50 h-3 rounded-full overflow-hidden border border-slate-100">
                             <!-- Simple Progress Bar Mockup -->
                            <div class="bg-blue-600 h-full rounded-full" style="width: {{ ($category->count / ($stats['total_participants'] ?: 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic">No data available yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#003366] to-[#002244] p-8 rounded-3xl shadow-xl shadow-blue-200">
                <h3 class="text-sm font-black text-white/50 uppercase tracking-[3px] mb-8 pb-4 border-b border-white/10">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ url('/admin/participants?status=pending') }}" class="p-4 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-all group">
                        <div class="text-[#E8630A] font-black text-xl mb-1 group-hover:scale-110 transition-transform">Follow Up</div>
                        <p class="text-[10px] text-white/50 font-bold uppercase tracking-widest">Pending Payments</p>
                    </a>
                    <a href="{{ url('/admin/participants') }}" class="p-4 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-all group">
                        <div class="text-emerald-400 font-black text-xl mb-1 group-hover:scale-110 transition-transform">Export</div>
                        <p class="text-[10px] text-white/50 font-bold uppercase tracking-widest">Participant List</p>
                    </a>
                </div>
                <div class="mt-8 p-6 bg-[#E8630A] rounded-2xl shadow-lg relative overflow-hidden group">
                     <div class="relative z-10">
                        <h4 class="text-white font-black text-sm uppercase tracking-wider">Upcoming Period</h4>
                        <p class="text-white font-bold text-xl mt-1">Normal Period</p>
                        <p class="text-white/70 text-[10px] uppercase font-black tracking-widest mt-2 cursor-pointer hover:underline">Settings &rarr;</p>
                     </div>
                     <svg class="absolute -bottom-8 -right-8 w-32 h-32 text-black/10 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
