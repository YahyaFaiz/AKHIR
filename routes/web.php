<?php

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

$pages = [
    'lapor' => 'lapor.pages.buatLaporan',
    'validasi' => 'lapor.pages.validasi',
];

foreach ($pages as $uri => $view) {
    Route::view($uri, $view)
    // ->middleware(['auth', 'verified'])
        ->name($uri);
}

require __DIR__.'/settings.php';
