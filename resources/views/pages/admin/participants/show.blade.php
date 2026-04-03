<x-layouts.admin title="Participant Profile & Logistics">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Main Profile Info -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-8">
                <div class="flex items-center justify-between gap-6 mb-10 pb-8 border-b border-slate-50">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-3xl bg-blue-50 flex items-center justify-center font-black text-blue-600 text-2xl uppercase tracking-widest border border-blue-100 shadow-xl shadow-blue-50">
                             {{ substr($participant->name, 0, 1) }}
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] font-black text-[#E8630A] uppercase tracking-[3px] mb-1">REGISTERED ATHLETE</p>
                            <h3 class="text-3xl font-black text-[#003366] uppercase tracking-tighter">{{ $participant->name }}</h3>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-xs font-bold text-slate-400 font-mono tracking-widest uppercase">Order #{{ $participant->order_code }}</span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest border border-emerald-100 px-2 py-0.5 rounded-full bg-emerald-50">Paid Status Verified</span>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:flex gap-2">
                        <button class="h-10 px-6 bg-slate-50 hover:bg-slate-100 text-slate-800 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all border border-slate-100">Resend Invoice</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-10 gap-x-8">
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-4">Contact Information</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 mb-1">Primary Email</label>
                                <p class="text-sm font-bold text-slate-800">{{ $participant->email }}</p>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 mb-1">WhatsApp Number</label>
                                <p class="text-sm font-bold text-slate-800">{{ $participant->phone_number }}</p>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 mb-1">Street Address</label>
                                <p class="text-sm font-bold text-slate-800 leading-relaxed">{{ $participant->address }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-4">Identification & Vitals</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 mb-1">NIK KTP</label>
                                <p class="text-sm font-bold text-slate-800">{{ $participant->nik }}</p>
                            </div>
                            <div class="flex gap-8">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Blood Type</label>
                                    <p class="text-sm font-bold text-slate-800 uppercase">{{ $participant->blood_type }}</p>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Date of Birth</label>
                                    <p class="text-sm font-bold text-slate-800">{{ $participant->date_birth }}</p>
                                </div>
                            </div>
                             <div>
                                <label class="block text-[11px] font-bold text-slate-400 mb-1">NIM / NRP (IPB ONLY)</label>
                                <p class="text-sm font-bold text-slate-800">{{ $participant->nim_nrp ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 p-6 bg-slate-50/50 border border-slate-100 rounded-2xl">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-4">Emergency Contact Verification</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                             <div>
                                <label class="block text-[11px] font-bold text-slate-400 mb-1">Contact Name</label>
                                <p class="text-sm font-bold text-slate-800 uppercase tracking-tighter">{{ $participant->emergency_contact_name }}</p>
                            </div>
                             <div>
                                <label class="block text-[11px] font-bold text-slate-400 mb-1">Contact Number</label>
                                <p class="text-sm font-bold text-slate-800">{{ $participant->emergency_contact_phone_number }}</p>
                            </div>
                             <div>
                                <label class="block text-[11px] font-bold text-slate-400 mb-1">Relationship</label>
                                <p class="text-sm font-bold text-slate-800 uppercase tracking-tighter">{{ $participant->emergency_contact_relationship }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between items-center px-4">
                 <a href="{{ url('/admin/participants') }}" class="text-xs font-bold text-slate-400 hover:text-[#003366] transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    BACK TO LIST
                </a>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Last Update: {{ $participant->updated_at->diffForHumans() }}</p>
            </div>
        </div>

        <!-- Logistics Card (Right Column) -->
        <div class="space-y-8">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col items-center p-8">
                 <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-8 text-center pb-4 border-b border-slate-50 w-full">Race Logistics Status</h4>
                 
                 <!-- BIB Section Mock -->
                 <div class="w-full bg-[#f8fafc] border border-slate-100 rounded-3xl p-6 text-center relative overflow-hidden mb-6">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-[3px] mb-2 leading-none">BIB CODE</div>
                    <div class="text-4xl font-black text-slate-300 tracking-widest uppercase">NOT ASSIGNED</div>
                    <p class="mt-4 text-[10px] font-bold text-blue-600 uppercase underline cursor-pointer">GENERATE BIB NOW</p>
                    <svg class="absolute -top-10 -right-10 w-24 h-24 text-slate-50/50" fill="currentColor" viewBox="0 0 24 24"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                 </div>

                 <div class="w-full space-y-4">
                    <div class="flex items-center justify-between p-4 bg-slate-50/50 border border-slate-100 rounded-2xl">
                         <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Jersey Size</p>
                            <h5 class="text-xl font-black text-[#003366] uppercase leading-none">{{ $participant->jersey_size }}</h5>
                         </div>
                         <div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                         </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-slate-50/50 border border-slate-100 rounded-2xl group cursor-pointer hover:border-emerald-200 transition-colors">
                         <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Racepack (RPC)</p>
                            <h5 class="text-xl font-black text-slate-300 uppercase leading-none">UNCLAIMED</h5>
                         </div>
                         <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path></svg>
                         </div>
                    </div>
                 </div>
            </div>

            <div class="bg-gradient-to-br from-[#003366] to-[#001122] p-8 rounded-3xl shadow-xl shadow-blue-100 relative overflow-hidden group">
                 <div class="relative z-10">
                    <h4 class="text-[10px] font-black text-white/40 uppercase tracking-[3px] mb-6">Payment Summary</h4>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xs text-white/70 font-bold uppercase tracking-wider">
                            <span>Ticket Price</span>
                            <span>IDR {{ number_format($participant->ticket->price, 0, ',', '.') }}</span>
                        </div>
                        @if($participant->donation_event > 0)
                        <div class="flex justify-between items-center text-xs text-white/70 font-bold uppercase tracking-wider">
                            <span>Donation Event</span>
                            <span>IDR {{ number_format($participant->donation_event, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($participant->donation_scholarship > 0)
                        <div class="flex justify-between items-center text-xs text-white/70 font-bold uppercase tracking-wider">
                            <span>Donation Scholarship</span>
                            <span>IDR {{ number_format($participant->donation_scholarship, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center pt-4 border-t border-white/10 text-xl font-black text-white uppercase tracking-tighter">
                            <span>Settled Sum</span>
                            <span class="text-[#E8630A]">IDR {{ number_format($participant->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                 </div>
                 <svg class="absolute -bottom-10 -right-10 w-40 h-40 text-black/20 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>
</x-layouts.admin>
