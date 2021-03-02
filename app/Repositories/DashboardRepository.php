<?php

namespace App\Repositories;

use App\Models\Announcement;
use App\Models\Company;
use App\Models\User;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getStats()
    {
        $companies = Company::join('mou', 'mou.company_id', '=', 'companies.company_id')
            ->join('addresses', 'addresses.company_id', '=', 'companies.company_id')
            ->where('addresses.address_type', '=', 'company')
            ->count();

        $announcements = Announcement::join('addresses', 'addresses.address_id', '=', 'announcements.address_id')
            ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
            ->join('job_types', 'job_types.announcement_id', '=', 'announcements.announcement_id')
            ->join('job_positions', 'job_positions.job_position_id', '=', 'announcements.job_position_id')
            ->where('addresses.address_type', 'announcement')
            ->count();

        $users = User::join('roles', 'roles.role_id', '=', 'users.role_id')
            ->select('users.email', 'roles.role_name')
            ->count();

        $stats = array(
            'count_all_companies' => $companies,
            'count_all_announcements' => $announcements,
            'count_all_users' => $users
        );

        return $stats;
    }
}

