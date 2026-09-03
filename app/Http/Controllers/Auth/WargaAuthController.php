<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
            return redirect()->route('birth.list');
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

        // Akun Aktif: Lakukan Login
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('birth.list'))
            ->with('success', 'Selamat datang kembali, ' . $user->name . '! Anda berhasil masuk.');
    }

    public function showRegisterForm(Request $request)
    {
        if (Auth::check() && Auth::user()->isWarga()) {
            return redirect()->route('birth.list');
        }

        $prefill = null;
        if ($request->filled('reapply_nik')) {
            $prefill = User::where('nik', $request->query('reapply_nik'))->where('status', 'rejected')->first();
        }

        return view('warga.auth.register', compact('prefill'));
    }

    public function register(Request $request)
    {
        // Cek apakah ini pendaftaran ulang akun yang sebelumnya ditolak
        $existingRejected = User::where('nik', $request->nik)->where('status', 'rejected')->first();

        $rules = [
            'nik' => [
                'required',
                'digits:16',
                $existingRejected ? 'nullable' : 'unique:users,nik',
            ],
            'family_card_no' => 'required|digits:16',
            'name' => 'required|string|max:255',
            'birth_place' => 'required|string|max:150',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'address' => 'required|string|max:500',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'phone' => 'required|string|max:20',
            'email' => [
                'nullable',
                'email',
                'max:255',
                $existingRejected ? Rule::unique('users', 'email')->ignore($existingRejected->id) : 'unique:users,email',
            ],
            'password' => 'required|string|min:6|confirmed',
        ];

        $validated = $request->validate($rules, [
            'required' => ':attribute wajib diisi.',
            'digits' => ':attribute harus terdiri dari tepat :digits digit angka.',
            'unique' => ':attribute sudah terdaftar dalam sistem.',
            'min' => ':attribute minimal berisi :min karakter.',
            'confirmed' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi baru.',
            'email' => 'Format email tidak valid.',
            'in' => 'Pilihan :attribute tidak valid.',
        ], [
            'nik' => 'Nomor Induk Kependudukan (NIK)',
            'family_card_no' => 'Nomor Kartu Keluarga (KK)',
            'name' => 'Nama Lengkap Sesuai KTP/KK',
            'birth_place' => 'Tempat Lahir',
            'birth_date' => 'Tanggal Lahir',
            'gender' => 'Jenis Kelamin',
            'address' => 'Alamat Lengkap',
            'rt' => 'RT',
            'rw' => 'RW',
            'phone' => 'Nomor Telepon / WhatsApp',
            'email' => 'Email',
            'password' => 'Kata Sandi',
            'password_confirmation' => 'Konfirmasi Kata Sandi',
        ]);

        if ($existingRejected) {
            // Perbarui data akun yang ditolak dan kembalikan ke status pending
            $existingRejected->update([
                'family_card_no' => $validated['family_card_no'],
                'name' => $validated['name'],
                'birth_place' => $validated['birth_place'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
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
            ]);
        } else {
            // Buat akun baru
            User::create([
                'role' => 'warga',
                'nik' => $validated['nik'],
                'family_card_no' => $validated['family_card_no'],
                'name' => $validated['name'],
                'birth_place' => $validated['birth_place'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'rt' => $validated['rt'],
                'rw' => $validated['rw'],
                'phone' => $validated['phone'],
                'email' => !empty($validated['email']) ? $validated['email'] : null,
                'password' => Hash::make($validated['password']),
                'status' => 'pending',
            ]);
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
