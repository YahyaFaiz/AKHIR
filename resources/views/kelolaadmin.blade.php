<x-layouts::app :title="__('Kelola Admin')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <!-- ================= 3 WIDGET CARDS (STATUS CEPAT) ================= -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            <!-- Card 1: Total Admin/Operator -->
            <div
                class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400 dark:text-neutral-500">
                        Total Admin & Operator</p>
                    <h3 id="widget-total-admin" class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100">12
                    </h3>
                </div>
                <div class="mt-4 text-xs text-neutral-400 dark:text-neutral-500">
                    Tersebar di berbagai Fakultas & Prodi UNTAN
                </div>
            </div>

            <!-- Card 2: Status Online Secara Real-time -->
            <div
                class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400 dark:text-neutral-500">
                            Sedang Aktif</p>
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    </div>
                    <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100">3</h3>
                </div>
                <div class="mt-4 text-xs text-emerald-600 dark:text-emerald-400">
                    ● <span id="widget-active-count">3</span> akun operator sedang membuka sistem
                </div>
            </div>

            <!-- Card 3: Jumlah Geofencing Poligon Aktif -->
            <div
                class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400 dark:text-neutral-500">
                        Poligon Geofence Aktif</p>
                    <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100">9</h3>
                </div>
                <div class="mt-4 text-xs text-neutral-400 dark:text-neutral-500">
                    Batas wilayah spasial terverifikasi
                </div>
            </div>
        </div>

        <!-- ================= MAIN WORKSPACE ================= -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 flex-1">

            <!-- SEKTOR KIRI: Tabel Utama Kelola Admin -->
            <div
                class="lg:col-span-2 flex flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">

                <!-- Header & Tombol Tambah -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-neutral-900 dark:text-neutral-100">Manajemen Hak Akses Admin
                        </h2>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Delegasikan hak akses serta batasan
                            wilayah kerja (Scope) petugas harian.</p>
                    </div>

                    <!-- Tombol Tambah Admin (Memicu Modal JS) -->
                    <button id="btn-tambah-admin"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600 transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Admin
                    </button>
                </div>

                <!-- PANEL FILTER: SEARCH & LIMIT DATA -->
                <div
                    class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-4 pb-4 border-b border-neutral-100 dark:border-neutral-700/50">
                    <!-- Pencarian Admin -->
                    <div class="relative w-full sm:max-w-xs">
                        {{-- <span
                            class="absolute inset-y-0 inset-s-0 flex items-center ps-3 pointer-events-none text-neutral-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                            </svg>
                        </span> --}}
                        <input type="text" id="search-admin" placeholder="Cari nama, email, atau wilayah..."
                            class="w-full rounded-lg border border-neutral-200 bg-white py-1.5 ps-9 pe-4 text-xs text-neutral-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100" />
                    </div>

                    <!-- Dropdown Limit Data -->
                    <div
                        class="flex items-center gap-2 self-end sm:self-auto text-xs text-neutral-500 dark:text-neutral-400">
                        <span>Tampilkan</span>
                        <select id="per-page"
                            class="rounded-lg border border-neutral-200 bg-white px-2 py-1 text-xs font-semibold text-neutral-800 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200 focus:outline-none">
                            <option value="5">5 data</option>
                            <option value="10" selected>10 data</option>
                            <option value="20">20 data</option>
                        </select>
                    </div>
                </div>

                <!-- Kontainer Tabel Responsif -->
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-400">
                        <thead
                            class="bg-neutral-50 text-xs uppercase text-neutral-700 dark:bg-neutral-700/50 dark:text-neutral-300">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold">Nama & Email</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Peran (Role)</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Cakupan (Scope)</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Aktivitas</th>
                                <th scope="col" class="px-6 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="admin-table-body" class="divide-y divide-neutral-200 dark:divide-neutral-700">

                            <!-- Dummy Data 1 (Admin Utama) -->
                            <tr class="admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100">Faiz Diennur Yahya
                                    </div>
                                    <div class="text-[11px] text-neutral-400">faiz@it.untan.ac.id</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-semibold text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-400/10 dark:text-purple-400">Admin
                                        Aplikasi</span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-neutral-500 dark:text-neutral-400">
                                    Global (Semua Wilayah)</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                        Sedang Online
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-xs font-bold">
                                    <button class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button>
                                </td>
                            </tr>

                            <!-- Dummy Data 2 (Operator FT) -->
                            <tr class="admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100">Budi Santoso</div>
                                    <div class="text-[11px] text-neutral-400">budi.bmn@untan.ac.id</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400">Operator</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-neutral-800 dark:text-neutral-200">Fakultas</div>
                                    <div class="text-[11px] text-neutral-400">Fakultas Teknik (ID: 1)</div>
                                </td>
                                <td class="px-6 py-4"><span class="text-xs text-neutral-400">Aktif 5m yang lalu</span>
                                </td>
                                <td class="px-6 py-4 text-right text-xs font-bold">
                                    <button class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button>
                                </td>
                            </tr>

                            <!-- Dummy Data 3 (Operator Prodi) -->
                            <tr class="admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100">Ani Wijaya</div>
                                    <div class="text-[11px] text-neutral-400">ani.if@untan.ac.id</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400">Operator</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-neutral-800 dark:text-neutral-200">Program Studi
                                    </div>
                                    <div class="text-[11px] text-neutral-400">Informatika (ID: 12)</div>
                                </td>
                                <td class="px-6 py-4"><span class="text-xs text-neutral-400">Aktif 2 jam yang
                                        lalu</span></td>
                                <td class="px-6 py-4 text-right text-xs font-bold">
                                    <button class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button>
                                </td>
                            </tr>

                            <!-- Dummy Data Tambahan (Biar pagination 10 data kelihatan berfungsi nyata) -->
                            <tr class="admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100">Hendra Wijaya</div>
                                    <div class="text-[11px] text-neutral-400">hendra.mipa@untan.ac.id</div>
                                </td>
                                <td class="px-6 py-4"><span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-400/10 dark:text-blue-400">Operator</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold">Fakultas</div>
                                    <div class="text-[11px] text-neutral-400">FMIPA (ID: 2)</div>
                                </td>
                                <td class="px-6 py-4"><span class="text-xs text-neutral-400">Aktif 1 hari yang
                                        lalu</span></td>
                                <td class="px-6 py-4 text-right text-xs font-bold"><button
                                        class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button></td>
                            </tr>
                            <tr class="admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100">Siska Amelia</div>
                                    <div class="text-[11px] text-neutral-400">siska.sk@untan.ac.id</div>
                                </td>
                                <td class="px-6 py-4"><span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-400/10 dark:text-blue-400">Operator</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold">Program Studi</div>
                                    <div class="text-[11px] text-neutral-400">Sist. Komputer (ID: 15)</div>
                                </td>
                                <td class="px-6 py-4"><span class="text-xs text-neutral-400">Aktif 3 hari yang
                                        lalu</span></td>
                                <td class="px-6 py-4 text-right text-xs font-bold"><button
                                        class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button></td>
                            </tr>
                            <tr class="admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100">Ahmad Fauzi</div>
                                    <div class="text-[11px] text-neutral-400">fauzi.fk@untan.ac.id</div>
                                </td>
                                <td class="px-6 py-4"><span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-400/10 dark:text-blue-400">Operator</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold">Fakultas</div>
                                    <div class="text-[11px] text-neutral-400">Kedokteran (ID: 3)</div>
                                </td>
                                <td class="px-6 py-4"><span class="text-xs text-neutral-400">Aktif 5 hari yang
                                        lalu</span></td>
                                <td class="px-6 py-4 text-right text-xs font-bold"><button
                                        class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button></td>
                            </tr>
                            <tr class="admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100">Putri Utami</div>
                                    <div class="text-[11px] text-neutral-400">putri.far@untan.ac.id</div>
                                </td>
                                <td class="px-6 py-4"><span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-400/10 dark:text-blue-400">Operator</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold">Program Studi</div>
                                    <div class="text-[11px] text-neutral-400">Farmasi (ID: 18)</div>
                                </td>
                                <td class="px-6 py-4"><span class="text-xs text-neutral-400">Aktif 1 minggu yang
                                        lalu</span></td>
                                <td class="px-6 py-4 text-right text-xs font-bold"><button
                                        class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button></td>
                            </tr>
                            <tr class="admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100">Denny Setiawan</div>
                                    <div class="text-[11px] text-neutral-400">denny.faperta@untan.ac.id</div>
                                </td>
                                <td class="px-6 py-4"><span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-400/10 dark:text-blue-400">Operator</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold">Fakultas</div>
                                    <div class="text-[11px] text-neutral-400">Pertanian (ID: 4)</div>
                                </td>
                                <td class="px-6 py-4"><span class="text-xs text-neutral-400">Aktif 1 minggu yang
                                        lalu</span></td>
                                <td class="px-6 py-4 text-right text-xs font-bold"><button
                                        class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button></td>
                            </tr>
                            <tr class="admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100">Rina Lestari</div>
                                    <div class="text-[11px] text-neutral-400">rina.fh@untan.ac.id</div>
                                </td>
                                <td class="px-6 py-4"><span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-400/10 dark:text-blue-400">Operator</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold">Fakultas</div>
                                    <div class="text-[11px] text-neutral-400">Hukum (ID: 5)</div>
                                </td>
                                <td class="px-6 py-4"><span class="text-xs text-neutral-400">Aktif 2 minggu yang
                                        lalu</span></td>
                                <td class="px-6 py-4 text-right text-xs font-bold"><button
                                        class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button></td>
                            </tr>
                            <tr class="admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100">Yusuf Pratama</div>
                                    <div class="text-[11px] text-neutral-400">yusuf.ts@untan.ac.id</div>
                                </td>
                                <td class="px-6 py-4"><span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-400/10 dark:text-blue-400">Operator</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold">Program Studi</div>
                                    <div class="text-[11px] text-neutral-400">Teknik Sipil (ID: 21)</div>
                                </td>
                                <td class="px-6 py-4"><span class="text-xs text-neutral-400">Aktif 2 minggu yang
                                        lalu</span></td>
                                <td class="px-6 py-4 text-right text-xs font-bold"><button
                                        class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button></td>
                            </tr>
                            <tr class="admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100">Mega Sari</div>
                                    <div class="text-[11px] text-neutral-400">mega.fisip@untan.ac.id</div>
                                </td>
                                <td class="px-6 py-4"><span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-400/10 dark:text-blue-400">Operator</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold">Fakultas</div>
                                    <div class="text-[11px] text-neutral-400">FISIP (ID: 6)</div>
                                </td>
                                <td class="px-6 py-4"><span class="text-xs text-neutral-400">Aktif 3 minggu yang
                                        lalu</span></td>
                                <td class="px-6 py-4 text-right text-xs font-bold"><button
                                        class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button></td>
                            </tr>
                            <tr class="admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-neutral-900 dark:text-neutral-100">Dodi Setiawan</div>
                                    <div class="text-[11px] text-neutral-400">dodi.fe@untan.ac.id</div>
                                </td>
                                <td class="px-6 py-4"><span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-400/10 dark:text-blue-400">Operator</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold">Fakultas</div>
                                    <div class="text-[11px] text-neutral-400">Ekonomi (ID: 7)</div>
                                </td>
                                <td class="px-6 py-4"><span class="text-xs text-neutral-400">Aktif 1 bulan yang
                                        lalu</span></td>
                                <td class="px-6 py-4 text-right text-xs font-bold"><button
                                        class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- FOOTER TABLE: PAGINATION -->
                <div
                    class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-4 border-t border-neutral-100 dark:border-neutral-700/50">
                    <p id="table-info" class="text-xs text-neutral-500 dark:text-neutral-400">
                        Menampilkan <span class="font-bold text-neutral-800 dark:text-neutral-200">1</span> sampai
                        <span class="font-bold text-neutral-800 dark:text-neutral-200">10</span> dari <span
                            class="font-bold text-neutral-800 dark:text-neutral-200">12</span> admin
                    </p>

                    <!-- Navigasi Halaman -->
                    <div class="inline-flex gap-1.5">
                        <button id="btn-prev"
                            class="rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 disabled:opacity-50">Sebelumnya</button>
                        <button id="btn-next"
                            class="rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 disabled:opacity-50">Berikutnya</button>
                    </div>
                </div>
            </div>

            <!-- SEKTOR KANAN: Log Aktivitas Admin (Audit Trail) -->
            <div
                class="flex flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div class="mb-4">
                    <h2 class="text-lg font-bold text-neutral-900 dark:text-neutral-100">Log Aktivitas Admin</h2>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Rekam jejak tindakan admin pada database
                        harian.</p>
                </div>

                <!-- Input Pencarian Log -->
                <div class="relative w-full mb-4">
                    {{-- <span
                        class="absolute inset-y-0 inset-s-0 flex items-center ps-3 pointer-events-none text-neutral-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                        </svg>
                    </span> --}}
                    <input type="text" id="search-log" placeholder="Cari log aktivitas..."
                        class="w-full rounded-lg border border-neutral-200 bg-white py-1.5 ps-9 pe-4 text-xs text-neutral-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100" />
                </div>

                <!-- Scrollbox Timeline Log -->
                <div class="flex-1 overflow-y-auto max-h-105 pr-2">
                    <ul id="log-list"
                        class="relative border-s border-neutral-200 dark:border-neutral-700 space-y-6 ml-2">
                        <!-- Log Entry 1 -->
                        <li class="ms-4 log-item transition-all duration-300">
                            <div
                                class="absolute -inset-s-1.5 mt-1.5 h-3 w-3 rounded-full border border-white bg-indigo-500 dark:border-neutral-800">
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-[10px] text-neutral-400 dark:text-neutral-500">Hari ini - 10:15
                                    WIB</span>
                                <span
                                    class="inline-flex items-center rounded-md bg-green-50 px-1.5 py-0.5 text-[9px] font-bold text-green-700 ring-1 ring-inset ring-green-600/10 dark:bg-green-500/10 dark:text-green-400">Spasial</span>
                            </div>
                            <p class="mt-1 text-xs font-bold text-neutral-800 dark:text-neutral-200">Budi Santoso</p>
                            <p class="text-[11px] text-neutral-500 dark:text-neutral-400">Menggambar poligon Geofencing
                                baru untuk wilayah Fakultas Teknik.</p>
                        </li>

                        <!-- Log Entry 2 -->
                        <li class="ms-4 log-item transition-all duration-300">
                            <div
                                class="absolute -inset-s-1.5 mt-1.5 h-3 w-3 rounded-full border border-white bg-amber-500 dark:border-neutral-800">
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-[10px] text-neutral-400 dark:text-neutral-500">Hari ini - 09:30
                                    WIB</span>
                                <span
                                    class="inline-flex items-center rounded-md bg-amber-50 px-1.5 py-0.5 text-[9px] font-bold text-amber-700 ring-1 ring-inset ring-amber-600/10 dark:bg-amber-500/10 dark:text-amber-400">Akses</span>
                            </div>
                            <p class="mt-1 text-xs font-bold text-neutral-800 dark:text-neutral-200">Faiz Diennur
                                (Admin IT)</p>
                            <p class="text-[11px] text-neutral-500 dark:text-neutral-400">Mengubah hak akses
                                operasional akun Ani Wijaya menjadi Operator Prodi.</p>
                        </li>

                        <!-- Log Entry 3 -->
                        <li class="ms-4 log-item transition-all duration-300">
                            <div
                                class="absolute -inset-s-1.5 mt-1.5 h-3 w-3 rounded-full border border-white bg-red-500 dark:border-neutral-800">
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-[10px] text-neutral-400 dark:text-neutral-500">Kemarin - 16:45
                                    WIB</span>
                                <span
                                    class="inline-flex items-center rounded-md bg-red-50 px-1.5 py-0.5 text-[9px] font-bold text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-500/10 dark:text-red-400">Aset</span>
                            </div>
                            <p class="mt-1 text-xs font-bold text-neutral-800 dark:text-neutral-200">Ani Wijaya</p>
                            <p class="text-[11px] text-neutral-500 dark:text-neutral-400">Menghapus laporan kerusakan
                                fasilitas toilet Teknik Kimia (duplikat).</p>
                        </li>
                    </ul>

                    <!-- Tombol Muat Lebih Banyak (Dinamis JavaScript) -->
                    <div class="mt-6 text-center">
                        <button id="btn-load-more"
                            class="inline-flex items-center justify-center gap-1.5 w-full rounded-lg border border-neutral-200 bg-neutral-50 hover:bg-neutral-100 dark:border-neutral-700 dark:bg-neutral-800/50 dark:hover:bg-neutral-700/80 px-4 py-2 text-xs font-bold text-neutral-600 dark:text-neutral-400 transition-all focus:outline-none">
                            <span>Muat Aktivitas Lainnya</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor" class="size-3 animate-bounce">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= MODAL TAMBAH ADMIN (POP-UP YANG BERFUNGSI NYATA!) ================= -->
    <div id="modal-admin"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
        <div
            class="w-full max-w-md scale-95 transform rounded-2xl border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-700 dark:bg-neutral-800 transition-all duration-300">
            <div class="flex items-center justify-between border-b border-neutral-100 pb-3 dark:border-neutral-700">
                <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100">Tambah Akun Admin Baru</h3>
                <button id="btn-close-modal"
                    class="rounded-lg p-1 text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="form-tambah-admin" class="space-y-4 mt-4">
                <!-- Input Nama -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400">Nama
                        Lengkap</label>
                    <input type="text" id="input-nama" required
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs text-neutral-900 focus:border-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100"
                        placeholder="Contoh: Dr. Rifi" />
                </div>
                <!-- Input Email -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400">Email
                        Instansi</label>
                    <input type="email" id="input-email" required
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs text-neutral-900 focus:border-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100"
                        placeholder="contoh@untan.ac.id" />
                </div>
                <!-- Dropdown Role -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400">Peran
                        (Role)</label>
                    <select id="input-role"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs text-neutral-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100">
                        <option value="Operator">Operator</option>
                        <option value="Admin Aplikasi">Admin Aplikasi</option>
                    </select>
                </div>
                <!-- Dropdown Scope Level -->
                <div>
                    <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400">Tingkat Wilayah
                        (Scope)</label>
                    <select id="input-scope-level"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs text-neutral-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100">
                        <option value="Fakultas">Fakultas</option>
                        <option value="Program Studi">Program Studi</option>
                        <option value="Global">Global (Semua Wilayah)</option>
                    </select>
                </div>
                <!-- Input Instansi Spesifik -->
                <div id="wrapper-instansi">
                    <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400">Nama Instansi /
                        Prodi</label>
                    <input type="text" id="input-instansi" required
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs text-neutral-900 focus:border-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100"
                        placeholder="Misal: Teknik Elektro" />
                </div>

                <div class="flex justify-end gap-2 border-t border-neutral-100 pt-4 dark:border-neutral-700">
                    <button type="button" id="btn-cancel-modal"
                        class="rounded-lg bg-neutral-100 px-4 py-2 text-xs font-bold text-neutral-600 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600">Batal</button>
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-500 dark:bg-indigo-500">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= KODE VANILLA JAVASCRIPT UNTUK INTERAKTIVITAS MAKSIMAL ================= -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- VARIABEL & ELEMEN DOM ---
            const searchAdminInput = document.getElementById('search-admin');
            const perPageSelect = document.getElementById('per-page');
            const tableBody = document.getElementById('admin-table-body');
            const btnPrev = document.getElementById('btn-prev');
            const btnNext = document.getElementById('btn-next');
            const tableInfo = document.getElementById('table-info');
            const widgetTotal = document.getElementById('widget-total-admin');
            const widgetActive = document.getElementById('widget-active-count');

            const searchLogInput = document.getElementById('search-log');
            const logList = document.getElementById('log-list');
            const btnLoadMore = document.getElementById('btn-load-more');

            const modal = document.getElementById('modal-admin');
            const btnTambahAdmin = document.getElementById('btn-tambah-admin');
            const btnCloseModal = document.getElementById('btn-close-modal');
            const btnCancelModal = document.getElementById('btn-cancel-modal');
            const formTambah = document.getElementById('form-tambah-admin');
            const inputScopeLevel = document.getElementById('input-scope-level');
            const wrapperInstansi = document.getElementById('wrapper-instansi');

            let currentPage = 1;
            let perPage = parseInt(perPageSelect.value);

            // --- FUNGSI UPDATE INTEGRASI TABEL ---
            function getAdminRows() {
                return Array.from(tableBody.querySelectorAll('.admin-row'));
            }

            function updateAdminTable() {
                const rows = getAdminRows();
                const query = searchAdminInput.value.toLowerCase().trim();

                // 1. Filter baris berdasarkan pencarian
                const filteredRows = rows.filter(row => {
                    const text = row.innerText.toLowerCase();
                    return text.includes(query);
                });

                const totalData = filteredRows.length;
                const totalHalaman = Math.ceil(totalData / perPage) || 1;

                // Batasi halaman agar tidak melampaui rentang
                if (currentPage > totalHalaman) currentPage = totalHalaman;
                if (currentPage < 1) currentPage = 1;

                const indexMulai = (currentPage - 1) * perPage;
                const indexSelesai = indexMulai + perPage;

                // Sembunyikan semua baris
                rows.forEach(row => row.classList.add('hidden'));

                // Tampilkan hanya data yang masuk halaman aktif
                filteredRows.slice(indexMulai, indexSelesai).forEach(row => {
                    row.classList.remove('hidden');
                });

                // Update Widget Total Admin
                widgetTotal.innerText = rows.length;

                // Hitung berapa admin yang online saat ini di dalam baris
                const onlineCount = rows.filter(row => row.innerText.includes('Online')).length;
                widgetActive.innerText = onlineCount;

                // Update Teks Informasi Halaman
                const dataMulai = totalData === 0 ? 0 : indexMulai + 1;
                const dataSelesai = Math.min(indexSelesai, totalData);
                tableInfo.innerHTML =
                    `Menampilkan <span class="font-bold text-neutral-800 dark:text-neutral-200">${dataMulai}</span> sampai <span class="font-bold text-neutral-800 dark:text-neutral-200">${dataSelesai}</span> dari <span class="font-bold text-neutral-800 dark:text-neutral-200">${totalData}</span> admin`;

                // Atur status aktif/tidaknya tombol pagination
                btnPrev.disabled = currentPage === 1;
                btnNext.disabled = currentPage === totalHalaman;

                // Percantik visual tombol jika di-disable
                btnPrev.className =
                    `rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 ${currentPage === 1 ? 'opacity-40 cursor-not-allowed' : 'opacity-100'}`;
                btnNext.className =
                    `rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 ${currentPage === totalHalaman ? 'opacity-40 cursor-not-allowed' : 'opacity-100'}`;
            }

            // --- EVENT LISTENERS TABEL ---
            searchAdminInput.addEventListener('input', () => {
                currentPage = 1; // Balik ke halaman 1 kalau lagi nyari
                updateAdminTable();
            });

            perPageSelect.addEventListener('change', () => {
                perPage = parseInt(perPageSelect.value);
                currentPage = 1;
                updateAdminTable();
            });

            btnPrev.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    updateAdminTable();
                }
            });

            btnNext.addEventListener('click', () => {
                const totalHalaman = Math.ceil(getAdminRows().filter(row => row.innerText.toLowerCase()
                    .includes(searchAdminInput.value.toLowerCase())).length / perPage);
                if (currentPage < totalHalaman) {
                    currentPage++;
                    updateAdminTable();
                }
            });

            // Delegate event hapus baris (supaya baris baru yang di-tambah juga bisa dihapus)
            tableBody.addEventListener('click', (e) => {
                if (e.target.classList.contains('btn-delete-row')) {
                    if (confirm('Apakah Anda yakin ingin menghapus akun admin ini?')) {
                        e.target.closest('.admin-row').remove();
                        updateAdminTable();
                        tambahLogAktivitas('Sistem', 'Menghapus salah satu akun hak akses admin.');
                    }
                }
            });


            // --- FITUR LOG AKTIVITAS (AUDIT TRAIL) ---

            // Saring Log Aktivitas
            searchLogInput.addEventListener('input', () => {
                const query = searchLogInput.value.toLowerCase().trim();
                const logItems = logList.querySelectorAll('.log-item');

                logItems.forEach(item => {
                    const text = item.innerText.toLowerCase();
                    if (text.includes(query)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });

            // Dummy database untuk dimuat secara dinamis saat klik "Muat Aktivitas Lainnya"
            const logPalsuTambahan = [{
                    waktu: "Kemarin - 14:20 WIB",
                    kategori: "Spasial",
                    katClass: "bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400",
                    user: "Hendra Wijaya",
                    aksi: "Memperbarui batas poligon wilayah Fakultas Matematika dan Ilmu Pengetahuan Alam."
                },
                {
                    waktu: "06 Sep - 11:05 WIB",
                    kategori: "Akses",
                    katClass: "bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400",
                    user: "Faiz Diennur (Admin IT)",
                    aksi: "Mendaftarkan operator baru Siska Amelia untuk area Sistem Komputer."
                },
                {
                    waktu: "05 Sep - 09:15 WIB",
                    kategori: "Aset",
                    katClass: "bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400",
                    user: "Budi Santoso",
                    action: "Menolak laporan palsu kerusakan AC di ruang sekretariat FT."
                }
            ];
            let indexLogPalsu = 0;

            btnLoadMore.addEventListener('click', () => {
                if (indexLogPalsu < logPalsuTambahan.length) {
                    const data = logPalsuTambahan[indexLogPalsu];

                    const li = document.createElement('li');
                    li.className =
                        "ms-4 log-item transition-all duration-500 transform translate-y-2 opacity-0";
                    li.innerHTML = `
                        <div class="absolute -start-1.5 mt-1.5 h-3 w-3 rounded-full border border-white bg-indigo-500 dark:border-neutral-800"></div>
                        <div class="flex justify-between items-center gap-2">
                            <span class="text-[10px] text-neutral-400 dark:text-neutral-500">${data.waktu}</span>
                            <span class="inline-flex items-center rounded-md ${data.katClass} px-1.5 py-0.5 text-[9px] font-bold ring-1 ring-inset ring-neutral-600/10">${data.kategori}</span>
                        </div>
                        <p class="mt-1 text-xs font-bold text-neutral-800 dark:text-neutral-200">${data.user}</p>
                        <p class="text-[11px] text-neutral-500 dark:text-neutral-400">${data.aksi || data.action}</p>
                    `;
                    logList.appendChild(li);

                    // Animasi slide-up halus
                    setTimeout(() => {
                        li.classList.remove('translate-y-2', 'opacity-0');
                    }, 50);

                    indexLogPalsu++;
                    if (indexLogPalsu >= logPalsuTambahan.length) {
                        btnLoadMore.innerHTML = '<span>Semua Log Aktivitas Telah Dimuat</span>';
                        btnLoadMore.disabled = true;
                        btnLoadMore.classList.add('opacity-40', 'cursor-not-allowed');
                    }
                }
            });

            // Fungsi pembantu menambahkan log baru secara dinamis
            function tambahLogAktivitas(user, aksi, kategori = 'Akses', katClass =
                'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400') {
                const li = document.createElement('li');
                li.className = "ms-4 log-item transition-all duration-500 transform translate-y-2 opacity-0";
                li.innerHTML = `
                    <div class="absolute -start-1.5 mt-1.5 h-3 w-3 rounded-full border border-white bg-indigo-500 dark:border-neutral-800"></div>
                    <div class="flex justify-between items-center gap-2">
                        <span class="text-[10px] text-neutral-400 dark:text-neutral-500">Baru Saja</span>
                        <span class="inline-flex items-center rounded-md ${katClass} px-1.5 py-0.5 text-[9px] font-bold ring-1 ring-inset ring-neutral-600/10">${kategori}</span>
                    </div>
                    <p class="mt-1 text-xs font-bold text-neutral-800 dark:text-neutral-200">${user}</p>
                    <p class="text-[11px] text-neutral-500 dark:text-neutral-400">${aksi}</p>
                `;
                logList.insertBefore(li, logList.firstChild);
                setTimeout(() => {
                    li.classList.remove('translate-y-2', 'opacity-0');
                }, 50);
            }


            // --- MANAJEMEN MODAL TAMBAH ADMIN ---

            function toggleModal(open) {
                if (open) {
                    modal.classList.remove('pointer-events-none');
                    modal.classList.add('opacity-100');
                    modal.querySelector('div').classList.remove('scale-95');
                    modal.querySelector('div').classList.add('scale-100');
                } else {
                    modal.classList.add('pointer-events-none');
                    modal.classList.remove('opacity-100');
                    modal.querySelector('div').classList.add('scale-95');
                    modal.querySelector('div').classList.remove('scale-100');
                    formTambah.reset();
                    wrapperInstansi.classList.remove('hidden');
                }
            }

            btnTambahAdmin.addEventListener('click', () => toggleModal(true));
            btnCloseModal.addEventListener('click', () => toggleModal(false));
            btnCancelModal.addEventListener('click', () => toggleModal(false));

            // Sembunyikan input instansi jika scope global dipilih
            inputScopeLevel.addEventListener('change', () => {
                if (inputScopeLevel.value === 'Global') {
                    wrapperInstansi.classList.add('hidden');
                    document.getElementById('input-instansi').required = false;
                } else {
                    wrapperInstansi.classList.remove('hidden');
                    document.getElementById('input-instansi').required = true;
                }
            });

            // Submit Form (Menambahkan Admin ke Tabel secara Nyata di Sisi Klien)
            formTambah.addEventListener('submit', (e) => {
                e.preventDefault();

                const nama = document.getElementById('input-nama').value;
                const email = document.getElementById('input-email').value;
                const role = document.getElementById('input-role').value;
                const scopeLvl = inputScopeLevel.value;
                const instansi = document.getElementById('input-instansi').value;

                let scopeHTML = '';
                if (scopeLvl === 'Global') {
                    scopeHTML = 'Global (Semua Wilayah)';
                } else {
                    scopeHTML = `<div class="text-xs font-bold text-neutral-800 dark:text-neutral-200">${scopeLvl}</div>
                                 <div class="text-[11px] text-neutral-400">${instansi} (Baru)</div>`;
                }

                // Buat baris TR baru
                const tr = document.createElement('tr');
                tr.className =
                    "admin-row hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors";
                tr.innerHTML = `
                    <td class="px-6 py-4">
                        <div class="font-bold text-neutral-900 dark:text-neutral-100">${nama}</div>
                        <div class="text-[11px] text-neutral-400">${email}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center rounded-md ${role === 'Admin Aplikasi' ? 'bg-purple-50 text-purple-700 dark:bg-purple-400/10 dark:text-purple-400' : 'bg-blue-50 text-blue-700 dark:bg-blue-400/10 dark:text-blue-400'} px-2 py-1 text-xs font-semibold ring-1 ring-inset ring-neutral-600/10">${role}</span>
                    </td>
                    <td class="px-6 py-4">
                        ${scopeHTML}
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs text-neutral-400">Baru Dibuat</span>
                    </td>
                    <td class="px-6 py-4 text-right text-xs font-bold">
                        <button class="text-red-600 hover:text-red-900 btn-delete-row">Hapus</button>
                    </td>
                `;

                // Masukkan baris baru ke paling atas tabel
                tableBody.insertBefore(tr, tableBody.firstChild);

                // Tutup modal
                toggleModal(false);

                // Reset pagination ke halaman 1 dan render ulang
                currentPage = 1;
                updateAdminTable();

                // Tambahkan jejak log audit di sisi kanan
                tambahLogAktivitas('Admin Aplikasi',
                    `Mendaftarkan akun ${nama} sebagai ${role} (${scopeLvl}).`);
            });

            // Inisialisasi awal tabel saat dimuat pertama kali
            updateAdminTable();
        });
    </script>
</x-layouts::app>
