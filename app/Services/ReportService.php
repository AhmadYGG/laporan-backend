<?php

namespace App\Services;

use App\Repositories\Report\ReportRepository;

class ReportService
{
    protected $repo;

    public function __construct(ReportRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createReportService($user, array $data)
    {
        $data['user_id'] = $user->id;

        if (isset($data['photo'])) {
            $data['photo_path'] = $data['photo']->store('reports');
        }

        return $this->repo->createReport($data);
    }

    public function updateReportService($user, $id, array $data)
    {
        $report = $this->repo->findByIdAndUser($id, $user->id);

        if (!$report) {
            abort(404, 'Report not found');
        }

        if (isset($data['photo'])) {
            $data['photo_path'] = $data['photo']->store('reports');
        }

        return $this->repo->updateReport($report, $data);
    }

    public function showReportService($user, $id)
    {
        if ($user->role === 'admin') {
            $report = $this->repo->findById($id);

            if (!$report) {
                abort(404, 'Report not found');
            }

            return $report;
        }

        $report = $this->repo->findByIdAndUser($id, $user->id);

        if (!$report) {
            abort(404, 'Report not found');
        }

        return $report;
    }

    public function indexReportService(array $params)
    {
        $user = $params['user'];

        if ($user->role === 'admin') {
            $paginator = $this->repo->getReportsForAdmin($params);
        }
        else {
            $paginator = $this->repo->getReportsForUser($params);
        }

        return [
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ];
    }
}
