<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCitizenController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = User::where('role', 'warga');

        if ($status && in_array($status, ['pending', 'active', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('family_card_no', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $citizens = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => User::where('role', 'warga')->count(),
            'pending' => User::where('role', 'warga')->where('status', 'pending')->count(),
            'active' => User::where('role', 'warga')->where('status', 'active')->count(),
            'rejected' => User::where('role', 'warga')->where('status', 'rejected')->count(),
        ];

        return view('admin.citizens.index', compact('citizens', 'status', 'search', 'stats'));
    }

    public function show(User $citizen)
    {
        if (!$citizen->isWarga()) {
            abort(404);
        }

        // Ambil riwayat pengajuan berdasarkan Nomor KK warga ini
        $birthSubmissions = BirthCertificate::where('family_card_no', $citizen->family_card_no)->latest()->get();
        $deathSubmissions = DeathCertificate::where('family_card_no', $citizen->family_card_no)->latest()->get();

        // Cari anggota keluarga lain yang terdaftar dalam satu KK
        $familyMembers = User::where('role', 'warga')
            ->where('family_card_no', $citizen->family_card_no)
            ->where('id', '!=', $citizen->id)
            ->get();

        return view('admin.citizens.show', compact('citizen', 'birthSubmissions', 'deathSubmissions', 'familyMembers'));
    }

    public function verify(Request $request, User $citizen)
    {
        if (!$citizen->isWarga()) {
            abort(404);
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:1000',
        ], [
            'rejection_reason.required_if' => 'Alasan atau catatan penolakan wajib diisi jika Anda menolak pendaftaran.',
        ]);

        if ($validated['action'] === 'approve') {
            $citizen->status = 'active';
            $citizen->rejection_reason = null;
            $citizen->verified_at = now();
            $citizen->verified_by = Auth::user()->name;
            $citizen->save();

            return redirect()->route('admin.citizens.show', $citizen)
                ->with('success', 'Akun warga (' . $citizen->name . ') berhasil diverifikasi dan diaktifkan!');
        } else {
            $citizen->status = 'rejected';
            $citizen->rejection_reason = $validated['rejection_reason'];
            $citizen->verified_at = now();
            $citizen->verified_by = Auth::user()->name;
            $citizen->save();

            return redirect()->route('admin.citizens.show', $citizen)
                ->with('success', 'Pendaftaran akun warga (' . $citizen->name . ') telah ditolak dengan catatan yang diberikan.');
        }
    }
}
