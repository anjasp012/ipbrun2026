<x-layouts.admin title="Participant Profile & Logistics">
    <div x-data="{ 
        editing: false,
        initialEmail: '{{ $participant->email }}',
        formatBestTime(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 6) value = value.slice(0, 6);
            let formattedValue = '';
            if (value.length > 0) {
                formattedValue = value.slice(0, 2);
                if (value.length > 2) {
                    formattedValue += ':' + value.slice(2, 4);
                    if (value.length > 4) {
                        formattedValue += ':' + value.slice(4, 6);
                    }
                }
            }
            e.target.value = formattedValue;
        },
        confirmResend() {
            Swal.fire({
                title: 'Resend Invoice?',
                text: 'Sistem akan mengirim ulang E-Invoice ke {{ $participant->email }} tanpa mengubah password yang sudah ada.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#003366',
                cancelButtonColor: '#d33',
                confirmButtonText: 'YA, KIRIM ULANG',
                cancelButtonText: 'BATAL',
                customClass: {
                    popup: 'rounded-[2.5rem]',
                    confirmButton: 'rounded-xl font-black px-8 py-4',
                    cancelButton: 'rounded-xl font-black px-8 py-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sending...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                    window.location.href = '{{ route('participants.resend-invoice', $participant) }}';
                }
            });
        },
        confirmSave(e) {
            const currentEmail = document.getElementsByName('email')[0].value;
            let message = 'Apakah Anda yakin ingin menyimpan perubahan data peserta ini?';
            let icon = 'question';

            if (currentEmail.toLowerCase() !== this.initialEmail.toLowerCase()) {
                message = '<strong>PERHATIAN!</strong> Anda mengubah alamat email. <br><br> Sistem akan secara otomatis <strong>mereset password</strong> dan mengirimkan email kredensial baru ke: <br> <span class=\'text-blue-600 font-bold\'>' + currentEmail + '</span>';
                icon = 'warning';
            }

            Swal.fire({
                title: 'Simpan Perubahan?',
                html: message,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#00875a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'YA, SIMPAN',
                cancelButtonText: 'BATAL',
                customClass: {
                    popup: 'rounded-[2.5rem]',
                    confirmButton: 'rounded-xl font-black px-8 py-4',
                    cancelButton: 'rounded-xl font-black px-8 py-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        }
    }">
        <form action="{{ route('participants.update', $participant) }}" method="POST" @submit.prevent="confirmSave($event)">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
                <!-- Main Profile Info -->
                <div class="lg:col-span-2 space-y-10">
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-10">
                        <div class="flex items-center justify-between gap-6 mb-12 pb-10 border-b border-slate-50">
                            <div class="flex items-center gap-10">
                                <div class="text-left">
                                    <p class="text-sm font-black text-[#E8630A] uppercase tracking-[5px] mb-3">
                                        REGISTERED
                                        ATHLETE</p>
                                    <template x-if="!editing">
                                        <h3 class="text-5xl font-[900] text-[#003366] uppercase tracking-tighter">
                                            {{ $participant->name }}</h3>
                                    </template>
                                    <template x-if="editing">
                                        <input type="text" name="name" value="{{ $participant->name }}"
                                            class="text-2xl font-[900] text-[#003366] uppercase border-b-2 border-blue-600 focus:outline-none bg-slate-50 px-2 py-1 rounded w-full">
                                    </template>

                                    <div class="flex items-center gap-6 mt-3">
                                        <span
                                            class="text-base font-bold text-slate-400 font-mono tracking-widest uppercase">
                                            NIK: {{ $participant->nik }}
                                        </span>
                                        <span class="w-2 h-2 bg-slate-300 rounded-full"></span>
                                        <span
                                            class="text-sm font-black text-blue-600 uppercase tracking-widest border border-blue-100 px-4 py-1.5 rounded-full bg-blue-50">
                                            {{ $participant->raceEntries->where('status', 'paid')->count() }}/{{ $participant->raceEntries->count() }}
                                            Tickets Paid
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <button type="button" @click="editing = !editing"
                                    class="h-14 px-10 rounded-2xl font-black text-xs uppercase tracking-widest transition-all border shadow-sm flex items-center gap-2"
                                    :class="editing ? 'bg-red-50 text-red-600 border-red-100' :
                                        'bg-blue-50 text-blue-600 border-blue-100'">
                                    <span x-text="editing ? 'CANCEL' : 'EDIT DATA'"></span>
                                </button>
                                <template x-if="editing">
                                    <button type="submit"
                                        class="h-14 px-10 bg-[#00875a] text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-emerald-900/10 hover:bg-emerald-700 transition-all">
                                        SAVE CHANGES
                                    </button>
                                </template>
                                <template x-if="!editing">
                                    <button type="button" @click="confirmResend()"
                                        class="h-14 px-10 bg-slate-50 hover:bg-slate-100 text-slate-800 text-sm font-black uppercase tracking-widest rounded-2xl transition-all border border-slate-100 flex items-center shadow-sm">
                                        Resend Invoice
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-12 gap-x-12">
                            <!-- Contact Information -->
                            <div class="space-y-8">
                                <h4
                                    class="text-sm font-black text-slate-400 uppercase tracking-[4px] mb-8 border-l-[6px] border-orange-500 pl-6">
                                    Contact Information</h4>

                                <div>
                                    <label
                                        class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Primary
                                        Email</label>
                                    <template x-if="!editing">
                                        <p class="text-lg font-black text-[#003366]">{{ $participant->email }}</p>
                                    </template>
                                    <template x-if="editing">
                                        <input type="email" name="email" value="{{ $participant->email }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                    </template>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">WhatsApp
                                        Number</label>
                                    <template x-if="!editing">
                                        <p class="text-lg font-black text-[#003366]">
                                            {{ $participant->phone_number ?: '-' }}</p>
                                    </template>
                                    <template x-if="editing">
                                        <input type="text" name="phone_number"
                                            value="{{ $participant->phone_number }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                    </template>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Street
                                        Address</label>
                                    <template x-if="!editing">
                                        <p class="text-lg font-black text-[#003366] leading-relaxed">
                                            {{ $participant->address ?: '-' }}</p>
                                    </template>
                                    <template x-if="editing">
                                        <textarea name="address"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366] h-32">{{ $participant->address }}</textarea>
                                    </template>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Nationality</label>
                                    <template x-if="!editing">
                                        <p class="text-lg font-black text-[#003366]">
                                            {{ $participant->nationality ?: '-' }}</p>
                                    </template>
                                    <template x-if="editing">
                                        <input type="text" name="nationality"
                                            value="{{ $participant->nationality }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                    </template>
                                </div>
                            </div>

                            <!-- Identification & Vitals -->
                            <div class="space-y-8">
                                <h4
                                    class="text-sm font-black text-slate-400 uppercase tracking-[4px] mb-8 border-l-[6px] border-blue-600 pl-6">
                                    Identification & Vitals</h4>

                                <div class="grid grid-cols-2 gap-6">
                                    <div class="col-span-2">
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">NIK
                                            KTP</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366]">{{ $participant->nik ?: '-' }}
                                            </p>
                                        </template>
                                        <template x-if="editing">
                                            <input type="text" name="nik" value="{{ $participant->nik }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                        </template>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Sex</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366] uppercase">
                                                {{ $participant->sex ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <select name="sex"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                                <option value="male"
                                                    {{ $participant->sex == 'male' ? 'selected' : '' }}>MALE</option>
                                                <option value="female"
                                                    {{ $participant->sex == 'female' ? 'selected' : '' }}>FEMALE
                                                </option>
                                            </select>
                                        </template>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Blood
                                            Type</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366] uppercase">
                                                {{ $participant->blood_type ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <select name="blood_type"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                                @foreach (['A', 'B', 'AB', 'O', '-'] as $bt)
                                                    <option value="{{ $bt }}"
                                                        {{ $participant->blood_type == $bt ? 'selected' : '' }}>
                                                        {{ $bt }}</option>
                                                @endforeach
                                            </select>
                                        </template>
                                    </div>

                                    <div class="col-span-2">
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Date
                                            of Birth</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366]">
                                                {{ $participant->date_birth ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <input type="text" name="date_birth"
                                                value="{{ $participant->date_birth }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]"
                                                placeholder="DD-MM-YYYY">
                                        </template>
                                    </div>

                                    <div class="col-span-2">
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">NIM
                                            / NRP (IPB ONLY)</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366]">
                                                {{ $participant->nim_nrp ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <input type="text" name="nim_nrp" value="{{ $participant->nim_nrp }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Emergency Contact -->
                            <div class="md:col-span-2 p-10 bg-slate-50/50 border border-slate-100 rounded-[2.5rem]">
                                <h4 class="text-sm font-black text-slate-400 uppercase tracking-[4px] mb-8">Emergency
                                    Contact Verification</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Contact
                                            Name</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366] uppercase tracking-tighter">
                                                {{ $participant->emergency_contact_name ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <input type="text" name="emergency_contact_name"
                                                value="{{ $participant->emergency_contact_name }}"
                                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                        </template>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Contact
                                            Number</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366]">
                                                {{ $participant->emergency_contact_phone_number ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <input type="text" name="emergency_contact_phone_number"
                                                value="{{ $participant->emergency_contact_phone_number }}"
                                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                        </template>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Relationship</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366] uppercase tracking-tighter">
                                                {{ $participant->emergency_contact_relationship ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <select name="emergency_contact_relationship"
                                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                                @foreach (['Orang Tua', 'Suami', 'Istri', 'Anak', 'Saudara', 'Teman'] as $rel)
                                                    <option value="{{ $rel }}"
                                                        {{ $participant->emergency_contact_relationship == $rel ? 'selected' : '' }}>
                                                        {{ strtoupper($rel) }}</option>
                                                @endforeach
                                            </select>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Running Data (New Data) -->
                            <div
                                class="md:col-span-2 p-10 bg-orange-50/20 border border-orange-100/50 rounded-[2.5rem]">
                                <h4
                                    class="text-sm font-black text-[#E8630A] uppercase tracking-[4px] mb-8 border-l-[6px] border-[#E8630A] pl-6">
                                    Running Portfolio & Preferences</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Running
                                            Community</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366]">
                                                {{ $participant->running_community ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <input type="text" name="running_community"
                                                value="{{ $participant->running_community }}"
                                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                        </template>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Best
                                            Time (HH:MM:SS)</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366]">
                                                {{ $participant->best_time ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <input type="text" name="best_time"
                                                value="{{ $participant->best_time }}"
                                                @input="formatBestTime($event)"
                                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]"
                                                placeholder="00:00:00">
                                        </template>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Previous
                                            Events</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366]">
                                                {{ $participant->previous_events ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <textarea name="previous_events"
                                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366] h-20">{{ $participant->previous_events }}</textarea>
                                        </template>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Medical Condition</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366]">
                                                {{ $participant->medical_condition ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <textarea name="medical_condition"
                                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366] h-20">{{ $participant->medical_condition }}</textarea>
                                        </template>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Shuttle
                                            Bus Terminal</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366]">
                                                {{ $participant->shuttle_bus ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <input type="text" name="shuttle_bus"
                                                value="{{ $participant->shuttle_bus }}"
                                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                        </template>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-widest">Jersey
                                            Size</label>
                                        <template x-if="!editing">
                                            <p class="text-lg font-black text-[#003366] uppercase">
                                                {{ $participant->jersey_size ?: '-' }}</p>
                                        </template>
                                        <template x-if="editing">
                                            <select name="jersey_size"
                                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 font-bold text-[#003366]">
                                                @foreach (['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL'] as $sz)
                                                    <option value="{{ $sz }}"
                                                        {{ $participant->jersey_size == $sz ? 'selected' : '' }}>
                                                        {{ $sz }}</option>
                                                @endforeach
                                            </select>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center px-6">
                        <a href="{{ url('/admin/participants') }}"
                            class="text-sm font-black text-slate-400 hover:text-[#003366] transition-all flex items-center gap-3 uppercase tracking-widest">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg> BACK TO LIST
                        </a>
                        <p class="text-sm font-black text-slate-300 uppercase tracking-[4px] italic">Last Update:
                            {{ $participant->updated_at->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- Logistics Card (Right Column) -->
                <div class="space-y-10">
                    <div
                        class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col items-center p-10">
                        <!-- Logistics Sections (Dynamic for HasMany) -->
                        <h3
                            class="text-sm font-black text-slate-400 uppercase tracking-[5px] mb-10 w-full border-b pb-6">
                            Race Logistics</h3>
                        @foreach ($participant->raceEntries->groupBy('order_id') as $orderId => $entries)
                            @php $order = $entries->first()->order; @endphp
                            <div
                                class="w-full bg-slate-50/80 border border-slate-100 rounded-[2.5rem] p-10 mb-10 last:mb-0">
                                <div class="flex justify-between items-center mb-8">
                                    <span
                                        class="text-sm font-black text-blue-600 font-mono uppercase tracking-widest">#{{ $order->order_code }}</span>
                                    @if ($order->status == 'paid')
                                        <span
                                            class="px-4 py-1.5 bg-emerald-50 text-emerald-600 text-[11px] font-black uppercase rounded-xl border border-emerald-100">Paid
                                            Order</span>
                                    @elseif($order->status == 'pending')
                                        <span
                                            class="px-4 py-1.5 bg-orange-50 text-orange-600 text-[11px] font-black uppercase rounded-xl border border-orange-100 animate-pulse">Pending
                                            Order</span>
                                    @else
                                        <span
                                            class="px-4 py-1.5 bg-slate-100 text-slate-500 text-[11px] font-black uppercase rounded-xl border border-slate-200">
                                            {{ strtoupper($order->status) }}</span>
                                    @endif
                                </div>
                                @foreach ($entries as $entry)
                                    <div
                                        class="w-full bg-white border border-slate-100 rounded-3xl p-8 text-center relative overflow-hidden mb-6 last:mb-0 shadow-sm hover:border-blue-100 transition-colors">
                                        <div
                                            class="text-sm font-black text-slate-400 uppercase tracking-[3px] mb-4 leading-none">
                                            {{ strtoupper($entry->ticket->category->name) }}
                                        </div>
                                        @if ($entry->bib_number)
                                            <div
                                                class="text-5xl font-black text-[#003366] tracking-[8px] uppercase font-mono">
                                                {{ $entry->bib_number }}</div>
                                        @else
                                            <div
                                                class="text-3xl font-black text-slate-200 tracking-widest uppercase italic opacity-60">
                                                NO BIB</div>
                                        @endif
                                        <div
                                            class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-between">
                                            <div class="text-left">
                                                <p
                                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                                                    Status Pengambilan Pack</p>
                                                @if ($entry->scanned_at)
                                                    <h5
                                                        class="text-sm font-black text-emerald-600 uppercase leading-none italic">
                                                        SUDAH DIAMBIL</h5>
                                                @else
                                                    <h5
                                                        class="text-sm font-black text-slate-300 uppercase leading-none italic">
                                                        BELUM DIAMBIL</h5>
                                                @endif
                                            </div>
                                            <div @class([
                                                'w-12 h-12 rounded-[1.25rem] border flex items-center justify-center shadow-inner',
                                                'bg-emerald-50 border-emerald-100 text-emerald-600' => $entry->scanned_at,
                                                'bg-slate-50 border-slate-100 text-slate-300' => !$entry->scanned_at,
                                            ])>
                                                @if ($entry->scanned_at)
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6 opacity-40" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                        <div
                            class="w-full mt-12 p-8 bg-blue-50/50 border border-blue-100/50 rounded-3xl flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Official
                                    Jersey Size
                                </p>
                                <h5 class="text-4xl font-black text-[#003366] uppercase leading-none">
                                    {{ $participant->jersey_size }}</h5>
                            </div>
                            <div
                                class="w-16 h-16 rounded-[1.25rem] bg-white border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-[#003366] to-[#001c38] p-10 rounded-[2.5rem] shadow-[0_30px_60px_-15px_rgba(0,51,102,0.3)] relative overflow-hidden group">
                        <div class="relative z-10">
                            <h4 class="text-sm font-black text-white/40 uppercase tracking-[5px] mb-10">Financial
                                Overview
                            </h4>
                            <div class="space-y-8"> @php
                                $paidOrders = \App\Models\Order::where('participant_id', $participant->id)
                                    ->where('status', 'paid')
                                    ->get();
                            @endphp @foreach ($paidOrders as $o)
                                    <div
                                        class="flex justify-between items-center text-base text-white/70 font-black uppercase tracking-widest">
                                        <span class="opacity-60">Order #{{ $o->order_code }}</span> <span
                                            class="text-white">IDR
                                            {{ number_format($o->total_price, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                                <div class="flex justify-between items-center pt-10 border-t border-white/10"> <span
                                        class="text-sm font-black text-white/40 uppercase tracking-[5px]">Total
                                        Paid</span>
                                    <span class="text-4xl font-black text-[#E8630A] uppercase tracking-tighter">IDR
                                        {{ number_format($paidOrders->sum('total_price'), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin>
