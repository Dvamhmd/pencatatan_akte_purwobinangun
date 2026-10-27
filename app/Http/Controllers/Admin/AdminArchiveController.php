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
            'birth' => BirthCertificate::where(function ($q) {
                $q->where('is_archived', true)->orWhereIn('status', ['picked_up', 'archived']);
            })->count(),
            'death' => DeathCertificate::where(function ($q) {
                $q->where('is_archived', true)->orWhereIn('status', ['picked_up', 'archived']);
            })->count(),
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
            $query = BirthCertificate::where(function ($q) {
                $q->where('is_archived', true)->orWhereIn('status', ['picked_up', 'archived']);
            });

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
            $query = DeathCertificate::where(function ($q) {
                $q->where('is_archived', true)->orWhereIn('status', ['picked_up', 'archived']);
            });

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

        $admin = Auth::user();

        $citizen->status = 'archived';
        $citizen->verified_at = now();
        $citizen->verified_by = $admin->name;
        $citizen->save();

        \App\Models\ActivityLog::record([
            'actor_id' => $admin->id,
            'actor_name' => $admin->name,
            'actor_role' => 'admin',
            'actor_nik' => $admin->nik,
            'module' => 'akun_warga',
            'action' => 'arsip',
            'description' => 'Pengarsipan akun warga.',
            'subject_type' => User::class,
            'subject_id' => $citizen->id,
            'reference_no' => $citizen->nik,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.citizens.index')
            ->with('success', 'Akun warga (' . $citizen->name . ') berhasil dipindahkan ke arsip.');
    }

    public function restoreCitizen(User $citizen)
    {
        if (!$citizen->isWarga()) {
            abort(404);
        }

        $admin = Auth::user();

        $citizen->status = 'active';
        $citizen->verified_at = now();
        $citizen->verified_by = $admin->name;
        $citizen->save();

        \App\Models\ActivityLog::record([
            'actor_id' => $admin->id,
            'actor_name' => $admin->name,
            'actor_role' => 'admin',
            'actor_nik' => $admin->nik,
            'module' => 'akun_warga',
            'action' => 'pulihkan',
            'description' => 'Pemulihan dan pengaktifan kembali akun warga.',
            'subject_type' => User::class,
            'subject_id' => $citizen->id,
            'reference_no' => $citizen->nik,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Akun warga (' . $citizen->name . ') berhasil dipulihkan dan diaktifkan kembali.');
    }

    public function archiveBirth(BirthCertificate $birth)
    {
        $admin = Auth::user();

        $birth->is_archived = true;
        $birth->processed_by = $admin->name;
        $birth->save();

        \App\Models\ActivityLog::record([
            'actor_id' => $admin->id,
            'actor_name' => $admin->name,
            'actor_role' => 'admin',
            'actor_nik' => $admin->nik,
            'module' => 'akte_kelahiran',
            'action' => 'arsip',
            'description' => 'Pengarsipan permohonan Akte Kelahiran.',
            'subject_type' => BirthCertificate::class,
            'subject_id' => $birth->id,
            'reference_no' => $birth->registration_no,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.birth.index')
            ->with('success', 'Pengajuan Akte Kelahiran (' . $birth->registration_no . ') berhasil diarsipkan.');
    }

    public function restoreBirth(BirthCertificate $birth)
    {
        $admin = Auth::user();

        $birth->is_archived = false;
        $birth->status = 'pending';
        $birth->processed_by = $admin->name;
        $birth->save();

        \App\Models\ActivityLog::record([
            'actor_id' => $admin->id,
            'actor_name' => $admin->name,
            'actor_role' => 'admin',
            'actor_nik' => $admin->nik,
            'module' => 'akte_kelahiran',
            'action' => 'pulihkan',
            'description' => 'Pemulihan permohonan Akte Kelahiran kembali ke status Menunggu Verifikasi.',
            'subject_type' => BirthCertificate::class,
            'subject_id' => $birth->id,
            'reference_no' => $birth->registration_no,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Pengajuan Akte Kelahiran (' . $birth->registration_no . ') berhasil dikembalikan ke status Menunggu Verifikasi.');
    }

    public function archiveDeath(DeathCertificate $death)
    {
        $admin = Auth::user();

        $death->is_archived = true;
        $death->processed_by = $admin->name;
        $death->save();

        \App\Models\ActivityLog::record([
            'actor_id' => $admin->id,
            'actor_name' => $admin->name,
            'actor_role' => 'admin',
            'actor_nik' => $admin->nik,
            'module' => 'akte_kematian',
            'action' => 'arsip',
            'description' => 'Pengarsipan permohonan Akte Kematian.',
            'subject_type' => DeathCertificate::class,
            'subject_id' => $death->id,
            'reference_no' => $death->registration_no,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.death.index')
            ->with('success', 'Pengajuan Akte Kematian (' . $death->registration_no . ') berhasil diarsipkan.');
    }

    public function restoreDeath(DeathCertificate $death)
    {
        $admin = Auth::user();

        $death->is_archived = false;
        $death->status = 'pending';
        $death->processed_by = $admin->name;
        $death->save();

        \App\Models\ActivityLog::record([
            'actor_id' => $admin->id,
            'actor_name' => $admin->name,
            'actor_role' => 'admin',
            'actor_nik' => $admin->nik,
            'module' => 'akte_kematian',
            'action' => 'pulihkan',
            'description' => 'Pemulihan permohonan Akte Kematian kembali ke status Menunggu Verifikasi.',
            'subject_type' => DeathCertificate::class,
            'subject_id' => $death->id,
            'reference_no' => $death->registration_no,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Pengajuan Akte Kematian (' . $death->registration_no . ') berhasil dikembalikan ke status Menunggu Verifikasi.');
    }
}
