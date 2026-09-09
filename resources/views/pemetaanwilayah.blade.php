<x-layouts::app :title="__('Pemetaan Wilayah')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl" x-data="pemetaanMap()">

        <!-- ================= 1. TOOLBAR ATAS / PANEL KONTROL DIGITASI ================= -->
        <div
            class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <div>
                <h2 class="text-base font-bold text-neutral-900 dark:text-neutral-100">Digitasi Batas Geofence</h2>
                <p class="text-xs text-neutral-500 dark:text-neutral-400">Gunakan alat Leaflet Draw di peta untuk
                    menggambar dan memetakan poligon area kampus.</p>
            </div>

            <!-- Tombol Aksi Toolbar -->
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <button @click="resetDraw()"
                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-100 dark:border-neutral-700 dark:bg-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-600 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Reset Canvas
                </button>
            </div>
        </div>

        <!-- ================= 2. MAIN WORKSPACE: CANVAS PETA & PANEL ATRIBUT ================= -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 flex-1">

            <!-- SEKTOR KIRI: Canvas Peta Interaktif Leaflet JS -->
            <div
                class="lg:col-span-2 flex flex-col rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between mb-3 px-1">
                    <div class="flex items-center gap-2">
                        <span class="flex h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200">Interactive Digitization
                            Canvas</span>
                    </div>
                    <span class="text-[11px] font-medium text-neutral-400">Gunakan toolbar di kiri atas peta untuk
                        menggambar</span>
                </div>

                <!-- Container Leaflet Map (WAJIB wire:ignore agar Livewire tidak merusak instance Leaflet) -->
                <div id="leaflet-map" wire:ignore
                    class="w-full flex-1 min-h-105 rounded-lg border border-neutral-200 dark:border-neutral-700 relative z-0">
                </div>

                <!-- Indikator Keterangan Layer -->
                <div class="flex flex-wrap items-center gap-4 mt-3 px-1 text-xs text-neutral-500 dark:text-neutral-400">
                    <div class="flex items-center gap-1.5">
                        <span class="h-3 w-3 rounded bg-indigo-500/40 border border-indigo-600"></span>
                        <span>Poligon Tersimpan</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="h-3 w-3 rounded bg-amber-500/40 border border-amber-600"></span>
                        <span>Draft Area (Sedang Digambar)</span>
                    </div>
                </div>
            </div>

            <!-- SEKTOR KANAN: Panel Atribut & Geometri Spasial -->
            <div
                class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div>
                    <div class="border-b border-neutral-100 pb-3 dark:border-neutral-700">
                        <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100">Atribut & Geometri
                            Spasial</h3>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Pilih target wilayah dan kelola data
                            geometri spasial poligon.</p>
                    </div>

                    <!-- Input Target Scope Wilayah -->
                    <div class="mt-4 space-y-1.5">
                        <label class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300">Target Wilayah
                            / Scope:</label>
                        <select x-model="selectedScope"
                            class="w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs font-bold text-neutral-800 focus:border-indigo-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200">
                            <option value="Fakultas Teknik">Fakultas Teknik (ID: 1)</option>
                            <option value="FMIPA">FMIPA (ID: 2)</option>
                            <option value="Prodi Informatika">Prodi Informatika (ID: 12)</option>
                            <option value="Global UNTAN">Global Kampus UNTAN</option>
                        </select>
                    </div>

                    <!-- Informasi Geometri Spasial Real-time -->
                    <div class="mt-4 space-y-3 pt-3 border-t border-neutral-100 dark:border-neutral-700/60">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-neutral-500 dark:text-neutral-400">Target Instansi:</span>
                            <span class="font-bold text-neutral-800 dark:text-neutral-200"
                                x-text="selectedScope"></span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-neutral-500 dark:text-neutral-400">Estimasi Luas Area:</span>
                            <span class="font-extrabold text-indigo-600 dark:text-indigo-400" x-text="areaSize"></span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-neutral-500 dark:text-neutral-400">Jumlah Titik Vertex:</span>
                            <span class="font-bold text-neutral-800 dark:text-neutral-200"
                                x-text="vertexCount + ' Titik'"></span>
                        </div>
                    </div>

                    <!-- Box Daftar Koordinat -->
                    <div class="mt-4">
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Daftar
                            Koordinat (Lat, Lng):</label>
                        <div
                            class="h-40 w-full overflow-y-auto rounded-lg border border-neutral-200 bg-neutral-50 p-3 font-mono text-[11px] text-neutral-700 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                            <template x-if="coordinates.length === 0">
                                <p class="text-neutral-400 italic">Belum ada poligon yang digambar. Klik ikon poligon
                                    pada toolbar peta di sebelah kiri untuk mulai membuat batas wilayah.</p>
                            </template>
                            <ul class="space-y-1" x-show="coordinates.length > 0">
                                <template x-for="(point, idx) in coordinates" :key="idx">
                                    <li
                                        class="flex justify-between py-0.5 border-b border-neutral-100 dark:border-neutral-800">
                                        <span class="text-neutral-400" x-text="'P' + (idx + 1) + ':'"></span>
                                        <span class="font-bold"
                                            x-text="point.lat.toFixed(6) + ', ' + point.lng.toFixed(6)"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi Simpan -->
                <div class="mt-6 pt-4 border-t border-neutral-100 dark:border-neutral-700">
                    <button @click="saveGeofence()"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-indigo-500 transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
                        </svg>
                        Simpan Poligon Geofence
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= 3. TABEL DAFTAR POLIGON GEOFENCE TERDAFTAR ================= -->
        <div
            class="flex flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-lg font-bold text-neutral-900 dark:text-neutral-100">Daftar Poligon Area Terdaftar
                    </h2>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Daftar batas geofence spasial yang
                        tersimpan di dalam basis data sistem WebGIS.</p>
                </div>
                <div class="relative w-full sm:max-w-xs">
                    <input type="text" x-model="searchGeofence" placeholder="Cari area atau scope..."
                        class="w-full rounded-lg border border-neutral-200 bg-white py-1.5 px-3 text-xs text-neutral-900 focus:border-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100" />
                </div>
            </div>

            <!-- Tabel Data Spasial -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-400">
                    <thead
                        class="bg-neutral-50 text-xs uppercase text-neutral-700 dark:bg-neutral-700/50 dark:text-neutral-300">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-semibold">Nama Zona / Area</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Tingkat Scope</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Luas Area</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Jumlah Vertex</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Status</th>
                            <th scope="col" class="px-6 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        <template x-for="item in filteredGeofences" :key="item.nama">
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100" x-text="item.nama">
                                    </div>
                                    <div class="text-[11px] text-neutral-400" x-text="item.sub"></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-400/10 dark:text-blue-400"
                                        x-text="item.scope"></span>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-neutral-800 dark:text-neutral-200 font-bold"
                                    x-text="item.luas"></td>
                                <td class="px-6 py-4 text-xs" x-text="item.vertex + ' Titik Koordinat'"></td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span> Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-xs font-bold">
                                    <button @click="focusMap(item.lat, item.lng)"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Lihat di
                                        Peta</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .leaflet-tile {
            filter: grayscale(100%) brightness(0.95) contrast(1.1) !important;
        }

        .leaflet-draw-toolbar a {
            background-color: #ffffff !important;
            border-color: #e5e7eb !important;
        }
    </style>

    <!-- ================= LOGIKA ALPINE.JS & LEAFLET ================= -->
    <script>
        function pemetaanMap() {
            return {
                map: null,
                drawnItems: null,
                selectedScope: 'Fakultas Teknik',
                vertexCount: 0,
                areaSize: '0 m²',
                coordinates: [],
                searchGeofence: '',

                geofences: [{
                    nama: 'Fakultas Teknik',
                    sub: 'Zona Utama Kampus',
                    scope: 'Fakultas (ID: 1)',
                    luas: '45.200 m²',
                    vertex: 6,
                    lat: -0.0562,
                    lng: 109.345
                }],

                get filteredGeofences() {
                    if (!this.searchGeofence.trim()) return this.geofences;
                    const q = this.searchGeofence.toLowerCase();
                    return this.geofences.filter(g => g.nama.toLowerCase().includes(q) || g.scope.toLowerCase()
                        .includes(q));
                },

                init() {
                    this.$nextTick(() => {
                        this.initLeafletMap();
                    });
                },

                initLeafletMap() {
                    const container = document.getElementById('leaflet-map');
                    if (!container || typeof L === 'undefined') return;

                    if (window.pemetaanMapInstance) {
                        window.pemetaanMapInstance.remove();
                        window.pemetaanMapInstance = null;
                    }

                    const untanCenter = [-0.0562, 109.345];
                    this.map = L.map('leaflet-map', {
                        zoomControl: true,
                        dragging: true,
                        scrollWheelZoom: true,
                        touchZoom: true
                    }).setView(untanCenter, 16);

                    window.pemetaanMapInstance = this.map;

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors',
                        maxZoom: 19
                    }).addTo(this.map);

                    setTimeout(() => {
                        if (this.map) this.map.invalidateSize();
                    }, 250);

                    this.drawnItems = new L.FeatureGroup();
                    this.map.addLayer(this.drawnItems);

                    if (typeof L.Control.Draw !== 'undefined') {
                        const drawControl = new L.Control.Draw({
                            position: 'topleft',
                            draw: {
                                polygon: {
                                    allowIntersection: false,
                                    showArea: true,
                                    shapeOptions: {
                                        color: '#6366f1',
                                        fillColor: '#818cf8',
                                        fillOpacity: 0.35,
                                        weight: 2
                                    }
                                },
                                polyline: false,
                                circle: false,
                                rectangle: false,
                                marker: false,
                                circlemarker: false
                            },
                            edit: {
                                featureGroup: this.drawnItems,
                                remove: true
                            }
                        });
                        this.map.addControl(drawControl);
                    }

                    // ================= EVENT LISTENERS LEAFLET DRAW ================= //

                    // 1. Saat Poligon Baru Selesai Digambar
                    this.map.on(L.Draw.Event.CREATED, (e) => {
                        const layer = e.layer;
                        this.drawnItems.clearLayers(); // Mengosongkan canvas agar fokus pada 1 poligon aktif
                        this.drawnItems.addLayer(layer);
                        this.updateSpatialData();
                    });

                    // 2. Saat Poligon Selesai Di-edit (Klik Save pada Toolbar)
                    this.map.on(L.Draw.Event.EDITED, () => {
                        this.updateSpatialData();
                    });

                    // 3. Saat Poligon Dihapus
                    this.map.on(L.Draw.Event.DELETED, () => {
                        this.updateSpatialData();
                    });

                    // 4. Update Real-Time Secara Live Sembari Menggeser Titik Vertex
                    this.map.on('draw:editvertex', () => {
                        this.updateSpatialData();
                    });
                },

                // ================= FUNGSI KALKULASI REAL-TIME ================= //
                updateSpatialData() {
                    const layers = this.drawnItems.getLayers();

                    // Jika poligon dihapus / kosong
                    if (layers.length === 0) {
                        this.vertexCount = 0;
                        this.areaSize = '0 m²';
                        this.coordinates = [];
                        return;
                    }

                    // Ambil layer poligon teraktif
                    const polygonLayer = layers[layers.length - 1];
                    let latlngs = polygonLayer.getLatLngs();

                    // Ekstrak array titik jika bertingkat (nested array khas Leaflet)
                    if (Array.isArray(latlngs) && Array.isArray(latlngs[0])) {
                        latlngs = latlngs[0];
                    }

                    if (latlngs && latlngs.length) {
                        // Update Jumlah Vertex
                        this.vertexCount = latlngs.length;

                        // Update Array Koordinat (Lat, Lng)
                        this.coordinates = latlngs.map(pt => ({
                            lat: pt.lat,
                            lng: pt.lng
                        }));

                        // Hitung Luas Area Spasial (Shoelace / Geodesic Formula)
                        if (typeof L.GeometryUtil !== 'undefined' && L.GeometryUtil.geodesicArea) {
                            const areaInMeters = L.GeometryUtil.geodesicArea(latlngs);
                            this.areaSize = `${Math.round(areaInMeters).toLocaleString('id-ID')} m²`;
                        } else {
                            let area = 0;
                            const numPoints = latlngs.length;
                            if (numPoints > 2) {
                                for (let i = 0; i < numPoints; i++) {
                                    const p1 = latlngs[i];
                                    const p2 = latlngs[(i + 1) % numPoints];
                                    area += (p2.lng - p1.lng) * (2 + Math.sin(p1.lat * Math.PI / 180) + Math.sin(p2.lat *
                                        Math.PI / 180));
                                }
                                area = Math.abs(area * 6378137 * 6378137 * Math.PI / 360);
                            }
                            this.areaSize = `${Math.round(area).toLocaleString('id-ID')} m²`;
                        }
                    }
                },

                resetDraw() {
                    if (this.drawnItems) this.drawnItems.clearLayers();
                    this.vertexCount = 0;
                    this.areaSize = '0 m²';
                    this.coordinates = [];
                },

                saveGeofence() {
                    if (this.coordinates.length === 0) {
                        alert('Silakan gambar poligon pada peta terlebih dahulu!');
                        return;
                    }
                    alert(`Poligon Geofence untuk "${this.selectedScope}" berhasil disimpan!`);
                },

                focusMap(lat, lng) {
                    if (this.map) {
                        this.map.flyTo([lat, lng], 18, {
                            duration: 1.5
                        });
                    }
                }
            }
        }
    </script>
</x-layouts::app>
