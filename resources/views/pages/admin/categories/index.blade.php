<x-layouts.admin title="Manajemen Kategori">
    <div class="space-y-6">
        <!-- Header Info -->
        <div class="flex items-center justify-between bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <div>
                <h3 class="text-2xl font-black text-[#003366] uppercase tracking-tight">Manajemen Kategori Tiket</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Kelola kategori untuk klasifikasi tiket</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                    Total Categories: {{ count($categories) }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 px-6 py-4 rounded-2xl border border-emerald-100 text-sm font-bold flex items-center gap-3 animate-slide-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 text-rose-700 px-6 py-4 rounded-2xl border border-rose-100 text-sm font-bold flex items-center gap-3 animate-slide-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Add Category Form -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm sticky top-24">
                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-6">Tambah Kategori Baru</h4>
                    <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5 ml-1">Nama Kategori</label>
                            <input type="text" name="name" required placeholder="Contoh: Early Bird"
                                class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-blue-300 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
                        </div>
                        <x-button variant="navy" type="submit" class="w-full justify-center py-3.5 mt-2">
                            Simpan Kategori
                        </x-button>
                    </form>
                </div>
            </div>

            <!-- Categories List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 uppercase tracking-[2px] text-[9px] font-black text-slate-400">
                                <th class="px-8 py-5">Nama Kategori</th>
                                <th class="px-8 py-5 text-center">Total Tiket</th>
                                <th class="px-8 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($categories as $category)
                            <tr class="group hover:bg-slate-50/50 transition-colors" x-data="{ editing: false, name: '{{ $category->name }}' }">
                                <td class="px-8 py-5">
                                    <div x-show="!editing">
                                        <span class="text-sm font-bold text-slate-700 uppercase tracking-tight">{{ $category->name }}</span>
                                    </div>
                                    <div x-show="editing" x-cloak>
                                        <div class="flex items-center gap-2">
                                            <input type="text" x-model="name"
                                                class="flex-1 bg-white border border-blue-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-50 outline-none">
                                            <button @click="
                                                fetch('{{ route('categories.update', $category) }}', {
                                                    method: 'PUT',
                                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                    body: JSON.stringify({ name: name })
                                                }).then(() => window.location.reload())
                                            " class="p-1.5 bg-emerald-100 text-emerald-600 rounded-lg hover:bg-emerald-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                            <button @click="editing = false" class="p-1.5 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-wider">
                                        {{ $category->tickets_count ?? $category->tickets()->count() }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="editing = true" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-8 py-20 text-center">
                                    <div class="opacity-20 mb-3">
                                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada kategori yang ditambahkan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
