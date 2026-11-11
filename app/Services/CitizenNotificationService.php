<?php

namespace App\Services;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\ProfileUpdateRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CitizenNotificationService
{
    /**
     * Mengambil seluruh notifikasi lengkap untuk warga terotentikasi.
     * Mencakup status akun, pendaftaran akun baru, pengajuan akte kelahiran,
     * pengajuan akte kematian, perubahan profil/KK, dan update data oleh admin.
     *
     * @param User $user
     * @return Collection
     */
    public static function getNotificationsForUser(User $user): Collection
    {
        $notifications = collect();

        if (!$user->isWarga()) {
            return $notifications;
        }

        // 1. Status Akun Warga & Verifikasi Pendaftaran
        if ($user->status === 'active') {
            $time = $user->verified_at ?? $user->created_at ?? now();
            $notifications->push([
                'id' => 'account_active_' . $user->id . '_' . ($time ? Carbon::parse($time)->timestamp : '0'),
                'category' => 'Akun Warga',
                'category_icon' => 'fa-solid fa-user-check',
                'category_color' => 'emerald',
                'type' => 'account',
                'status' => 'active',
                'status_label' => 'Akun Terverifikasi',
                'status_badge_class' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'icon_class' => 'fa-solid fa-circle-check text-emerald-600',
                'icon_bg' => 'bg-emerald-50 border border-emerald-200',
                'title' => 'Akun Terverifikasi',
                'message' => 'Akun warga (NIK: ' . $user->nik . ') telah aktif.',
                'admin_note' => null,
                'reference_no' => $user->nik,
                'time' => $time,
                'formatted_time' => Carbon::parse($time)->locale('id')->diffForHumans(),
                'url' => route('profile.index'),
                'url_label' => 'Lihat Data Profil',
            ]);
        } elseif ($user->status === 'pending') {
            $time = $user->created_at ?? now();
            $notifications->push([
                'id' => 'account_pending_' . $user->id . '_' . ($time ? Carbon::parse($time)->timestamp : '0'),
                'category' => 'Pendaftaran Akun',
                'category_icon' => 'fa-solid fa-user-clock',
                'category_color' => 'amber',
                'type' => 'account',
                'status' => 'pending',
                'status_label' => 'Menunggu Verifikasi',
                'status_badge_class' => 'bg-amber-100 text-amber-800 border-amber-300',
                'icon_class' => 'fa-solid fa-clock text-amber-600',
                'icon_bg' => 'bg-amber-50 border border-amber-200',
                'title' => 'Menunggu Verifikasi Akun',
                'message' => 'Pendaftaran akun sedang menunggu verifikasi.',
                'admin_note' => null,
                'reference_no' => $user->nik,
                'time' => $time,
                'formatted_time' => Carbon::parse($time)->locale('id')->diffForHumans(),
                'url' => route('profile.index'),
                'url_label' => 'Lihat Status Akun',
            ]);
        } elseif ($user->status === 'rejected') {
            $time = $user->updated_at ?? $user->created_at ?? now();
            $notifications->push([
                'id' => 'account_rejected_' . $user->id . '_' . ($time ? Carbon::parse($time)->timestamp : '0'),
                'category' => 'Pendaftaran Akun',
                'category_icon' => 'fa-solid fa-user-xmark',
                'category_color' => 'rose',
                'type' => 'account',
                'status' => 'rejected',
                'status_label' => 'Pendaftaran Ditolak',
                'status_badge_class' => 'bg-rose-100 text-rose-800 border-rose-300',
                'icon_class' => 'fa-solid fa-circle-xmark text-rose-600',
                'icon_bg' => 'bg-rose-50 border border-rose-200',
                'title' => 'Pendaftaran Akun Ditolak',
                'message' => 'Pendaftaran akun Anda ditolak.',
                'admin_note' => $user->rejection_reason ?: 'Data NIK/KK atau dokumen yang dilampirkan tidak sesuai.',
                'reference_no' => $user->nik,
                'time' => $time,
                'formatted_time' => Carbon::parse($time)->locale('id')->diffForHumans(),
                'url' => route('profile.index'),
                'url_label' => 'Lihat Rincian Penolakan',
            ]);
        }

        // 2. Pengajuan Akte Kelahiran
        try {
            $birthSubmissions = BirthCertificate::where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if (!empty($user->family_card_no)) {
                    $q->orWhere('family_card_no', $user->family_card_no);
                }
            })->latest('updated_at')->get();

            foreach ($birthSubmissions as $birth) {
                $status = $birth->status;
                $time = $birth->updated_at ?? $birth->created_at;

                $statusLabel = $birth->status_label;
                $statusBadgeClass = $birth->status_badge_class;
                $adminNote = $birth->rejection_note;

                if (in_array($status, ['ready_for_pickup', 'completed'])) {
                    $iconClass = 'fa-solid fa-circle-check text-emerald-600';
                    $iconBg = 'bg-emerald-50 border border-emerald-200';
                    $title = 'Akte Kelahiran Siap Diambil';
                    $message = "Akte Kelahiran an. {$birth->child_name} siap diambil.";
                } elseif ($status === 'rejected') {
                    $iconClass = 'fa-solid fa-circle-xmark text-rose-600';
                    $iconBg = 'bg-rose-50 border border-rose-200';
                    $title = 'Akte Kelahiran Ditolak';
                    $message = "Pengajuan Akte Kelahiran an. {$birth->child_name} ditolak.";
                } elseif ($status === 'revision') {
                    $iconClass = 'fa-solid fa-triangle-exclamation text-amber-600';
                    $iconBg = 'bg-amber-50 border border-amber-200';
                    $title = 'Revisi Akte Kelahiran';
                    $message = "Pengajuan Akte Kelahiran an. {$birth->child_name} perlu revisi berkas.";
                } elseif (in_array($status, ['in_process', 'verified'])) {
                    $iconClass = 'fa-solid fa-arrows-rotate text-blue-600';
                    $iconBg = 'bg-blue-50 border border-blue-200';
                    $title = 'Akte Kelahiran Diproses';
                    $message = "Pengajuan Akte Kelahiran an. {$birth->child_name} sedang diproses.";
                } elseif ($status === 'picked_up') {
                    $iconClass = 'fa-solid fa-clipboard-check text-teal-600';
                    $iconBg = 'bg-teal-50 border border-teal-200';
                    $title = 'Akte Kelahiran Diambil';
                    $message = "Akte Kelahiran an. {$birth->child_name} sudah diambil.";
                } elseif ($status === 'archived') {
                    $iconClass = 'fa-solid fa-box-archive text-slate-600';
                    $iconBg = 'bg-slate-100 border border-slate-200';
                    $title = 'Akte Kelahiran Diarsipkan';
                    $message = "Pengajuan Akte Kelahiran an. {$birth->child_name} diarsipkan.";
                } else {
                    $iconClass = 'fa-solid fa-clock text-amber-600';
                    $iconBg = 'bg-amber-50 border border-amber-200';
                    $title = 'Akte Kelahiran Terkirim';
                    $message = "Pengajuan Akte Kelahiran an. {$birth->child_name} menunggu verifikasi.";
                }

                $notifications->push([
                    'id' => 'birth_' . $birth->id . '_' . $status . '_' . ($time ? Carbon::parse($time)->timestamp : '0'),
                    'category' => 'Akte Kelahiran',
                    'category_icon' => 'fa-solid fa-baby',
                    'category_color' => 'sky',
                    'type' => 'birth',
                    'status' => $status,
                    'status_label' => $statusLabel,
                    'status_badge_class' => $statusBadgeClass,
                    'icon_class' => $iconClass,
                    'icon_bg' => $iconBg,
                    'title' => $title,
                    'message' => $message,
                    'admin_note' => $adminNote,
                    'reference_no' => $birth->registration_no,
                    'time' => $time,
                    'formatted_time' => Carbon::parse($time)->locale('id')->diffForHumans(),
                    'url' => route('tracking.show', ['type' => 'birth', 'registrationNo' => $birth->registration_no]),
                    'url_label' => 'Lihat Detail Permohonan',
                ]);
            }
        } catch (\Throwable $e) {
            // Log fallback silently
        }

        // 3. Pengajuan Akte Kematian
        try {
            $deathSubmissions = DeathCertificate::where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if (!empty($user->family_card_no)) {
                    $q->orWhere('family_card_no', $user->family_card_no);
                }
            })->latest('updated_at')->get();

            foreach ($deathSubmissions as $death) {
                $status = $death->status;
                $time = $death->updated_at ?? $death->created_at;

                $statusLabel = $death->status_label;
                $statusBadgeClass = $death->status_badge_class;
                $adminNote = $death->rejection_note;

                if (in_array($status, ['ready_for_pickup', 'completed'])) {
                    $iconClass = 'fa-solid fa-circle-check text-emerald-600';
                    $iconBg = 'bg-emerald-50 border border-emerald-200';
                    $title = 'Surat Kematian Siap Diambil';
                    $message = "Surat Kematian an. {$death->deceased_name} siap diambil.";
                } elseif ($status === 'rejected') {
                    $iconClass = 'fa-solid fa-circle-xmark text-rose-600';
                    $iconBg = 'bg-rose-50 border border-rose-200';
                    $title = 'Surat Kematian Ditolak';
                    $message = "Pengajuan Surat Kematian an. {$death->deceased_name} ditolak.";
                } elseif ($status === 'revision') {
                    $iconClass = 'fa-solid fa-triangle-exclamation text-amber-600';
                    $iconBg = 'bg-amber-50 border border-amber-200';
                    $title = 'Revisi Surat Kematian';
                    $message = "Pengajuan Surat Kematian an. {$death->deceased_name} perlu revisi berkas.";
                } elseif (in_array($status, ['in_process', 'verified'])) {
                    $iconClass = 'fa-solid fa-arrows-rotate text-blue-600';
                    $iconBg = 'bg-blue-50 border border-blue-200';
                    $title = 'Surat Kematian Diproses';
                    $message = "Pengajuan Surat Kematian an. {$death->deceased_name} sedang diproses.";
                } elseif ($status === 'picked_up') {
                    $iconClass = 'fa-solid fa-clipboard-check text-teal-600';
                    $iconBg = 'bg-teal-50 border border-teal-200';
                    $title = 'Surat Kematian Diambil';
                    $message = "Surat Kematian an. {$death->deceased_name} sudah diambil.";
                } elseif ($status === 'archived') {
                    $iconClass = 'fa-solid fa-box-archive text-slate-600';
                    $iconBg = 'bg-slate-100 border border-slate-200';
                    $title = 'Surat Kematian Diarsipkan';
                    $message = "Pengajuan Surat Kematian an. {$death->deceased_name} diarsipkan.";
                } else {
                    $iconClass = 'fa-solid fa-clock text-amber-600';
                    $iconBg = 'bg-amber-50 border border-amber-200';
                    $title = 'Surat Kematian Terkirim';
                    $message = "Pengajuan Surat Kematian an. {$death->deceased_name} menunggu verifikasi.";
                }

                $notifications->push([
                    'id' => 'death_' . $death->id . '_' . $status . '_' . ($time ? Carbon::parse($time)->timestamp : '0'),
                    'category' => 'Akte Kematian',
                    'category_icon' => 'fa-solid fa-book-skull',
                    'category_color' => 'rose',
                    'type' => 'death',
                    'status' => $status,
                    'status_label' => $statusLabel,
                    'status_badge_class' => $statusBadgeClass,
                    'icon_class' => $iconClass,
                    'icon_bg' => $iconBg,
                    'title' => $title,
                    'message' => $message,
                    'admin_note' => $adminNote,
                    'reference_no' => $death->registration_no,
                    'time' => $time,
                    'formatted_time' => Carbon::parse($time)->locale('id')->diffForHumans(),
                    'url' => route('tracking.show', ['type' => 'death', 'registrationNo' => $death->registration_no]),
                    'url_label' => 'Lihat Detail Permohonan',
                ]);
            }
        } catch (\Throwable $e) {
            // Log fallback silently
        }

        // 4. Pengajuan Perubahan Profil / KK dan Update Langsung oleh Admin
        try {
            $profileRequests = ProfileUpdateRequest::where('user_id', $user->id)
                ->latest('updated_at')
                ->get();

            foreach ($profileRequests as $req) {
                $status = $req->status;
                $time = $req->processed_at ?? $req->updated_at ?? $req->created_at;
                $isAdminDirectUpdate = !empty($req->admin_notes) && str_contains($req->admin_notes, 'Pembaruan data resmi langsung oleh Administrator');

                $statusLabel = $req->status_label;
                $statusBadgeClass = $req->status_badge_class;
                $adminNote = $req->admin_notes;

                if ($isAdminDirectUpdate) {
                    $iconClass = 'fa-solid fa-user-gear text-teal-600';
                    $iconBg = 'bg-teal-50 border border-teal-200';
                    $title = 'Perubahan Data oleh Admin';
                    $message = 'Data profil / KK Anda diperbarui oleh admin.';
                } elseif ($status === 'approved') {
                    $iconClass = 'fa-solid fa-circle-check text-emerald-600';
                    $iconBg = 'bg-emerald-50 border border-emerald-200';
                    $title = 'Perubahan Profil Disetujui';
                    $message = 'Perubahan data profil & KK Anda disetujui.';
                } elseif ($status === 'rejected') {
                    $iconClass = 'fa-solid fa-circle-xmark text-rose-600';
                    $iconBg = 'bg-rose-50 border border-rose-200';
                    $title = 'Perubahan Profil Ditolak';
                    $message = 'Perubahan data profil & KK Anda ditolak.';
                } else {
                    $iconClass = 'fa-solid fa-clock text-amber-600';
                    $iconBg = 'bg-amber-50 border border-amber-200';
                    $title = 'Perubahan Profil Menunggu Verifikasi';
                    $message = 'Pengajuan perubahan profil & KK menunggu verifikasi.';
                }

                $notifications->push([
                    'id' => 'profile_' . $req->id . '_' . $status . '_' . ($time ? Carbon::parse($time)->timestamp : '0'),
                    'category' => $isAdminDirectUpdate ? 'Perubahan Data Admin' : 'Perubahan Profil & KK',
                    'category_icon' => 'fa-solid fa-user-pen',
                    'category_color' => 'indigo',
                    'type' => 'profile',
                    'status' => $status,
                    'status_label' => $statusLabel,
                    'status_badge_class' => $statusBadgeClass,
                    'icon_class' => $iconClass,
                    'icon_bg' => $iconBg,
                    'title' => $title,
                    'message' => $message,
                    'admin_note' => $adminNote,
                    'reference_no' => null,
                    'time' => $time,
                    'formatted_time' => Carbon::parse($time)->locale('id')->diffForHumans(),
                    'url' => route('profile.index'),
                    'url_label' => 'Buka Menu Profil',
                ]);
            }
        } catch (\Throwable $e) {
            // Log fallback silently
        }

        // Urutkan berdasarkan waktu paling baru
        return $notifications->sortByDesc(function ($item) {
            return $item['time'] ? Carbon::parse($item['time'])->timestamp : 0;
        })->values();
    }
}
