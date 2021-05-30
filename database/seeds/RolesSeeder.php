<?php

use Illuminate\Database\Seeder;

use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles_data = [
            'other',
            'student',
            'manager',
            'coordinator',
            'admin'
        ];

        foreach ($roles_data as  $role_data) {
            $role = new Role();
            $role->role_name = $role_data;
            $role->save();
        }
    }
}
