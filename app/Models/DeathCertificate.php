<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeathCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_no',
        'family_card_no',
        'deceased_nik',
        'deceased_name',
        'gender',
        'birth_date',
        'religion',
        'padukuhan',
        'rt',
        'rw',
        'death_date',
        'death_time',
        'death_place',
        'cause_of_death',
        'reported_by_title',
        'applicant_nik',
        'applicant_name',
        'applicant_phone',
        'applicant_relation',
        'witness_nik',
        'witness_name',
        'doc_death_statement',
        'doc_family_card',
        'doc_deceased_ktp',
        'doc_applicant_ktp',
        'status',
        'rejection_note',
        'processed_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function generateRegistrationNo(): string
    {
        $prefix = 'AKM-' . date('Ymd');
        $lastRecord = self::where('registration_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->registration_no, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . '-' . $newNumber;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Berkas Terverifikasi',
            'in_process' => 'Sedang Diproses Kelurahan',
            'completed' => 'Selesai / Siap Diambil',
            'rejected' => 'Ditolak / Perlu Perbaikan',
            default => 'Diajukan',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'verified' => 'bg-blue-100 text-blue-800 border-blue-300',
            'in_process' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'rejected' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
