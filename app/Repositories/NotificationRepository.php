<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getNotificationByUserId($data)
    {
        $notification = Notification::where('user_id', $data['my_user_id'])->get();
        return $notification;
    }

    public function updateNotificationByUserId($data)
    {
        $notification = Notification::where('notification_id', $data['notification_id'])
            ->where('user_id', $data['my_user_id'])->first();

        if (!is_null($notification)) {
            $notification->read_at = $data['read_at'];
            $notification->save();
            return $notification->toArray();
        }

        return $notification;

    }
}
