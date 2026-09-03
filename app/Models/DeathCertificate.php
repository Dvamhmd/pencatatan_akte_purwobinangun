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
        'is_archived',
        'rejection_note',
        'processed_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
        'is_archived' => 'boolean',
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
            'in_process', 'verified' => 'Sedang Diproses',
            'revision' => 'Revisi Berkas',
            'rejected' => 'Dibatalkan',
            'ready_for_pickup', 'completed' => 'Siap Diambil',
            'picked_up' => 'Sudah Diambil',
            'archived' => 'Diarsipkan',
            default => 'Diajukan',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'in_process', 'verified' => 'bg-blue-100 text-blue-800 border-blue-300',
            'revision' => 'bg-orange-100 text-orange-800 border-orange-300',
            'rejected' => 'bg-rose-100 text-rose-800 border-rose-300',
            'ready_for_pickup', 'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'picked_up', 'archived' => 'bg-slate-100 text-slate-800 border-slate-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInProcess(): bool
    {
        return in_array($this->status, ['in_process', 'verified']);
    }

    public function isRevision(): bool
    {
        return $this->status === 'revision';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isReadyForPickup(): bool
    {
        return in_array($this->status, ['ready_for_pickup', 'completed']);
    }

    public function isPickedUp(): bool
    {
        return in_array($this->status, ['picked_up', 'archived']);
    }

    public function isArchived(): bool
    {
        return $this->is_archived || in_array($this->status, ['picked_up', 'archived']);
    }
}
