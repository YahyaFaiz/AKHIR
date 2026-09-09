<x-layouts::app :title="__('Kelola Admin')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" x-data="adminManager()">

        <!-- ================= 3 WIDGET CARDS (STATUS CEPAT) ================= -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Card 1: Total Admin/Operator -->
            <div
                class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400 dark:text-neutral-500">
                        Total Admin & Operator</p>
                    <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100" x-text="admins.length">
                    </h3>
                </div>
                <div class="mt-4 text-xs text-neutral-400 dark:text-neutral-500">Tersebar di berbagai Fakultas & Prodi
                    UNTAN</div>
            </div>

            <!-- Card 2: Status Online Secara Real-time -->
            <div
                class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div>
                    <div class="flex items-center gap-2">
                        <p
                            class="text-xs font-semibold uppercase tracking-wider text-neutral-400 dark:text-neutral-500">
                            Sedang Aktif</p>
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    </div>
                    <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100" x-text="onlineCount">
                    </h3>
                </div>
                <div class="mt-4 text-xs text-emerald-600 dark:text-emerald-400">
                    ● <span x-text="onlineCount"></span> akun operator sedang membuka sistem
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
                <div class="mt-4 text-xs text-neutral-400 dark:text-neutral-500">Batas wilayah spasial terverifikasi
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

                    <!-- Tombol Buka Modal -->
                    <button @click="openModal = true"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition-all shadow-sm">
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
                        <input type="text" x-model="searchAdmin" @input="currentPage = 1"
                            placeholder="Cari nama, email, atau wilayah..."
                            class="w-full rounded-lg border border-neutral-200 bg-white py-1.5 px-3 text-xs text-neutral-900 focus:border-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100" />
                    </div>

                    <!-- Dropdown Limit Data -->
                    <div class="flex items-center gap-2 text-xs text-neutral-500 dark:text-neutral-400">
                        <span>Tampilkan</span>
                        <select x-model.number="perPage" @change="currentPage = 1"
                            class="rounded-lg border border-neutral-200 bg-white px-2 py-1 text-xs font-semibold text-neutral-800 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            <option value="5">5 data</option>
                            <option value="10">10 data</option>
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
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            <template x-for="(admin, index) in paginatedAdmins" :key="admin.email">
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-neutral-900 dark:text-neutral-100"
                                            x-text="admin.nama"></div>
                                        <div class="text-[11px] text-neutral-400" x-text="admin.email"></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-semibold ring-1 ring-inset"
                                            :class="admin.role === 'Admin Aplikasi' ?
                                                'bg-purple-50 text-purple-700 ring-purple-700/10 dark:bg-purple-400/10 dark:text-purple-400' :
                                                'bg-blue-50 text-blue-700 ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400'"
                                            x-text="admin.role"></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <template x-if="admin.scopeLvl === 'Global'">
                                            <span
                                                class="text-xs font-semibold text-neutral-500 dark:text-neutral-400">Global
                                                (Semua Wilayah)</span>
                                        </template>
                                        <template x-if="admin.scopeLvl !== 'Global'">
                                            <div>
                                                <div class="text-xs font-bold text-neutral-800 dark:text-neutral-200"
                                                    x-text="admin.scopeLvl"></div>
                                                <div class="text-[11px] text-neutral-400" x-text="admin.instansi"></div>
                                            </div>
                                        </template>
                                    </td>
                                    <td class="px-6 py-4">
                                        <template x-if="admin.isOnline">
                                            <span
                                                class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                                <span
                                                    class="h-1.5 w-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                                Sedang Online
                                            </span>
                                        </template>
                                        <template x-if="!admin.isOnline">
                                            <span class="text-xs text-neutral-400" x-text="admin.lastActive"></span>
                                        </template>
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs font-bold">
                                        <button @click="deleteAdmin(admin)"
                                            class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- FOOTER TABLE: PAGINATION -->
                <div
                    class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-4 border-t border-neutral-100 dark:border-neutral-700/50">
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                        Menampilkan <span class="font-bold text-neutral-800 dark:text-neutral-200"
                            x-text="startData"></span>
                        sampai <span class="font-bold text-neutral-800 dark:text-neutral-200" x-text="endData"></span>
                        dari <span class="font-bold text-neutral-800 dark:text-neutral-200"
                            x-text="filteredAdmins.length"></span> admin
                    </p>

                    <!-- Navigasi Halaman -->
                    <div class="inline-flex gap-1.5">
                        <button @click="currentPage--" :disabled="currentPage === 1"
                            class="rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 disabled:opacity-40 disabled:cursor-not-allowed">Sebelumnya</button>
                        <button @click="currentPage++" :disabled="currentPage >= maxPage"
                            class="rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 disabled:opacity-40 disabled:cursor-not-allowed">Berikutnya</button>
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

                <div class="relative w-full mb-4">
                    <input type="text" x-model="searchLog" placeholder="Cari log aktivitas..."
                        class="w-full rounded-lg border border-neutral-200 bg-white py-1.5 px-3 text-xs text-neutral-900 focus:border-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100" />
                </div>

                <!-- Scrollbox Timeline Log -->
                <div class="flex-1 overflow-y-auto max-h-105 pr-2">
                    <ul class="relative border-s border-neutral-200 dark:border-neutral-700 space-y-6 ml-2">
                        <template x-for="log in filteredLogs" :key="log.id">
                            <li class="ms-4 transition-all duration-300">
                                <div
                                    class="absolute -inset-s-1.5 mt-1.5 h-3 w-3 rounded-full border border-white bg-indigo-500 dark:border-neutral-800">
                                </div>
                                <div class="flex justify-between items-center gap-2">
                                    <span class="text-[10px] text-neutral-400 dark:text-neutral-500"
                                        x-text="log.waktu"></span>
                                    <span
                                        class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[9px] font-bold ring-1 ring-inset"
                                        :class="log.katClass" x-text="log.kategori"></span>
                                </div>
                                <p class="mt-1 text-xs font-bold text-neutral-800 dark:text-neutral-200"
                                    x-text="log.user"></p>
                                <p class="text-[11px] text-neutral-500 dark:text-neutral-400" x-text="log.aksi"></p>
                            </li>
                        </template>
                    </ul>

                    <div class="mt-6 text-center" x-if="hasMoreLogs">
                        <button @click="loadMoreLogs" :disabled="!hasMoreLogs"
                            class="inline-flex items-center justify-center gap-1.5 w-full rounded-lg border border-neutral-200 bg-neutral-50 hover:bg-neutral-100 dark:border-neutral-700 dark:bg-neutral-800/50 px-4 py-2 text-xs font-bold text-neutral-600 dark:text-neutral-400 transition-all disabled:opacity-40">
                            <span x-text="hasMoreLogs ? 'Muat Aktivitas Lainnya' : 'Semua Log Telah Dimuat'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= MODAL TAMBAH ADMIN ================= -->
        <div x-show="openModal" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="openModal = false"
                class="w-full max-w-md transform rounded-2xl border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-700 dark:bg-neutral-800">
                <div
                    class="flex items-center justify-between border-b border-neutral-100 pb-3 dark:border-neutral-700">
                    <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100">Tambah Akun Admin Baru</h3>
                    <button @click="openModal = false"
                        class="rounded-lg p-1 text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700">✕</button>
                </div>

                <form @submit.prevent="saveAdmin" class="space-y-4 mt-4">
                    <div>
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400">Nama
                            Lengkap</label>
                        <input type="text" x-model="form.nama" required
                            class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100"
                            placeholder="Contoh: Dr. Rifi" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400">Email
                            Instansi</label>
                        <input type="email" x-model="form.email" required
                            class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100"
                            placeholder="contoh@untan.ac.id" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400">Peran
                            (Role)</label>
                        <select x-model="form.role"
                            class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100">
                            <option value="Operator">Operator</option>
                            <option value="Admin Aplikasi">Admin Aplikasi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400">Tingkat
                            Wilayah (Scope)</label>
                        <select x-model="form.scopeLvl"
                            class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100">
                            <option value="Fakultas">Fakultas</option>
                            <option value="Program Studi">Program Studi</option>
                            <option value="Global">Global (Semua Wilayah)</option>
                        </select>
                    </div>
                    <div x-show="form.scopeLvl !== 'Global'">
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400">Nama Instansi
                            / Prodi</label>
                        <input type="text" x-model="form.instansi" :required="form.scopeLvl !== 'Global'"
                            class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100"
                            placeholder="Misal: Teknik Elektro" />
                    </div>

                    <div class="flex justify-end gap-2 border-t border-neutral-100 pt-4 dark:border-neutral-700">
                        <button type="button" @click="openModal = false"
                            class="rounded-lg bg-neutral-100 px-4 py-2 text-xs font-bold text-neutral-600 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300">Batal</button>
                        <button type="submit"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-500">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= LOGIKA STATE ALPINE.JS ================= -->
    <script>
        function adminManager() {
            return {
                openModal: false,
                searchAdmin: '',
                searchLog: '',
                perPage: 10,
                currentPage: 1,

                form: {
                    nama: '',
                    email: '',
                    role: 'Operator',
                    scopeLvl: 'Fakultas',
                    instansi: ''
                },

                admins: [{
                        nama: 'Faiz Diennur Yahya',
                        email: 'faiz@it.untan.ac.id',
                        role: 'Admin Aplikasi',
                        scopeLvl: 'Global',
                        instansi: 'Global',
                        isOnline: true,
                        lastActive: ''
                    },
                    {
                        nama: 'Budi Santoso',
                        email: 'budi.bmn@untan.ac.id',
                        role: 'Operator',
                        scopeLvl: 'Fakultas',
                        instansi: 'Fakultas Teknik (ID: 1)',
                        isOnline: false,
                        lastActive: 'Aktif 5m yang lalu'
                    },
                    {
                        nama: 'Ani Wijaya',
                        email: 'ani.if@untan.ac.id',
                        role: 'Operator',
                        scopeLvl: 'Program Studi',
                        instansi: 'Informatika (ID: 12)',
                        isOnline: false,
                        lastActive: 'Aktif 2 jam yang lalu'
                    },
                    {
                        nama: 'Hendra Wijaya',
                        email: 'hendra.mipa@untan.ac.id',
                        role: 'Operator',
                        scopeLvl: 'Fakultas',
                        instansi: 'FMIPA (ID: 2)',
                        isOnline: false,
                        lastActive: 'Aktif 1 hari yang lalu'
                    },
                    {
                        nama: 'Siska Amelia',
                        email: 'siska.sk@untan.ac.id',
                        role: 'Operator',
                        scopeLvl: 'Program Studi',
                        instansi: 'Sist. Komputer (ID: 15)',
                        isOnline: false,
                        lastActive: 'Aktif 3 hari yang lalu'
                    },
                ],

                logs: [{
                        id: 1,
                        waktu: 'Hari ini - 10:15 WIB',
                        kategori: 'Spasial',
                        katClass: 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                        user: 'Budi Santoso',
                        aksi: 'Menggambar poligon Geofencing baru untuk wilayah Fakultas Teknik.'
                    },
                    {
                        id: 2,
                        waktu: 'Hari ini - 09:30 WIB',
                        kategori: 'Akses',
                        katClass: 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                        user: 'Faiz Diennur (Admin IT)',
                        aksi: 'Mengubah hak akses operasional akun Ani Wijaya menjadi Operator Prodi.'
                    },
                ],

                hasMoreLogs: true,

                get onlineCount() {
                    return this.admins.filter(a => a.isOnline).length;
                },

                get filteredAdmins() {
                    if (!this.searchAdmin.trim()) return this.admins;
                    const q = this.searchAdmin.toLowerCase();
                    return this.admins.filter(a =>
                        a.nama.toLowerCase().includes(q) ||
                        a.email.toLowerCase().includes(q) ||
                        a.instansi.toLowerCase().includes(q)
                    );
                },

                get paginatedAdmins() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredAdmins.slice(start, start + this.perPage);
                },

                get maxPage() {
                    return Math.ceil(this.filteredAdmins.length / this.perPage) || 1;
                },

                get startData() {
                    return this.filteredAdmins.length === 0 ? 0 : (this.currentPage - 1) * this.perPage + 1;
                },

                get endData() {
                    return Math.min(this.currentPage * this.perPage, this.filteredAdmins.length);
                },

                get filteredLogs() {
                    if (!this.searchLog.trim()) return this.logs;
                    const q = this.searchLog.toLowerCase();
                    return this.logs.filter(l =>
                        l.user.toLowerCase().includes(q) ||
                        l.aksi.toLowerCase().includes(q)
                    );
                },

                saveAdmin() {
                    this.admins.unshift({
                        nama: this.form.nama,
                        email: this.form.email,
                        role: this.form.role,
                        scopeLvl: this.form.scopeLvl,
                        instansi: this.form.scopeLvl === 'Global' ? 'Global (Semua Wilayah)' :
                            `${this.form.instansi} (Baru)`,
                        isOnline: false,
                        lastActive: 'Baru Dibuat'
                    });

                    this.addLog('Admin Aplikasi', `Mendaftarkan akun ${this.form.nama} sebagai ${this.form.role}.`);

                    this.form = {
                        nama: '',
                        email: '',
                        role: 'Operator',
                        scopeLvl: 'Fakultas',
                        instansi: ''
                    };
                    this.openModal = false;
                    this.currentPage = 1;
                },

                deleteAdmin(admin) {
                    if (confirm(`Apakah Anda yakin ingin menghapus akun ${admin.nama}?`)) {
                        this.admins = this.admins.filter(a => a.email !== admin.email);
                        this.addLog('Sistem', `Menghapus akun admin ${admin.nama}.`);
                    }
                },

                addLog(user, aksi) {
                    this.logs.unshift({
                        id: Date.now(),
                        waktu: 'Baru Saja',
                        kategori: 'Akses',
                        katClass: 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                        user: user,
                        aksi: aksi
                    });
                },

                loadMoreLogs() {
                    this.logs.push({
                        id: 10,
                        waktu: 'Kemarin - 14:20 WIB',
                        kategori: 'Spasial',
                        katClass: 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                        user: 'Hendra Wijaya',
                        aksi: 'Memperbarui batas poligon FMIPA.'
                    }, {
                        id: 11,
                        waktu: '05 Sep - 09:15 WIB',
                        kategori: 'Aset',
                        katClass: 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                        user: 'Budi Santoso',
                        aksi: 'Menolak laporan palsu kerusakan AC.'
                    });
                    this.hasMoreLogs = false;
                }
            }
        }
    </script>
</x-layouts::app>
