<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Faker\Provider\Uuid;

use App\Models\JobPosition;

$factory->define(JobPosition::class, function (Faker $faker) {
    return [
        'job_position_id' => Uuid::uuid(),
        'job_position' => 'Software Engineer'
    ];
});
