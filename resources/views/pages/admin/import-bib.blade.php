<x-layouts.admin title="Bulk Update BIB Numbers">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Upload Card -->
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/50 p-10">
            <div class="flex items-center gap-6 mb-10">
                <div class="w-14 h-14 bg-[#003366] text-white rounded-[1.25rem] flex items-center justify-center shadow-lg shadow-blue-900/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Upload Excel File</h3>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest leading-none mt-2">Bulk Update BIB Numbers</p>
                </div>
            </div>

            <form action="{{ route('admin.import-bib.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="space-y-3">
                    <label class="text-[13px] font-black text-slate-400 uppercase tracking-widest ml-6">Pilih Kategori / Tipe Tiket</label>
                    <select name="ticket_id" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] px-8 py-5 text-base font-bold text-[#003366] outline-none focus:border-[#E8630A] focus:bg-white transition-all">
                        <option value="">-- Pilih Tiket --</option>
                        @foreach($tickets as $t)
                            <option value="{{ $t->id }}">
                                {{ $t->category->name ?? 'Kategori' }} ({{ $t->type === 'ipb' ? 'IPB Family' : 'Public (Umum)' }}) - Periode: {{ $t->period->name ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-3">
                    <label class="text-[13px] font-black text-slate-400 uppercase tracking-widest ml-6">Excel Document (.xlsx, .xls)</label>
                    <div class="relative group">
                        <input type="file" name="file" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] px-8 py-5 text-sm font-bold text-[#003366] outline-none focus:border-[#E8630A] focus:bg-white transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full h-16 bg-blue-900 text-white rounded-2xl font-black uppercase text-[12px] tracking-widest hover:bg-blue-800 transition-all flex items-center justify-center gap-4 shadow-lg shadow-blue-900/20 active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Upload & Update BIB Numbers
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions Card -->
        <div class="space-y-8">
            <div class="bg-[#003366] rounded-[2.5rem] p-12 text-white relative overflow-hidden">
                <div class="absolute right-[-40px] top-[-40px] w-64 h-64 bg-white opacity-[0.03] rounded-full"></div>
                <h4 class="text-sm font-black text-[#E8630A] uppercase tracking-[5px] mb-6">Instructions</h4>
                <div class="space-y-6">
                    <div class="flex gap-6">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-sm font-black shrink-0">1</div>
                        <p class="text-sm font-bold leading-relaxed opacity-70">Sistem akan membaca kolom <strong>ID</strong> sebagai Order Code (contoh: IPBR26-0DS6IE) untuk mencari order peserta di database.</p>
                    </div>
                    <div class="flex gap-6">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-sm font-black shrink-0">2</div>
                        <p class="text-sm font-bold leading-relaxed opacity-70">Nilai pada kolom <strong>BIB</strong> akan di-update ke database pada data Race Entry terkait.</p>
                    </div>
                    <div class="flex gap-6">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-sm font-black shrink-0">3</div>
                        <p class="text-sm font-bold leading-relaxed opacity-70">Jika satu order memiliki beberapa tiket (kategori ganda), pastikan kolom <strong>KET</strong> berisi nama kategori/tiket yang sesuai (contoh: 5K, 10K, HM) agar sistem dapat mencocokkannya dengan benar.</p>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Error Logs Section -->
    @if(session('import_errors'))
        <div class="mt-8 bg-white rounded-[2.5rem] border border-slate-200 p-10 shadow-sm animate-slide-in">
            <h4 class="text-lg font-black text-rose-600 uppercase tracking-wider mb-6">Import Errors / Logs</h4>
            <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 overflow-y-auto max-h-96">
                <ul class="space-y-3">
                    @foreach(session('import_errors') as $error)
                        <li class="flex items-start gap-3 text-sm font-bold text-rose-800">
                            <span class="w-2 h-2 bg-rose-500 rounded-full mt-2 shrink-0"></span>
                            <span>{{ $error }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</x-layouts.admin>
