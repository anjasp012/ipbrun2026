<x-layouts.app title="Rute Resmi">
    <!-- Leaflet CSS & JS via CDN -->
    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('gpxViewer', () => ({
                    modalOpen: false,
                    loading: false,
                    currentCategory: '',
                    currentDistance: '',
                    currentGpxUrl: '',
                    currentThemeColor: '',
                    map: null,
                    polyline: null,
                    startMarker: null,
                    endMarker: null,

                    openDetail(gpxUrl, categoryName, distance, themeColor) {
                        this.loading = true;
                        this.currentCategory = categoryName;
                        this.currentDistance = distance;
                        this.currentGpxUrl = gpxUrl;
                        this.currentThemeColor = themeColor;
                        this.modalOpen = true;

                        // Fetch GPX file
                        fetch(gpxUrl)
                            .then(res => {
                                if (!res.ok) throw new Error('Gagal memuat file GPX');
                                return res.text();
                            })
                            .then(text => {
                                this.loading = false;

                                // Wait for modal to render and map container to be visible
                                this.$nextTick(() => {
                                    setTimeout(() => {
                                        this.initMap(text, themeColor);
                                    }, 150);
                                });
                            })
                            .catch(err => {
                                this.loading = false;
                                Swal.fire('Error', err.message, 'error');
                                this.modalOpen = false;
                            });
                    },

                    initMap(gpxText, colorHex) {
                        // Initialize map if not yet done
                        if (!this.map) {
                            this.map = L.map('gpx-map', {
                                scrollWheelZoom: true
                            }).setView([-6.56084, 106.72611], 14);

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap contributors'
                            }).addTo(this.map);
                        }

                        // Force Leaflet to recalculate container size
                        this.map.invalidateSize();

                        // Remove existing layers if any
                        if (this.polyline) this.map.removeLayer(this.polyline);
                        if (this.startMarker) this.map.removeLayer(this.startMarker);
                        if (this.endMarker) this.map.removeLayer(this.endMarker);

                        try {
                            // Parse XML
                            let parser = new DOMParser();
                            let xmlDoc = parser.parseFromString(gpxText, 'text/xml');
                            let trkpts = xmlDoc.getElementsByTagName('trkpt');

                            if (trkpts.length === 0) {
                                throw new Error('Tidak ditemukan titik rute di dalam file GPX');
                            }

                            let latlngs = [];
                            for (let i = 0; i < trkpts.length; i++) {
                                let lat = parseFloat(trkpts[i].getAttribute('lat'));
                                let lon = parseFloat(trkpts[i].getAttribute('lon'));
                                latlngs.push([lat, lon]);
                            }

                            // Draw Route Polyline
                            this.polyline = L.polyline(latlngs, {
                                color: colorHex || '#E8630A',
                                weight: 5,
                                opacity: 0.9,
                                lineJoin: 'round'
                            }).addTo(this.map);

                            // Zoom map to fit polyline
                            this.map.fitBounds(this.polyline.getBounds(), { padding: [30, 30] });

                            // Add START marker
                            this.startMarker = L.marker(latlngs[0], {
                                icon: L.divIcon({
                                    html: '<div class="w-8 h-8 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center font-black text-xs text-white shadow-xl animate-bounce">S</div>',
                                    className: '',
                                    iconSize: [32, 32],
                                    iconAnchor: [16, 16]
                                })
                            }).addTo(this.map).bindPopup('<b>STARTING POINT</b><br>' + this.currentCategory);

                            // Add FINISH marker
                            this.endMarker = L.marker(latlngs[latlngs.length - 1], {
                                icon: L.divIcon({
                                    html: '<div class="w-8 h-8 rounded-full bg-rose-500 border-2 border-white flex items-center justify-center font-black text-xs text-white shadow-xl">F</div>',
                                    className: '',
                                    iconSize: [32, 32],
                                    iconAnchor: [16, 16]
                                })
                            }).addTo(this.map).bindPopup('<b>FINISH LINE</b><br>' + this.currentCategory);

                        } catch (e) {
                            Swal.fire('Parsing Error', 'Gagal memproses file GPX: ' + e.message, 'error');
                        }
                    }
                }));
            });
        </script>
    @endpush

    <div class="fixed inset-0 bg-[#f1f5f9] z-[-2]"></div>
    <div class="fixed inset-0 z-[-1] bg-cover bg-center bg-fixed opacity-100"
        style="background-image: url('{{ asset('assets/images/bg.png') }}')"></div>
    <div class="fixed inset-0 bg-blue-950/25 z-[-1]"></div>

    <div class="min-h-screen py-16 px-4 sm:px-6 lg:px-8 flex flex-col items-center"
         x-data="gpxViewer">

        <!-- Header Section -->
        <div class="max-w-4xl w-full text-center mb-16">
            <div class="inline-flex items-center gap-2.5 px-4.5 py-1.5 bg-orange-500/20 text-[#FF7A21] rounded-full text-[10px] font-black uppercase tracking-[3px] mb-6 border border-orange-500/30 backdrop-blur-sm animate-pulse">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Race Route Maps
            </div>
            <h1 class="text-4xl md:text-6xl font-[900] text-white uppercase tracking-tight mb-4 drop-shadow-md">
                Rute Resmi <span class="text-[#FF7A21]">IPB RUN 2026</span>
            </h1>
            <p class="text-white/80 font-bold uppercase tracking-[3px] text-xs md:text-sm max-w-2xl mx-auto leading-relaxed">
                Jelajahi dan unduh peta navigasi resmi untuk masing-masing kategori lomba di Kampus Dramaga, IPB University.
            </p>
        </div>

        <!-- GPX Category Cards -->
        <div class="max-w-7xl w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 px-4 md:px-0">
            @php
                $categories = [
                    [
                        'name' => '5K Fun Run',
                        'distance' => '5 KM',
                        'desc' => 'Rute santai melintasi ikon-ikon keindahan dan kerimbunan Kampus Dramaga. Sangat cocok bagi pemula dan keluarga.',
                        'gpx' => asset('assets/gpx/5K.gpx'),
                        'theme' => 'emerald',
                        'colorHex' => '#10B981',
                        'icon' => '<svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                    ],
                    [
                        'name' => '10K Challenger',
                        'distance' => '10 KM',
                        'desc' => 'Tantangan tingkat menengah untuk menguji kecepatan Anda. Melalui tanjakan-tanjakan taktis kampus yang asri.',
                        'gpx' => asset('assets/gpx/10K.gpx'),
                        'theme' => 'sky',
                        'colorHex' => '#0EA5E9',
                        'icon' => '<svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>'
                    ],
                    [
                        'name' => '21K Half Marathon',
                        'distance' => '21 KM',
                        'desc' => 'Setengah marathon menantang ketahanan fisik penuh dengan rute komplit berkeliling seluruh penjuru Kampus IPB.',
                        'gpx' => asset('assets/gpx/21K.gpx'),
                        'theme' => 'indigo',
                        'colorHex' => '#6366F1',
                        'icon' => '<svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>'
                    ],
                    [
                        'name' => '42K Full Marathon',
                        'distance' => '42 KM',
                        'desc' => 'Ujian ketahanan tertinggi bagi pelari tangguh! Melibatkan putaran prestisius dan penuh memori di IPB RUN 2026.',
                        'gpx' => asset('assets/gpx/42K.gpx'),
                        'theme' => 'rose',
                        'colorHex' => '#F43F5E',
                        'icon' => '<svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-11.314l.707.707m11.314 11.314l.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z"></path></svg>'
                    ]
                ];
            @endphp

            @foreach ($categories as $cat)
                <!-- Category Card -->
                <div class="group bg-white/95 backdrop-blur-md rounded-[1.5rem] border border-white/20 shadow-2xl p-8 hover:scale-[1.03] transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <!-- Icon & Distance Badge -->
                        <div class="flex items-center justify-between mb-8">
                            <div class="w-14 h-14 bg-{{ $cat['theme'] }}-50 rounded-[1.25rem] flex items-center justify-center border border-{{ $cat['theme'] }}-100 group-hover:scale-110 transition-transform">
                                {!! $cat['icon'] !!}
                            </div>
                            <span class="px-5 py-2 rounded-full bg-{{ $cat['theme'] }}-50 text-{{ $cat['theme'] }}-600 text-xs font-black tracking-widest border border-{{ $cat['theme'] }}-100/50 uppercase">
                                {{ $cat['distance'] }}
                            </span>
                        </div>

                        <!-- Card Meta -->
                        <h3 class="text-xl font-black text-[#003366] uppercase tracking-tight mb-3">
                            {{ $cat['name'] }}
                        </h3>
                        <p class="text-slate-500 text-xs leading-relaxed font-bold uppercase opacity-80 mb-6">
                            {{ $cat['desc'] }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3 mt-6">
                        <button type="button"
                                id="btn-detail-{{ $cat['theme'] }}"
                                @click="openDetail('{{ $cat['gpx'] }}', '{{ $cat['name'] }}', '{{ $cat['distance'] }}', '{{ $cat['colorHex'] }}')"
                                class="w-full h-13 bg-slate-50 text-[#003366] border border-slate-200/80 rounded-2xl flex items-center justify-center gap-2 font-black text-[11px] uppercase tracking-widest hover:bg-[#003366] hover:text-white hover:border-[#003366] transition-all active:scale-95 shadow-sm">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Detail GPX
                        </button>
                        
                        <a href="{{ $cat['gpx'] }}" 
                           download
                           id="btn-download-{{ $cat['theme'] }}"
                           class="w-full h-13 bg-[#E8630A] text-white rounded-2xl flex items-center justify-center gap-2 font-black text-[11px] uppercase tracking-widest hover:bg-[#d05607] transition-all active:scale-95 shadow-md shadow-orange-600/10">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh GPX
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Detail Modal Container (Alpine.js) -->
        <div x-show="modalOpen" 
             x-cloak
             style="display: none"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto"
             role="dialog"
             aria-modal="true">
            
            <!-- Backdrop with Blur -->
            <div x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-blue-950/40 backdrop-blur-sm"
                 @click="modalOpen = false"></div>

            <!-- Modal Content Card -->
            <div x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="relative w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden flex flex-col z-10">
                
                <!-- Loading State inside Modal -->
                <div x-show="loading" class="absolute inset-0 bg-white/80 backdrop-blur-xs z-50 flex flex-col items-center justify-center">
                    <div class="w-12 h-12 border-4 border-[#003366] border-t-transparent rounded-full animate-spin mb-4"></div>
                    <span class="text-sm font-black text-[#003366] uppercase tracking-widest">Loading Route Data...</span>
                </div>

                <!-- Modal Header -->
                <div class="px-8 md:px-10 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl md:text-2xl font-black text-[#003366] uppercase tracking-tight" x-text="currentCategory"></h2>
                            <span class="px-3.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-black rounded-full uppercase tracking-wider" x-text="currentDistance"></span>
                        </div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Peta Navigasi & File Koordinat GPX</p>
                    </div>
                    <button type="button"
                            @click="modalOpen = false"
                            class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-100 flex items-center justify-center transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-8 md:p-10 flex-1">
                    <div class="space-y-4">
                        <div id="gpx-map" class="w-full h-[400px] rounded-3xl border border-slate-100 shadow-inner bg-slate-100 overflow-hidden relative z-0"></div>
                        <div class="flex items-center gap-6 text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider pl-1 pt-1">
                            <div class="flex items-center gap-2">
                                <div class="w-3.5 h-3.5 rounded-full bg-emerald-500 border border-white shadow-sm flex items-center justify-center font-black text-[7px] text-white">S</div>
                                <span>Titik Start</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3.5 h-3.5 rounded-full bg-rose-500 border border-white shadow-sm flex items-center justify-center font-black text-[7px] text-white">F</div>
                                <span>Titik Finish</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-1 rounded-full" :style="{ backgroundColor: currentThemeColor }"></div>
                                <span>Garis Rute Perlombaan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-8 md:px-10 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-slate-400 font-bold uppercase tracking-widest text-[9px] md:text-xs">
                        IPB RUN 2026 • OFFICIAL MAP NAVIGATION
                    </span>
                    <a :href="currentGpxUrl" 
                       download
                       class="h-12 px-6 bg-[#E8630A] text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-[#d05607] transition-all flex items-center gap-2 active:scale-95 shadow-md shadow-orange-600/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download File
                    </a>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-16 text-center pb-20">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 px-8 py-4.5 bg-white/20 hover:bg-white/30 backdrop-blur-md text-white rounded-[2rem] font-[800] text-xs uppercase tracking-widest transition-all active:scale-95 border border-white/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</x-layouts.app>
