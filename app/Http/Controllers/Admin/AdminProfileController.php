<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        return view('admin.profile.index', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($admin->id),
            ],
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama lengkap admin wajib diisi.',
            'email.required' => 'Alamat email admin wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email tersebut sudah digunakan oleh akun lain.',
        ]);

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $admin->phone,
        ]);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profil admin dan email pengirim notifikasi berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $admin = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if (!Hash::check($validated['current_password'], $admin->password)) {
            return back()->withErrors([
                'current_password' => 'Kata sandi saat ini yang Anda masukkan tidak sesuai.',
            ]);
        }

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Kata sandi akun admin berhasil diperbarui.');
    }
}
