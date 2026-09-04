<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CitizenAccountStatusNotification;
use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AdminCitizenController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = User::where('role', 'warga')->where('status', '!=', 'archived');

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
            'total' => User::where('role', 'warga')->where('status', '!=', 'archived')->count(),
            'pending' => User::where('role', 'warga')->where('status', 'pending')->count(),
            'active' => User::where('role', 'warga')->where('status', 'active')->count(),
            'rejected' => User::where('role', 'warga')->where('status', 'rejected')->count(),
            'archived' => User::where('role', 'warga')->where('status', 'archived')->count(),
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

    public function edit(User $citizen)
    {
        if (!$citizen->isWarga()) {
            abort(404);
        }

        return view('admin.citizens.edit', compact('citizen'));
    }

    public function update(Request $request, User $citizen)
    {
        if (!$citizen->isWarga()) {
            abort(404);
        }

        $validated = $request->validate([
            'nik' => [
                'required',
                'digits:16',
                Rule::unique('users', 'nik')->ignore($citizen->id),
            ],
            'family_card_no' => 'required|digits:16',
            'name' => 'required|string|max:255',
            'birth_place' => 'required|string|max:150',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'phone' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($citizen->id),
            ],
            'address' => 'required|string|max:500',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'status' => 'required|in:active,pending,rejected,archived',
            'rejection_reason' => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:6',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus berupa 16 digit angka.',
            'nik.unique' => 'NIK sudah digunakan oleh akun lain.',
            'family_card_no.required' => 'Nomor KK wajib diisi.',
            'family_card_no.digits' => 'Nomor KK harus berupa 16 digit angka.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'birth_place.required' => 'Tempat lahir wajib diisi.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'phone.required' => 'Nomor HP / WhatsApp wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'rt.required' => 'RT wajib diisi.',
            'rw.required' => 'RW wajib diisi.',
            'status.required' => 'Status akun wajib dipilih.',
            'password.min' => 'Password minimal terdiri dari 6 karakter jika ingin diubah.',
        ]);

        $updateData = [
            'nik' => $validated['nik'],
            'family_card_no' => $validated['family_card_no'],
            'name' => $validated['name'],
            'birth_place' => $validated['birth_place'],
            'birth_date' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'rt' => $validated['rt'],
            'rw' => $validated['rw'],
            'status' => $validated['status'],
            'rejection_reason' => $validated['status'] === 'rejected' ? ($validated['rejection_reason'] ?? $citizen->rejection_reason) : null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $citizen->update($updateData);

        if ($validated['status'] === 'archived') {
            return redirect()->route('admin.citizens.index')
                ->with('success', 'Data akun warga (' . $citizen->name . ') berhasil diperbarui dan dipindahkan ke arsip.');
        }

        return redirect()->route('admin.citizens.show', $citizen)
            ->with('success', 'Data warga (' . $citizen->name . ') berhasil diperbarui.');
    }

    public function verify(Request $request, User $citizen)
    {
        if (!$citizen->isWarga()) {
            abort(404);
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject,archive',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:1000',
        ], [
            'rejection_reason.required_if' => 'Alasan atau catatan penolakan/penonaktifan akun wajib diisi.',
        ]);

        $sendEmail = $request->boolean('send_email');
        $sendWhatsApp = $request->boolean('send_whatsapp');
        $verifierName = Auth::user()->name;

        if ($validated['action'] === 'approve') {
            $citizen->status = 'active';
            $citizen->rejection_reason = null;
            $citizen->verified_at = now();
            $citizen->verified_by = $verifierName;
            $citizen->save();

            $message = 'Akun warga (' . $citizen->name . ') berhasil diverifikasi dan diaktifkan!';

            // Kirim notifikasi Email jika diaktifkan
            if ($sendEmail && $citizen->email) {
                try {
                    Mail::to($citizen->email)->send(new CitizenAccountStatusNotification(
                        citizen: $citizen,
                        actionType: 'approved',
                        reason: null,
                        processedBy: $verifierName
                    ));
                    $message .= ' Notifikasi telah otomatis dikirimkan ke email warga (' . $citizen->email . ').';
                } catch (\Throwable $e) {
                    Log::error('Gagal mengirim email notifikasi verifikasi akun: ' . $e->getMessage());
                    $message .= ' (Status tersimpan, namun notifikasi email gagal dikirim. Silakan periksa konfigurasi SMTP).';
                }
            }

            // Kirim notifikasi WhatsApp jika diaktifkan
            if ($sendWhatsApp && $citizen->phone) {
                $waSent = WhatsAppNotificationService::sendCitizenAccountStatusNotification(
                    citizen: $citizen,
                    actionType: 'approved',
                    reason: null,
                    adminName: $verifierName
                );

                if ($waSent) {
                    $message .= ' Notifikasi WhatsApp berhasil dikirim ke ' . $citizen->phone . '.';
                }
            }

            return redirect()->route('admin.citizens.show', $citizen)
                ->with('success', $message);
        } elseif ($validated['action'] === 'archive') {
            $citizen->status = 'archived';
            $citizen->verified_at = now();
            $citizen->verified_by = $verifierName;
            $citizen->save();

            return redirect()->route('admin.citizens.index')
                ->with('success', 'Data akun warga (' . $citizen->name . ') telah berhasil diarsipkan.');
        } else {
            $wasActive = $citizen->isActive();
            $actionType = $wasActive ? 'deactivated' : 'rejected';
            $citizen->status = 'rejected';
            $citizen->rejection_reason = $validated['rejection_reason'];
            $citizen->verified_at = now();
            $citizen->verified_by = $verifierName;
            $citizen->save();

            $message = $wasActive
                ? 'Akun warga (' . $citizen->name . ') telah dinonaktifkan.'
                : 'Pendaftaran akun warga (' . $citizen->name . ') telah ditolak dengan catatan yang diberikan.';

            // Kirim notifikasi Email jika diaktifkan
            if ($sendEmail && $citizen->email) {
                try {
                    Mail::to($citizen->email)->send(new CitizenAccountStatusNotification(
                        citizen: $citizen,
                        actionType: $actionType,
                        reason: $validated['rejection_reason'],
                        processedBy: $verifierName
                    ));
                    $message .= ' Notifikasi telah otomatis dikirimkan ke email warga (' . $citizen->email . ').';
                } catch (\Throwable $e) {
                    Log::error('Gagal mengirim email notifikasi penolakan/penonaktifan akun: ' . $e->getMessage());
                    $message .= ' (Status tersimpan, namun notifikasi email gagal dikirim. Silakan periksa konfigurasi SMTP).';
                }
            }

            // Kirim notifikasi WhatsApp jika diaktifkan
            if ($sendWhatsApp && $citizen->phone) {
                $waSent = WhatsAppNotificationService::sendCitizenAccountStatusNotification(
                    citizen: $citizen,
                    actionType: $actionType,
                    reason: $validated['rejection_reason'],
                    adminName: $verifierName
                );

                if ($waSent) {
                    $message .= ' Notifikasi WhatsApp berhasil dikirim ke ' . $citizen->phone . '.';
                }
            }

            return redirect()->route('admin.citizens.show', $citizen)
                ->with('success', $message);
        }
    }
}
