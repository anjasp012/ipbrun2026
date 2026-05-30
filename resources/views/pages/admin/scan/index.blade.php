<x-layouts.admin title="Scan Race Pack (RPC)">
    <div class="space-y-8">
        {{-- Header Banner --}}
        <div class="bg-gradient-to-br from-[#003366] to-[#001c38] rounded-2xl p-8 flex items-center justify-between relative overflow-hidden shadow-2xl shadow-blue-950/20">
            <div class="absolute -right-10 -top-10 w-56 h-56 bg-white/5 rounded-full"></div>
            <div class="absolute -left-6 -bottom-10 w-40 h-40 bg-[#E8630A]/10 rounded-full"></div>
            <div class="relative z-10">
                <p class="text-[11px] font-black text-[#E8630A] uppercase tracking-[5px] mb-2">Admin Panel</p>
                <h2 class="text-3xl font-black text-white uppercase tracking-tight">Scan Race Pack</h2>
                <p class="text-white/50 text-sm font-bold mt-2 tracking-wide">Arahkan kamera ke QR code peserta untuk mencatat pengambilan Race Pack</p>
            </div>
            <div class="relative z-10 hidden md:flex items-center gap-3">
                <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 3.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zm-12 0h.01"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-5 gap-8 items-start">

            {{-- === CAMERA SCAN PANEL (Left/Main) === --}}
            <div class="xl:col-span-3 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-black text-slate-800 uppercase tracking-widest">Kamera Scan</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Posisikan QR Code di tengah area kamera</p>
                        </div>
                        <div id="scan-indicator" class="flex items-center gap-2 px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl">
                            <span class="w-2 h-2 bg-slate-300 rounded-full" id="indicator-dot"></span>
                            <span class="text-xs font-black text-slate-400 uppercase tracking-wider" id="indicator-text">Siap</span>
                        </div>
                    </div>

                    {{-- QR Reader Area --}}
                    <div class="p-6">
                        <div class="relative bg-slate-900 rounded-2xl overflow-hidden" style="aspect-ratio: 4/3; max-height: 400px;">
                            <div id="qr-reader" class="w-full h-full"></div>

                            {{-- Overlay: scanning corners --}}
                            <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                                <div class="relative w-48 h-48">
                                    <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-[#E8630A] rounded-tl-lg"></div>
                                    <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-[#E8630A] rounded-tr-lg"></div>
                                    <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-[#E8630A] rounded-bl-lg"></div>
                                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-[#E8630A] rounded-br-lg"></div>
                                    <div id="scan-line" class="absolute left-2 right-2 h-0.5 bg-[#E8630A] opacity-80" style="top: 50%; transform: translateY(-50%);"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Camera Controls --}}
                        <div class="mt-4 flex gap-3">
                            <button id="btn-start-camera" onclick="startCamera()"
                                class="flex-1 h-12 bg-[#003366] text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-900 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Nyalakan Kamera
                            </button>
                            <button id="btn-stop-camera" onclick="stopCamera()" style="display:none;"
                                class="flex-1 h-12 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-100 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Matikan Kamera
                            </button>
                        </div>
                    </div>

                    {{-- Manual Input Section --}}
                    <div class="px-6 pb-6">
                        <div class="p-5 bg-slate-50 border border-slate-100 rounded-xl">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Input Manual (Jika Kamera Tidak Tersedia)</p>
                            <div class="flex gap-3">
                                <input type="text" id="manual-input"
                                    placeholder="Masukkan Race Entry ID..."
                                    class="flex-1 h-11 px-4 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:border-[#003366] focus:ring-2 focus:ring-[#003366]/10 font-mono"
                                    onkeydown="if(event.key==='Enter') processManualInput()">
                                <button onclick="processManualInput()"
                                    class="h-11 px-6 bg-[#003366] text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-900 transition-all">
                                    Proses
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- === RESULT PANEL === --}}
                <div id="result-panel" class="hidden bg-white rounded-2xl border-2 shadow-sm overflow-hidden transition-all">
                    <div id="result-header" class="px-8 py-5 flex items-center gap-4">
                        <div id="result-icon" class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"></div>
                        <div>
                            <p id="result-status-label" class="text-[10px] font-black uppercase tracking-widest mb-1"></p>
                            <p id="result-message" class="text-base font-black text-slate-800"></p>
                        </div>
                    </div>
                    <div id="result-details" class="px-8 pb-8 hidden">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 p-6 bg-slate-50 rounded-xl border border-slate-100">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Peserta</p>
                                <p id="res-name" class="text-sm font-black text-slate-800 uppercase"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kategori</p>
                                <p id="res-category" class="text-sm font-black text-[#003366] uppercase"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Nomor BIB</p>
                                <p id="res-bib" class="text-sm font-black text-[#E8630A] font-mono uppercase"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Ukuran Jersey</p>
                                <p id="res-jersey" class="text-sm font-black text-slate-800 uppercase"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Waktu Scan</p>
                                <p id="res-time" class="text-xs font-black text-slate-500 font-mono"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Operator</p>
                                <p id="res-operator" class="text-xs font-black text-slate-500 uppercase"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === LOG SCAN TERBARU (Right) === --}}
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Log Scan Terbaru</h3>
                        <span id="scan-count-badge" class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[11px] font-black rounded-full border border-emerald-100">
                            {{ $recentScans->count() }} scan
                        </span>
                    </div>

                    <div id="scan-log-container" class="divide-y divide-slate-50 max-h-[600px] overflow-y-auto">
                        @forelse($recentScans as $scan)
                            <div class="px-6 py-4 hover:bg-slate-50/50 transition-colors scan-log-item"
                                 data-entry-id="{{ $scan->id }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-grow min-w-0">
                                        <p class="text-sm font-black text-slate-800 uppercase truncate">{{ $scan->participant->name ?? '-' }}</p>
                                        <p class="text-[10px] font-bold text-[#003366] uppercase tracking-wide">{{ $scan->ticket->category->name ?? '-' }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1">
                                            {{ \Carbon\Carbon::parse($scan->scanned_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                            • {{ $scan->scanner->name ?? 'Unknown' }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @if($scan->bib_number)
                                            <span class="px-2.5 py-1 bg-blue-50 text-[#003366] text-[10px] font-black rounded-lg border border-blue-100 font-mono">
                                                {{ $scan->bib_number }}
                                            </span>
                                        @endif
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full flex-shrink-0"></span>
                                    </div>
                                </div>
                                {{-- Reset button: superadmin only --}}
                                @if(auth()->user()->role === 'superadmin')
                                    <form action="{{ route('admin.scan-rpc.reset', $scan->id) }}" method="POST" class="mt-2"
                                          onsubmit="return confirm('Reset status pengambilan untuk {{ addslashes($scan->participant->name ?? '') }}?')">
                                        @csrf
                                        <button type="submit"
                                            class="text-[9px] font-black text-rose-400 hover:text-rose-600 uppercase tracking-widest transition-colors">
                                            ↩ Reset Status
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="px-6 py-12 text-center" id="empty-log">
                                <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Belum ada scan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Stats Card --}}
                <div class="bg-[#003366] rounded-2xl p-6 text-white">
                    <p class="text-[10px] font-black text-white/40 uppercase tracking-[4px] mb-4">Statistik Hari Ini</p>
                    @php
                        $todayScans = \App\Models\RaceEntry::whereNotNull('scanned_at')
                            ->whereDate('scanned_at', today())
                            ->count();
                        $totalPaid = \App\Models\RaceEntry::where('status', 'paid')->count();
                        $totalScanned = \App\Models\RaceEntry::whereNotNull('scanned_at')->where('status', 'paid')->count();
                    @endphp
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-white/60 uppercase tracking-wider">Scan Hari Ini</span>
                            <span class="text-2xl font-black text-[#E8630A]">{{ number_format($todayScans) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-white/60 uppercase tracking-wider">Total Diambil</span>
                            <span class="text-2xl font-black text-emerald-400">{{ number_format($totalScanned) }}</span>
                        </div>
                        <div class="pt-4 border-t border-white/10">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-white/40 uppercase tracking-wider">Progress Pengambilan</span>
                                <span class="text-xs font-black text-white/60">{{ $totalPaid > 0 ? round(($totalScanned / $totalPaid) * 100) : 0 }}%</span>
                            </div>
                            <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-400 rounded-full transition-all"
                                    style="width: {{ $totalPaid > 0 ? round(($totalScanned / $totalPaid) * 100) : 0 }}%">
                                </div>
                            </div>
                            <p class="text-[10px] text-white/30 font-bold mt-2">{{ number_format($totalScanned) }} / {{ number_format($totalPaid) }} peserta paid</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrCode = null;
        let isProcessing = false;
        let lastScannedId = null;
        let cooldownTimer = null;

        const CSRF_TOKEN = '{{ csrf_token() }}';
        const PROCESS_URL = '{{ route('admin.scan-rpc.process') }}';

        function setIndicator(state) {
            const dot = document.getElementById('indicator-dot');
            const text = document.getElementById('indicator-text');
            const states = {
                idle:     { color: 'bg-slate-300',   label: 'Siap' },
                scanning: { color: 'bg-blue-500 animate-pulse', label: 'Scanning...' },
                success:  { color: 'bg-emerald-500', label: 'Berhasil!' },
                error:    { color: 'bg-rose-500',    label: 'Error' },
                warning:  { color: 'bg-amber-500',   label: 'Perhatian' },
            };
            const s = states[state] || states.idle;
            dot.className = `w-2 h-2 rounded-full ${s.color}`;
            text.textContent = s.label;
        }

        function startCamera() {
            html5QrCode = new Html5Qrcode("qr-reader");
            const config = {
                fps: 10,
                qrbox: { width: 220, height: 220 },
                aspectRatio: 1.333,
            };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                (errorMessage) => { /* ignore per-frame errors */ }
            ).then(() => {
                document.getElementById('btn-start-camera').style.display = 'none';
                document.getElementById('btn-stop-camera').style.display = 'flex';
                setIndicator('scanning');
                animateScanLine();
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Error',
                    text: 'Tidak dapat mengakses kamera. Pastikan izin kamera diberikan pada browser. Detail: ' + err,
                    confirmButtonColor: '#003366'
                });
            });
        }

        function stopCamera() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                    html5QrCode = null;
                    document.getElementById('btn-start-camera').style.display = 'flex';
                    document.getElementById('btn-stop-camera').style.display = 'none';
                    setIndicator('idle');
                });
            }
        }

        function animateScanLine() {
            const line = document.getElementById('scan-line');
            if (!line) return;
            let pos = 0;
            let dir = 1;
            setInterval(() => {
                pos += dir * 2;
                if (pos >= 95) dir = -1;
                if (pos <= 5) dir = 1;
                line.style.top = pos + '%';
            }, 30);
        }

        function onScanSuccess(decodedText) {
            if (isProcessing || decodedText === lastScannedId) return;

            // Play sound feedback
            playBeep();
            processScan(decodedText);
        }

        function processManualInput() {
            const val = document.getElementById('manual-input').value.trim();
            if (!val) return;
            processScan(val);
            document.getElementById('manual-input').value = '';
        }

        function processScan(raceEntryId) {
            if (isProcessing) return;
            isProcessing = true;
            lastScannedId = raceEntryId;
            setIndicator('scanning');

            fetch(PROCESS_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ race_entry_id: raceEntryId })
            })
            .then(res => res.json())
            .then(data => {
                showResult(data);
                if (data.status === 'success') {
                    addToLog(data);
                    setIndicator('success');
                } else if (data.status === 'already_scanned') {
                    setIndicator('warning');
                } else {
                    setIndicator('error');
                }
            })
            .catch(err => {
                showResult({ status: 'error', message: 'Koneksi gagal. Periksa jaringan.' });
                setIndicator('error');
            })
            .finally(() => {
                // Cooldown 2.5 seconds before next scan
                clearTimeout(cooldownTimer);
                cooldownTimer = setTimeout(() => {
                    isProcessing = false;
                    lastScannedId = null;
                    setIndicator(html5QrCode ? 'scanning' : 'idle');
                }, 2500);
            });
        }

        function showResult(data) {
            const panel = document.getElementById('result-panel');
            const header = document.getElementById('result-header');
            const icon = document.getElementById('result-icon');
            const statusLabel = document.getElementById('result-status-label');
            const message = document.getElementById('result-message');
            const details = document.getElementById('result-details');

            panel.classList.remove('hidden');

            const configs = {
                success: {
                    panelBorder: 'border-emerald-200',
                    headerBg: 'bg-emerald-50',
                    iconBg: 'bg-emerald-500 text-white',
                    iconSvg: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>',
                    labelColor: 'text-emerald-600',
                    label: '✅ Race Pack Berhasil Dicatat',
                },
                already_scanned: {
                    panelBorder: 'border-amber-200',
                    headerBg: 'bg-amber-50',
                    iconBg: 'bg-amber-500 text-white',
                    iconSvg: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
                    labelColor: 'text-amber-600',
                    label: '⚠️ Sudah Pernah Diambil',
                },
                invalid: {
                    panelBorder: 'border-orange-200',
                    headerBg: 'bg-orange-50',
                    iconBg: 'bg-orange-500 text-white',
                    iconSvg: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>',
                    labelColor: 'text-orange-600',
                    label: '🚫 Status Pembayaran Tidak Valid',
                },
                not_found: {
                    panelBorder: 'border-rose-200',
                    headerBg: 'bg-rose-50',
                    iconBg: 'bg-rose-500 text-white',
                    iconSvg: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>',
                    labelColor: 'text-rose-600',
                    label: '❌ QR Code Tidak Ditemukan',
                },
                error: {
                    panelBorder: 'border-slate-200',
                    headerBg: 'bg-slate-50',
                    iconBg: 'bg-slate-400 text-white',
                    iconSvg: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                    labelColor: 'text-slate-500',
                    label: 'Terjadi Kesalahan',
                },
            };

            const cfg = configs[data.status] || configs.error;

            panel.className = `bg-white rounded-2xl border-2 ${cfg.panelBorder} shadow-sm overflow-hidden transition-all`;
            header.className = `px-8 py-5 ${cfg.headerBg} flex items-center gap-4`;
            icon.className = `w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 ${cfg.iconBg}`;
            icon.innerHTML = cfg.iconSvg;
            statusLabel.className = `text-[10px] font-black uppercase tracking-widest mb-1 ${cfg.labelColor}`;
            statusLabel.textContent = cfg.label;
            message.textContent = data.message;

            // Fill in details if available
            if (data.participant_name) {
                document.getElementById('res-name').textContent = data.participant_name || '-';
                document.getElementById('res-category').textContent = data.category || '-';
                document.getElementById('res-bib').textContent = data.bib_number || '-';
                document.getElementById('res-jersey').textContent = data.jersey_size || '-';
                document.getElementById('res-time').textContent = data.scanned_at || '-';
                document.getElementById('res-operator').textContent = data.scanned_by || '-';
                details.classList.remove('hidden');
            } else {
                details.classList.add('hidden');
            }

            // Auto-scroll to result
            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function addToLog(data) {
            const container = document.getElementById('scan-log-container');
            const empty = document.getElementById('empty-log');
            if (empty) empty.remove();

            const logItem = document.createElement('div');
            logItem.className = 'px-6 py-4 bg-emerald-50/50 hover:bg-slate-50/50 transition-colors scan-log-item border-b border-slate-50';
            logItem.innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-grow min-w-0">
                        <p class="text-sm font-black text-slate-800 uppercase truncate">${data.participant_name || '-'}</p>
                        <p class="text-[10px] font-bold text-[#003366] uppercase tracking-wide">${data.category || '-'}</p>
                        <p class="text-[10px] font-bold text-slate-400 mt-1">${data.scanned_at || '-'} • ${data.scanned_by || '-'}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        ${data.bib_number && data.bib_number !== 'Belum ditetapkan' ? `<span class="px-2.5 py-1 bg-blue-50 text-[#003366] text-[10px] font-black rounded-lg border border-blue-100 font-mono">${data.bib_number}</span>` : ''}
                        <span class="w-2 h-2 bg-emerald-500 rounded-full flex-shrink-0"></span>
                    </div>
                </div>
            `;
            container.insertBefore(logItem, container.firstChild);

            // Update badge count
            const badge = document.getElementById('scan-count-badge');
            const current = parseInt(badge.textContent) || 0;
            badge.textContent = (current + 1) + ' scan';
        }

        function playBeep() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                const ctx = new AudioContext();
                const oscillator = ctx.createOscillator();
                const gainNode = ctx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(ctx.destination);
                oscillator.frequency.value = 880;
                oscillator.type = 'sine';
                gainNode.gain.setValueAtTime(0.3, ctx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15);
                oscillator.start(ctx.currentTime);
                oscillator.stop(ctx.currentTime + 0.15);
            } catch(e) {}
        }
    </script>
    @endpush
</x-layouts.admin>
