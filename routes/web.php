<?php

use App\Http\Controllers\Admin\UserManagerController;
use App\Http\Controllers\Operator\LaporanController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/api/categories', function () {
    return response()->json([
        // ================= KELOMPOK: KELAS (Fasilitas Dalam Ruangan/Lab) =================
        [
            'id' => 1,
            'group' => 'kelas',
            'name' => 'Lampu Kelas / Ruangan',
            'hint' => 'Masalah pencahayaan di dalam ruang kelas atau laboratorium',
            'allow_manual' => false,
            'subcategories' => [
                ['id' => 101, 'name' => 'Mati total', 'allow_manual' => false],
                ['id' => 102, 'name' => 'Redup / Berkedip', 'allow_manual' => false],
                ['id' => 103, 'name' => 'Sakelar / Fiting rusak', 'allow_manual' => false],
                ['id' => 104, 'name' => 'Lainnya', 'allow_manual' => true],
            ],
        ],
        [
            'id' => 2,
            'group' => 'kelas',
            'name' => 'AC / Pendingin Ruangan',
            'hint' => 'Masalah pendingin udara di dalam ruangan',
            'allow_manual' => false,
            'subcategories' => [
                ['id' => 201, 'name' => 'Tidak dingin / Hanya angin', 'allow_manual' => false],
                ['id' => 202, 'name' => 'Bocor / Menetes air', 'allow_manual' => false],
                ['id' => 203, 'name' => 'Bising / Suara kasar', 'allow_manual' => false],
                ['id' => 204, 'name' => 'Lainnya', 'allow_manual' => true],
            ],
        ],
        [
            'id' => 3,
            'group' => 'kelas',
            'name' => 'Proyektor & Media Pembelajaran',
            'hint' => 'Kerusakan LCD Proyektor, Layar, atau Papan Tulis',
            'allow_manual' => false,
            'subcategories' => [
                ['id' => 301, 'name' => 'Proyektor mati / Buram', 'allow_manual' => false],
                ['id' => 302, 'name' => 'Kabel HDMI / VGA rusak', 'allow_manual' => false],
                ['id' => 303, 'name' => 'Layar proyektor macet', 'allow_manual' => false],
                ['id' => 304, 'name' => 'Lainnya', 'allow_manual' => true],
            ],
        ],
        [
            'id' => 4,
            'group' => 'kelas',
            'name' => 'Meja & Kursi Kuliah',
            'hint' => 'Kerusakan furnitur / mebel di dalam kelas',
            'allow_manual' => false,
            'subcategories' => [
                ['id' => 401, 'name' => 'Kursi patah / Goyang', 'allow_manual' => false],
                ['id' => 402, 'name' => 'Meja rusak / Lepas', 'allow_manual' => false],
                ['id' => 403, 'name' => 'Lainnya', 'allow_manual' => true],
            ],
        ],
        [
            'id' => 5,
            'group' => 'kelas',
            'name' => 'Stopkontak & Stop Kontak Ruangan',
            'hint' => 'Colokan listrik dan kelistrikan di area meja/dinding kelas',
            'allow_manual' => false,
            'subcategories' => [
                ['id' => 501, 'name' => 'Stopkontak tidak ada arus listrik', 'allow_manual' => false],
                ['id' => 502, 'name' => 'Stopkontak kendor / Longgar', 'allow_manual' => false],
                ['id' => 503, 'name' => 'Mengeluarkan percikan api', 'allow_manual' => false],
                ['id' => 504, 'name' => 'Lainnya', 'allow_manual' => true],
            ],
        ],

        // ================= KELOMPOK: LUAR (Fasilitas Gedung & Luar Ruangan) =================
        [
            'id' => 6,
            'group' => 'luar',
            'name' => 'Toilet & Sanitasi Gedung',
            'hint' => 'Kerusakan fasilitas kebersihan dan air di area gedung',
            'allow_manual' => false,
            'subcategories' => [
                ['id' => 601, 'name' => 'Kran air bocor / Mati', 'allow_manual' => false],
                ['id' => 602, 'name' => 'Pintu / Kunci toilet rusak', 'allow_manual' => false],
                ['id' => 603, 'name' => 'Toilet / Flush tersumbat', 'allow_manual' => false],
                ['id' => 604, 'name' => 'Lainnya', 'allow_manual' => true],
            ],
        ],
        [
            'id' => 7,
            'group' => 'luar',
            'name' => 'Lampu Koridor & Area Luar',
            'hint' => 'Pencahayaan di lorong gedung, tangga, atau sekitar luar ruangan',
            'allow_manual' => false,
            'subcategories' => [
                ['id' => 701, 'name' => 'Lampu lorong mati', 'allow_manual' => false],
                ['id' => 702, 'name' => 'Lampu tangga / Taman mati', 'allow_manual' => false],
                ['id' => 703, 'name' => 'Lainnya', 'allow_manual' => true],
            ],
        ],
        [
            'id' => 8,
            'group' => 'luar',
            'name' => 'Pintu & Jendela Gedung',
            'hint' => 'Fasilitas fisik pintu utama gedung, lorong, dan jendela',
            'allow_manual' => false,
            'subcategories' => [
                ['id' => 801, 'name' => 'Gagang / Kunci pintu rusak', 'allow_manual' => false],
                ['id' => 802, 'name' => 'Engsel pintu / Jendela lepas', 'allow_manual' => false],
                ['id' => 803, 'name' => 'Kaca retak / Pecah', 'allow_manual' => false],
                ['id' => 804, 'name' => 'Lainnya', 'allow_manual' => true],
            ],
        ],
        [
            'id' => 9,
            'group' => 'luar',
            'name' => 'Area Parkir & Kanopi',
            'hint' => 'Fasilitas pendukung di luar gedung dan area parkir kendaraan',
            'allow_manual' => false,
            'subcategories' => [
                ['id' => 901, 'name' => 'Atap kanopi bocor / Rusak', 'allow_manual' => false],
                ['id' => 902, 'name' => 'Pembatas / Palang parkir rusak', 'allow_manual' => false],
                ['id' => 903, 'name' => 'Lainnya', 'allow_manual' => true],
            ],
        ],
    ]);
});

// ── DASHBOARD (hanya admin_it & operator) ────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin_it,operator'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

// ── halaman kelola admin ─────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin_it,operator'])->group(function () {
    Route::view('kelolaadmin', 'kelolaadmin')->name('kelolaadmin');
});

// ── halaman kelola admin ─────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin_it,operator'])->group(function () {
    Route::view('pemetaanwilayah', 'pemetaanwilayah')->name('pemetaanwilayah');
});

// halaman laporan masuk admin
Route::middleware(['auth', 'verified', 'role:admin_it,operator'])->group(function () {
    Route::view('laporanmasuk', 'laporanmasuk')->name('laporanmasuk');
});

Route::middleware(['auth', 'verified', 'role:admin_it,operator'])->group(function () {
    Route::view('kelolaketegori', 'kelolaketegori')->name('kelolaketegori');
});

Route::middleware(['auth', 'verified', 'role:admin_it,operator'])->group(function () {
    Route::view('keloladatagedung', 'keloladatagedung')->name('keloladatagedung');
});

// ── PELAPOR — halaman buat laporan ───────────────────────────────────────────
$pages = [
    'lapor'    => 'lapor.pages.buatLaporan',
    'validasi' => 'lapor.pages.validasi',
];

foreach ($pages as $uri => $view) {
    Route::view($uri, $view)
    // ->middleware(['auth', 'verified'])
        ->name($uri);
}

// ── OPERATOR & ADMIN IT — kelola laporan ─────────────────────────────────────
Route::middleware(['auth', 'role:operator,admin_it'])
    ->prefix('operator')
    ->name('operator.')
    ->group(function () {
        Route::get('/laporan',          [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/{id}',     [LaporanController::class, 'show'])->name('laporan.show');
        Route::patch('/laporan/{id}',   [LaporanController::class, 'update'])->name('laporan.update');
    });

// ── ADMIN IT SAJA — kelola pengguna ──────────────────────────────────────────
Route::middleware(['auth', 'role:admin_it'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserManagerController::class);
    });

require __DIR__.'/settings.php';
