<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MOU;
use Faker\Generator as Faker;

use Faker\Provider\Uuid;

$factory->define(MOU::class, function (Faker $faker) {
    return [
        "mou_id" => Uuid::uuid(),
        "mou_type" => "ชนิด MOU",
        "mou_link" => "https://www.google.co.th",
        "start_date_mou" => "30 กันยายน 2563",
        "end_date_mou" => "30 กันยายน 2565"
    ];
});
