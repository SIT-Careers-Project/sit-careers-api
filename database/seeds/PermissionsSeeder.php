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
            'create_user',
            'update_user',
            'delete_user',
            'access_resume',
            'access_resume_by_company',
            'access_resume_by_admin',
            'create_resume',
            'update_resume',
            'delete_resume',
            'access_academic_announcement',
            'access_academic_announcement_by_company',
            'create_academic_announcement',
            'update_academic_announcement',
            'delete_academic_announcement',
            'access_academic_application_by_student',
            'access_academic_application_by_company',
            'access_academic_application_by_admin',
            'create_academic_application',
            'update_academic_application',
            'delete_academic_application',
            'access_academic_banner',
            'create_academic_banner',
            'update_academic_banner',
            'delete_academic_banner',
            'access_dashboard',
            'access_dashboard_admin',
            'access_history'
        ];

        foreach ($permissions_data as  $permission_data) {
            $permission = new Permission();
            $permission->permission_name = $permission_data;
            $permission->save();
        }
    }
}
