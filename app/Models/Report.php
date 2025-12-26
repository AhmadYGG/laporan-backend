<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'photo_path',
        'status', // pending, in_progress, done, rejected
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(ReportLog::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
