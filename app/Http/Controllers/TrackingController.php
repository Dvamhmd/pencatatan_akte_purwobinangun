<?php

namespace App\Http\Controllers;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->input('keyword', ''));
        $result = null;
        $type = null;
        $user = Auth::user();

        if (!empty($keyword)) {
            // Cek di Akte Kelahiran
            $birthQuery = BirthCertificate::where(function ($q) use ($keyword) {
                $q->where('registration_no', $keyword)
                  ->orWhere('applicant_nik', $keyword)
                  ->orWhere('father_nik', $keyword)
                  ->orWhere('mother_nik', $keyword);
            });

            // Jika Warga sedang login, batasi hanya KK miliknya
            if ($user && $user->isWarga()) {
                $birthQuery->where('family_card_no', $user->family_card_no);
            }

            $birth = $birthQuery->latest()->first();

            if ($birth) {
                $result = $birth;
                $type = 'birth';
            } else {
                // Cek di Akte Kematian
                $deathQuery = DeathCertificate::where(function ($q) use ($keyword) {
                    $q->where('registration_no', $keyword)
                      ->orWhere('deceased_nik', $keyword)
                      ->orWhere('applicant_nik', $keyword);
                });

                if ($user && $user->isWarga()) {
                    $deathQuery->where('family_card_no', $user->family_card_no);
                }

                $death = $deathQuery->latest()->first();

                if ($death) {
                    $result = $death;
                    $type = 'death';
                }
            }
        }

        return view('tracking.index', compact('keyword', 'result', 'type'));
    }

    public function show(string $type, string $registrationNo)
    {
        if ($type === 'birth') {
            $data = BirthCertificate::where('registration_no', $registrationNo)->firstOrFail();
        } else {
            $data = DeathCertificate::where('registration_no', $registrationNo)->firstOrFail();
        }

        // Keamanan Akses Data Sensitif Berbasis KK
        if (!Auth::check()) {
            return redirect()->guest(route('warga.login'))
                ->with('info', 'Silakan masuk ke akun warga Anda untuk mengakses detail dan status pengajuan.');
        }

        $user = Auth::user();
        if ($user->isWarga()) {
            if ($data->family_card_no && $data->family_card_no !== $user->family_card_no) {
                abort(403, 'Akses Ditolak: Anda hanya berhak melihat dokumen dan status pengajuan dari Kartu Keluarga (KK) Anda sendiri.');
            }
        }

        return view('tracking.show', compact('data', 'type'));
    }

    public function printReceipt(string $type, string $registrationNo)
    {
        if ($type === 'birth') {
            $data = BirthCertificate::where('registration_no', $registrationNo)->firstOrFail();
        } else {
            $data = DeathCertificate::where('registration_no', $registrationNo)->firstOrFail();
        }

        // Keamanan Akses Data Sensitif Berbasis KK
        if (!Auth::check()) {
            return redirect()->guest(route('warga.login'))
                ->with('info', 'Silakan masuk ke akun warga Anda untuk mencetak bukti pendaftaran.');
        }

        $user = Auth::user();
        if ($user->isWarga()) {
            if ($data->family_card_no && $data->family_card_no !== $user->family_card_no) {
                abort(403, 'Akses Ditolak: Anda hanya berhak mencetak bukti pengajuan dari Kartu Keluarga (KK) Anda sendiri.');
            }
        }

        return view('tracking.print-receipt', compact('data', 'type'));
    }
}
