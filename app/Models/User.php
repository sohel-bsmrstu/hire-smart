<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

     const MEMBER_TYPE_ADMIN = "admin";
    const MEMBER_TYPE_EMPLOYEE = "employer";
    const MEMBER_TYPE_CANDIDATE = "candidate";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Added role
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
        ];
    }

    /**
     * Get the jobs posted by the user (if employer).
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Get the applications made by the user (if candidate).
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Check user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === self::MEMBER_TYPE_ADMIN;
    }

    /**
     * Check user is employee
     *
     * @return bool
     */
    public function isEmployer(): bool
    {
        return $this->role === self::MEMBER_TYPE_EMPLOYEE;
    }

    /**
     * Check user is candidate
     *
     * @return bool
     */
    public function isCandidate(): bool
    {
        return $this->role === self::MEMBER_TYPE_CANDIDATE;
    }
}
