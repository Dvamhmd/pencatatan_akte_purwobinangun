<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action_type',
        'module',
        'title',
        'description',
        'reference_no',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Catat aktivitas baru ke log database
     */
    public static function record(array $data): self
    {
        $user = Auth::user();

        $actionType = $data['action_type'] ?? $data['action'] ?? 'update';
        // Map alias modul jika dikirim dalam format bahasa lain
        $module = $data['module'] ?? 'general';
        $moduleMap = [
            'akte_kelahiran' => 'birth_certificate',
            'akte_kematian' => 'death_certificate',
            'akun_warga' => 'citizen',
            'perubahan_profil' => 'profile_update',
        ];
        if (isset($moduleMap[$module])) {
            $module = $moduleMap[$module];
        }

        $actionMap = [
            'pengajuan' => 'create',
            'setujui' => 'approve',
            'acc' => 'approve',
            'tolak' => 'reject',
            'ubah' => 'update',
            'arsip' => 'archive',
            'pulihkan' => 'restore',
            'hapus' => 'delete',
            'daftar_ulang' => 'create',
            'pendaftaran' => 'create',
            'batal' => 'delete',
        ];
        if (isset($actionMap[$actionType])) {
            $actionType = $actionMap[$actionType];
        }

        $userName = $data['user_name'] ?? $data['actor_name'] ?? ($user?->name ?? 'Sistem / Warga');
        $userRole = $data['user_role'] ?? $data['actor_role'] ?? ($user?->role ?? 'system');
        $userId = $data['user_id'] ?? $data['actor_id'] ?? $user?->id;
        
        $properties = $data['properties'] ?? $data['metadata'] ?? [];
        if (!is_array($properties)) {
            $properties = [];
        }
        if (isset($data['actor_nik'])) {
            $properties['actor_nik'] = $data['actor_nik'];
        }
        if (isset($data['user_agent'])) {
            $properties['user_agent'] = $data['user_agent'];
        }

        $title = $data['title'] ?? null;
        if (!$title) {
            $title = match ($actionType) {
                'create' => 'Pengajuan Baru',
                'approve' => 'Persetujuan / ACC Berkas',
                'reject' => 'Penolakan Berkas',
                'update' => 'Perubahan Data',
                'archive' => 'Pengarsipan Data',
                'restore' => 'Pemulihan Data Arsip',
                'delete' => 'Penghapusan Data',
                default => 'Aktivitas Sistem',
            };
        }

        $attributes = [
            'user_id' => $userId,
            'user_name' => $userName,
            'user_role' => $userRole,
            'action_type' => $actionType,
            'module' => $module,
            'title' => $title,
            'description' => $data['description'] ?? $title,
            'reference_no' => $data['reference_no'] ?? null,
            'subject_type' => $data['subject_type'] ?? null,
            'subject_id' => $data['subject_id'] ?? null,
            'properties' => !empty($properties) ? $properties : null,
            'ip_address' => $data['ip_address'] ?? request()?->ip(),
        ];

        return self::create($attributes);
    }

    /**
     * Backfill data historis dari data pengajuan dan verifikasi yang sudah ada jika log masih kosong
     */
    public static function backfillHistoricalData(): void
    {
        if (self::count() > 0) {
            return;
        }

        // 1. Akun Warga
        foreach (User::where('role', 'warga')->get() as $user) {
            self::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_role' => 'warga',
                'action_type' => 'create',
                'module' => 'auth',
                'title' => 'Pendaftaran Akun Warga Baru',
                'description' => 'Pendaftaran akun kependudukan baru.',
                'reference_no' => $user->nik,
                'subject_type' => User::class,
                'subject_id' => $user->id,
                'created_at' => $user->created_at,
                'updated_at' => $user->created_at,
            ]);

            if ($user->status === 'active' && $user->verified_at) {
                self::create([
                    'user_id' => null,
                    'user_name' => $user->verified_by ?: 'Petugas Pelayanan Kalurahan',
                    'user_role' => 'admin',
                    'action_type' => 'approve',
                    'module' => 'citizen',
                    'title' => 'Verifikasi Akun Warga Disetujui',
                    'description' => 'Persetujuan dan pengaktifan akun warga.',
                    'reference_no' => $user->nik,
                    'subject_type' => User::class,
                    'subject_id' => $user->id,
                    'created_at' => $user->verified_at,
                    'updated_at' => $user->verified_at,
                ]);
            } elseif ($user->status === 'archived' && $user->verified_at) {
                self::create([
                    'user_id' => null,
                    'user_name' => $user->verified_by ?: 'Petugas Pelayanan Kalurahan',
                    'user_role' => 'admin',
                    'action_type' => 'archive',
                    'module' => 'citizen',
                    'title' => 'Penghapusan / Pengarsipan Akun Warga',
                    'description' => 'Penghapusan akun warga dan pemindahan ke arsip database.' . ($user->rejection_reason ? ' Catatan: ' . $user->rejection_reason : ''),
                    'reference_no' => $user->nik,
                    'subject_type' => User::class,
                    'subject_id' => $user->id,
                    'created_at' => $user->verified_at,
                    'updated_at' => $user->verified_at,
                ]);
            }
        }

        // 2. Akte Kelahiran
        foreach (BirthCertificate::all() as $birth) {
            self::create([
                'user_id' => $birth->user_id,
                'user_name' => $birth->applicant_name ?: 'Warga Pemohon',
                'user_role' => 'warga',
                'action_type' => 'create',
                'module' => 'birth_certificate',
                'title' => 'Pengajuan Akte Kelahiran Baru',
                'description' => 'Pengajuan permohonan Akte Kelahiran baru.',
                'reference_no' => $birth->registration_no,
                'subject_type' => BirthCertificate::class,
                'subject_id' => $birth->id,
                'created_at' => $birth->created_at,
                'updated_at' => $birth->created_at,
            ]);

            if ($birth->status !== 'pending') {
                self::create([
                    'user_id' => null,
                    'user_name' => 'Petugas Pelayanan Kalurahan',
                    'user_role' => 'admin',
                    'action_type' => in_array($birth->status, ['picked_up', 'completed', 'ready_for_pickup']) ? 'approve' : ($birth->status === 'rejected' ? 'reject' : 'update'),
                    'module' => 'birth_certificate',
                    'title' => 'Pembaruan Status Akte Kelahiran',
                    'description' => "Pembaruan status permohonan Akte Kelahiran menjadi '{$birth->status_label}'.",
                    'reference_no' => $birth->registration_no,
                    'subject_type' => BirthCertificate::class,
                    'subject_id' => $birth->id,
                    'created_at' => $birth->updated_at,
                    'updated_at' => $birth->updated_at,
                ]);
            }
        }

        // 3. Akte Kematian
        foreach (DeathCertificate::all() as $death) {
            self::create([
                'user_id' => $death->user_id,
                'user_name' => $death->applicant_name ?: 'Warga Pemohon',
                'user_role' => 'warga',
                'action_type' => 'create',
                'module' => 'death_certificate',
                'title' => 'Pengajuan Akte Kematian Baru',
                'description' => 'Pengajuan permohonan Akte Kematian baru.',
                'reference_no' => $death->registration_no,
                'subject_type' => DeathCertificate::class,
                'subject_id' => $death->id,
                'created_at' => $death->created_at,
                'updated_at' => $death->created_at,
            ]);

            if ($death->status !== 'pending') {
                self::create([
                    'user_id' => null,
                    'user_name' => 'Petugas Pelayanan Kalurahan',
                    'user_role' => 'admin',
                    'action_type' => in_array($death->status, ['picked_up', 'completed', 'ready_for_pickup']) ? 'approve' : ($death->status === 'rejected' ? 'reject' : 'update'),
                    'module' => 'death_certificate',
                    'title' => 'Pembaruan Status Akte Kematian',
                    'description' => "Pembaruan status permohonan Akte Kematian menjadi '{$death->status_label}'.",
                    'reference_no' => $death->registration_no,
                    'subject_type' => DeathCertificate::class,
                    'subject_id' => $death->id,
                    'created_at' => $death->updated_at,
                    'updated_at' => $death->updated_at,
                ]);
            }
        }

        // 4. Perubahan Data KK
        foreach (ProfileUpdateRequest::all() as $req) {
            self::create([
                'user_id' => $req->user_id,
                'user_name' => $req->name ?: 'Warga Pemohon',
                'user_role' => 'warga',
                'action_type' => 'create',
                'module' => 'profile_update',
                'title' => 'Pengajuan Perubahan Data Kartu Keluarga',
                'description' => 'Pengajuan permohonan perubahan data Kartu Keluarga dan profil warga.',
                'reference_no' => $req->family_card_no,
                'subject_type' => ProfileUpdateRequest::class,
                'subject_id' => $req->id,
                'created_at' => $req->created_at,
                'updated_at' => $req->created_at,
            ]);
        }
    }

    public function getModuleLabelAttribute(): string
    {
        return match ($this->module) {
            'birth_certificate' => 'Akte Kelahiran',
            'death_certificate' => 'Akte Kematian',
            'citizen' => 'Akun Warga',
            'profile_update' => 'Perubahan Data KK',
            'auth' => 'Pendaftaran / Akun',
            default => ucfirst(str_replace('_', ' ', $this->module)),
        };
    }

    public function getModuleBadgeClassAttribute(): string
    {
        return match ($this->module) {
            'birth_certificate' => 'bg-teal-50 text-teal-800 border-teal-200',
            'death_certificate' => 'bg-rose-50 text-rose-800 border-rose-200',
            'citizen' => 'bg-amber-50 text-amber-800 border-amber-200',
            'profile_update' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
            'auth' => 'bg-sky-50 text-sky-800 border-sky-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action_type) {
            'create' => 'Pengajuan / Input Baru',
            'approve' => 'Disetujui / Terverifikasi',
            'reject' => 'Ditolak',
            'update' => 'Perubahan Data',
            'delete' => 'Dihapus',
            'archive' => 'Diarsipkan',
            'restore' => 'Dipulihkan',
            'login' => 'Masuk Sistem',
            default => ucfirst($this->action_type),
        };
    }

    public function getActionBadgeClassAttribute(): string
    {
        return match ($this->action_type) {
            'create' => 'bg-sky-100 text-sky-800 border-sky-300',
            'approve' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'reject' => 'bg-rose-100 text-rose-800 border-rose-300',
            'update' => 'bg-amber-100 text-amber-800 border-amber-300',
            'delete' => 'bg-red-100 text-red-800 border-red-300',
            'archive' => 'bg-slate-200 text-slate-800 border-slate-400',
            'restore' => 'bg-teal-100 text-teal-800 border-teal-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    public function getUserRoleBadgeClassAttribute(): string
    {
        return match ($this->user_role) {
            'admin' => 'bg-teal-100 text-[#0b7c89] border-teal-300 font-bold',
            'warga' => 'bg-blue-100 text-blue-800 border-blue-300 font-bold',
            default => 'bg-slate-100 text-slate-700 border-slate-300 font-medium',
        };
    }

    public function getActorNameAttribute(): ?string
    {
        return $this->user_name;
    }

    public function getActorRoleAttribute(): ?string
    {
        return $this->user_role;
    }

    public function getActorNikAttribute(): ?string
    {
        return $this->properties['actor_nik'] ?? null;
    }

    public function getMetadataAttribute(): ?array
    {
        return $this->properties;
    }

    public function getUserAgentAttribute(): ?string
    {
        return $this->properties['user_agent'] ?? null;
    }

    public function getDescriptionAttribute(?string $value): string
    {
        if (empty($value)) {
            return '';
        }

        $desc = $value;

        // Bersihkan pola "Admin (...) " atau "Petugas (...) " di awal teks
        $desc = preg_replace('/^(?:Admin|Petugas)(?:\s*\([^)]*\))?\s+/i', '', $desc);

        // Bersihkan pola "Warga [Nama] (mendaftarkan|mengajukan|memperbarui|membatalkan)" di awal teks
        $desc = preg_replace('/^Warga\s+[^,.\n]+?\s+(mendaftarkan|mengajukan|memperbarui|membatalkan)/i', '$1', $desc);

        // Bersihkan pola "(NIK: ...)", "(No. Registrasi: ...)", "(No. KK: ...)"
        $desc = preg_replace('/\s*\((?:NIK|No\.?\s*Registrasi|No\.?\s*KK):?\s*[^)]+\)/i', '', $desc);
        $desc = preg_replace('/\s+dengan NIK\s+\d+/i', '', $desc);
        $desc = preg_replace('/\s+dan No\.?\s*KK\s+\d+/i', '', $desc);

        return ucfirst(trim($desc));
    }
}
