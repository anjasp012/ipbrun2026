<x-layouts.admin title="Participant Profile & Logistics">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
        <!-- Main Profile Info -->
        <div class="lg:col-span-2 space-y-10">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-10">
                <div class="flex items-center justify-between gap-6 mb-12 pb-10 border-b border-slate-50">
                    <div class="flex items-center gap-8">
                        <div class="w-20 h-20 rounded-[2rem] bg-blue-50 flex items-center justify-center font-black text-blue-600 text-3xl uppercase tracking-widest border border-blue-100 shadow-xl shadow-blue-50">
                             {{ substr($participant->name, 0, 1) }}
                        </div>
                        <div class="text-left">
                            <p class="text-xs font-black text-[#E8630A] uppercase tracking-[4px] mb-2">REGISTERED ATHLETE</p>
                            <h3 class="text-4xl font-[900] text-[#003366] uppercase tracking-tighter">{{ $participant->name }}</h3>
                            <div class="flex items-center gap-4 mt-2">
                                <span class="text-sm font-bold text-slate-400 font-mono tracking-widest uppercase">NIK: {{ $participant->nik }}</span>
                                <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                <span class="text-xs font-black text-blue-600 uppercase tracking-widest border border-blue-100 px-3 py-1 rounded-full bg-blue-50">
                                    {{ $participant->raceEntries->where('status', 'paid')->count() }}/{{ $participant->raceEntries->count() }} Tickets Paid
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:flex gap-3">
                        <a href="{{ route('participants.resend-invoice', $participant) }}" 
                           class="h-12 px-8 bg-slate-50 hover:bg-slate-100 text-slate-800 text-xs font-black uppercase tracking-widest rounded-2xl transition-all border border-slate-100 flex items-center shadow-sm">
                           Resend Invoice
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-12 gap-x-12">
                    <div>
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-[3px] mb-6 border-l-4 border-orange-500 pl-4">Contact Information</h4>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-widest">Primary Email</label>
                                <p class="text-base font-black text-[#003366]">{{ $participant->email }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-widest">WhatsApp Number</label>
                                <p class="text-base font-black text-[#003366]">{{ $participant->phone_number }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-widest">Street Address</label>
                                <p class="text-base font-black text-[#003366] leading-relaxed">{{ $participant->address }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-[3px] mb-6 border-l-4 border-blue-600 pl-4">Identification & Vitals</h4>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-widest">NIK KTP</label>
                                <p class="text-base font-black text-[#003366]">{{ $participant->nik }}</p>
                            </div>
                            <div class="flex gap-12">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-widest">Blood Type</label>
                                    <p class="text-base font-black text-[#003366] uppercase">{{ $participant->blood_type }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-widest">Date of Birth</label>
                                    <p class="text-base font-black text-[#003366]">{{ $participant->date_birth }}</p>
                                </div>
                            </div>
                             <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-widest">NIM / NRP (IPB ONLY)</label>
                                <p class="text-base font-black text-[#003366]">{{ $participant->nim_nrp ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 p-8 bg-slate-50/50 border border-slate-100 rounded-[2rem]">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-[3px] mb-6">Emergency Contact Verification</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                             <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-widest">Contact Name</label>
                                <p class="text-base font-black text-[#003366] uppercase tracking-tighter">{{ $participant->emergency_contact_name }}</p>
                            </div>
                             <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-widest">Contact Number</label>
                                <p class="text-base font-black text-[#003366]">{{ $participant->emergency_contact_phone_number }}</p>
                            </div>
                             <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-widest">Relationship</label>
                                <p class="text-base font-black text-[#003366] uppercase tracking-tighter">{{ $participant->emergency_contact_relationship }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between items-center px-6">
                 <a href="{{ url('/admin/participants') }}" class="text-sm font-black text-slate-400 hover:text-[#003366] transition-all flex items-center gap-3 uppercase tracking-widest">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    BACK TO LIST
                </a>
                <p class="text-xs font-black text-slate-300 uppercase tracking-[3px] italic">Last Update: {{ $participant->updated_at->diffForHumans() }}</p>
            </div>
        </div>

        <!-- Logistics Card (Right Column) -->
        <div class="space-y-10">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col items-center p-10">
                 <!-- Logistics Sections (Dynamic for HasMany) -->
                 <h3 class="text-xs font-black text-slate-400 uppercase tracking-[4px] mb-8 w-full border-b pb-4">Race Logistics</h3>
                 
                 @foreach($participant->raceEntries->groupBy('order_id') as $orderId => $entries)
                 @php $order = $entries->first()->order; @endphp
                 <div class="w-full bg-slate-50/80 border border-slate-100 rounded-[2rem] p-8 mb-8 last:mb-0">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xs font-black text-blue-600 font-mono uppercase tracking-widest">#{{ $order->order_code }}</span>
                        @if($order->status == 'paid')
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase rounded-lg border border-emerald-100">Paid Order</span>
                        @else
                            <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[9px] font-black uppercase rounded-lg border border-orange-100 animate-pulse">Pending Order</span>
                        @endif
                    </div>
                    
                    @foreach($entries as $entry)
                    <div class="w-full bg-white border border-slate-100 rounded-2xl p-6 text-center relative overflow-hidden mb-4 last:mb-0 shadow-sm hover:border-blue-100 transition-colors">
                        <div class="text-xs font-black text-slate-400 uppercase tracking-[2px] mb-3 leading-none">
                            {{ strtoupper($entry->ticket->category->name) }}
                        </div>
                        @if($entry->bib_number)
                            <div class="text-4xl font-black text-[#003366] tracking-[6px] uppercase font-mono">{{ $entry->bib_number }}</div>
                        @else
                            <div class="text-2xl font-black text-slate-200 tracking-widest uppercase italic opacity-60">NO BIB</div>
                        @endif
                        
                        <div class="mt-6 pt-5 border-t border-slate-50 flex items-center justify-between">
                            <div class="text-left">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Pengambilan Pack</p>
                                @if($entry->scanned_at)
                                    <h5 class="text-xs font-black text-emerald-600 uppercase leading-none italic">SUDAH DIAMBIL</h5>
                                @else
                                    <h5 class="text-xs font-black text-slate-300 uppercase leading-none italic">BELUM DIAMBIL</h5>
                                @endif
                            </div>
                            <div @class([
                                'w-10 h-10 rounded-xl border flex items-center justify-center shadow-inner',
                                'bg-emerald-50 border-emerald-100 text-emerald-600' => $entry->scanned_at,
                                'bg-slate-50 border-slate-100 text-slate-300' => !$entry->scanned_at
                            ])>
                                @if($entry->scanned_at)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                @else
                                    <svg class="w-5 h-5 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                 </div>
                 @endforeach

                 <div class="w-full mt-10 p-6 bg-blue-50/50 border border-blue-100/50 rounded-2xl flex items-center justify-between">
                     <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Official Jersey Size</p>
                        <h5 class="text-3xl font-black text-[#003366] uppercase leading-none">{{ $participant->jersey_size }}</h5>
                     </div>
                     <div class="w-14 h-14 rounded-2xl bg-white border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm">
                         <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                     </div>
                 </div>
            </div>

            <div class="bg-gradient-to-br from-[#003366] to-[#001c38] p-10 rounded-[2.5rem] shadow-[0_30px_60px_-15px_rgba(0,51,102,0.3)] relative overflow-hidden group">
                 <div class="relative z-10">
                    <h4 class="text-xs font-black text-white/40 uppercase tracking-[4px] mb-8">Financial Overview</h4>
                    <div class="space-y-6">
                        @php
                            $paidOrders = \App\Models\Order::where('participant_id', $participant->id)->where('status', 'paid')->get();
                        @endphp
                        @foreach($paidOrders as $o)
                        <div class="flex justify-between items-center text-sm text-white/70 font-black uppercase tracking-widest">
                            <span class="opacity-60">Order #{{ $o->order_code }}</span>
                            <span class="text-white">IDR {{ number_format($o->total_price, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        
                        <div class="flex justify-between items-center pt-8 border-t border-white/10">
                            <span class="text-xs font-black text-white/40 uppercase tracking-[4px]">Total Paid</span>
                            <span class="text-3xl font-black text-[#E8630A] uppercase tracking-tighter">IDR {{ number_format($paidOrders->sum('total_price'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
