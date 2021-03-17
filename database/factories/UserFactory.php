<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;

use Faker\Generator as Faker;
use Faker\Provider\Uuid;

use App\Models\User;
use App\Models\Role;

$factory->define(User::class, function (Faker $faker) use ($factory) {
    $roleAdmin = Role::where('role_name', 'admin')->first();
    return [
        'role_id' => $roleAdmin->role_id,
        'user_id' => Uuid::uuid(),
        'username' => 'mild',
        'password' => '123',
        'first_name' => 'Tassaneeewan',
        'last_name' => 'Noita',
        'email' => 'testuser@gmail.com',
        'token' => 'testuser@gmail.com',
        'created_by' => ''
    ];
});
