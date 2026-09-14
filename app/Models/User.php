<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nip',
        'email',
        'password',
        'role',
        'satker_id',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Determine whether the user has the given role.
     */
    public function hasRoleName(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Get the satker that the user belongs to.
     */
    public function satker(): BelongsTo
    {
        return $this->belongsTo(Satker::class);
    }

    /**
     * Get the monev assignments for this user.
     */
    public function penugasanMonev(): HasMany
    {
        return $this->hasMany(PenugasanMonev::class, 'monev_user_id');
    }

    /**
     * Get the satker representative assignments for this user.
     */
    public function perwakilanSatker(): HasMany
    {
        return $this->hasMany(PerwakilanSatker::class);
    }

    /**
     * Get the in-app notifications for this user.
     */
    public function appNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }
}
