<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Faker\Generator as Faker;
use Faker\Provider\Uuid;

use App\Models\Announcement;
use App\Models\Address;
use App\Models\Company;
use App\Models\JobPosition;


$factory->define(Announcement::class, function (Faker $faker) use ($factory) {
    return [
        'announcement_id' => Uuid::uuid(),
        'company_id' => $factory->create(Company::class)->company_id,
        'address_id' => $factory->create(Address::class)->address_id,
        'announcement_title' => 'รับสมัคร Software Engineer',
        'job_description' => 'มาสมัครงานกับเรา ได้ทำทุกอย่างที่อยากทำ',
        'job_position_id' => $factory->create(JobPosition::class)->job_position_id,
        'property' => 'คนดี ก็เพียงพอ',
        'picture' => 'path/picture',
        'start_date' => '2021-01-10 13:00:00',
        'end_date' => Carbon::now()->addDays(5),
        'salary' => '28,000',
        'welfare' => 'มีเงินเดือนให้',
        'status' => 'OPEN',
        'start_business_day' => 'จันทร์',
        'end_business_day' => 'ศุกร์',
        'start_business_time' => '09:00',
        'end_business_time' => '18:00',
    ];
});
