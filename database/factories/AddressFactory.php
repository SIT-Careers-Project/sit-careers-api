<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Address;
use Faker\Generator as Faker;

use Faker\Provider\Uuid;

$factory->define(Address::class, function (Faker $faker) {
    return [
        "address_id" => Uuid::uuid(),
        "address_one" => "999/2 หอพักสุรีแมนชั่น",
        "address_two" => "-",
        "lane" => "2",
        "road" => "วิภาวดีรังสิต",
        "sub_district" => "ดินแดง",
        "district" => "ดินแดง",
        "province" => "กรุงเทพ",
        "postal_code" => "10400",
    ];
});
