<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role user: pelapor | operator | admin_it
            $table->string('role')->default('pelapor')->after('email');

            // Level wilayah kerja operator: fakultas | prodi | null
            $table->string('scope_level')->nullable()->after('role');

            // ID dari fakultas atau prodi yang dikelola
            $table->unsignedBigInteger('scope_id')->nullable()->after('scope_level');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'scope_level', 'scope_id']);
        });
    }
};
