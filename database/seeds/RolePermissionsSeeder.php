<?php

use Illuminate\Database\Seeder;

use App\Models\RolePermission;
use App\Models\Permission;
use App\Models\Role;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdmin = Role::where('role_name', 'admin')->first();
        $permissions = Permission::all();

        foreach ($permissions as  $permission) {
            $rolePermission = new RolePermission();
            $rolePermission->permission_id = $permission->permission_id;
            $rolePermission->role_id = $roleAdmin->role_id;
            $rolePermission->save();
        }

        $roleStudent = Role::where('role_name', 'student')->first();
        $permissionStudent = [
            'access_company',
            'access_academic_announcement',
            'access_dashboard',
            'access_resume',
            'create_resume',
            'update_resume',
            'access_announcement_resume_by_student',
            'create_announcement_resume',
            'access_notification',
            'update_notification'
        ];

        foreach ($permissionStudent as  $permission) {
            $permissionId = Permission::where('permission_name', $permission)->select('permission_id')->get();

            $rolePermission = new RolePermission();
            $rolePermission->permission_id = $permissionId[0]['permission_id'];
            $rolePermission->role_id = $roleStudent->role_id;
            $rolePermission->save();
        }

        $roleCompany = Role::where('role_name', 'manager')->first();
        $permissionManager = [
            'access_company',
            'update_company',
            'request_delete_company',
            'access_user',
            'create_user_by_manager',
            'update_user',
            'access_academic_announcement',
            'access_academic_announcement_by_company',
            'create_academic_announcement',
            'update_academic_announcement',
            'delete_academic_announcement',
            'access_dashboard',
            'access_announcement_resume_by_company',
            'update_announcement_resume',
            'access_notification',
            'update_notification',
        ];

        foreach ($permissionManager as  $permission) {
            $permissionId = Permission::where('permission_name', $permission)->select('permission_id')->get();

            $rolePermission = new RolePermission();
            $rolePermission->permission_id = $permissionId[0]['permission_id'];
            $rolePermission->role_id = $roleCompany->role_id;
            $rolePermission->save();
        }

        $roleCompany = Role::where('role_name', 'coordinator')->first();
        $permissionCoordinator = [
            'access_company',
            'update_company',
            'access_user',
            'update_user',
            'access_academic_announcement',
            'access_academic_announcement_by_company',
            'create_academic_announcement',
            'update_academic_announcement',
            'delete_academic_announcement',
            'access_dashboard',
            'access_announcement_resume_by_company',
            'update_announcement_resume',
            'access_notification',
            'update_notification',
        ];

        foreach ($permissionCoordinator as  $permission) {
            $permissionId = Permission::where('permission_name', $permission)->select('permission_id')->get();

            $rolePermission = new RolePermission();
            $rolePermission->permission_id = $permissionId[0]['permission_id'];
            $rolePermission->role_id = $roleCompany->role_id;
            $rolePermission->save();
        }
    }
}
