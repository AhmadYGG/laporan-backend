<?php

namespace App\Repositories\Notification;

use App\Models\Notification;

class NotificationRepository
{
    public function createNotification(array $data)
    {
        return Notification::create($data);
    }

    public function getUserNotifications($userId, array $params)
    {
        $query = Notification::where('user_id', $userId)
            ->with('report:id,title,status')
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        $perPage = $params['per_page'] ?? 10;
        $page = $params['page'] ?? 1;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        
        if (!$notification) {
            return false;
        }

        $notification->status = true;
        $notification->save();

        return true;
    }

    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('status', false)
            ->update(['status' => true]);
    }

    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('status', false)
            ->count();
    }

    public function getNotificationById($notificationId)
    {
        return Notification::find($notificationId);
    }
}
