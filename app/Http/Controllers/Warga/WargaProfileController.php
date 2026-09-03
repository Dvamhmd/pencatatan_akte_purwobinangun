<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

        return view('warga.profile.index', compact('warga', 'familyMembers'));
    }

    /**
     * Perbarui data profil warga.
     */
    public function updateProfile(Request $request)
    {
        /** @var User $warga */
        $warga = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_place' => 'required|string|max:150',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
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
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'birth_place.required' => 'Tempat lahir wajib diisi.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'phone.required' => 'Nomor HP / WhatsApp wajib diisi.',
            'email.required' => 'Alamat email wajib diisi untuk penerimaan notifikasi berkas.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email tersebut sudah terdaftar pada akun lain.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'rt.required' => 'RT wajib diisi.',
            'rw.required' => 'RW wajib diisi.',
        ]);

        $warga->update([
            'name' => $validated['name'],
            'birth_place' => $validated['birth_place'],
            'birth_date' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'rt' => $validated['rt'],
            'rw' => $validated['rw'],
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Data profil warga Anda berhasil diperbarui.');
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
