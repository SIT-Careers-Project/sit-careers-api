<?php

namespace App\Repositories;


interface NotificationRepositoryInterface
{
    public function getNotificationByUserId($data);
    public function updateNotificationByUserId($data);
}
