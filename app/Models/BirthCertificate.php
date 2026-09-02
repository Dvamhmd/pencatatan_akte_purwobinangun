<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BirthCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_no',
        'child_name',
        'gender',
        'birth_place',
        'birth_date',
        'birth_time',
        'birth_type',
        'birth_order',
        'birth_helper',
        'birth_place_type',
        'weight_kg',
        'length_cm',
        'father_nik',
        'father_name',
        'father_birth_date',
        'father_job',
        'mother_nik',
        'mother_name',
        'mother_birth_date',
        'mother_job',
        'applicant_nik',
        'applicant_name',
        'applicant_phone',
        'applicant_relation',
        'address',
        'padukuhan',
        'rt',
        'rw',
        'doc_birth_cert',
        'doc_family_card',
        'doc_marriage_cert',
        'doc_parents_ktp',
        'doc_witness_ktp',
        'status',
        'rejection_note',
        'processed_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'father_birth_date' => 'date',
        'mother_birth_date' => 'date',
    ];

    public static function generateRegistrationNo(): string
    {
        $prefix = 'AKL-' . date('Ymd');
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
