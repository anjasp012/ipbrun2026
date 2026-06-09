<x-layouts.admin title="Manage Time Results">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Upload Card -->
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/50 p-10">
            <div class="flex items-center gap-6 mb-10">
                <div class="w-14 h-14 bg-[#003366] text-white rounded-[1.25rem] flex items-center justify-center shadow-lg shadow-blue-900/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Import Excel</h3>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest leading-none mt-2">Upload Race Timing Data</p>
                </div>
            </div>

            <form action="{{ route('admin.time-result.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="space-y-3">
                    <label class="text-[13px] font-black text-slate-400 uppercase tracking-widest ml-6">Excel/CSV Document (.xlsx, .xls, .csv)</label>
                    <div class="relative group">
                        <input type="file" name="file" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] px-8 py-5 text-sm font-bold text-[#003366] outline-none focus:border-[#E8630A] focus:bg-white transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="submit" class="flex-1 h-16 bg-blue-900 text-white rounded-2xl font-black uppercase text-[12px] tracking-widest hover:bg-blue-800 transition-all flex items-center justify-center gap-4 shadow-lg shadow-blue-900/20 active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Upload & Import Results
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions Card & Reset Card -->
        <div class="space-y-8">
            <div class="bg-[#003366] rounded-[2.5rem] p-10 text-white relative overflow-hidden">
                <div class="absolute right-[-40px] top-[-40px] w-64 h-64 bg-white opacity-[0.03] rounded-full"></div>
                <h4 class="text-sm font-black text-[#E8630A] uppercase tracking-[5px] mb-6">Instructions & Format</h4>
                <div class="space-y-4">
                    <p class="text-sm font-bold leading-relaxed opacity-70">
                        Format file Excel harus memiliki baris header (Heading Row) dengan nama kolom berikut (case-insensitive):
                    </p>
                    <div class="grid grid-cols-2 gap-2 text-xs font-mono bg-black/20 p-4 rounded-xl">
                        <div>• item (e.g. 10K UMUM)</div>
                        <div>• bib (e.g. 1002)</div>
                        <div>• name (e.g. John Doe)</div>
                        <div>• gender (M/F)</div>
                        <div>• gun_time (H:MM:SS)</div>
                        <div>• net_time (H:MM:SS)</div>
                        <div>• start_time (H:MM:SS)</div>
                        <div>• cp1 (H:MM:SS)</div>
                        <div>• cp2 (H:MM:SS)</div>
                        <div>• status (Finished/DNF/DNS)</div>
                    </div>
                    <p class="text-xs font-bold leading-relaxed opacity-50">
                        * Data akan di-upsert (dibuat baru atau diupdate jika sudah ada) berdasarkan kombinasi <strong>bib + nama file Excel</strong>.
                    </p>
                </div>
            </div>

            <!-- Danger Zone / Reset -->
            <div class="bg-rose-50 border-2 border-rose-100 rounded-[2.5rem] p-10">
                <h4 class="text-base font-black text-rose-800 uppercase tracking-widest mb-4">Danger Zone</h4>
                <p class="text-sm font-bold text-rose-700/80 mb-6">Menghapus seluruh data catatan waktu pelari dari database secara permanen.</p>
                <form action="{{ route('admin.time-result.destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh data hasil waktu lari?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="h-12 px-8 bg-rose-600 text-white rounded-xl font-black uppercase text-[11px] tracking-widest hover:bg-rose-700 transition-all shadow-md active:scale-95">
                        Reset / Hapus Semua Data
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Error Logs Section -->
    @if(session('import_errors'))
        <div class="mb-8 bg-white rounded-[2.5rem] border border-slate-200 p-10 shadow-sm animate-slide-in">
            <h4 class="text-lg font-black text-rose-600 uppercase tracking-wider mb-6">Import Errors / Logs</h4>
            <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 overflow-y-auto max-h-60">
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

    <!-- Preview Table Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/50 p-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <h3 class="text-xl font-black text-[#003366] uppercase tracking-tight">Race Results Data</h3>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest leading-none mt-2">Database Preview</p>
            </div>

            <!-- Search and Filter Form -->
            <form action="{{ route('admin.time-result.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div>
                    <select name="tab" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-[#003366] outline-none focus:border-[#E8630A] transition-all">
                        <option value="">-- All Excel Tabs --</option>
                        @foreach($tabs as $t)
                            <option value="{{ $t }}" {{ request('tab') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search BIB / Name..." class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 pr-10 text-sm font-bold text-[#003366] placeholder:text-slate-400 outline-none focus:border-[#E8630A] transition-all">
                    @if(request()->filled('search') || request()->filled('tab'))
                        <a href="{{ route('admin.time-result.index') }}" class="absolute right-3 top-3.5 text-slate-400 hover:text-[#E8630A]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    @endif
                </div>
                <button type="submit" class="h-11 px-6 bg-[#003366] text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-opacity-90 transition-all">
                    Apply
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-4 text-xs font-black text-slate-400 uppercase tracking-widest pl-4">BIB</th>
                        <th class="pb-4 text-xs font-black text-slate-400 uppercase tracking-widest">Name</th>
                        <th class="pb-4 text-xs font-black text-slate-400 uppercase tracking-widest">Category</th>
                        <th class="pb-4 text-xs font-black text-slate-400 uppercase tracking-widest">Tab (Source)</th>
                        <th class="pb-4 text-xs font-black text-slate-400 uppercase tracking-widest">Gender</th>
                        <th class="pb-4 text-xs font-black text-slate-400 uppercase tracking-widest">Gun Time</th>
                        <th class="pb-4 text-xs font-black text-slate-400 uppercase tracking-widest">Net Time</th>
                        <th class="pb-4 text-xs font-black text-slate-400 uppercase tracking-widest">CP1</th>
                        <th class="pb-4 text-xs font-black text-slate-400 uppercase tracking-widest">CP2</th>
                        <th class="pb-4 text-xs font-black text-slate-400 uppercase tracking-widest pr-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($results as $res)
                        <tr class="hover:bg-slate-50/50 transition-all font-bold text-sm text-slate-600">
                            <td class="py-4 pl-4 font-black text-[#003366]">{{ $res->bib }}</td>
                            <td class="py-4 text-[#003366]">{{ $res->name }}</td>
                            <td class="py-4"><span class="px-3 py-1 bg-blue-50 text-blue-800 rounded-full text-xs font-black">{{ $res->item }}</span></td>
                            <td class="py-4"><span class="px-3 py-1 bg-orange-50 text-orange-800 rounded-full text-xs font-black">{{ $res->tab }}</span></td>
                            <td class="py-4">{{ $res->gender ?: '-' }}</td>
                            <td class="py-4 font-mono">{{ $res->gun_time ?: '-' }}</td>
                            <td class="py-4 font-mono text-[#E8630A]">{{ $res->net_time ?: '-' }}</td>
                            <td class="py-4 font-mono">{{ $res->cp1 ?: '-' }}</td>
                            <td class="py-4 font-mono">{{ $res->cp2 ?: '-' }}</td>
                            <td class="py-4 pr-4">
                                <span class="px-3 py-1 rounded-full text-xs font-black {{ $res->status === 'Finished' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $res->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center text-sm text-slate-400">
                                No race results found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($results->hasPages())
            <div class="mt-8">
                {{ $results->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
