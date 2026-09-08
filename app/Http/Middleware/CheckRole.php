<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Pemakaian: Route::middleware('role:admin_it,operator')
     *
     * Jika user tidak memiliki role yang sesuai:
     * - Pelapor  → redirect ke halaman /lapor
     * - Guest    → redirect ke halaman login
     * - Role lain yang tidak dikenali → abort 403
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Belum login → arahkan ke login
        if (!$user) {
            return redirect()->route('login');
        }

        // Role sudah sesuai → lanjut
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Pelapor tidak boleh masuk dashboard / area admin
        if ($user->role === 'pelapor') {
            return redirect()->route('lapor')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        // Role lain yang tidak dikenali
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}

