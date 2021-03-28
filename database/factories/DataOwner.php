<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\DataOwner;
use App\Models\Company;
use App\Models\User;
use Faker\Generator as Faker;

use Faker\Provider\Uuid;

$factory->define(DataOwner::class, function (Faker $faker) use ($factory) {
    return [
        "data_owner_id" => Uuid::uuid(),
        "company_id" => $factory->create(Company::class)->company_id,
        "user_id" => $factory->create(User::class)->user_id,
        "request_delete" => false,
    ];
});