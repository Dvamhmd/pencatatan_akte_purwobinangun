<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use App\Models\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Services\AdminNotificationService;

class WargaProfileController extends Controller
{
    /**
     * Tampilkan halaman profil warga.
     */
    public function index()
    {
        /** @var User $warga */
        $warga = Auth::user();

        // Ambil data anggota keluarga lain yang terdaftar dalam satu KK
        $familyMembers = User::where('role', 'warga')
            ->where('family_card_no', $warga->family_card_no)
            ->where('id', '!=', $warga->id)
            ->get();

        $warga->load('familyMembers');

        // Ambil pengajuan perubahan data aktif dan riwayat terakhir
        $pendingRequest = $warga->latestPendingProfileRequest();
        $latestRequest = $warga->profileUpdateRequests()->latest()->first();

        return view('warga.profile.index', compact('warga', 'familyMembers', 'pendingRequest', 'latestRequest'));
    }

    /**
     * Kirim permohonan perubahan data profil & keluarga warga ke admin untuk diverifikasi.
     */
    public function updateProfile(Request $request)
    {
        /** @var User $warga */
        $warga = Auth::user();

        $validated = $request->validate([
            'nik' => [
                'required',
                'digits:16',
                Rule::unique('users', 'nik')->ignore($warga->id),
            ],
            'family_card_no' => 'required|digits:16',
            'doc_family_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'name' => 'required|string|max:255',
            'birth_place' => 'required|string|max:150',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'family_relationship' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($warga->id),
            ],
            'address' => 'required|string|max:500',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'family_members' => 'nullable|array',
            'family_members.*.family_card_no' => 'nullable|digits:16',
            'family_members.*.nik' => 'nullable|digits:16',
            'family_members.*.name' => 'nullable|string|max:255',
            'family_members.*.birth_place' => 'nullable|string|max:150',
            'family_members.*.birth_date' => 'nullable|date',
            'family_members.*.gender' => 'nullable|in:L,P',
            'family_members.*.family_relationship' => 'nullable|string|max:100',
        ], [
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK tersebut sudah terdaftar pada akun warga lain.',
            'family_card_no.required' => 'Nomor Kartu Keluarga (KK) wajib diisi.',
            'family_card_no.digits' => 'Nomor KK harus terdiri dari 16 digit angka.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'birth_place.required' => 'Tempat lahir wajib diisi.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'family_relationship.required' => 'Posisi dalam keluarga wajib dipilih.',
            'phone.required' => 'Nomor HP / WhatsApp wajib diisi.',
            'email.required' => 'Alamat email wajib diisi untuk penerimaan notifikasi berkas.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email tersebut sudah terdaftar pada akun lain.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'rt.required' => 'RT wajib diisi.',
            'rw.required' => 'RW wajib diisi.',
            'doc_family_card.file' => 'Berkas Kartu Keluarga harus berupa file yang valid.',
            'doc_family_card.mimes' => 'Format berkas Kartu Keluarga harus JPG, JPEG, PNG, atau PDF.',
            'doc_family_card.max' => 'Ukuran berkas Kartu Keluarga maksimal 3MB.',
        ]);

        // Simpan File Dokumen KK jika diunggah baru
        $docFamilyCardPath = null;
        if ($request->hasFile('doc_family_card')) {
            $docFamilyCardPath = $request->file('doc_family_card')->store('uploads/warga_docs', 'public');
        }

        // Siapkan array anggota keluarga yang bersih
        $familyMembersData = [];
        if (!empty($request->family_members) && is_array($request->family_members)) {
            foreach ($request->family_members as $memberData) {
                if (empty(trim($memberData['name'] ?? '')) && empty(trim($memberData['nik'] ?? ''))) {
                    continue;
                }
                $familyMembersData[] = [
                    'family_card_no' => !empty($memberData['family_card_no']) ? $memberData['family_card_no'] : $validated['family_card_no'],
                    'nik' => !empty($memberData['nik']) ? $memberData['nik'] : null,
                    'name' => trim($memberData['name'] ?? ''),
                    'birth_place' => $memberData['birth_place'] ?? null,
                    'birth_date' => !empty($memberData['birth_date']) ? $memberData['birth_date'] : null,
                    'gender' => !empty($memberData['gender']) ? $memberData['gender'] : null,
                    'family_relationship' => !empty($memberData['family_relationship']) ? $memberData['family_relationship'] : 'Anggota Keluarga',
                ];
            }
        }

        // Cek apakah sudah ada permohonan pending sebelumnya
        $pendingRequest = $warga->latestPendingProfileRequest();

        $dataToSave = [
            'user_id' => $warga->id,
            'nik' => $validated['nik'],
            'family_card_no' => $validated['family_card_no'],
            'name' => $validated['name'],
            'birth_place' => $validated['birth_place'],
            'birth_date' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'family_relationship' => $validated['family_relationship'],
            'address' => $validated['address'],
            'rt' => $validated['rt'],
            'rw' => $validated['rw'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'family_members_data' => $familyMembersData,
            'status' => 'pending',
            'admin_notes' => null,
            'processed_by' => null,
            'processed_at' => null,
        ];

        if ($docFamilyCardPath) {
            $dataToSave['doc_family_card'] = $docFamilyCardPath;
        } elseif ($pendingRequest && $pendingRequest->doc_family_card) {
            $dataToSave['doc_family_card'] = $pendingRequest->doc_family_card;
        } else {
            $dataToSave['doc_family_card'] = $warga->doc_family_card;
        }

        if ($pendingRequest) {
            $pendingRequest->update($dataToSave);
            $savedRequest = $pendingRequest;
        } else {
            $savedRequest = ProfileUpdateRequest::create($dataToSave);
        }

        // Notifikasi email otomatis ke admin/petugas kalurahan
        AdminNotificationService::notifyNewProfileUpdateRequest($savedRequest);

        return redirect()->route('profile.index')
            ->with('success', 'Permohonan perubahan data profil dan anggota keluarga Anda berhasil dikirim ke admin kelurahan. Data akun Anda akan diperbarui setelah diverifikasi dan disetujui oleh admin.');
    }

    /**
     * Batalkan permohonan perubahan data yang masih berstatus pending.
     */
    public function cancelRequest(ProfileUpdateRequest $profileRequest)
    {
        /** @var User $warga */
        $warga = Auth::user();

        if ($profileRequest->user_id !== $warga->id || !$profileRequest->isPending()) {
            return redirect()->route('profile.index')
                ->with('error', 'Permohonan perubahan data tidak dapat dibatalkan.');
        }

        $profileRequest->delete();

        return redirect()->route('profile.index')
            ->with('info', 'Permohonan perubahan data profil Anda telah berhasil dibatalkan.');
    }

    /**
     * Perbarui kata sandi akun warga.
     */
    public function updatePassword(Request $request)
    {
        /** @var User $warga */
        $warga = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
        ]);

        if (!Hash::check($validated['current_password'], $warga->password)) {
            return back()->withErrors([
                'current_password' => 'Kata sandi saat ini yang Anda masukkan tidak sesuai.',
            ]);
        }

        $warga->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Kata sandi akun warga Anda berhasil diperbarui.');
    }
}
