<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class WargaAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isWarga()) {
            return redirect()->route('submissions.index');
        }

        return view('warga.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16',
            'password' => 'required|string',
        ], [
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari tepat 16 digit angka.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $user = User::where('nik', $request->nik)->first();

        if (!$user) {
            return back()->withErrors([
                'nik' => 'NIK belum terdaftar dalam sistem. Silakan lakukan pendaftaran akun warga terlebih dahulu.',
            ])->onlyInput('nik');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Kata sandi yang Anda masukkan salah. Silakan periksa kembali.',
            ])->onlyInput('nik');
        }

        // Cek Status Akun
        if ($user->isPending()) {
            return back()->with('pending_notice', [
                'name' => $user->name,
                'nik' => $user->nik,
                'message' => 'Pendaftaran akun Anda sedang menunggu verifikasi dari petugas kelurahan. Anda akan dapat menggunakan akun setelah proses verifikasi selesai.',
            ])->onlyInput('nik');
        }

        if ($user->isRejected()) {
            return back()->with('rejected_notice', [
                'name' => $user->name,
                'nik' => $user->nik,
                'reason' => $user->rejection_reason ?: 'Data tidak sesuai atau persyaratan belum terpenuhi.',
                'message' => 'Pendaftaran akun Anda ditolak oleh petugas kelurahan.',
            ])->onlyInput('nik');
        }

        if ($user->isArchived()) {
            return back()->with('rejected_notice', [
                'name' => $user->name,
                'nik' => $user->nik,
                'reason' => $user->rejection_reason ?: 'Akun telah dinonaktifkan atau diarsipkan oleh petugas kelurahan.',
                'message' => 'Akun warga Anda saat ini berstatus diarsipkan / dinonaktifkan oleh petugas.',
            ])->onlyInput('nik');
        }

        // Akun Aktif: Lakukan Login
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('submissions.index'))
            ->with('success', 'Selamat datang kembali, ' . $user->name . '! Anda berhasil masuk.');
    }

    public function showRegisterForm(Request $request)
    {
        if (Auth::check() && Auth::user()->isWarga()) {
            return redirect()->route('submissions.index');
        }

        $prefill = null;
        if ($request->filled('reapply_nik')) {
            $prefill = User::with('familyMembers')->where('nik', $request->query('reapply_nik'))->whereIn('status', ['rejected', 'archived'])->first();
        }

        return view('warga.auth.register', compact('prefill'));
    }

    public function register(Request $request)
    {
        // Cek apakah ini pendaftaran ulang akun yang sebelumnya ditolak atau diarsipkan
        $existingRejected = User::where('nik', $request->nik)->whereIn('status', ['rejected', 'archived'])->first();

        $rules = [
            'nik' => [
                'required',
                'digits:16',
                $existingRejected ? 'nullable' : 'unique:users,nik',
            ],
            'family_card_no' => 'required|digits:16',
            'doc_family_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'name' => 'required|string|max:255',
            'birth_place' => 'required|string|max:150',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'family_relationship' => 'nullable|string|max:100',
            'address' => 'required|string|max:500',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'phone' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'max:255',
                $existingRejected ? Rule::unique('users', 'email')->ignore($existingRejected->id) : 'unique:users,email',
            ],
            'password' => 'required|string|min:6|confirmed',
            'family_members' => 'nullable|array',
            'family_members.*.family_card_no' => 'nullable|digits:16',
            'family_members.*.nik' => 'nullable|digits:16',
            'family_members.*.name' => 'nullable|string|max:255',
            'family_members.*.birth_place' => 'nullable|string|max:150',
            'family_members.*.birth_date' => 'nullable|date',
            'family_members.*.gender' => 'nullable|in:L,P',
            'family_members.*.family_relationship' => 'nullable|string|max:100',
        ];

        $validated = $request->validate($rules, [
            'required' => ':attribute wajib diisi.',
            'digits' => ':attribute harus terdiri dari tepat :digits digit angka.',
            'unique' => ':attribute sudah terdaftar dalam sistem.',
            'min' => ':attribute minimal berisi :min karakter.',
            'confirmed' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi baru.',
            'email.required' => 'Alamat email wajib diisi untuk menerima notifikasi status permohonan.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar dalam sistem.',
            'email' => 'Format email tidak valid.',
            'in' => 'Pilihan :attribute tidak valid.',
            'doc_family_card.file' => 'Berkas Kartu Keluarga harus berupa file yang valid.',
            'doc_family_card.mimes' => 'Format berkas Kartu Keluarga harus berupa JPG, JPEG, PNG, atau PDF.',
            'doc_family_card.max' => 'Ukuran berkas Kartu Keluarga maksimal 3MB.',
        ], [
            'nik' => 'Nomor Induk Kependudukan (NIK)',
            'family_card_no' => 'Nomor Kartu Keluarga (KK)',
            'doc_family_card' => 'Dokumen Kartu Keluarga (KK)',
            'name' => 'Nama Lengkap Sesuai KTP/KK',
            'birth_place' => 'Tempat Lahir',
            'birth_date' => 'Tanggal Lahir',
            'gender' => 'Jenis Kelamin',
            'family_relationship' => 'Posisi dalam Keluarga',
            'address' => 'Alamat Lengkap',
            'rt' => 'RT',
            'rw' => 'RW',
            'phone' => 'Nomor Telepon / WhatsApp',
            'email' => 'Email',
            'password' => 'Kata Sandi',
            'password_confirmation' => 'Konfirmasi Kata Sandi',
            'family_members.*.family_card_no' => 'Nomor KK Anggota Keluarga',
            'family_members.*.nik' => 'NIK Anggota Keluarga',
            'family_members.*.name' => 'Nama Anggota Keluarga',
            'family_members.*.birth_place' => 'Tempat Lahir Anggota Keluarga',
            'family_members.*.birth_date' => 'Tanggal Lahir Anggota Keluarga',
            'family_members.*.gender' => 'Jenis Kelamin Anggota Keluarga',
            'family_members.*.family_relationship' => 'Posisi dalam Keluarga Anggota',
        ]);

        $familyRelationship = !empty($validated['family_relationship']) ? $validated['family_relationship'] : 'Kepala Keluarga';

        // Simpan File Dokumen KK jika diunggah
        $docFamilyCardPath = null;
        if ($request->hasFile('doc_family_card')) {
            $docFamilyCardPath = $request->file('doc_family_card')->store('uploads/warga_docs', 'public');
        }

        if ($existingRejected) {
            // Perbarui data akun yang ditolak dan kembalikan ke status pending
            $updateData = [
                'family_card_no' => $validated['family_card_no'],
                'name' => $validated['name'],
                'birth_place' => $validated['birth_place'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'family_relationship' => $familyRelationship,
                'address' => $validated['address'],
                'rt' => $validated['rt'],
                'rw' => $validated['rw'],
                'phone' => $validated['phone'],
                'email' => !empty($validated['email']) ? $validated['email'] : null,
                'password' => Hash::make($validated['password']),
                'status' => 'pending',
                'rejection_reason' => null,
                'verified_at' => null,
                'verified_by' => null,
            ];

            if ($docFamilyCardPath) {
                $updateData['doc_family_card'] = $docFamilyCardPath;
            }

            $existingRejected->update($updateData);
            $user = $existingRejected;
            $user->familyMembers()->delete();
        } else {
            // Buat akun baru
            $user = User::create([
                'role' => 'warga',
                'nik' => $validated['nik'],
                'family_card_no' => $validated['family_card_no'],
                'doc_family_card' => $docFamilyCardPath,
                'name' => $validated['name'],
                'birth_place' => $validated['birth_place'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'family_relationship' => $familyRelationship,
                'address' => $validated['address'],
                'rt' => $validated['rt'],
                'rw' => $validated['rw'],
                'phone' => $validated['phone'],
                'email' => !empty($validated['email']) ? $validated['email'] : null,
                'password' => Hash::make($validated['password']),
                'status' => 'pending',
            ]);
        }

        // Simpan Anggota Keluarga jika ada
        if (!empty($request->family_members) && is_array($request->family_members)) {
            foreach ($request->family_members as $memberData) {
                if (empty(trim($memberData['name'] ?? '')) && empty(trim($memberData['nik'] ?? ''))) {
                    continue;
                }
                FamilyMember::create([
                    'user_id' => $user->id,
                    'family_card_no' => !empty($memberData['family_card_no']) ? $memberData['family_card_no'] : $user->family_card_no,
                    'nik' => !empty($memberData['nik']) ? $memberData['nik'] : null,
                    'name' => $memberData['name'] ?? '',
                    'birth_place' => $memberData['birth_place'] ?? null,
                    'birth_date' => !empty($memberData['birth_date']) ? $memberData['birth_date'] : null,
                    'gender' => !empty($memberData['gender']) ? $memberData['gender'] : null,
                    'family_relationship' => !empty($memberData['family_relationship']) ? $memberData['family_relationship'] : 'Anggota Keluarga',
                ]);
            }
        }

        // Pastikan sesi aktif lama (jika ada) dikeluarkan agar berstatus belum login (guest)
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('warga.login')->with('registration_success', [
            'name' => $validated['name'],
            'nik' => $validated['nik'],
            'message' => 'Pendaftaran akun Anda berhasil dikirim! Status akun Anda saat ini sedang menunggu verifikasi dari petugas kelurahan. Anda akan dapat menggunakan akun setelah proses verifikasi selesai.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Anda telah berhasil keluar dari akun warga.');
    }
}
