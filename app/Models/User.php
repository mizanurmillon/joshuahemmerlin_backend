<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

      /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Return the primary key of the user (id)
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'provider',
        'provider_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'agree_to_terms' => 'boolean',
            'id' => 'integer',
        ];
    }

    // Relationship to the referrer
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    // Users referred by this user
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_id');
    }

    public function membership()
    {
        return $this->hasOne(Membership::class);
    }

    public function isMember(): bool
    {
        return (bool) $this->membership()->exists();
    }

    public function isExpired(): bool
    {
        return $this->membership && $this->membership->end_date < now();
    }

    public function logo()
    {
        return $this->hasOne(UploadLogo::class);
    }

    public function userRole()
    {
        return $this->hasOne(UserRole::class);
    }

    public function roleAccess()
    {
        return $this->hasOne(RoleAccess::class);
    }

}
