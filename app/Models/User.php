<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int    $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $scope_level
 * @property int|null    $scope_id
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password', 'role', 'scope_level', 'scope_id'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ──────────────────────────────────────────────
    // HELPER METHODS — cukup pakai ini di mana saja
    // ──────────────────────────────────────────────

    /** Apakah user adalah Super Admin / IT? */
    public function isAdminIT(): bool
    {
        return $this->role === 'admin_it';
    }

    /** Apakah user adalah Operator (petugas)? */
    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    /** Apakah user adalah Pelapor biasa? */
    public function isPelapor(): bool
    {
        return $this->role === 'pelapor';
    }

    /**
     * Apakah user ini berwenang atas scope tertentu?
     *
     * @param  int    $scopeId    ID fakultas/prodi dari laporan
     * @param  string $scopeLevel 'fakultas' atau 'prodi'
     */
    public function canManageScope(int $scopeId, string $scopeLevel): bool
    {
        // Admin IT bisa semua
        if ($this->isAdminIT()) {
            return true;
        }

        // Operator hanya boleh kelola scope-nya sendiri
        return $this->isOperator()
            && $this->scope_level === $scopeLevel
            && (int) $this->scope_id === $scopeId;
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1) . Str::substr($initials, -1)
            : $initials;
    }
}
