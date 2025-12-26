<?php

namespace App\Repositories\ReportLog;

use App\Models\ReportLog;

class ReportLogRepository
{
    public function createLog(array $data)
    {
        return ReportLog::create($data);
    }

    public function getLogsByReportId($reportId)
    {
        return ReportLog::where('report_id', $reportId)
            ->with('changedBy:id,name')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
