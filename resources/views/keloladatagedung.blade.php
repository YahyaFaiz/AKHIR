<x-layouts::app :title="__('Kelola Data Gedung')">

    <!-- ================= CSS & JS LEAFLET & LEAFLET DRAW CDN ================= -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl" x-data="buildingManager()" x-init="initMasterMap()">

        <!-- ================= 1. HEADER & BARIS AKSI UTAMA ================= -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-neutral-900 dark:text-neutral-100">MANAJEMEN SISTEM / DATA GEDUNG &
                    SCOPE WILAYAH</h1>
                <p class="text-xs text-neutral-500 dark:text-neutral-400">Peta spasial master tapak bangunan fisik gedung
                    kampus dan pemetaan keterikatan zona geofence.</p>
            </div>

            <!-- Tombol Tambah Gedung Baru -->
            <button type="button" @click="openAddModal()"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-indigo-500 transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                + Tambah Gedung Baru
            </button>
        </div>

        <!-- ================= 2. RINGKASAN WIDGET CARD ================= -->
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <div
                class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400">TOTAL GEDUNG</span>
                <div class="mt-2 text-2xl font-bold text-neutral-900 dark:text-neutral-100"
                    x-text="buildings.length + ' Gedung'"></div>
                <span class="text-[10px] text-neutral-400">Terdaftar di Sistem</span>
            </div>

            <div
                class="flex flex-col justify-between rounded-xl border border-blue-200 bg-blue-50/40 p-4 shadow-sm dark:border-blue-900/50 dark:bg-blue-950/20">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">SCOPE
                    FAKULTAS</span>
                <div class="mt-2 text-2xl font-bold text-blue-700 dark:text-blue-300"
                    x-text="countScopeFakultas() + ' Fakultas'"></div>
                <span class="text-[10px] text-blue-600/80 dark:text-blue-400/80">Unit Fakultas</span>
            </div>

            <div
                class="flex flex-col justify-between rounded-xl border border-indigo-200 bg-indigo-50/40 p-4 shadow-sm dark:border-indigo-900/50 dark:bg-indigo-950/20">
                <span
                    class="text-[11px] font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">POLIGON
                    TAPAK BANGUNAN</span>
                <div class="mt-2 text-2xl font-bold text-indigo-700 dark:text-indigo-300"
                    x-text="buildings.length + ' Area Poligon'"></div>
                <span class="text-[10px] text-indigo-600/80 dark:text-indigo-400/80">Terplot di Peta Master</span>
            </div>

            <div
                class="flex flex-col justify-between rounded-xl border border-emerald-200 bg-emerald-50/40 p-4 shadow-sm dark:border-emerald-900/50 dark:bg-emerald-950/20">
                <span
                    class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">STATUS
                    GEOFENCE</span>
                <div class="mt-2 text-2xl font-bold text-emerald-700 dark:text-emerald-300"
                    x-text="'✓ ' + countGeofenced() + ' Terhubung'"></div>
                <span class="text-[10px] text-emerald-600/80 dark:text-emerald-400/80">Zona Geofence Kampus</span>
            </div>
        </div>

        <!-- ================= 3. PETA UTAMA SPASIAL KAMPUS (BESAR) ================= -->
        <div
            class="flex flex-col rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 space-y-3">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-neutral-100 pb-3 dark:border-neutral-700/60">
                <div>
                    <h2 class="text-sm font-bold text-neutral-900 dark:text-neutral-100 flex items-center gap-2">
                        🗺️ PETA MASTER SEBARAN TAPAK BANGUNAN GEDUNG KAMPUS
                    </h2>
                    <p class="text-[11px] text-neutral-500">Visualisasi poligon seluruh gedung terdaftar. Klik area
                        poligon untuk melihat detail gedung.</p>
                </div>

                <button type="button" @click="fitMasterMapBounds()"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-1.5 text-xs font-bold text-neutral-700 hover:bg-neutral-100 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 transition-colors">
                    🎯 Fokus Semua Gedung
                </button>
            </div>

            <!-- Container Peta Leaflet Utama -->
            <div id="master-campus-map" wire:ignore
                class="h-96 w-full rounded-xl border border-neutral-200 dark:border-neutral-700 relative z-0"></div>
        </div>

        <!-- ================= 4. TABEL UTAMA & PANEL FILTER ================= -->
        <div
            class="flex flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 flex-1">

            <div
                class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-neutral-100 dark:border-neutral-700/60">
                <div class="flex flex-wrap items-center gap-1.5 bg-neutral-100 p-1 rounded-xl dark:bg-neutral-900/60">
                    <button @click="activeScope = 'Semua'; currentPage = 1"
                        :class="activeScope === 'Semua' ?
                            'bg-white text-neutral-900 shadow-sm dark:bg-neutral-800 dark:text-neutral-100' :
                            'text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'"
                        class="rounded-lg px-3 py-1.5 text-xs font-bold transition-all">
                        Semua Scope
                    </button>
                    <template x-for="sc in scopes" :key="sc">
                        <button @click="activeScope = sc; currentPage = 1"
                            :class="activeScope === sc ?
                                'bg-white text-neutral-900 shadow-sm dark:bg-neutral-800 dark:text-neutral-100' :
                                'text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'"
                            class="rounded-lg px-3 py-1.5 text-xs font-bold transition-all">
                            <span x-text="sc"></span> (<span x-text="countScope(sc)"></span>)
                        </button>
                    </template>
                </div>

                <div class="relative w-full sm:w-72">
                    <input type="text" x-model="searchQuery" @input="currentPage = 1"
                        placeholder="🔍 Cari Gedung / Kode..."
                        class="w-full rounded-lg border border-neutral-200 bg-white py-1.5 px-3 text-xs text-neutral-900 focus:border-indigo-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" />
                </div>
            </div>

            <!-- TABEL DATA GEDUNG -->
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-400">
                    <thead
                        class="bg-neutral-50 text-xs uppercase text-neutral-700 dark:bg-neutral-700/50 dark:text-neutral-300">
                        <tr>
                            <th scope="col" class="px-5 py-3.5 font-bold">KODE & NAMA GEDUNG</th>
                            <th scope="col" class="px-5 py-3.5 font-bold">TINGKAT SCOPE WILAYAH</th>
                            <th scope="col" class="px-5 py-3.5 font-bold">GEOMETRI POLIGON GEDUNG</th>
                            <th scope="col" class="px-5 py-3.5 font-bold">POLIGON GEOFENCE ZONA</th>
                            <th scope="col" class="px-5 py-3.5 font-bold">STATUS</th>
                            <th scope="col" class="px-5 py-3.5 text-right font-bold">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        <template x-for="item in paginatedBuildings" :key="item.id">
                            <tr class="hover:bg-neutral-50/80 dark:hover:bg-neutral-700/30 transition-colors align-top">
                                <td class="px-5 py-4 min-w-[200px]">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="rounded bg-neutral-100 px-2 py-0.5 font-mono text-xs font-bold text-neutral-800 dark:bg-neutral-700 dark:text-neutral-200"
                                            x-text="'[' + item.kode + ']'"></span>
                                        <span class="font-bold text-neutral-900 dark:text-neutral-100 text-sm"
                                            x-text="item.nama"></span>
                                    </div>
                                    <p class="mt-1 text-[11px] text-neutral-400" x-text="item.deskripsi"></p>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="text-xs font-bold text-neutral-800 dark:text-neutral-200"
                                        x-text="item.scope"></div>
                                    <div class="text-[10px] text-neutral-400"
                                        x-text="'(Scope ID: ' + item.scopeId + ')'"></div>
                                </td>

                                <!-- GEOMETRI POLIGON GEDUNG -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400"
                                        x-text="'📐 Poligon (' + (item.polygon ? item.polygon.length : 0) + ' Titik)'">
                                    </div>
                                    <div class="text-[10px] font-mono text-neutral-400"
                                        x-text="'Pusat: ' + getCentroid(item.polygon)"></div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="text-xs font-bold text-indigo-600 dark:text-indigo-400"
                                        x-text="'Poligon ID #' + item.geofenceId"></div>
                                    <div class="text-[10px] text-neutral-400" x-text="'(' + item.geofenceName + ')'">
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-md"
                                        :class="item.status === 'Aktif' ?
                                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400' :
                                            'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400'">
                                        <span class="h-1.5 w-1.5 rounded-full"
                                            :class="item.status === 'Aktif' ? 'bg-emerald-600' : 'bg-amber-600'"></span>
                                        <span x-text="item.status"></span>
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" @click="focusOnBuildingMap(item)"
                                            class="text-xs font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 hover:underline">
                                            [🔍Lihat Peta]
                                        </button>
                                        <button type="button" @click="openEditModal(item)"
                                            class="text-xs font-bold text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 hover:underline">
                                            [✏️Edit]
                                        </button>
                                        <button type="button" @click="deleteBuilding(item)"
                                            class="text-xs font-bold text-red-600 hover:text-red-800 dark:text-red-400 hover:underline">
                                            [🗑️Hapus]
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- FOOTER TABLE -->
            <div
                class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-4 border-t border-neutral-100 dark:border-neutral-700/60 text-xs text-neutral-500 dark:text-neutral-400">
                <div>
                    [ Total: <span class="font-bold text-neutral-800 dark:text-neutral-200"
                        x-text="filteredBuildings.length"></span> Data Gedung Terdaftar ]
                </div>

                <div class="inline-flex gap-1.5">
                    <button @click="currentPage--" :disabled="currentPage === 1"
                        class="rounded-lg border border-neutral-200 bg-white px-3 py-1.5 font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 disabled:opacity-40">&lt;
                        Prev</button>
                    <span class="px-2 py-1.5 font-bold text-neutral-800 dark:text-neutral-200"
                        x-text="currentPage"></span>
                    <button @click="currentPage++" :disabled="currentPage >= maxPage"
                        class="rounded-lg border border-neutral-200 bg-white px-3 py-1.5 font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 disabled:opacity-40">Next
                        &gt;</button>
                </div>
            </div>
        </div>

        <!-- ================= 5. MODAL POP-UP: LEAFLET DRAW POLIGON GEDUNG ================= -->
        <div x-show="showModal" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display: none;">
            <div @click.away="closeModal()"
                class="w-full max-w-5xl max-h-[90vh] overflow-y-auto rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">

                <div
                    class="flex items-center justify-between border-b border-neutral-100 pb-3 dark:border-neutral-700">
                    <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100"
                        x-text="isEditMode ? 'MODAL: EDIT GEDUNG & POLIGON (LEAFLET DRAW)' : 'MODAL: KELOLA GEDUNG & POLIGON (LEAFLET DRAW)'">
                    </h3>
                    <button type="button" @click="closeModal()"
                        class="rounded-lg p-1.5 text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700">✕</button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">

                    <!-- FORM ATRIBUT GEDUNG -->
                    <form @submit.prevent="saveBuilding" id="buildingForm" class="space-y-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">[
                            FORM INFORMASI GEDUNG ]</h4>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Kode
                                Gedung (Unique Code):</label>
                            <input type="text" x-model="form.kode" required placeholder="FT-A"
                                class="w-full rounded-lg border border-neutral-200 bg-white p-2 text-xs font-bold text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Nama
                                Gedung / Bangunan Fisik:</label>
                            <input type="text" x-model="form.nama" required placeholder="Gedung A Fakultas Teknik"
                                class="w-full rounded-lg border border-neutral-200 bg-white p-2 text-xs font-bold text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" />
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Deskripsi
                                / Fungsi Gedung:</label>
                            <input type="text" x-model="form.deskripsi" placeholder="Ruang Kuliah / Laboratorium"
                                class="w-full rounded-lg border border-neutral-200 bg-white p-2 text-xs text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" />
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Tingkat
                                Scope / Wilayah Penanggung Jawab:</label>
                            <select x-model="form.scope" required
                                class="w-full rounded-lg border border-neutral-200 bg-white p-2 text-xs font-bold text-neutral-800 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                                <template x-for="sc in scopes" :key="sc">
                                    <option :value="sc" x-text="sc"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Tautkan
                                ke Zona Geofence Spasial Kampus:</label>
                            <select x-model="form.geofenceId" required
                                class="w-full rounded-lg border border-neutral-200 bg-white p-2 text-xs font-bold text-neutral-800 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                                <option value="1">Poligon ID #1 (FT_Main_Zone)</option>
                                <option value="2">Poligon ID #2 (FMIPA_Zone)</option>
                                <option value="99">Poligon ID #99 (UNTAN_Global)</option>
                            </select>
                        </div>

                        <!-- RINGKASAN DATA POLIGON LEAFLET DRAW -->
                        <div
                            class="space-y-2 rounded-xl border border-neutral-200 bg-neutral-50 p-3 dark:border-neutral-700 dark:bg-neutral-900/40">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-neutral-800 dark:text-neutral-200">📐
                                    RINGKASAN LEAFLET DRAW POLIGON:</label>
                                <span class="text-[10px] font-mono text-indigo-600 dark:text-indigo-400 font-bold"
                                    x-text="form.polygon.length + ' Titik Sudut'"></span>
                            </div>

                            <p class="text-[11px] text-neutral-500">Gunakan Toolbar Leaflet Draw di kiri atas peta
                                untuk menggambar area poligon gedung.</p>

                            <button type="button" @click="clearDrawPolygon()" :disabled="form.polygon.length === 0"
                                class="w-full py-1.5 text-xs font-bold rounded-lg border border-red-200 bg-white text-red-600 hover:bg-red-50 dark:border-red-900/40 dark:bg-neutral-800 dark:text-red-400 disabled:opacity-40 transition-colors">
                                🗑️ Reset / Hapus Poligon
                            </button>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-2">Status
                                Gedung:</label>
                            <div class="space-y-1.5 text-xs">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="status" value="Aktif" x-model="form.status"
                                        class="text-indigo-600 focus:ring-indigo-500" />
                                    <span class="font-medium text-neutral-800 dark:text-neutral-200">Aktif (Bisa
                                        dipilih mahasiswa saat melapor)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="status" value="Nonaktif" x-model="form.status"
                                        class="text-indigo-600 focus:ring-indigo-500" />
                                    <span class="font-medium text-neutral-800 dark:text-neutral-200">Nonaktif / Dalam
                                        Renovasi Total</span>
                                </label>
                            </div>
                        </div>
                    </form>

                    <!-- PRATINJAU PETA LEAFLET DRAW TOOLBAR -->
                    <div class="flex flex-col space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">[
                            PETA INTERAKTIF + LEAFLET DRAW TOOLBAR ]</h4>
                        <span class="text-xs text-neutral-500 dark:text-neutral-400">Gunakan toolbar di kiri atas peta
                            untuk menarik/mengubah poligon tapak bangunan:</span>

                        <!-- Map Container Modal -->
                        <div id="modal-building-map" wire:ignore
                            class="h-80 w-full rounded-xl border border-neutral-200 dark:border-neutral-700 relative z-0">
                        </div>

                        <div
                            class="rounded-lg border border-neutral-200 bg-neutral-50 p-3 dark:border-neutral-700 dark:bg-neutral-900/50 space-y-1 text-xs">
                            <div class="font-mono text-neutral-600 dark:text-neutral-400"
                                x-text="'Pusat Gedung: ' + getCentroid(form.polygon)"></div>
                            <div class="font-bold text-emerald-600 dark:text-emerald-400"
                                x-text="'✓ Terhubung ke Zona Geofence ID #' + form.geofenceId"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div
                    class="flex items-center justify-end gap-2 border-t border-neutral-100 pt-4 mt-6 dark:border-neutral-700">
                    <button type="button" @click="closeModal()"
                        class="rounded-lg bg-neutral-100 px-4 py-2 text-xs font-bold text-neutral-600 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300">BATAL</button>
                    <button type="submit" form="buildingForm"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-500 shadow-sm">💾
                        SIMPAN DATA GEDUNG</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= LOGIKA ALPINE.JS & LEAFLET ================= -->
    <script>
        window.buildingManager = function() {
            return {
                activeScope: 'Semua',
                searchQuery: '',
                perPage: 5,
                currentPage: 1,

                showModal: false,
                isEditMode: false,
                selectedBuildingId: null,

                // Instance Peta Master & Modal
                masterMapInstance: null,
                masterMapLayersGroup: null,
                modalMapInstance: null,
                drawnItemsGroup: null,
                drawControl: null,

                scopes: [
                    'Fakultas Teknik',
                    'FMIPA',
                    'Global UNTAN'
                ],

                form: {
                    kode: '',
                    nama: '',
                    deskripsi: '',
                    scope: 'Fakultas Teknik',
                    scopeId: 1,
                    geofenceId: 1,
                    geofenceName: 'FT_Main_Zone',
                    polygon: [],
                    status: 'Aktif'
                },

                // DAFTAR BANYAK GEDUNG KAMPUS KELOLAAN (POLIGON SAMPEL LENGKAP)
                buildings: [{
                        id: 1,
                        kode: 'FT-A',
                        nama: 'Gedung A Fakultas Teknik',
                        deskripsi: 'Ruang Kuliah Teori & Jurusan Sipil/Elektro',
                        scope: 'Fakultas Teknik',
                        scopeId: 1,
                        geofenceId: 1,
                        geofenceName: 'FT_Main_Zone',
                        polygon: [
                            [-0.056150, 109.344900],
                            [-0.056150, 109.345100],
                            [-0.056250, 109.345100],
                            [-0.056250, 109.344900]
                        ],
                        status: 'Aktif'
                    },
                    {
                        id: 2,
                        kode: 'FT-LAB',
                        nama: 'Gedung D Lab Informatika',
                        deskripsi: 'Laboratorium Komputer & Rekayasa Perangkat Lunak',
                        scope: 'Fakultas Teknik',
                        scopeId: 1,
                        geofenceId: 1,
                        geofenceName: 'FT_Main_Zone',
                        polygon: [
                            [-0.055750, 109.345150],
                            [-0.055750, 109.345350],
                            [-0.055880, 109.345350],
                            [-0.055880, 109.345150]
                        ],
                        status: 'Aktif'
                    },
                    {
                        id: 3,
                        kode: 'FMIPA-B',
                        nama: 'Gedung B FMIPA',
                        deskripsi: 'Laboratorium Biologi & Kimia Dasar',
                        scope: 'FMIPA',
                        scopeId: 2,
                        geofenceId: 2,
                        geofenceName: 'FMIPA_Zone',
                        polygon: [
                            [-0.056400, 109.343800],
                            [-0.056400, 109.344050],
                            [-0.056550, 109.344050],
                            [-0.056550, 109.343800]
                        ],
                        status: 'Aktif'
                    },
                    {
                        id: 4,
                        kode: 'GKU-01',
                        nama: 'Gedung Kuliah Bersama (GKU)',
                        deskripsi: 'Gedung Perkuliahan Umum Lintas Fakultas',
                        scope: 'Global UNTAN',
                        scopeId: 0,
                        geofenceId: 99,
                        geofenceName: 'UNTAN_Global',
                        polygon: [
                            [-0.056900, 109.344700],
                            [-0.056900, 109.345000],
                            [-0.057100, 109.345000],
                            [-0.057100, 109.344700]
                        ],
                        status: 'Aktif'
                    },
                    {
                        id: 5,
                        kode: 'REKTORAT',
                        nama: 'Gedung Rektorat UNTAN',
                        deskripsi: 'Pusat Administrasi & Pelayanan Mahasiswa',
                        scope: 'Global UNTAN',
                        scopeId: 0,
                        geofenceId: 99,
                        geofenceName: 'UNTAN_Global',
                        polygon: [
                            [-0.055300, 109.344100],
                            [-0.055300, 109.344450],
                            [-0.055500, 109.344450],
                            [-0.055500, 109.344100]
                        ],
                        status: 'Aktif'
                    },
                    {
                        id: 6,
                        kode: 'PERPUS',
                        nama: 'Gedung Perpustakaan Utama',
                        deskripsi: 'Perpustakaan Pusat & Ruang Diskusi Digital',
                        scope: 'Global UNTAN',
                        scopeId: 0,
                        geofenceId: 99,
                        geofenceName: 'UNTAN_Global',
                        polygon: [
                            [-0.055700, 109.343700],
                            [-0.055700, 109.343950],
                            [-0.055900, 109.343950],
                            [-0.055900, 109.343700]
                        ],
                        status: 'Aktif'
                    }
                ],

                countScopeFakultas() {
                    return this.scopes.filter(s => s.includes('Fakultas') || s.includes('FMIPA')).length;
                },

                countGeofenced() {
                    return this.buildings.filter(b => b.geofenceId).length;
                },

                countScope(sc) {
                    return this.buildings.filter(b => b.scope === sc).length;
                },

                get filteredBuildings() {
                    const q = this.searchQuery.toLowerCase().trim();
                    return this.buildings.filter(b => {
                        const matchSc = this.activeScope === 'Semua' || b.scope === this.activeScope;
                        const matchQuery = !q || b.kode.toLowerCase().includes(q) ||
                            b.nama.toLowerCase().includes(q) ||
                            b.deskripsi.toLowerCase().includes(q);
                        return matchSc && matchQuery;
                    });
                },

                get paginatedBuildings() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredBuildings.slice(start, start + this.perPage);
                },

                get maxPage() {
                    return Math.ceil(this.filteredBuildings.length / this.perPage) || 1;
                },

                getCentroid(poly) {
                    if (!poly || poly.length === 0) return 'Belum ada poligon';
                    let sumLat = 0,
                        sumLng = 0;
                    poly.forEach(pt => {
                        sumLat += Number(pt[0]);
                        sumLng += Number(pt[1]);
                    });
                    return `Lat: ${(sumLat / poly.length).toFixed(6)}, Lng: ${(sumLng / poly.length).toFixed(6)}`;
                },

                // --- 1. PETA UTAMA SPASIAL KAMPUS (MASTER MAP LOGIC) ---
                initMasterMap() {
                    this.$nextTick(() => {
                        const mapEl = document.getElementById('master-campus-map');
                        if (!mapEl || typeof L === 'undefined') return;

                        if (this.masterMapInstance) return;

                        this.masterMapInstance = L.map('master-campus-map', {
                            zoomControl: true,
                            attributionControl: false
                        }).setView([-0.0562, 109.3445], 17);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this
                            .masterMapInstance);

                        this.masterMapLayersGroup = new L.FeatureGroup().addTo(this.masterMapInstance);
                        this.renderMasterMapPolygons();
                    });
                },

                renderMasterMapPolygons() {
                    if (!this.masterMapInstance || !this.masterMapLayersGroup) return;

                    this.masterMapLayersGroup.clearLayers();

                    // Layer Poligon Kampus Background (Zona Geofence)
                    L.polygon([
                        [-0.0550, 109.3430],
                        [-0.0550, 109.3468],
                        [-0.0575, 109.3468],
                        [-0.0575, 109.3430]
                    ], {
                        color: '#6366f1',
                        fillColor: '#818cf8',
                        fillOpacity: 0.08,
                        weight: 1.5,
                        dashArray: '4, 4'
                    }).addTo(this.masterMapLayersGroup);

                    // Loop Merender Seluruh Poligon Gedung
                    this.buildings.forEach(b => {
                        if (b.polygon && b.polygon.length > 0) {
                            const isFT = b.scope.includes('Teknik');
                            const isFMIPA = b.scope.includes('FMIPA');

                            const strokeColor = isFT ? '#4f46e5' : (isFMIPA ? '#0284c7' : '#d97706');
                            const fillColor = isFT ? '#6366f1' : (isFMIPA ? '#38bdf8' : '#fbbf24');

                            const poly = L.polygon(b.polygon, {
                                color: strokeColor,
                                fillColor: fillColor,
                                fillOpacity: 0.55,
                                weight: 2
                            }).addTo(this.masterMapLayersGroup);

                            poly.bindPopup(`
                            <div class="p-1 space-y-1 text-xs">
                                <div class="font-bold text-neutral-900">[${b.kode}] ${b.nama}</div>
                                <div class="text-neutral-500">${b.deskripsi}</div>
                                <div class="font-semibold text-indigo-600">Scope: ${b.scope}</div>
                                <div class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold ${b.status === 'Aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'}">
                                    Status: ${b.status}
                                </div>
                            </div>
                        `);
                        }
                    });
                },

                fitMasterMapBounds() {
                    if (this.masterMapInstance && this.masterMapLayersGroup && this.masterMapLayersGroup.getLayers()
                        .length > 0) {
                        this.masterMapInstance.fitBounds(this.masterMapLayersGroup.getBounds(), {
                            padding: [20, 20]
                        });
                    }
                },

                focusOnBuildingMap(item) {
                    if (this.masterMapInstance && item.polygon && item.polygon.length > 0) {
                        const tempPoly = L.polygon(item.polygon);
                        this.masterMapInstance.fitBounds(tempPoly.getBounds(), {
                            maxZoom: 19,
                            padding: [30, 30]
                        });

                        // Smooth scroll ke Peta Master di atas
                        document.getElementById('master-campus-map')?.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                },

                // --- 2. MODAL LEAFLET DRAW LOGIC ---
                openAddModal() {
                    this.isEditMode = false;
                    this.selectedBuildingId = null;
                    this.form = {
                        kode: '',
                        nama: '',
                        deskripsi: '',
                        scope: 'Fakultas Teknik',
                        scopeId: 1,
                        geofenceId: 1,
                        geofenceName: 'FT_Main_Zone',
                        polygon: [
                            [-0.056150, 109.344900],
                            [-0.056150, 109.345100],
                            [-0.056250, 109.345100],
                            [-0.056250, 109.344900]
                        ],
                        status: 'Aktif'
                    };
                    this.showModal = true;

                    this.$nextTick(() => {
                        this.initModalLeafletDraw();
                    });
                },

                openEditModal(item) {
                    this.isEditMode = true;
                    this.selectedBuildingId = item.id;
                    this.form = {
                        ...item,
                        polygon: JSON.parse(JSON.stringify(item.polygon || []))
                    };
                    this.showModal = true;

                    this.$nextTick(() => {
                        this.initModalLeafletDraw();
                    });
                },

                closeModal() {
                    this.showModal = false;
                    if (this.modalMapInstance) {
                        this.modalMapInstance.remove();
                        this.modalMapInstance = null;
                    }
                },

                initModalLeafletDraw() {
                    const mapEl = document.getElementById('modal-building-map');
                    if (!mapEl || typeof L === 'undefined') return;

                    if (this.modalMapInstance) {
                        this.modalMapInstance.remove();
                        this.modalMapInstance = null;
                    }

                    const defaultCenter = this.form.polygon && this.form.polygon.length > 0 ?
                        [this.form.polygon[0][0], this.form.polygon[0][1]] :
                        [-0.0562, 109.345];

                    this.modalMapInstance = L.map('modal-building-map', {
                        zoomControl: true,
                        attributionControl: false
                    }).setView(defaultCenter, 18);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.modalMapInstance);

                    this.drawnItemsGroup = new L.FeatureGroup();
                    this.modalMapInstance.addLayer(this.drawnItemsGroup);

                    if (typeof L.Control.Draw !== 'undefined') {
                        this.drawControl = new L.Control.Draw({
                            draw: {
                                polygon: {
                                    allowIntersection: false,
                                    showArea: true,
                                    shapeOptions: {
                                        color: '#f59e0b',
                                        fillColor: '#fbbf24',
                                        fillOpacity: 0.5,
                                        weight: 2.5
                                    }
                                },
                                polyline: false,
                                circle: false,
                                rectangle: false,
                                marker: false,
                                circlemarker: false
                            },
                            edit: {
                                featureGroup: this.drawnItemsGroup,
                                remove: true
                            }
                        });
                        this.modalMapInstance.addControl(this.drawControl);

                        this.modalMapInstance.on(L.Draw.Event.CREATED, (e) => {
                            const layer = e.layer;
                            this.drawnItemsGroup.clearLayers();
                            this.drawnItemsGroup.addLayer(layer);

                            const latlngs = layer.getLatLngs()[0].map(pt => [pt.lat, pt.lng]);
                            this.form.polygon = latlngs;
                        });

                        this.modalMapInstance.on(L.Draw.Event.EDITED, (e) => {
                            e.layers.eachLayer((layer) => {
                                const latlngs = layer.getLatLngs()[0].map(pt => [pt.lat, pt.lng]);
                                this.form.polygon = latlngs;
                            });
                        });

                        this.modalMapInstance.on(L.Draw.Event.DELETED, () => {
                            this.form.polygon = [];
                        });
                    }

                    if (this.form.polygon && this.form.polygon.length > 0) {
                        const polyLayer = L.polygon(this.form.polygon, {
                            color: '#f59e0b',
                            fillColor: '#fbbf24',
                            fillOpacity: 0.5,
                            weight: 2.5
                        });
                        this.drawnItemsGroup.addLayer(polyLayer);
                        this.modalMapInstance.fitBounds(polyLayer.getBounds(), {
                            padding: [20, 20]
                        });
                    }

                    setTimeout(() => {
                        if (this.modalMapInstance) this.modalMapInstance.invalidateSize();
                    }, 200);
                },

                clearDrawPolygon() {
                    if (this.drawnItemsGroup) {
                        this.drawnItemsGroup.clearLayers();
                    }
                    this.form.polygon = [];
                },

                saveBuilding() {
                    if (!this.form.kode || !this.form.nama) return;

                    if (this.isEditMode) {
                        const target = this.buildings.find(b => b.id === this.selectedBuildingId);
                        if (target) {
                            Object.assign(target, this.form);
                        }
                    } else {
                        this.buildings.unshift({
                            id: Date.now(),
                            ...this.form
                        });
                    }

                    this.renderMasterMapPolygons();
                    this.closeModal();
                },

                deleteBuilding(item) {
                    if (confirm(`Hapus data gedung "${item.nama}" (${item.kode})?`)) {
                        this.buildings = this.buildings.filter(b => b.id !== item.id);
                        this.renderMasterMapPolygons();
                    }
                }
            };
        };
    </script>
</x-layouts::app>
