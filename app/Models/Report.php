<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location', // format: "lat,lng"
        'photo_path',
        'status', // pending, in_progress, done, rejected
    ];

    /**
     * Get latitude from location
     */
    public function getLatitudeAttribute()
    {
        if (!$this->location || !str_contains($this->location, ',')) {
            return null;
        }
        return (float) explode(',', $this->location)[0];
    }

    /**
     * Get longitude from location
     */
    public function getLongitudeAttribute()
    {
        if (!$this->location || !str_contains($this->location, ',')) {
            return null;
        }
        return (float) explode(',', $this->location)[1];
    }

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
