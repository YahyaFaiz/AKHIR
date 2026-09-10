<x-layouts::app :title="__('Laporan Masuk')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl" x-data="laporanManager()">

        <!-- ================= 1. HEADER & BARIS AKSIS UTAMA ================= -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-neutral-900 dark:text-neutral-100">DASHBOARD ADMIN / LAPORAN MASUK</h1>
                <p class="text-xs text-neutral-500 dark:text-neutral-400">Moderasi awal foto & koordinat spasial sebelum
                    dipublikasikan ke daftar voting publik.</p>
            </div>

            <!-- Tombol Aksi Cetak & Refresh -->
            <div class="flex items-center gap-2">
                <button @click="refreshData()"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    + Refresh Data
                </button>
                <button @click="printRekap()"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-indigo-500 shadow-sm transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231a1.125 1.125 0 0 1-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.08 48.08 0 0 0-3.413-.387M3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.08 48.08 0 0 1 3.413-.387m0 0A48.58 48.58 0 0 1 12 6.75c1.38 0 2.744.058 4.087.171m-8.174 0a3.75 3.75 0 0 0-3.75 3.75v3.75" />
                    </svg>
                    Cetak Rekap
                </button>
            </div>
        </div>

        <!-- ================= 2. RINGKASAN WIDGET CARD (5 WIDGET MOCKUP) ================= -->
        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
            <!-- 1. Total Masuk -->
            <div
                class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400">TOTAL MASUK</span>
                <div class="mt-2 text-2xl font-bold text-neutral-900 dark:text-neutral-100"
                    x-text="laporan.length + ' Laporan'"></div>
                <span class="text-[10px] text-neutral-400">(Semua data)</span>
            </div>

            <!-- 2. Pending Moderasi -->
            <div
                class="flex flex-col justify-between rounded-xl border border-amber-200 bg-amber-50/40 p-4 shadow-sm dark:border-amber-900/50 dark:bg-amber-950/20">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400">🔔
                    PENDING MODERASI</span>
                <div class="mt-2 text-2xl font-bold text-amber-700 dark:text-amber-300"
                    x-text="countStatus('Menunggu') + ' Perlu Cek'"></div>
                <span class="text-[10px] text-amber-600/80 dark:text-amber-400/80">(Butuh Approval)</span>
            </div>

            <!-- 3. Layak Voting (20m) -->
            <div
                class="flex flex-col justify-between rounded-xl border border-blue-200 bg-blue-50/40 p-4 shadow-sm dark:border-blue-900/50 dark:bg-blue-950/20">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">LAYAK
                    VOTING (20m)</span>
                <div class="mt-2 text-2xl font-bold text-blue-700 dark:text-blue-300"
                    x-text="countStatus('Voting') + ' Laporan'"></div>
                <span class="text-[10px] text-blue-600/80 dark:text-blue-400/80">(Aktif di Publik)</span>
            </div>

            <!-- 4. Dalam Perbaikan -->
            <div
                class="flex flex-col justify-between rounded-xl border border-indigo-200 bg-indigo-50/40 p-4 shadow-sm dark:border-indigo-900/50 dark:bg-indigo-950/20">
                <span
                    class="text-[11px] font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">DALAM
                    PERBAIKAN</span>
                <div class="mt-2 text-2xl font-bold text-indigo-700 dark:text-indigo-300"
                    x-text="countStatus('Perbaikan') + ' Laporan'"></div>
                <span class="text-[10px] text-indigo-600/80 dark:text-indigo-400/80">(SPK Perbaikan BMN)</span>
            </div>

            <!-- 5. Selesai -->
            <div
                class="flex flex-col justify-between rounded-xl border border-emerald-200 bg-emerald-50/40 p-4 shadow-sm dark:border-emerald-900/50 dark:bg-emerald-950/20">
                <span
                    class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">SELESAI</span>
                <div class="mt-2 text-2xl font-bold text-emerald-700 dark:text-emerald-300"
                    x-text="countStatus('Selesai') + ' Laporan'"></div>
                <span class="text-[10px] text-emerald-600/80 dark:text-emerald-400/80">(Selesai Fisik)</span>
            </div>
        </div>

        <!-- ================= 3. TABEL UTAMA LAPORAN MASUK ================= -->
        <div
            class="flex flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 flex-1">

            <!-- PANEL FILTER: STATUS & PENCARIAN -->
            <div
                class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6 pb-4 border-b border-neutral-100 dark:border-neutral-700/60">

                <!-- Tab Filter Status (Sesuai Wireframe Mockup) -->
                <div class="flex flex-wrap items-center gap-1.5 bg-neutral-100 p-1 rounded-xl dark:bg-neutral-900/60">
                    <button @click="activeTab = 'Semua'; currentPage = 1"
                        :class="activeTab === 'Semua' ?
                            'bg-white text-neutral-900 shadow-sm dark:bg-neutral-800 dark:text-neutral-100' :
                            'text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'"
                        class="rounded-lg px-3 py-1.5 text-xs font-bold transition-all">Semua</button>

                    <button @click="activeTab = 'Menunggu'; currentPage = 1"
                        :class="activeTab === 'Menunggu' ?
                            'bg-white text-neutral-900 shadow-sm dark:bg-neutral-800 dark:text-neutral-100' :
                            'text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'"
                        class="rounded-lg px-3 py-1.5 text-xs font-bold transition-all">
                        🔔 Menunggu Approval (<span x-text="countStatus('Menunggu')"></span>)
                    </button>

                    <button @click="activeTab = 'Voting'; currentPage = 1"
                        :class="activeTab === 'Voting' ?
                            'bg-white text-neutral-900 shadow-sm dark:bg-neutral-800 dark:text-neutral-100' :
                            'text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'"
                        class="rounded-lg px-3 py-1.5 text-xs font-bold transition-all">
                        Voting (<span x-text="countStatus('Voting')"></span>)
                    </button>

                    <button @click="activeTab = 'Perbaikan'; currentPage = 1"
                        :class="activeTab === 'Perbaikan' ?
                            'bg-white text-neutral-900 shadow-sm dark:bg-neutral-800 dark:text-neutral-100' :
                            'text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'"
                        class="rounded-lg px-3 py-1.5 text-xs font-bold transition-all">
                        Perbaikan (<span x-text="countStatus('Perbaikan')"></span>)
                    </button>

                    <button @click="activeTab = 'Selesai'; currentPage = 1"
                        :class="activeTab === 'Selesai' ?
                            'bg-white text-neutral-900 shadow-sm dark:bg-neutral-800 dark:text-neutral-100' :
                            'text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'"
                        class="rounded-lg px-3 py-1.5 text-xs font-bold transition-all">
                        Selesai
                    </button>
                </div>

                <!-- Input Cari Laporan -->
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="searchQuery" @input="currentPage = 1"
                        placeholder="🔍 Cari Laporan..."
                        class="w-full rounded-lg border border-neutral-200 bg-white py-1.5 px-3 text-xs text-neutral-900 focus:border-indigo-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" />
                </div>
            </div>

            <!-- Tabel Konten Laporan Sesuai Kolom Wireframe -->
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-400">
                    <thead
                        class="bg-neutral-50 text-xs uppercase text-neutral-700 dark:bg-neutral-700/50 dark:text-neutral-300">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-semibold">FOTO & JUDUL</th>
                            <th scope="col" class="px-4 py-3 font-semibold">KATEGORI</th>
                            <th scope="col" class="px-4 py-3 font-semibold">LOKASI & GEOTAG</th>
                            <th scope="col" class="px-4 py-3 font-semibold">PELAPOR</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-center">VOTING</th>
                            <th scope="col" class="px-4 py-3 font-semibold">STATUS</th>
                            <th scope="col" class="px-4 py-3 text-right font-semibold">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        <template x-for="item in paginatedLaporan" :key="item.id">
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <!-- Foto & Judul -->
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg bg-neutral-100 border border-neutral-200 dark:border-neutral-700">
                                            <img :src="item.fotoUrl" :alt="item.judul"
                                                class="h-full w-full object-cover" />
                                        </div>
                                        <div>
                                            <div class="font-bold text-neutral-900 dark:text-neutral-100"
                                                x-text="item.judul"></div>
                                            <div class="text-[11px] text-neutral-400" x-text="item.subJudul"></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td class="px-4 py-4">
                                    <div class="text-xs font-semibold text-neutral-800 dark:text-neutral-200"
                                        x-text="item.kategori"></div>
                                    <div class="text-[11px] text-neutral-400"
                                        x-text="'(' + item.detailKategori + ')'"></div>
                                </td>

                                <!-- Lokasi & Geotag -->
                                <td class="px-4 py-4">
                                    <div class="text-xs font-bold text-neutral-800 dark:text-neutral-200"
                                        x-text="item.lokasiFisik"></div>
                                    <div class="text-[10px] font-mono text-neutral-400"
                                        x-text="'Lat: ' + item.lat + ', Lng: ' + item.lng"></div>
                                </td>

                                <!-- Pelapor -->
                                <td class="px-4 py-4">
                                    <div class="text-xs font-medium text-neutral-800 dark:text-neutral-200"
                                        x-text="item.emailPelapor"></div>
                                    <div class="text-[11px] text-neutral-400" x-text="item.rolePelapor"></div>
                                </td>

                                <!-- Voting -->
                                <td class="px-4 py-4 text-center">
                                    <template x-if="item.status === 'Menunggu'">
                                        <span class="text-xs text-neutral-400">-</span>
                                    </template>
                                    <template x-if="item.status !== 'Menunggu'">
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-neutral-100 px-2.5 py-1 text-xs font-bold text-neutral-700 dark:bg-neutral-700 dark:text-neutral-200">
                                            👍 <span x-text="item.voting"></span>
                                        </span>
                                    </template>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold ring-1 ring-inset"
                                        :class="{
                                            'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-400': item
                                                .status === 'Menunggu',
                                            'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-400/10 dark:text-blue-400': item
                                                .status === 'Voting',
                                            'bg-indigo-50 text-indigo-700 ring-indigo-600/20 dark:bg-indigo-400/10 dark:text-indigo-400': item
                                                .status === 'Perbaikan',
                                            'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-400': item
                                                .status === 'Selesai'
                                        }"
                                        x-text="item.status === 'Menunggu' ? '🔔 Pending (Baru)' : (item.status === 'Voting' ? 'Voting Publik' : (item.status === 'Perbaikan' ? 'Perbaikan BMN' : 'Selesai'))"></span>
                                </td>

                                <!-- Tombol Aksi (Dua Jenis Sesuai Mockup Wireframe) -->
                                <td class="px-4 py-4 text-right">
                                    <template x-if="item.status === 'Menunggu'">
                                        <button @click="openModerasiModal(item)"
                                            class="rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700 hover:bg-amber-100 dark:bg-amber-950/40 dark:text-amber-300 dark:hover:bg-amber-900/60 transition-colors">
                                            [Tinjau & Cek]
                                        </button>
                                    </template>
                                    <template x-if="item.status !== 'Menunggu'">
                                        <button @click="openDetailModal(item)"
                                            class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-400 dark:hover:bg-indigo-900/60 transition-colors">
                                            [Detail]
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- FOOTER & PAGINASI -->
            <div
                class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-4 border-t border-neutral-100 dark:border-neutral-700/60">
                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                    Showing <span class="font-bold text-neutral-800 dark:text-neutral-200" x-text="startData"></span>
                    -
                    <span class="font-bold text-neutral-800 dark:text-neutral-200" x-text="endData"></span> of
                    <span class="font-bold text-neutral-800 dark:text-neutral-200"
                        x-text="filteredLaporan.length"></span> Laporan
                </p>

                <div class="inline-flex gap-1.5">
                    <button @click="currentPage--" :disabled="currentPage === 1"
                        class="rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 disabled:opacity-40">&lt;
                        Prev</button>
                    <button @click="currentPage++" :disabled="currentPage >= maxPage"
                        class="rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 disabled:opacity-40">Next
                        &gt;</button>
                </div>
            </div>
        </div>

        <!-- ================= 4. MODAL POP-UP 1: MODERASI & VERIFIKASI SPASIAL (MODERASI AWAL #101) ================= -->
        <div x-show="showModerasiModal" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            style="display: none;">
            <div @click.away="closeModerasiModal()"
                class="w-full max-w-5xl max-h-[90vh] overflow-y-auto transform rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">

                <!-- Modal Header -->
                <div
                    class="flex items-center justify-between border-b border-neutral-100 pb-4 dark:border-neutral-700">
                    <div>
                        <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100"
                            x-text="'MODAL: MODERASI & VERIFIKASI SPASIAL LAPORAN AWAL MASUK (#' + (selectedReport ? selectedReport.id : '') + ')'">
                        </h3>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Verifikasi fisik foto kamera &
                            kelayakan batas spasial poligon geofence.</p>
                    </div>
                    <button @click="closeModerasiModal()"
                        class="rounded-lg p-1.5 text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700">✕</button>
                </div>

                <template x-if="selectedReport">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

                        <!-- KOLOM KIRI: VERIFIKASI FOTO & KOORDINAT -->
                        <div
                            class="space-y-5 border-r-0 lg:border-r border-neutral-100 dark:border-neutral-700/60 lg:pr-6">
                            <h4
                                class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                                [ KOLOM KIRI: VERIFIKASI FOTO & KOORDINAT ]</h4>

                            <!-- 1. Bukti Foto Real-time Kamera -->
                            <div
                                class="rounded-xl border border-neutral-200 bg-neutral-50 p-3 dark:border-neutral-700 dark:bg-neutral-900/50">
                                <span class="block text-xs font-bold text-neutral-800 dark:text-neutral-200 mb-2">1.
                                    BUKTI FOTO REAL-TIME KAMERA PELAPOR</span>
                                <div
                                    class="h-48 w-full overflow-hidden rounded-lg border border-neutral-200 bg-neutral-200 dark:border-neutral-700 relative">
                                    <img :src="selectedReport.fotoUrl" class="h-full w-full object-cover" />
                                </div>
                                <div
                                    class="mt-2 inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                    <span>✓ Ambil Kamera Langsung (Bukan Galeri)</span>
                                </div>
                            </div>

                            <!-- 2. Geotagging & Poligon Geofence Kampus -->
                            <div
                                class="rounded-xl border border-neutral-200 bg-neutral-50 p-3 dark:border-neutral-700 dark:bg-neutral-900/50">
                                <span class="block text-xs font-bold text-neutral-800 dark:text-neutral-200 mb-2">2.
                                    GEOTAGGING & POLIGON GEOFENCE KAMPUS</span>

                                <!-- Container Map Leaflet -->
                                <div id="modal-moderasi-map" wire:ignore
                                    class="h-48 w-full rounded-lg border border-neutral-200 dark:border-neutral-700 relative z-0">
                                </div>

                                <div class="mt-3 space-y-1 text-xs">
                                    <div class="font-mono text-neutral-600 dark:text-neutral-400"
                                        x-text="'Lat: ' + selectedReport.lat.toFixed(6) + ', Lng: ' + selectedReport.lng.toFixed(6)">
                                    </div>
                                    <div class="font-bold text-emerald-600 dark:text-emerald-400"
                                        x-text="'Status Geofence: ' + selectedReport.statusGeofence"></div>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: DETAIL & KEPUTUSAN ADMIN -->
                        <div class="space-y-4">
                            <h4
                                class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                                [ KOLOM KANAN: DETAIL & KEPUTUSAN ADMIN ]</h4>

                            <!-- Ringkasan Detail Laporan -->
                            <div class="space-y-2 text-xs divide-y divide-neutral-100 dark:divide-neutral-700/60">
                                <div class="flex justify-between py-1">
                                    <span class="text-neutral-500">Judul Laporan:</span>
                                    <span class="font-bold text-neutral-900 dark:text-neutral-100"
                                        x-text="selectedReport.judul"></span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span class="text-neutral-500">Pelapor:</span>
                                    <span class="font-bold text-neutral-900 dark:text-neutral-100"
                                        x-text="selectedReport.namaPelapor + ' (NIM: ' + selectedReport.nimPelapor + ')'"></span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span class="text-neutral-500">Kategori:</span>
                                    <span class="font-bold text-neutral-900 dark:text-neutral-100"
                                        x-text="selectedReport.kategori + ' (' + selectedReport.detailKategori + ')'"></span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span class="text-neutral-500">Lokasi Fisik:</span>
                                    <span class="font-bold text-neutral-900 dark:text-neutral-100"
                                        x-text="selectedReport.lokasiFisik"></span>
                                </div>
                            </div>

                            <!-- Catatan Pelapor -->
                            <div
                                class="rounded-lg border border-neutral-200 bg-neutral-50 p-3 dark:border-neutral-700 dark:bg-neutral-900/40">
                                <span class="block text-[11px] font-semibold text-neutral-400 mb-1">Catatan
                                    Pelapor:</span>
                                <p class="text-xs italic text-neutral-700 dark:text-neutral-300"
                                    x-text="'&quot;' + selectedReport.catatanPelapor + '&quot;'"></p>
                            </div>

                            <hr class="border-neutral-100 dark:border-neutral-700" />

                            <!-- KEPUTUSAN MODERASI ADMIN -->
                            <div class="space-y-3 pt-1">
                                <h5 class="text-xs font-bold text-neutral-900 dark:text-neutral-100">KEPUTUSAN MODERASI
                                    ADMIN:</h5>

                                <div class="space-y-2 text-xs">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="keputusan" value="publish"
                                            x-model="keputusanModerasi"
                                            class="text-indigo-600 focus:ring-indigo-500" />
                                        <span class="font-semibold text-neutral-800 dark:text-neutral-200">Terbitkan ke
                                            Daftar Voting Publik (Radius 20-30m)</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="keputusan" value="bypass"
                                            x-model="keputusanModerasi"
                                            class="text-indigo-600 focus:ring-indigo-500" />
                                        <span class="font-semibold text-neutral-800 dark:text-neutral-200">Langsung
                                            Proses Perbaikan (Bypass ke BMN)</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="keputusan" value="reject"
                                            x-model="keputusanModerasi" class="text-red-600 focus:ring-red-500" />
                                        <span class="font-semibold text-red-600 dark:text-red-400">Tolak Laporan (Foto
                                            Tidak Jelas / Di Luar Area)</span>
                                    </label>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Alasan
                                        Penolakan / Catatan Petugas (Jika Ditolak):</label>
                                    <textarea x-model="catatanModerasi" rows="2" placeholder="Tuliskan alasan jika foto gelap/tidak sesuai..."
                                        class="w-full rounded-lg border border-neutral-200 bg-white p-2 text-xs text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Modal Actions Footer -->
                <div
                    class="flex items-center justify-end gap-2 border-t border-neutral-100 pt-4 mt-6 dark:border-neutral-700">
                    <button type="button" @click="closeModerasiModal()"
                        class="rounded-lg bg-neutral-100 px-4 py-2 text-xs font-bold text-neutral-600 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300">
                        BATAL
                    </button>
                    <template x-if="keputusanModerasi === 'reject'">
                        <button type="button" @click="eksekusiModerasi('reject')"
                            class="rounded-lg bg-red-600 px-4 py-2 text-xs font-bold text-white hover:bg-red-500 shadow-sm">
                            ❌ TOLAK LAPORAN
                        </button>
                    </template>
                    <template x-if="keputusanModerasi !== 'reject'">
                        <button type="button" @click="eksekusiModerasi(keputusanModerasi)"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-500 shadow-sm">
                            ✅ SETUJUI & PUBLISH
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- ================= 5. MODAL POP-UP 2: KELOLA STATUS PENANGANAN BMN & SPK (#102) ================= -->
        <div x-show="showDetailModal" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            style="display: none;">
            <div @click.away="closeDetailModal()"
                class="w-full max-w-5xl max-h-[90vh] overflow-y-auto transform rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">

                <!-- Modal Header -->
                <div
                    class="flex items-center justify-between border-b border-neutral-100 pb-4 dark:border-neutral-700">
                    <div>
                        <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100"
                            x-text="'MODAL: KELOLA STATUS PENANGANAN BMN & SPK (#' + (selectedReport ? selectedReport.id : '') + ')'">
                        </h3>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Pembaruan status operasional
                            penanganan fasilitas dan penerbitan SPK.</p>
                    </div>
                    <button @click="closeDetailModal()"
                        class="rounded-lg p-1.5 text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700">✕</button>
                </div>

                <template x-if="selectedReport">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

                        <!-- KOLOM KIRI: RINGKASAN BUKTI & DUKUNGAN -->
                        <div
                            class="space-y-4 border-r-0 lg:border-r border-neutral-100 dark:border-neutral-700/60 lg:pr-6">
                            <h4
                                class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                                [ RINGKASAN BUKTI & DUKUNGAN ]</h4>

                            <div class="space-y-2 text-xs">
                                <div
                                    class="flex justify-between py-1 border-b border-neutral-100 dark:border-neutral-700/50">
                                    <span class="text-neutral-500">Status Saat Ini:</span>
                                    <span class="font-bold text-blue-600 dark:text-blue-400"
                                        x-text="'🔵 ' + selectedReport.status"></span>
                                </div>
                                <div
                                    class="flex justify-between py-1 border-b border-neutral-100 dark:border-neutral-700/50">
                                    <span class="text-neutral-500">Dukungan Voting:</span>
                                    <span class="font-bold text-neutral-900 dark:text-neutral-100"
                                        x-text="'👍 ' + selectedReport.voting + ' Mahasiswa (Radius 20m)'"></span>
                                </div>
                            </div>

                            <!-- Preview Foto Kerusakan -->
                            <div
                                class="rounded-xl border border-neutral-200 bg-neutral-50 p-3 dark:border-neutral-700 dark:bg-neutral-900/50">
                                <span
                                    class="block text-xs font-bold text-neutral-800 dark:text-neutral-200 mb-2">[PREVIEW
                                    FOTO KERUSAKAN]</span>
                                <div
                                    class="h-40 w-full overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700 mb-2">
                                    <img :src="selectedReport.fotoUrl" class="h-full w-full object-cover" />
                                </div>
                                <div class="text-[11px] font-mono text-neutral-500"
                                    x-text="'Lat: ' + selectedReport.lat + ', Lng: ' + selectedReport.lng"></div>
                                <div class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400"
                                    x-text="'Geofence: ' + selectedReport.statusGeofence"></div>
                            </div>

                            <!-- RIWAYAT JEJAK STATUS (AUDIT TRAIL) -->
                            <div class="space-y-2 pt-2">
                                <h5 class="text-xs font-bold text-neutral-900 dark:text-neutral-100">RIWAYAT JEJAK
                                    STATUS (AUDIT TRAIL):</h5>
                                <ul class="space-y-1.5 text-xs text-neutral-600 dark:text-neutral-400">
                                    <li class="flex items-center gap-2">
                                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                        <span>• 08/09/2026 14:10 - Dilaporkan Mahasiswa</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                        <span>• 08/09/2026 15:30 - Disetujui Admin (Voting)</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: KELOLA STATUS & SURAT PERINTAH KERJA (SPK) -->
                        <div class="space-y-4">
                            <h4
                                class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                                [ KELOLA STATUS & SURAT PERINTAH KERJA (SPK) ]</h4>

                            <div class="space-y-2 text-xs border-b border-neutral-100 pb-3 dark:border-neutral-700/60">
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Judul Laporan:</span>
                                    <span class="font-bold text-neutral-900 dark:text-neutral-100"
                                        x-text="selectedReport.judul"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Lokasi Fisik:</span>
                                    <span class="font-bold text-neutral-900 dark:text-neutral-100"
                                        x-text="selectedReport.lokasiFisik"></span>
                                </div>
                            </div>

                            <!-- FORM PEMBARUAN STATUS OPERATOR BMN / FAKULTAS -->
                            <div class="space-y-3 pt-1">
                                <h5 class="text-xs font-bold text-neutral-900 dark:text-neutral-100">PEMBARUAN STATUS
                                    OPERATOR BMN / FAKULTAS:</h5>

                                <div>
                                    <label
                                        class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Pilih
                                        Status Baru:</label>
                                    <select x-model="bmnStatus"
                                        class="w-full rounded-lg border border-neutral-200 bg-white p-2 text-xs font-bold text-neutral-800 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200">
                                        <option value="Voting">🔵 Layak Voting Publik</option>
                                        <option value="Perbaikan">🛠️ Dalam Proses Perbaikan (SPK Diterbitkan)</option>
                                        <option value="Selesai">✅ Selesai Perbaikan</option>
                                    </select>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Nomor
                                        SPK / Surat Tugas Teknisi (Opsional):</label>
                                    <input type="text" x-model="nomorSPK" placeholder="SPK/BMN/FT/2026/09/012"
                                        class="w-full rounded-lg border border-neutral-200 bg-white p-2 text-xs text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" />
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Catatan
                                        Tindakan Petugas / Teknisi:</label>
                                    <textarea x-model="catatanTeknisi" rows="3"
                                        placeholder="Teknisi listrik telah ditugaskan untuk mengganti ballast dan bohlam..."
                                        class="w-full rounded-lg border border-neutral-200 bg-white p-2 text-xs text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Modal Actions Footer -->
                <div
                    class="flex items-center justify-end gap-2 border-t border-neutral-100 pt-4 mt-6 dark:border-neutral-700">
                    <button type="button" @click="closeDetailModal()"
                        class="rounded-lg bg-neutral-100 px-4 py-2 text-xs font-bold text-neutral-600 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300">
                        BATAL
                    </button>
                    <button type="button" @click="simpanPerubahanBMN()"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-500 shadow-sm">
                        💾 SIMPAN PERUBAHAN
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= LOGIKA ALPINE.JS & LEAFLET MOCKUP ================= -->
    <script>
        function laporanManager() {
            return {
                activeTab: 'Semua',
                searchQuery: '',
                perPage: 5,
                currentPage: 1,

                showModerasiModal: false,
                showDetailModal: false,
                selectedReport: null,

                // State Modal 1 (Moderasi Awal)
                keputusanModerasi: 'publish',
                catatanModerasi: '',
                moderasiMapInstance: null,

                // State Modal 2 (Kelola BMN)
                bmnStatus: 'Perbaikan',
                nomorSPK: 'SPK/BMN/FT/2026/09/012',
                catatanTeknisi: 'Teknisi listrik telah ditugaskan untuk mengganti ballast dan bohlam lampu LED yang mati.',

                laporan: [{
                        id: 101,
                        judul: 'AC Bocor Di R. A201',
                        subJudul: 'Di R. A201',
                        kategori: 'AC / Pendingin',
                        detailKategori: 'Tidak dingin',
                        lokasiFisik: 'Gedung A (A201)',
                        lat: -0.056200,
                        lng: 109.345000,
                        emailPelapor: 'd1041221087@...',
                        namaPelapor: 'Faiz Diennur Yahya',
                        nimPelapor: 'D1041221087',
                        rolePelapor: 'Mahasiswa FT',
                        voting: 0,
                        status: 'Menunggu',
                        statusGeofence: '✓ Dalam Zona Kampus UNTAN',
                        catatanPelapor: 'Air AC menetes deras ke meja kuliah baris depan.',
                        fotoUrl: 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=500&q=80'
                    },
                    {
                        id: 102,
                        judul: 'Lampu Mati Di Lab Komp',
                        subJudul: 'Di Lab Komp',
                        kategori: 'Lampu Kelas',
                        detailKategori: 'Mati total',
                        lokasiFisik: 'Lab Komputer (Gedung D)',
                        lat: -0.055800,
                        lng: 109.344000,
                        emailPelapor: 'mhs_hukum@...',
                        namaPelapor: 'Rian Hidayat',
                        nimPelapor: 'B1011211002',
                        rolePelapor: 'Mahasiswa Hukum',
                        voting: 12,
                        status: 'Voting',
                        statusGeofence: '✓ Terverifikasi di Lab FT',
                        catatanPelapor: 'Lampu neon ruangan mati total saat praktikum malam.',
                        fotoUrl: 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=500&q=80'
                    },
                    {
                        id: 103,
                        judul: 'Kran Bocor Di Toilet GKU',
                        subJudul: 'Di Toilet GKU',
                        kategori: 'Sanitasi / Toilet',
                        detailKategori: 'Kran air bocor',
                        lokasiFisik: 'Toilet Lt.2 GKU',
                        lat: -0.057000,
                        lng: 109.346000,
                        emailPelapor: 'mhs_mipa@...',
                        namaPelapor: 'Siti Rahma',
                        nimPelapor: 'H1031201045',
                        rolePelapor: 'Mahasiswa FMIPA',
                        voting: 8,
                        status: 'Perbaikan',
                        statusGeofence: '✓ Dalam Zona GKU Kampus',
                        catatanPelapor: 'Air kran tidak bisa dimatikan meluap ke koridor.',
                        fotoUrl: 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=500&q=80'
                    }
                ],

                countStatus(st) {
                    return this.laporan.filter(l => l.status === st).length;
                },

                get filteredLaporan() {
                    return this.laporan.filter(l => {
                        const matchTab = this.activeTab === 'Semua' || l.status === this.activeTab;
                        const q = this.searchQuery.toLowerCase().trim();
                        const matchQuery = !q || l.judul.toLowerCase().includes(q) || l.lokasiFisik
                        .toLowerCase().includes(q) || l.emailPelapor.toLowerCase().includes(q);
                        return matchTab && matchQuery;
                    });
                },

                get paginatedLaporan() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredLaporan.slice(start, start + this.perPage);
                },

                get maxPage() {
                    return Math.ceil(this.filteredLaporan.length / this.perPage) || 1;
                },

                get startData() {
                    return this.filteredLaporan.length === 0 ? 0 : (this.currentPage - 1) * this.perPage + 1;
                },

                get endData() {
                    return Math.min(this.currentPage * this.perPage, this.filteredLaporan.length);
                },

                // --- MODAL 1: MODERASI AWAL ---
                openModerasiModal(item) {
                    this.selectedReport = item;
                    this.keputusanModerasi = 'publish';
                    this.catatanModerasi = '';
                    this.showModerasiModal = true;

                    this.$nextTick(() => {
                        this.initModerasiMap();
                    });
                },

                closeModerasiModal() {
                    this.showModerasiModal = false;
                    if (this.moderasiMapInstance) {
                        this.moderasiMapInstance.remove();
                        this.moderasiMapInstance = null;
                    }
                },

                initModerasiMap() {
                    const mapEl = document.getElementById('modal-moderasi-map');
                    if (!mapEl || typeof L === 'undefined' || !this.selectedReport) return;

                    if (this.moderasiMapInstance) {
                        this.moderasiMapInstance.remove();
                        this.moderasiMapInstance = null;
                    }

                    const coords = [this.selectedReport.lat, this.selectedReport.lng];

                    this.moderasiMapInstance = L.map('modal-moderasi-map', {
                        zoomControl: false,
                        attributionControl: false
                    }).setView(coords, 17);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.moderasiMapInstance);

                    L.polygon([
                        [-0.0555, 109.3435],
                        [-0.0555, 109.3465],
                        [-0.0575, 109.3465],
                        [-0.0575, 109.3435]
                    ], {
                        color: '#6366f1',
                        fillColor: '#818cf8',
                        fillOpacity: 0.25,
                        weight: 2
                    }).addTo(this.moderasiMapInstance);

                    L.marker(coords).addTo(this.moderasiMapInstance)
                        .bindPopup(`<b>${this.selectedReport.judul}</b><br>Geotag Kamera Pelapor`)
                        .openPopup();

                    setTimeout(() => {
                        if (this.moderasiMapInstance) this.moderasiMapInstance.invalidateSize();
                    }, 200);
                },

                eksekusiModerasi(tipe) {
                    if (!this.selectedReport) return;

                    if (tipe === 'reject') {
                        this.selectedReport.status = 'Ditolak';
                        alert(
                            `Laporan #${this.selectedReport.id} ditolak dengan alasan: "${this.catatanModerasi || 'Foto/Lokasi tidak sesuai'}"`);
                    } else if (tipe === 'bypass') {
                        this.selectedReport.status = 'Perbaikan';
                        alert(`Laporan #${this.selectedReport.id} disetujui dan langsung diteruskan ke BMN (Perbaikan)!`);
                    } else {
                        this.selectedReport.status = 'Voting';
                        alert(`Laporan #${this.selectedReport.id} disetujui dan diterbitkan ke Voting Publik!`);
                    }

                    this.closeModerasiModal();
                },

                // --- MODAL 2: KELOLA STATUS BMN ---
                openDetailModal(item) {
                    this.selectedReport = item;
                    this.bmnStatus = item.status;
                    this.showDetailModal = true;
                },

                closeDetailModal() {
                    this.showDetailModal = false;
                },

                simpanPerubahanBMN() {
                    if (this.selectedReport) {
                        this.selectedReport.status = this.bmnStatus;
                        alert(
                            `Status laporan #${this.selectedReport.id} berhasil diperbarui menjadi "${this.bmnStatus}"! Nomor SPK: ${this.nomorSPK}`);
                        this.closeDetailModal();
                    }
                },

                refreshData() {
                    alert('Data Laporan Masuk berhasil diperbarui!');
                },

                printRekap() {
                    alert('Mencetak Rekapitulasi Laporan Masuk...');
                }
            }
        }
    </script>
</x-layouts::app>
