<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nik',
        'family_card_no',
        'doc_family_card',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'rt',
        'rw',
        'phone',
        'family_relationship',
        'status',
        'rejection_reason',
        'verified_at',
        'verified_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isWarga(): bool
    {
        return $this->role === 'warga';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Aktif / Terverifikasi',
            'pending' => 'Menunggu Verifikasi',
            'rejected' => 'Ditolak',
            'archived' => 'Diarsipkan',
            default => 'Belum Terverifikasi',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'rejected' => 'bg-rose-100 text-rose-800 border-rose-300',
            'archived' => 'bg-slate-200 text-slate-800 border-slate-400',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    public function birthCertificates(): HasMany
    {
        return $this->hasMany(BirthCertificate::class, 'user_id');
    }

    public function deathCertificates(): HasMany
    {
        return $this->hasMany(DeathCertificate::class, 'user_id');
    }

    public function familyMembers(): HasMany
    {
        return $this->hasMany(FamilyMember::class, 'user_id');
    }

    public function profileUpdateRequests(): HasMany
    {
        return $this->hasMany(ProfileUpdateRequest::class, 'user_id');
    }

    public function latestPendingProfileRequest(): ?ProfileUpdateRequest
    {
        return $this->profileUpdateRequests()->where('status', 'pending')->latest()->first();
    }
}
