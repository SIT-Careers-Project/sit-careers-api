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
}
