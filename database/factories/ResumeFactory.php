<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Faker\Provider\Uuid;

use App\Models\Resume;
use App\Models\User;


$factory->define(Resume::class, function (Faker $faker) use ($factory) {
    return [
        'student_id' => $factory->create(User::class)->user_id,
        'resume_date' => '2021-02-04',
        'name_title' => 'นาย',
        'first_name' => 'ชาเขียว',
        'last_name' => 'มัทฉะ',
        'curriculum' => 'IT',
        'year' => '4',
        'tel_no' => '0956787294',
        'email' => 'mild@gmail.com',
        'resume_link' => 'https://mild-resume.netlify.com/',
        'path_file' => '-',
        'university_name' => 'มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าธนบุรี'
    ];
});
