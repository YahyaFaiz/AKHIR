<?php

use App\Http\Controllers\Admin\UserManagerController;
use App\Http\Controllers\Operator\LaporanController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/api/categories', function () {
    return response()->json([
        [
            'id' => 1,
            'group' => 'kelas',
            'name' => 'Lampu Kelas',
            'hint' => 'Masalah pencahayaan di ruang kelas',
            'allow_manual' => false,
            'subcategories' => [
                ['id' => 101, 'name' => 'Mati total', 'allow_manual' => false],
                ['id' => 102, 'name' => 'Redup', 'allow_manual' => false],
                ['id' => 103, 'name' => 'Lainnya', 'allow_manual' => true],
            ],
        ],
        [
            'id' => 2,
            'group' => 'luar',
            'name' => 'Jalan dan Trotoar',
            'hint' => 'Kerusakan infrastruktur luar ruangan',
            'allow_manual' => false,
            'subcategories' => [
                ['id' => 201, 'name' => 'Retak', 'allow_manual' => false],
                ['id' => 202, 'name' => 'Bocor', 'allow_manual' => false],
                ['id' => 203, 'name' => 'Lainnya', 'allow_manual' => true],
            ],
        ],
        [
            'id' => 3,
            'group' => 'kelas',
            'name' => 'AC',
            'hint' => 'Masalah pendingin ruangan',
            'allow_manual' => false,
            'subcategories' => [
                ['id' => 301, 'name' => 'Tidak dingin', 'allow_manual' => false],
                ['id' => 302, 'name' => 'Bising', 'allow_manual' => false],
                ['id' => 303, 'name' => 'Lainnya', 'allow_manual' => true],
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
