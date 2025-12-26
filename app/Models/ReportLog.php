<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportLog extends Model
{
    protected $fillable = [
        'report_id',
        'changed_by',
        'new_status',
        'notes',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
