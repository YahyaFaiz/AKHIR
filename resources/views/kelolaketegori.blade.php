<x-layouts::app :title="__('Kelola Kategori & Subkategori')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl" x-data="categoryManager()">

        <!-- ================= 1. HEADER & BARIS AKSI UTAMA ================= -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-neutral-900 dark:text-neutral-100">MANAJEMEN SISTEM / KATEGORI &
                    SUBKATEGORI</h1>
                <p class="text-xs text-neutral-500 dark:text-neutral-400">Kelola referensi jenis kerusakan fasilitas
                    kampus dan aturan kelompok group secara dinamis.</p>
            </div>

            <!-- Tombol Aksi Utama -->
            <div class="flex items-center gap-2">
                <!-- Tombol Kelola Group (Tambah / Hapus Group) -->
                <button @click="showGroupModal = true"
                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs font-bold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm transition-all">
                    ⚙️ Kelola Group (<span x-text="groups.length"></span>)
                </button>

                <!-- Tombol Tambah Kategori Utama -->
                <button @click="openAddModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-500 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    + Tambah Kategori
                </button>
            </div>
        </div>

        <!-- ================= 2. TABEL UTAMA & PANEL FILTER ================= -->
        <div
            class="flex flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 flex-1">

            <!-- PANEL FILTER: TAB GROUP DINAMIS & PENCARIAN -->
            <div
                class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-neutral-100 dark:border-neutral-700/60">

                <!-- Tab Filter Group (Otomatis Menyesuaikan Daftar Group) -->
                <div class="flex flex-wrap items-center gap-1.5 bg-neutral-100 p-1 rounded-xl dark:bg-neutral-900/60">
                    <button @click="activeGroup = 'Semua'; currentPage = 1"
                        :class="activeGroup === 'Semua' ?
                            'bg-white text-neutral-900 shadow-sm dark:bg-neutral-800 dark:text-neutral-100' :
                            'text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'"
                        class="rounded-lg px-3 py-1.5 text-xs font-bold transition-all">
                        Semua Group
                    </button>

                    <!-- Loop Tab Group Dinamis -->
                    <template x-for="grp in groups" :key="grp">
                        <button @click="activeGroup = grp; currentPage = 1"
                            :class="activeGroup === grp ?
                                'bg-white text-neutral-900 shadow-sm dark:bg-neutral-800 dark:text-neutral-100' :
                                'text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'"
                            class="rounded-lg px-3 py-1.5 text-xs font-bold transition-all">
                            <span x-text="grp"></span> (<span x-text="countGroup(grp)"></span>)
                        </button>
                    </template>
                </div>

                <!-- Input Pencarian -->
                <div class="relative w-full sm:w-72">
                    <input type="text" x-model="searchQuery" @input="currentPage = 1"
                        placeholder="🔍 Cari Kategori / Sub..."
                        class="w-full rounded-lg border border-neutral-200 bg-white py-1.5 px-3 text-xs text-neutral-900 focus:border-indigo-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" />
                </div>
            </div>

            <!-- TABEL KATEGORI & SUBKATEGORI -->
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-400">
                    <thead
                        class="bg-neutral-50 text-xs uppercase text-neutral-700 dark:bg-neutral-700/50 dark:text-neutral-300">
                        <tr>
                            <th scope="col" class="px-5 py-3.5 font-bold">KATEGORI UTAMA</th>
                            <th scope="col" class="px-5 py-3.5 font-bold">KELOMPOK GROUP</th>
                            <th scope="col" class="px-5 py-3.5 font-bold">DAFTAR SUBKATEGORI TERDAFTAR</th>
                            <th scope="col" class="px-5 py-3.5 font-bold text-center">DESKRIPSI MANUAL</th>
                            <th scope="col" class="px-5 py-3.5 text-right font-bold">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        <template x-for="cat in paginatedCategories" :key="cat.id">
                            <tr class="hover:bg-neutral-50/80 dark:hover:bg-neutral-700/30 transition-colors align-top">

                                <!-- Kategori Utama -->
                                <td class="px-5 py-4 min-w-[200px]">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg" x-text="cat.icon"></span>
                                        <span class="font-bold text-neutral-900 dark:text-neutral-100 text-sm"
                                            x-text="cat.nama"></span>
                                    </div>
                                    <p class="mt-1 text-[11px] text-neutral-400 leading-relaxed" x-text="cat.hint"></p>
                                </td>

                                <!-- Kelompok Group -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold ring-1 ring-inset bg-indigo-50 text-indigo-700 ring-indigo-600/20 dark:bg-indigo-400/10 dark:text-indigo-400"
                                        x-text="'[' + cat.group + ']'"></span>
                                </td>

                                <!-- Daftar Subkategori -->
                                <td class="px-5 py-4">
                                    <ul class="space-y-1.5 text-xs text-neutral-800 dark:text-neutral-200">
                                        <template x-for="(sub, idx) in cat.subkategori" :key="idx">
                                            <li class="flex items-center gap-1.5">
                                                <span class="text-neutral-400">•</span>
                                                <span x-text="sub.nama"></span>
                                                <template x-if="sub.allowManual">
                                                    <span
                                                        class="text-[10px] italic text-emerald-600 dark:text-emerald-400 font-medium">(Input
                                                        Manual)</span>
                                                </template>
                                            </li>
                                        </template>
                                    </ul>
                                </td>

                                <!-- Status Deskripsi Manual -->
                                <td class="px-5 py-4 text-center">
                                    <ul class="space-y-1.5 text-xs">
                                        <template x-for="(sub, idx) in cat.subkategori" :key="idx">
                                            <li class="h-4 flex items-center justify-center">
                                                <template x-if="sub.allowManual">
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded">
                                                        ✓ Aktif
                                                    </span>
                                                </template>
                                                <template x-if="!sub.allowManual">
                                                    <span class="text-[11px] text-neutral-400">Nonaktif</span>
                                                </template>
                                            </li>
                                        </template>
                                    </ul>
                                </td>

                                <!-- Aksi -->
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex flex-col items-end gap-1.5">
                                        <button @click="openQuickAddSubModal(cat)"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 hover:underline">
                                            [ + Subkategori ]
                                        </button>
                                        <button @click="openEditModal(cat)"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 hover:underline">
                                            [ ✏️ Edit ]
                                        </button>
                                        <button @click="deleteCategory(cat)"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-red-600 hover:text-red-800 dark:text-red-400 hover:underline">
                                            [ 🗑️ Hapus ]
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
                        x-text="categories.length"></span> Kategori,
                    <span class="font-bold text-neutral-800 dark:text-neutral-200" x-text="groups.length"></span>
                    Kelompok Group ]
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

        <!-- ================= 3. MODAL POP-UP: TAMBAH / EDIT KATEGORI ================= -->
        <div x-show="showModal" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            style="display: none;">
            <div @click.away="closeModal()"
                class="w-full max-w-2xl max-h-[90vh] overflow-y-auto transform rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">

                <div
                    class="flex items-center justify-between border-b border-neutral-100 pb-3 dark:border-neutral-700">
                    <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100"
                        x-text="isEditMode ? 'EDIT KATEGORI & SUBKATEGORI' : 'TAMBAH KATEGORI BARU'"></h3>
                    <button @click="closeModal()"
                        class="rounded-lg p-1.5 text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700">✕</button>
                </div>

                <form @submit.prevent="saveCategory" class="space-y-4 mt-4">

                    <div class="grid grid-cols-4 gap-3">
                        <div class="col-span-1">
                            <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Icon
                                Emoji:</label>
                            <input type="text" x-model="form.icon" required placeholder="💡"
                                class="w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-center text-sm font-bold text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" />
                        </div>
                        <div class="col-span-3">
                            <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Nama
                                Kategori Utama:</label>
                            <input type="text" x-model="form.nama" required
                                placeholder="Contoh: Proyektor & Media Pembelajaran"
                                class="w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs font-bold text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" />
                        </div>
                    </div>

                    <!-- PILIHAN DROPDOWN GROUP DINAMIS -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400">Kelompok
                                Group Fasilitas:</label>
                            {{-- <button type="button" @click="showGroupModal = true"
                                class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                ⚙️ Kelola / + Tambah Group
                            </button> --}}
                        </div>
                        <select x-model="form.group" required
                            class="w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs font-bold text-neutral-800 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                            <template x-for="grp in groups" :key="grp">
                                <option :value="grp" x-text="grp"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Petunjuk
                            Singkat (Hint di Form Pelapor):</label>
                        <input type="text" x-model="form.hint"
                            placeholder="Contoh: Masalah LCD proyektor, kabel HDMI/VGA..."
                            class="w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" />
                    </div>

                    <!-- DYNAMIC ROW SUBKATEGORI -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400">Daftar
                                Subkategori Kerusakan:</label>
                            <button type="button" @click="addSubRow()"
                                class="text-[11px] font-bold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                                + Tambah Baris Subkategori
                            </button>
                        </div>

                        <div class="space-y-2 max-h-52 overflow-y-auto pr-1">
                            <template x-for="(sub, index) in form.subkategori" :key="index">
                                <div
                                    class="flex items-center gap-2 rounded-lg border border-neutral-200 bg-neutral-50 p-2 dark:border-neutral-700 dark:bg-neutral-900/60">
                                    <span class="text-xs font-mono font-bold text-neutral-400 w-5"
                                        x-text="(index + 1) + '.'"></span>
                                    <input type="text" x-model="sub.nama" required
                                        placeholder="Nama Subkategori..."
                                        class="flex-1 rounded-md border border-neutral-200 bg-white px-2.5 py-1.5 text-xs text-neutral-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100" />

                                    <label class="flex items-center gap-1.5 px-2 cursor-pointer whitespace-nowrap">
                                        <input type="checkbox" x-model="sub.allowManual"
                                            class="rounded text-indigo-600 focus:ring-indigo-500" />
                                        <span
                                            class="text-[11px] font-semibold text-neutral-600 dark:text-neutral-300">Input
                                            Manual</span>
                                    </label>

                                    <button type="button" @click="removeSubRow(index)"
                                        class="p-1 text-red-500 hover:text-red-700 dark:hover:text-red-400">🗑️</button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-end gap-2 border-t border-neutral-100 pt-4 dark:border-neutral-700">
                        <button type="button" @click="closeModal()"
                            class="rounded-lg bg-neutral-100 px-4 py-2 text-xs font-bold text-neutral-600 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300">BATAL</button>
                        <button type="submit"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-500 shadow-sm">💾
                            SIMPAN KATEGORI</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= 4. MODAL POP-UP KHUSUS: KELOLA GROUP (TAMBAH & HAPUS GROUP) ================= -->
        <div x-show="showGroupModal" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            style="display: none;">
            <div @click.away="showGroupModal = false"
                class="w-full max-w-md transform rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">

                <div
                    class="flex items-center justify-between border-b border-neutral-100 pb-3 dark:border-neutral-700">
                    <div>
                        <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100">KELOLA KELOMPOK GROUP
                            FASILITAS</h3>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Tambah kelompok baru atau hapus
                            kelompok yang ada.</p>
                    </div>
                    <button @click="showGroupModal = false"
                        class="rounded-lg p-1.5 text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700">✕</button>
                </div>

                <!-- FORM TAMBAH GROUP BARU -->
                <form @submit.prevent="addNewGroup()" class="flex gap-2 mt-4">
                    <input type="text" x-model="newGroupName" required placeholder="Masukkan Nama Group Baru..."
                        class="flex-1 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" />
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-3.5 py-2 text-xs font-bold text-white hover:bg-indigo-500 shadow-sm whitespace-nowrap">
                        + Tambah Group
                    </button>
                </form>

                <!-- DAFTAR GROUP SAAT INI (BISA DIHAPUS) -->
                <div class="mt-4">
                    <span class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-2">Daftar Group
                        Terdaftar:</span>

                    <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                        <template x-for="(grp, idx) in groups" :key="idx">
                            <div
                                class="flex items-center justify-between p-2.5 rounded-xl border border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/50 text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-neutral-800 dark:text-neutral-200"
                                        x-text="grp"></span>
                                    <span class="text-[10px] text-neutral-400"
                                        x-text="'(' + countGroup(grp) + ' Kategori)'"></span>
                                </div>
                                <button type="button" @click="deleteGroup(grp)"
                                    class="inline-flex items-center gap-1 rounded px-2 py-1 text-[11px] font-bold text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40 transition-colors">
                                    🗑️ Hapus
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="mt-6 pt-3 border-t border-neutral-100 dark:border-neutral-700 flex justify-end">
                    <button type="button" @click="showGroupModal = false"
                        class="rounded-lg bg-neutral-100 px-4 py-2 text-xs font-bold text-neutral-600 hover:bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-300">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= LOGIKA ALPINE.JS ================= -->
    <script>
        function categoryManager() {
            return {
                activeGroup: 'Semua',
                searchQuery: '',
                perPage: 5,
                currentPage: 1,

                showModal: false,
                showGroupModal: false,
                isEditMode: false,
                selectedCatId: null,

                newGroupName: '',

                // Array Kelompok Group Dinamis (Dapat ditambah & dihapus)
                groups: [
                    'Fasilitas Kelas',
                    'Fasilitas Gedung'
                ],

                form: {
                    icon: '💡',
                    nama: '',
                    group: '',
                    hint: '',
                    subkategori: []
                },

                categories: [{
                        id: 1,
                        icon: '💡',
                        nama: 'Lampu Kelas',
                        hint: 'Pencahayaan kelas',
                        group: 'Fasilitas Kelas',
                        subkategori: [{
                                nama: 'Mati total',
                                allowManual: false
                            },
                            {
                                nama: 'Redup / Berkedip',
                                allowManual: false
                            },
                            {
                                nama: 'Lainnya (Input Manual)',
                                allowManual: true
                            }
                        ]
                    },
                    {
                        id: 2,
                        icon: '🚽',
                        nama: 'Toilet & Sanitasi',
                        hint: 'Kebersihan & air',
                        group: 'Fasilitas Gedung',
                        subkategori: [{
                                nama: 'Kran air bocor / mati',
                                allowManual: false
                            },
                            {
                                nama: 'Lainnya (Input Manual)',
                                allowManual: true
                            }
                        ]
                    }
                ],

                countGroup(grp) {
                    return this.categories.filter(c => c.group === grp).length;
                },

                get filteredCategories() {
                    return this.categories.filter(c => {
                        const matchGrp = this.activeGroup === 'Semua' || c.group === this.activeGroup;
                        const q = this.searchQuery.toLowerCase().trim();
                        const matchQuery = !q || c.nama.toLowerCase().includes(q) ||
                            c.hint.toLowerCase().includes(q) ||
                            c.subkategori.some(s => s.nama.toLowerCase().includes(q));
                        return matchGrp && matchQuery;
                    });
                },

                get paginatedCategories() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredCategories.slice(start, start + this.perPage);
                },

                get maxPage() {
                    return Math.ceil(this.filteredCategories.length / this.perPage) || 1;
                },

                // --- FUNGSI TAMBAH & HAPUS GROUP ---
                addNewGroup() {
                    const name = this.newGroupName.trim();
                    if (!name) return;

                    if (this.groups.includes(name)) {
                        alert(`Group "${name}" sudah ada!`);
                        return;
                    }

                    this.groups.push(name);
                    this.newGroupName = '';
                    alert(`Group "${name}" berhasil ditambahkan!`);
                },

                deleteGroup(grp) {
                    if (confirm(`Apakah Anda yakin ingin menghapus kelompok group "${grp}"?`)) {
                        this.groups = this.groups.filter(g => g !== grp);

                        // Jika tab filter sedang memilih group yang dihapus, kembalikan ke 'Semua'
                        if (this.activeGroup === grp) {
                            this.activeGroup = 'Semua';
                        }

                        // Jika form sedang memakai group yang dihapus, reset pilihan
                        if (this.form.group === grp) {
                            this.form.group = this.groups[0] || '';
                        }
                    }
                },

                // --- FUNGSI KATEGORI & SUBKATEGORI ---
                openAddModal() {
                    this.isEditMode = false;
                    this.selectedCatId = null;
                    this.form = {
                        icon: '💡',
                        nama: '',
                        group: this.groups[0] || 'Fasilitas Kelas',
                        hint: '',
                        subkategori: [{
                                nama: '',
                                allowManual: false
                            },
                            {
                                nama: 'Lainnya (Sebutkan)',
                                allowManual: true
                            }
                        ]
                    };
                    this.showModal = true;
                },

                openEditModal(cat) {
                    this.isEditMode = true;
                    this.selectedCatId = cat.id;
                    this.form = {
                        icon: cat.icon,
                        nama: cat.nama,
                        group: cat.group,
                        hint: cat.hint,
                        subkategori: JSON.parse(JSON.stringify(cat.subkategori))
                    };
                    this.showModal = true;
                },

                openQuickAddSubModal(cat) {
                    this.openEditModal(cat);
                    this.addSubRow();
                },

                closeModal() {
                    this.showModal = false;
                },

                addSubRow() {
                    this.form.subkategori.push({
                        nama: '',
                        allowManual: false
                    });
                },

                removeSubRow(idx) {
                    this.form.subkategori.splice(idx, 1);
                },

                saveCategory() {
                    if (!this.form.nama.trim()) return;

                    if (this.isEditMode) {
                        const target = this.categories.find(c => c.id === this.selectedCatId);
                        if (target) {
                            target.icon = this.form.icon;
                            target.nama = this.form.nama;
                            target.group = this.form.group;
                            target.hint = this.form.hint;
                            target.subkategori = JSON.parse(JSON.stringify(this.form.subkategori));
                        }
                    } else {
                        this.categories.unshift({
                            id: Date.now(),
                            icon: this.form.icon,
                            nama: this.form.nama,
                            group: this.form.group,
                            hint: this.form.hint,
                            subkategori: JSON.parse(JSON.stringify(this.form.subkategori))
                        });
                    }

                    this.closeModal();
                },

                deleteCategory(cat) {
                    if (confirm(`Apakah Anda yakin ingin menghapus kategori "${cat.nama}"?`)) {
                        this.categories = this.categories.filter(c => c.id !== cat.id);
                    }
                }
            }
        }
    </script>
</x-layouts::app>
