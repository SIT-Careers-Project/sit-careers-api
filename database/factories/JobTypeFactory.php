<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Faker\Provider\Uuid;

use App\Models\JobType;

$factory->define(JobType::class, function (Faker $faker) {
    return [
        'job_id' => Uuid::uuid(),
        'job_type' => 'WiL'
    ];
});
