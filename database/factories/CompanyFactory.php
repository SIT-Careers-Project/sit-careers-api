<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;

use Faker\Generator as Faker;
use Faker\Provider\Uuid;

use App\Models\Company;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'company_id' => Uuid::uuid(),
        'company_name_th' => 'บริษัท เทส จำกัด',
        'company_name_en' => 'Test COmpany',
        'description' => 'เป็นบริษัทพัฒนา software บริษัทใหญ่ อยู่เยอรมัน',
        'about_us' => 'อยากเท่ อยากเจ๋ง มาเข้าบริษัทนี้',
        'logo' => 'path/to/logo',
        'company_type' => 'Technology',
        'e_mail_coordinator' => 'test@gmail.com',
        'e_mail_manager' => 'company@gmail.com',
        'tel_no' => '0988882356',
        'phone_no' => '0298987645',
        'website' => 'http://test.com',
        'start_business_day' => 'จันทร์',
        'end_business_day' => 'ศุกร์',
        'start_business_time' => '09:00',
        'end_business_time' => '18:00',
    ];
});
