<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        $mailSenderAddress = config('mail.from.address') ?: env('MAIL_FROM_ADDRESS', 'ahmadtaupik580@gmail.com');
        $mailSenderName = config('mail.from.name') ?: env('MAIL_FROM_NAME', 'Pelayanan Akte Purwobinangun');
        $adminNotificationEmail = config('mail.admin_notification_email') ?: (env('ADMIN_NOTIFICATION_EMAIL') ?: $admin->email);
        $mailUsername = env('MAIL_USERNAME', config('mail.mailers.smtp.username'));

        return view('admin.profile.index', compact(
            'admin',
            'mailSenderAddress',
            'mailSenderName',
            'adminNotificationEmail',
            'mailUsername'
        ));
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
            ->with('success', 'Profil akun admin berhasil diperbarui.');
    }

    public function updateEmailSettings(Request $request)
    {
        $validated = $request->validate([
            'admin_notification_email' => 'required|string|max:500',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
            'mail_password' => 'nullable|string|max:255',
        ], [
            'admin_notification_email.required' => 'Alamat email penerima notifikasi pengajuan baru wajib diisi.',
            'mail_from_address.required' => 'Alamat email pengirim notifikasi ke warga wajib diisi.',
            'mail_from_address.email' => 'Format alamat email pengirim tidak valid.',
            'mail_from_name.required' => 'Nama pengirim notifikasi resmi wajib diisi.',
        ]);

        // Validasi setiap alamat email penerima (mendukung format multi-email dipisah koma)
        $emails = array_map('trim', explode(',', $validated['admin_notification_email']));
        $cleanEmails = [];
        foreach ($emails as $email) {
            if (empty($email)) {
                continue;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return back()->withErrors([
                    'admin_notification_email' => "Format email penerima notifikasi '{$email}' tidak valid.",
                ])->withInput();
            }
            $cleanEmails[] = strtolower($email);
        }

        if (empty($cleanEmails)) {
            return back()->withErrors([
                'admin_notification_email' => 'Setidaknya masukkan satu alamat email penerima notifikasi yang valid.',
            ])->withInput();
        }

        $cleanAdminNotificationEmail = implode(', ', array_unique($cleanEmails));

        // Data konfigurasi yang akan diperbarui ke .env
        $envData = [
            'ADMIN_NOTIFICATION_EMAIL' => $cleanAdminNotificationEmail,
            'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
            'MAIL_FROM_NAME' => $validated['mail_from_name'],
        ];

        // Jika memasukkan App Password baru, perbarui juga kredensial SMTP
        if ($request->filled('mail_password')) {
            $cleanPassword = str_replace(' ', '', $request->input('mail_password'));
            $envData['MAIL_PASSWORD'] = $cleanPassword;
            $envData['MAIL_USERNAME'] = $validated['mail_from_address'];
        }

        $this->updateEnvFile($envData);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Pengaturan email pengirim ke warga dan email penerima notifikasi pengajuan berhasil disimpan.');
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

    /**
     * Perbarui variabel di file .env secara aman dan bersihkan cache konfigurasi.
     */
    protected function updateEnvFile(array $values): bool
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return false;
        }

        $content = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            $valueStr = (string) $value;
            $formattedValue = (str_contains($valueStr, ' ') || str_contains($valueStr, '#') || str_contains($valueStr, '$') || str_contains($valueStr, '"'))
                ? '"' . str_replace('"', '\"', $valueStr) . '"'
                : $valueStr;

            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$formattedValue}", $content);
            } else {
                $content .= "\n{$key}={$formattedValue}";
            }
        }

        file_put_contents($envPath, $content);

        try {
            Artisan::call('config:clear');
        } catch (\Throwable $e) {
            // Abaikan jika env testing
        }

        return true;
    }
}
