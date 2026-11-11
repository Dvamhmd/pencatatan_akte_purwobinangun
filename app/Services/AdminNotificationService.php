<?php

namespace App\Services;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\ProfileUpdateRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AdminNotificationService
{
    /**
     * Mengambil seluruh pemberitahuan permohonan/pendaftaran yang perlu ditinjau admin
     * untuk ditampilkan langsung melalui portal website admin.
     *
     * @return Collection
     */
    public static function getNotificationsForAdmin(): Collection
    {
        $notifications = collect();

        // 1. Pengajuan Akte Kelahiran baru (menunggu verifikasi atau revisi berkas masuk)
        try {
            $births = BirthCertificate::where('is_archived', false)
                ->whereIn('status', ['pending', 'revision'])
                ->latest('updated_at')
                ->get();

            foreach ($births as $birth) {
                $isRevision = $birth->status === 'revision';
                $time = $birth->updated_at ?? $birth->created_at ?? now();

                $notifications->push([
                    'id' => 'admin_birth_' . $birth->id . '_' . $birth->status . '_' . Carbon::parse($time)->timestamp,
                    'category' => 'Akte Kelahiran',
                    'category_icon' => 'fa-solid fa-baby',
                    'type' => 'akte',
                    'status' => $birth->status,
                    'status_label' => $birth->status_label,
                    'status_badge_class' => $birth->status_badge_class,
                    'icon_class' => $isRevision ? 'fa-solid fa-file-pen text-amber-600' : 'fa-solid fa-baby text-teal-600',
                    'icon_bg' => $isRevision ? 'bg-amber-50 border border-amber-200' : 'bg-teal-50 border border-teal-200',
                    'title' => $isRevision
                        ? 'Revisi Akte Kelahiran'
                        : 'Pengajuan Akte Kelahiran Baru',
                    'message' => "An. <strong>" . e($birth->child_name) . "</strong> (Pemohon: " . e($birth->applicant_name) . ").",
                    'reference_no' => $birth->registration_no,
                    'time' => $time,
                    'formatted_time' => Carbon::parse($time)->locale('id')->diffForHumans(),
                    'url' => route('admin.birth.show', $birth->id),
                    'url_label' => 'Periksa Pengajuan',
                ]);
            }
        } catch (\Throwable $e) {
            // Abaikan jika tabel belum tersedia saat migrasi awal
        }

        // 2. Pengajuan Akte Kematian baru
        try {
            $deaths = DeathCertificate::where('is_archived', false)
                ->whereIn('status', ['pending', 'revision'])
                ->latest('updated_at')
                ->get();

            foreach ($deaths as $death) {
                $isRevision = $death->status === 'revision';
                $time = $death->updated_at ?? $death->created_at ?? now();

                $notifications->push([
                    'id' => 'admin_death_' . $death->id . '_' . $death->status . '_' . Carbon::parse($time)->timestamp,
                    'category' => 'Akte Kematian',
                    'category_icon' => 'fa-solid fa-book-skull',
                    'type' => 'akte',
                    'status' => $death->status,
                    'status_label' => $death->status_label,
                    'status_badge_class' => $death->status_badge_class,
                    'icon_class' => $isRevision ? 'fa-solid fa-file-pen text-amber-600' : 'fa-solid fa-book-skull text-rose-600',
                    'icon_bg' => $isRevision ? 'bg-amber-50 border border-amber-200' : 'bg-rose-50 border border-rose-200',
                    'title' => $isRevision
                        ? 'Revisi Akte Kematian'
                        : 'Pengajuan Akte Kematian Baru',
                    'message' => "An. <strong>" . e($death->deceased_name) . "</strong> (Pelapor: " . e($death->applicant_name) . ").",
                    'reference_no' => $death->registration_no,
                    'time' => $time,
                    'formatted_time' => Carbon::parse($time)->locale('id')->diffForHumans(),
                    'url' => route('admin.death.show', $death->id),
                    'url_label' => 'Periksa Pengajuan',
                ]);
            }
        } catch (\Throwable $e) {
            // Abaikan jika tabel belum tersedia saat migrasi awal
        }

        // 3. Pendaftaran Akun Warga baru (menunggu verifikasi)
        try {
            $citizens = User::where('role', 'warga')
                ->where('status', 'pending')
                ->latest('created_at')
                ->get();

            foreach ($citizens as $citizen) {
                $time = $citizen->created_at ?? now();

                $notifications->push([
                    'id' => 'admin_citizen_' . $citizen->id . '_' . Carbon::parse($time)->timestamp,
                    'category' => 'Akun Warga',
                    'category_icon' => 'fa-solid fa-user-check',
                    'type' => 'citizen',
                    'status' => 'pending',
                    'status_label' => 'Menunggu Verifikasi',
                    'status_badge_class' => 'bg-amber-100 text-amber-800 border-amber-300',
                    'icon_class' => 'fa-solid fa-user-clock text-amber-600',
                    'icon_bg' => 'bg-amber-50 border border-amber-200',
                    'title' => 'Pendaftaran Akun Baru',
                    'message' => "<strong>" . e($citizen->name) . "</strong> (NIK: " . e($citizen->nik) . ") menunggu verifikasi.",
                    'reference_no' => $citizen->nik,
                    'time' => $time,
                    'formatted_time' => Carbon::parse($time)->locale('id')->diffForHumans(),
                    'url' => route('admin.citizens.show', $citizen->id),
                    'url_label' => 'Verifikasi Akun',
                ]);
            }
        } catch (\Throwable $e) {
            // Abaikan jika tabel belum tersedia saat migrasi awal
        }

        // 4. Permohonan Perubahan Data Profil & KK (menunggu verifikasi)
        try {
            $profileRequests = ProfileUpdateRequest::with('user')
                ->where('status', 'pending')
                ->latest('updated_at')
                ->get();

            foreach ($profileRequests as $profileReq) {
                $time = $profileReq->updated_at ?? $profileReq->created_at ?? now();

                $notifications->push([
                    'id' => 'admin_profile_' . $profileReq->id . '_' . Carbon::parse($time)->timestamp,
                    'category' => 'Perubahan Data',
                    'category_icon' => 'fa-solid fa-user-pen',
                    'type' => 'profile',
                    'status' => 'pending',
                    'status_label' => 'Menunggu Verifikasi',
                    'status_badge_class' => 'bg-amber-100 text-amber-800 border-amber-300',
                    'icon_class' => 'fa-solid fa-user-pen text-indigo-600',
                    'icon_bg' => 'bg-indigo-50 border border-indigo-200',
                    'title' => 'Perubahan Data Warga',
                    'message' => "<strong>" . e($profileReq->name) . "</strong> mengajukan pembaruan profil / KK.",
                    'reference_no' => $profileReq->family_card_no ?: ($profileReq->user?->nik),
                    'time' => $time,
                    'formatted_time' => Carbon::parse($time)->locale('id')->diffForHumans(),
                    'url' => route('admin.profile_requests.show', $profileReq->id),
                    'url_label' => 'Tinjau Perubahan',
                ]);
            }
        } catch (\Throwable $e) {
            // Abaikan jika tabel belum tersedia saat migrasi awal
        }

        return $notifications->sortByDesc(function ($item) {
            return $item['time'] ? Carbon::parse($item['time'])->timestamp : 0;
        })->values();
    }
}
