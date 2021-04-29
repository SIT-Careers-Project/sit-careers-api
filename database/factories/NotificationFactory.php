<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Notification;
use App\Models\User;
use Faker\Generator as Faker;
use Faker\Provider\Uuid;

$factory->define(Notification::class, function (Faker $faker) use ($factory){
    return [
        'notification_id' => Uuid::uuid(),
        'user_id' => $factory->create(User::class)->user_id,
        'message' => 'คุณชาเขียว ส่งคำขอสมัครงานของบริษัท Test Factory ในหน้าประกาศ รับสมัครงานตำแหน่ง Factory บริษัท เทสดาต้า จำกัด',
        'url' => '-',
        'read_at' => null
    ];
});
