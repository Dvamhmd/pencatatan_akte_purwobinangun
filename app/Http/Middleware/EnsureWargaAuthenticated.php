<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureWargaAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isWarga()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan masuk ke akun warga Anda terlebih dahulu untuk mengakses layanan ini.',
                    'redirect' => route('warga.login'),
                ], 401);
            }

            return redirect()->guest(route('warga.login'))
                ->with('info', 'Silakan masuk menggunakan NIK dan kata sandi Anda terlebih dahulu untuk mengakses menu ini.');
        }

        $user = Auth::user();

        // Jika akun warga masih berstatus 'pending'
        if ($user->isPending()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('warga.login')->with('warning', 'Pendaftaran akun Anda sedang menunggu verifikasi dari petugas kelurahan. Anda akan dapat menggunakan akun setelah proses verifikasi selesai.');
        }

        // Jika akun warga ditolak
        if ($user->isRejected()) {
            $reason = $user->rejection_reason ?: 'Persyaratan pendaftaran belum terpenuhi.';
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('warga.login')->with('error', 'Pendaftaran akun Anda ditolak oleh petugas kelurahan dengan alasan: "' . $reason . '". Silakan perbaiki data Anda atau lakukan pendaftaran kembali.');
        }

        return $next($request);
    }
}
