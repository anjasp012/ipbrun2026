<x-layouts.admin title="System Settings">
    <div class="space-y-6">
        <!-- Header Info -->
        <div class="flex items-center justify-between bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <div>
                <h3 class="text-2xl font-black text-[#003366] uppercase tracking-tight">System Settings</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Manage your application configuration and integrations</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                    WA ENGINE: {{ ($settings['wa_notification_active'] ?? '0') == '1' ? 'ACTIVE' : 'DISABLED' }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 px-6 py-4 rounded-2xl border border-emerald-100 text-sm font-bold flex items-center gap-3 animate-slide-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            
            <div class="lg:col-span-2 space-y-6">
                <!-- WhatsApp Notification Configuration -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">WhatsApp (Fonnte)</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Notification Integration</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="wa_notification_active" value="1" class="sr-only peer" {{ ($settings['wa_notification_active'] ?? '0') == '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-100 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-200 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex gap-4">
                            <div class="flex-shrink-0 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wide leading-relaxed">
                                Aktifkan fitur ini untuk mengirimkan notifikasi otomatis melalui WhatsApp kepada peserta. Hubungkan token Anda dari panel <a href="https://fonnte.com" target="_blank" class="text-blue-600 hover:underline">Fonnte</a>.
                            </p>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5 ml-1">Fonnte API Token</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-blue-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                </div>
                                <input type="password" 
                                       name="fonnte_token" 
                                       value="{{ $settings['fonnte_token'] ?? '' }}"
                                       placeholder="Enter your Fonnte token..." 
                                       class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 placeholder:text-slate-300 focus:bg-white focus:border-blue-300 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-[#003366] p-8 rounded-3xl border border-[#002244] shadow-xl text-white sticky top-24">
                    <h4 class="text-sm font-black uppercase tracking-widest mb-4 opacity-50">Actions</h4>
                    <p class="text-xs font-bold uppercase tracking-wider mb-8 leading-relaxed">Pastikan semua data konfigurasi sudah benar sebelum menekan tombol simpan.</p>
                    
                    <button type="submit" class="w-full bg-[#E8630A] hover:bg-[#FF7A21] text-white py-4 px-6 rounded-2xl font-black uppercase tracking-widest text-xs transition-all transform hover:scale-[1.02] active:scale-95 shadow-lg shadow-orange-900/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin>
