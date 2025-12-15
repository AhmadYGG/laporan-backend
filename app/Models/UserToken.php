<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $table = 'user_tokens';

    protected $fillable = [
        'user_id',
        'role',
        'user_agent',
        'ip_address',
        'refresh_token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
