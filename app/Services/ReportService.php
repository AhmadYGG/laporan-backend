<?php

namespace App\Services;

use App\Repositories\Report\ReportRepository;
use App\Repositories\ReportLog\ReportLogRepository;

class ReportService
{
    protected $repo;
    protected $logRepo;
    protected $notificationService;

    public function __construct(
        ReportRepository $repo,
        ReportLogRepository $logRepo,
        NotificationService $notificationService
    ) {
        $this->repo = $repo;
        $this->logRepo = $logRepo;
        $this->notificationService = $notificationService;
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

    public function updateReportStatusService($adminUser, $reportId, $newStatus, $notes = null)
    {
        // Only admin can update status
        if ($adminUser->role !== 'admin') {
            abort(403, 'Unauthorized. Only admin can update report status.');
        }

        $report = $this->repo->findById($reportId);

        if (!$report) {
            abort(404, 'Report not found');
        }

        $oldStatus = $report->status;

        // Update report status
        $this->repo->updateReport($report, ['status' => $newStatus]);

        // Create log entry
        $this->logRepo->createLog([
            'report_id' => $reportId,
            'changed_by' => $adminUser->id,
            'new_status' => $newStatus,
            'notes' => $notes,
        ]);

        // Generate notification message based on status
        $message = $this->generateNotificationMessage($newStatus, $report->title);

        // Send notification to report owner
        $this->notificationService->createNotification(
            $report->user_id,
            $reportId,
            $message
        );

        return $report->fresh();
    }

    private function generateNotificationMessage($status, $reportTitle)
    {
        $messages = [
            'done' => "Laporan '{$reportTitle}' telah disetujui dan diselesaikan.",
            'rejected' => "Laporan '{$reportTitle}' ditolak. Silakan periksa kembali laporan Anda.",
            'in_progress' => "Laporan '{$reportTitle}' sedang dalam proses penanganan.",
            'pending' => "Laporan '{$reportTitle}' dikembalikan ke status pending.",
        ];

        return $messages[$status] ?? "Status laporan '{$reportTitle}' telah diperbarui menjadi {$status}.";
    }
}
