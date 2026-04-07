<x-layouts.admin title="Message Blast - IPB RUN 2026">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Message Creation Card -->
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/50 p-10">
            <div class="flex items-center gap-6 mb-10">
                <div class="w-14 h-14 bg-[#003366] text-white rounded-[1.25rem] flex items-center justify-center shadow-lg shadow-blue-900/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Create Promotion Message</h3>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest leading-none mt-2">Compose your broadcast message</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-8 p-6 bg-emerald-50 border border-emerald-200 rounded-[1.5rem] text-emerald-600 text-base font-bold flex items-center gap-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
            @endif

            <form id="blastForm" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf
                <div class="space-y-3">
                    <label class="text-[13px] font-black text-slate-400 uppercase tracking-widest ml-6">Recipients (Manual Input)</label>
                    <textarea 
                        name="targets" 
                        id="form-targets"
                        required
                        rows="5"
                        placeholder="Ex: email1@example.com, email2@example.com OR 0812345678, 0898765432"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] px-8 py-5 text-base font-bold text-[#003366] outline-none focus:border-[#E8630A] focus:bg-white transition-all"></textarea>
                    <p class="text-[12px] text-slate-400 font-bold ml-6 uppercase opacity-60">Separate with commas (,) or new lines</p>
                </div>

                <div class="space-y-3">
                    <label class="text-[13px] font-black text-slate-400 uppercase tracking-widest ml-6">Subject (Email Only)</label>
                    <input 
                        type="text" 
                        name="subject" 
                        id="form-subject"
                        required
                        placeholder="Ex: Pendaftaran IPB RUN 2026 Segera Ditutup!"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] px-8 py-5 text-base font-bold text-[#003366] outline-none focus:border-[#E8630A] focus:bg-white transition-all">
                </div>

                <div class="space-y-3">
                    <label class="text-[13px] font-black text-slate-400 uppercase tracking-widest ml-6">Message Body</label>
                    <textarea 
                        name="message" 
                        id="form-message"
                        required
                        rows="8"
                        placeholder="Type your promotional message here..."
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] px-8 py-5 text-base font-bold text-[#003366] outline-none focus:border-[#E8630A] focus:bg-white transition-all"></textarea>
                </div>

                <div class="space-y-3">
                    <label class="text-[13px] font-black text-slate-400 uppercase tracking-widest ml-6">Attachment (Optional)</label>
                    <div class="relative group">
                        <input type="file" name="attachment" id="form-attachment"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] px-8 py-4 text-sm font-bold text-[#003366] outline-none focus:border-[#E8630A] focus:bg-white transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                    <p class="text-[11px] text-slate-400 font-bold ml-6 uppercase opacity-60">Max size: 5MB (JPG, PNG, PDF, DOCX)</p>
                </div>

                <div class="grid grid-cols-2 gap-6 pt-6">
                    <button 
                        type="button"
                        onclick="submitBlast('{{ route('admin.blast.email') }}')"
                        class="h-16 bg-blue-900 text-white rounded-2xl font-black uppercase text-[12px] tracking-widest hover:bg-blue-800 transition-all flex items-center justify-center gap-4 shadow-lg shadow-blue-900/20 active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Blast via Email
                    </button>
                    <button 
                        type="button" 
                        onclick="submitBlast('{{ route('admin.blast.whatsapp') }}')"
                        class="h-16 bg-emerald-600 text-white rounded-2xl font-black uppercase text-[12px] tracking-widest hover:bg-emerald-500 transition-all flex items-center justify-center gap-4 shadow-lg shadow-emerald-600/20 active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        Blast via WhatsApp
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions & Preview Card -->
        <div class="space-y-8">
            <div class="bg-[#003366] rounded-[2.5rem] p-12 text-white relative overflow-hidden">
                <div class="absolute right-[-40px] top-[-40px] w-64 h-64 bg-white opacity-[0.03] rounded-full"></div>
                <h4 class="text-sm font-black text-[#E8630A] uppercase tracking-[5px] mb-6">Instructions</h4>
                <div class="space-y-6">
                    <div class="flex gap-6">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-sm font-black shrink-0">1</div>
                        <p class="text-sm font-bold leading-relaxed opacity-70">Blast email akan mengirimkan pesan ke seluruh peserta yang terdaftar dengan subjek tertentu.</p>
                    </div>
                    <div class="flex gap-6">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-sm font-black shrink-0">2</div>
                        <p class="text-sm font-bold leading-relaxed opacity-70">Blast WhatsApp akan menggunakan layanan Fonnte. Pastikan token API masih aktif di menu Settings.</p>
                    </div>
                    <div class="flex gap-6">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-sm font-black shrink-0">3</div>
                        <p class="text-sm font-bold leading-relaxed opacity-70">Pesan akan dikirimkan secara sekuensial. Harap tunggu hingga halaman selesai melakukan load.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 p-12">
                <p class="text-[12px] font-black text-slate-400 uppercase tracking-widest text-center mb-8">Real-time Preview</p>
                <div class="p-8 bg-slate-50 rounded-[2rem]">
                    <div id="preview-subject" class="text-base font-black text-[#003366] mb-3 uppercase tracking-tighter">[Enter Subject]</div>
                    <div id="preview-message" class="text-sm text-slate-500 font-bold leading-relaxed whitespace-pre-line italic">
                        Your message preview will appear here as you type...
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const subjectInput = document.getElementById('form-subject');
        const messageInput = document.getElementById('form-message');
        const previewSubject = document.getElementById('preview-subject');
        const previewMessage = document.getElementById('preview-message');

        subjectInput.addEventListener('input', (e) => {
            previewSubject.innerText = e.target.value || '[Enter Subject]';
        });

        messageInput.addEventListener('input', (e) => {
            previewMessage.innerText = e.target.value || 'Your message preview will appear here as you type...';
        });

        function submitBlast(url) {
            if(!subjectInput.value && url.includes('email')) {
                Swal.fire('Error', 'Subject is required for Email blast!', 'error');
                return;
            }
            if(!messageInput.value) {
                Swal.fire('Error', 'Message body is required!', 'error');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "This message will be broadcasted to ALL participants!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#003366',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Blast Now!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sending Broadcast...',
                        text: 'Please do not close this tab.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    const form = document.getElementById('blastForm');
                    form.action = url;
                    form.submit();
                }
            });
        }
    </script>
    @endpush
</x-layouts.admin>
