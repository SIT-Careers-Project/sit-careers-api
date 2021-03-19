<?php

use Illuminate\Database\Seeder;

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
        $roleAdmin = Role::where('role_name', 'admin')->first();
        DB::table('users')->insert([
            'user_id' => Str::uuid(),
            'role_id' => $roleAdmin->role_id,
            'username' => 'user_test_01',
            'password' => '$2y$10$O893eh1lmXBh4wJP8rNCt.vBzO48VVIqBrK5dBlMEUeOM5bJFcObG',
            'first_name' => 'User',
            'last_name' => 'Test',
            'created_by' => '-',
            'email' => 'userTest@mail.sit.coll.com'
        ]);
    }
}
