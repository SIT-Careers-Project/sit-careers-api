<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Announcement;
use App\Models\AnnouncementResume;
use App\Models\Resume;
use Faker\Generator as Faker;
use Faker\Provider\Uuid;

$factory->define(AnnouncementResume::class, function (Faker $faker) use ($factory) {
    return [
        'announcement_resume_id' => Uuid::uuid(),
        'announcement_id' => $factory->create(Announcement::class)->announcement_id,
        'resume_id' => $factory->create(Resume::class)->resume_id,
        'status' => '-',
        'note' => '-'
    ];
});
