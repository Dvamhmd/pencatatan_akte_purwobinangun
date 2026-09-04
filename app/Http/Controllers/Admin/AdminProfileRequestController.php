<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\FamilyMember;
use App\Models\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminProfileRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = ProfileUpdateRequest::with('user')->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('family_card_no', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('nik', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => ProfileUpdateRequest::count(),
            'pending' => ProfileUpdateRequest::where('status', 'pending')->count(),
            'approved' => ProfileUpdateRequest::where('status', 'approved')->count(),
            'rejected' => ProfileUpdateRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.profile_requests.index', compact('requests', 'status', 'search', 'stats'));
    }

    public function show(ProfileUpdateRequest $profileRequest)
    {
        $profileRequest->load('user.familyMembers');
        $user = $profileRequest->user;

        return view('admin.profile_requests.show', compact('profileRequest', 'user'));
    }

    public function verify(Request $request, ProfileUpdateRequest $profileRequest)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:1000',
        ], [
            'action.required' => 'Pilihan tindakan verifikasi wajib ditentukan.',
            'rejection_reason.required_if' => 'Alasan atau catatan penolakan permohonan wajib diisi jika menolak.',
        ]);

        $verifierName = Auth::user()->name;

        if ($validated['action'] === 'approve') {
            DB::transaction(function () use ($profileRequest, $verifierName) {
                /** @var User $user */
                $user = $profileRequest->user;

                // Update data akun warga
                $updateData = [
                    'nik' => $profileRequest->nik ?: $user->nik,
                    'family_card_no' => $profileRequest->family_card_no ?: $user->family_card_no,
                    'name' => $profileRequest->name,
                    'birth_place' => $profileRequest->birth_place,
                    'birth_date' => $profileRequest->birth_date,
                    'gender' => $profileRequest->gender,
                    'family_relationship' => $profileRequest->family_relationship,
                    'phone' => $profileRequest->phone,
                    'email' => $profileRequest->email,
                    'address' => $profileRequest->address,
                    'rt' => $profileRequest->rt,
                    'rw' => $profileRequest->rw,
                ];

                if ($profileRequest->doc_family_card) {
                    $updateData['doc_family_card'] = $profileRequest->doc_family_card;
                }

                $user->update($updateData);

                // Sinkronisasi anggota keluarga
                $user->familyMembers()->delete();

                if (!empty($profileRequest->family_members_data) && is_array($profileRequest->family_members_data)) {
                    foreach ($profileRequest->family_members_data as $m) {
                        if (empty(trim($m['name'] ?? '')) && empty(trim($m['nik'] ?? ''))) {
                            continue;
                        }
                        FamilyMember::create([
                            'user_id' => $user->id,
                            'family_card_no' => !empty($m['family_card_no']) ? $m['family_card_no'] : $user->family_card_no,
                            'nik' => !empty($m['nik']) ? $m['nik'] : null,
                            'name' => $m['name'],
                            'birth_place' => $m['birth_place'] ?? null,
                            'birth_date' => !empty($m['birth_date']) ? $m['birth_date'] : null,
                            'gender' => !empty($m['gender']) ? $m['gender'] : null,
                            'family_relationship' => !empty($m['family_relationship']) ? $m['family_relationship'] : 'Anggota Keluarga',
                        ]);
                    }
                }

                // Sinkronisasi data permohonan akta terkait
                BirthCertificate::where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('applicant_nik', $user->nik);
                })->update([
                    'applicant_phone' => $user->phone,
                    'applicant_name' => $user->name,
                ]);

                DeathCertificate::where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('applicant_nik', $user->nik);
                })->update([
                    'applicant_phone' => $user->phone,
                    'applicant_name' => $user->name,
                ]);

                // Update status permohonan
                $profileRequest->update([
                    'status' => 'approved',
                    'admin_notes' => null,
                    'processed_by' => $verifierName,
                    'processed_at' => now(),
                ]);
            });

            return redirect()->route('admin.profile_requests.show', $profileRequest)
                ->with('success', 'Permohonan perubahan data warga (' . $profileRequest->name . ') berhasil disetujui. Data profil dan anggota keluarga telah berhasil diperbarui di sistem.');
        } else {
            $profileRequest->update([
                'status' => 'rejected',
                'admin_notes' => $validated['rejection_reason'],
                'processed_by' => $verifierName,
                'processed_at' => now(),
            ]);

            return redirect()->route('admin.profile_requests.show', $profileRequest)
                ->with('success', 'Permohonan perubahan data warga (' . $profileRequest->name . ') telah ditolak dengan catatan yang diberikan.');
        }
    }
}
