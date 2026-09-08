<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Super Admin IT ────────────────────────────────────
        User::create([
            'name'        => 'Admin IT',
            'email'       => 'admin@kampus.ac.id',
            'password'    => Hash::make('password123'),
            'role'        => 'admin_it',
            'scope_level' => null,
            'scope_id'    => null,
        ]);

        // ── Operator Fakultas Teknik (scope_id = 1) ───────────
        User::create([
            'name'        => 'Operator Teknik',
            'email'       => 'op.teknik@kampus.ac.id',
            'password'    => Hash::make('password123'),
            'role'        => 'operator',
            'scope_level' => 'fakultas',
            'scope_id'    => 1,
        ]);

        // ── Operator Prodi Informatika (scope_id = 3) ─────────
        User::create([
            'name'        => 'Operator Informatika',
            'email'       => 'op.informatika@kampus.ac.id',
            'password'    => Hash::make('password123'),
            'role'        => 'operator',
            'scope_level' => 'prodi',
            'scope_id'    => 3,
        ]);

        // ── Pelapor contoh ─────────────────────────────────────
        User::create([
            'name'        => 'Mahasiswa Contoh',
            'email'       => 'mahasiswa@kampus.ac.id',
            'password'    => Hash::make('password123'),
            'role'        => 'pelapor',
            'scope_level' => null,
            'scope_id'    => null,
        ]);
    }
}
