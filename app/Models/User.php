<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'nik', 'email_phone', 'name', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    // JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'user_id' => $this->id,
            'username' => $this->name,
            'email_phone' => $this->email_phone,
        ];
    }

    // Mutator untuk hashing password
    public function setPasswordAttribute($value)
    {
        if ($value !== null && \Illuminate\Support\Facades\Hash::needsRehash($value)) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    // Relationships
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function reportLogs()
    {
        return $this->hasMany(ReportLog::class, 'changed_by');
    }
}
