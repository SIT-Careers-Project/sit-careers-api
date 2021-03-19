<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'user_id' => Str::uuid(),
            'username' => 'user_test_01',
            'password' => '$2y$10$O893eh1lmXBh4wJP8rNCt.vBzO48VVIqBrK5dBlMEUeOM5bJFcObG',
            'first_name' => 'User',
            'last_name' => 'Test',
            'email' => 'test@mail.com'
        ]);
    }
}
