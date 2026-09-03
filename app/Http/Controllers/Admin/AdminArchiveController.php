<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminArchiveController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'citizens');
        $search = $request->input('search');

        // Statistik Total Arsip / Ditolak
        $counts = [
            'citizens' => User::where('role', 'warga')->whereIn('status', ['rejected', 'archived'])->count(),
            'birth' => BirthCertificate::whereIn('status', ['rejected', 'archived'])->count(),
            'death' => DeathCertificate::whereIn('status', ['rejected', 'archived'])->count(),
        ];
        $counts['total'] = $counts['citizens'] + $counts['birth'] + $counts['death'];

        $citizens = collect();
        $births = collect();
        $deaths = collect();

        if ($tab === 'citizens') {
            $query = User::where('role', 'warga')->whereIn('status', ['rejected', 'archived']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nik', 'like', "%{$search}%")
                      ->orWhere('family_card_no', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%")
                      ->orWhere('rejection_reason', 'like', "%{$search}%");
                });
            }

            $citizens = $query->latest('updated_at')->paginate(10)->withQueryString();
        } elseif ($tab === 'birth') {
            $query = BirthCertificate::whereIn('status', ['rejected', 'archived']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('registration_no', 'like', "%{$search}%")
                      ->orWhere('child_name', 'like', "%{$search}%")
                      ->orWhere('father_name', 'like', "%{$search}%")
                      ->orWhere('mother_name', 'like', "%{$search}%")
                      ->orWhere('applicant_nik', 'like', "%{$search}%")
                      ->orWhere('applicant_name', 'like', "%{$search}%");
                });
            }

            $births = $query->latest('updated_at')->paginate(10)->withQueryString();
        } elseif ($tab === 'death') {
            $query = DeathCertificate::whereIn('status', ['rejected', 'archived']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('registration_no', 'like', "%{$search}%")
                      ->orWhere('deceased_name', 'like', "%{$search}%")
                      ->orWhere('deceased_nik', 'like', "%{$search}%")
                      ->orWhere('applicant_name', 'like', "%{$search}%")
                      ->orWhere('applicant_nik', 'like', "%{$search}%");
                });
            }

            $deaths = $query->latest('updated_at')->paginate(10)->withQueryString();
        }

        return view('admin.archive.index', compact('tab', 'search', 'counts', 'citizens', 'births', 'deaths'));
    }

    public function archiveCitizen(User $citizen)
    {
        if (!$citizen->isWarga()) {
            abort(404);
        }

        $citizen->status = 'archived';
        $citizen->verified_at = now();
        $citizen->verified_by = Auth::user()->name;
        $citizen->save();

        return back()->with('success', 'Akun warga (' . $citizen->name . ') berhasil dipindahkan ke arsip.');
    }

    public function restoreCitizen(User $citizen)
    {
        if (!$citizen->isWarga()) {
            abort(404);
        }

        $citizen->status = 'active';
        $citizen->verified_at = now();
        $citizen->verified_by = Auth::user()->name;
        $citizen->save();

        return back()->with('success', 'Akun warga (' . $citizen->name . ') berhasil dipulihkan dan diaktifkan kembali.');
    }

    public function archiveBirth(BirthCertificate $birth)
    {
        $birth->status = 'archived';
        $birth->processed_by = Auth::user()->name;
        $birth->save();

        return back()->with('success', 'Pengajuan Akte Kelahiran (' . $birth->registration_no . ') berhasil diarsipkan.');
    }

    public function restoreBirth(BirthCertificate $birth)
    {
        $birth->status = 'pending';
        $birth->processed_by = Auth::user()->name;
        $birth->save();

        return back()->with('success', 'Pengajuan Akte Kelahiran (' . $birth->registration_no . ') berhasil dikembalikan ke status Menunggu Verifikasi.');
    }

    public function archiveDeath(DeathCertificate $death)
    {
        $death->status = 'archived';
        $death->processed_by = Auth::user()->name;
        $death->save();

        return back()->with('success', 'Pengajuan Akte Kematian (' . $death->registration_no . ') berhasil diarsipkan.');
    }

    public function restoreDeath(DeathCertificate $death)
    {
        $death->status = 'pending';
        $death->processed_by = Auth::user()->name;
        $death->save();

        return back()->with('success', 'Pengajuan Akte Kematian (' . $death->registration_no . ') berhasil dikembalikan ke status Menunggu Verifikasi.');
    }
}
