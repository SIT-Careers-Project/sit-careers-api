<?php

use Illuminate\Database\Seeder;

use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions_data = [
            'access_company',
            'access_company_by_admin',
            'create_company',
            'update_company',
            'delete_company',
            'request_delete_company',
            'access_user',
            'access_user_by_admin',
            'create_user_by_admin',
            'create_user_by_manager',
            'update_user',
            'delete_user',
            'access_resume',
            'create_resume',
            'update_resume',
            'delete_resume',
            'access_announcement_resume_by_student',
            'access_announcement_resume_by_company',
            'access_announcement_resume_by_admin',
            'create_announcement_resume',
            'update_announcement_resume',
            'delete_announcement_resume',
            'access_academic_announcement',
            'access_academic_announcement_by_company',
            'create_academic_announcement',
            'update_academic_announcement',
            'delete_academic_announcement',
            'access_academic_banner',
            'create_academic_banner',
            'update_academic_banner',
            'delete_academic_banner',
            'access_dashboard',
            'access_dashboard_admin',
            'access_history',
            'access_notification',
            'update_notification',
            'delete_notification'
        ];

        foreach ($permissions_data as  $permission_data) {
            $permission = new Permission();
            $permission->permission_name = $permission_data;
            $permission->save();
        }
    }
}
