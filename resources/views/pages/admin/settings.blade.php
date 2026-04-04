<x-layouts.admin title="Settings">
    <div class="max-w-4xl mx-auto py-4">
        <div class="mb-10">
            <h3 class="text-2xl font-black text-[#003366] uppercase">System Settings</h3>
            <p class="text-slate-500 font-medium">Manage your application configuration and integrations.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- WhatsApp Notification Configuration -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-black text-[#003366] uppercase tracking-tight">WhatsApp (Fonnte)</h4>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">Notification Integration</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="wa_notification_active" value="1" class="sr-only peer" {{ ($settings['wa_notification_active'] ?? '0') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-sm font-bold text-slate-700 uppercase tracking-wide">Status Notifikasi</span>
                    </label>
                </div>
                
                <div class="p-8 space-y-6">
                    <p class="text-sm text-slate-500 leading-relaxed max-w-2xl">
                        Aktifkan fitur ini untuk mengirimkan notifikasi otomatis melalui WhatsApp kepada peserta saat pendaftaran atau pembayaran berhasil. Token API didapatkan dari panel dashboard <a href="https://fonnte.com" target="_blank" class="text-emerald-600 font-bold hover:underline">Fonnte</a>.
                    </p>

                    <div>
                        <label class="block text-xs font-black text-[#003366] uppercase tracking-widest mb-2 px-1">Fonnte API Token</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            </div>
                            <input type="password" 
                                   name="fonnte_token" 
                                   value="{{ $settings['fonnte_token'] ?? '' }}"
                                   placeholder="Enter your Fonnte token..." 
                                   class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#003366] focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-10 py-4 bg-[#003366] text-white rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-[#E8630A] transition-all transform hover:scale-[1.02] active:scale-95 shadow-lg shadow-[#003366]/20">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
