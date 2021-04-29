<?php

namespace App\Http\RulesValidation;

trait NotificationRules
{
    private $rulesUpdateNotification = [
        'notification_id' => 'required|string',
    ];
}
