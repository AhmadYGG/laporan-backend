<?php

namespace App\Services;

use App\Repositories\Notification\NotificationRepository;

class NotificationService
{
    protected $repo;

    public function __construct(NotificationRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createNotification($userId, $reportId, $message)
    {
        $data = [
            'user_id' => $userId,
            'report_id' => $reportId,
            'message' => $message,
            'status' => false, // unread by default
        ];

        return $this->repo->createNotification($data);
    }

    public function getUserNotifications($userId, array $params)
    {
        $paginator = $this->repo->getUserNotifications($userId, $params);

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

    public function markAsRead($userId, $notificationId)
    {
        $notification = $this->repo->getNotificationById($notificationId);

        if (!$notification) {
            abort(404, 'Notification not found');
        }

        // Check ownership
        if ($notification->user_id !== $userId) {
            abort(403, 'Unauthorized');
        }

        $result = $this->repo->markAsRead($notificationId);

        if (!$result) {
            abort(500, 'Failed to mark notification as read');
        }

        return true;
    }

    public function markAllAsRead($userId)
    {
        return $this->repo->markAllAsRead($userId);
    }

    public function getUnreadCount($userId)
    {
        return $this->repo->getUnreadCount($userId);
    }
}
