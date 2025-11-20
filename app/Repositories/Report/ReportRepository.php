<?php

namespace App\Repositories\Report;

use App\Models\Report;

class ReportRepository
{
    public function createReport(array $data)
    {
        return Report::create($data);
    }

    public function updateReport(Report $report, array $data)
    {
        $report->update($data);
        return $report;
    }

    public function findByIdAndUser($id, $userId)
    {
        return Report::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function findById($id)
    {
        return Report::find($id);
    }

    public function getReportsForUser(array $params)
    {
        $query = Report::where('user_id', $params['user']->id);

        if (!empty($params['search'])) {
            $query->where('title', 'like', "%{$params['search']}%");
        }

        if (!empty($params['periode_start']) && !empty($params['periode_end'])) {
            $query->whereBetween('created_at', [
                $params['periode_start'],
                $params['periode_end'],
            ]);
        }

        return $query->paginate($params['per_page']);
    }

    public function getReportsForAdmin(array $params)
    {
        $query = Report::query();

        if (!empty($params['search'])) {
            $query->where('title', 'like', "%{$params['search']}%");
        }

        if (!empty($params['periode_start']) && !empty($params['periode_end'])) {
            $query->whereBetween('created_at', [
                $params['periode_start'],
                $params['periode_end'],
            ]);
        }

        return $query->paginate($params['per_page']);
    }
}
