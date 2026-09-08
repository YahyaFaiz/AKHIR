<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Tampilkan daftar laporan sesuai scope operator yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        // Nanti ganti dengan model Laporan yang sebenarnya
        // Contoh saja — sesuaikan dengan model dan kolom di DB kamu
        // $query = \App\Models\Laporan::latest();

        if ($user->isAdminIT()) {
            // Admin IT → lihat SEMUA laporan
            // $laporan = $query->paginate(15);
            $laporan = collect(); // placeholder
        } elseif ($user->isOperator()) {
            // Operator → filter hanya scope-nya saja
            // $laporan = $query
            //     ->where('scope_level', $user->scope_level)
            //     ->where('scope_id',    $user->scope_id)
            //     ->paginate(15);
            $laporan = collect(); // placeholder
        } else {
            abort(403);
        }

        return view('operator.laporan.index', compact('laporan', 'user'));
    }

    /**
     * Update status laporan — cek scope sebelum update.
     */
    public function update(Request $request, int $id)
    {
        $user = Auth::user();

        // Nanti uncomment setelah model Laporan dibuat:
        // $laporan = \App\Models\Laporan::findOrFail($id);
        //
        // if (!$user->canManageScope($laporan->scope_id, $laporan->scope_level)) {
        //     abort(403, 'Anda hanya bisa mengelola laporan di wilayah Anda.');
        // }
        //
        // $request->validate([
        //     'status'  => ['required', 'in:pending,valid,proses,selesai,ditolak'],
        //     'catatan' => ['nullable', 'string', 'max:500'],
        // ]);
        //
        // $laporan->update([
        //     'status'        => $request->status,
        //     'catatan'       => $request->catatan,
        //     'diproses_oleh' => $user->id,
        // ]);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Tampilkan detail satu laporan.
     */
    public function show(int $id)
    {
        $user = Auth::user();

        // Nanti uncomment setelah model Laporan dibuat:
        // $laporan = \App\Models\Laporan::findOrFail($id);
        //
        // if (!$user->canManageScope($laporan->scope_id, $laporan->scope_level)) {
        //     abort(403);
        // }
        //
        // return view('operator.laporan.show', compact('laporan'));

        return view('operator.laporan.show', ['user' => $user, 'id' => $id]);
    }
}
