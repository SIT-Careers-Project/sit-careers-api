<?php

namespace App\Traits;

use App\Models\Announcement;
use App\Models\Company;
use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class DashboardExport implements WithMultipleSheets
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function sheets(): array
    {
        $sheets = [
            new DashBoardStats($this->request),
            new DashboardCompanyTypes($this->request),
            new DashboardAnnouncments($this->request)
        ];

        return $sheets;
    }
}

class DashBoardStats implements FromCollection, WithHeadings, WithTitle
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $data = $this->request;

        $start_date = $data['start_date']." 00:00:00";
        $end_date = $data['end_date']." 23:59:59";

        $companies = Company::join('mou', 'mou.company_id', '=', 'companies.company_id')
            ->join('addresses', 'addresses.company_id', '=', 'companies.company_id')
            ->where('addresses.address_type', '=', 'company')
            ->whereBetween('companies.created_at', [$start_date, $end_date])
            ->count();

        $announcements = Announcement::join('addresses', 'addresses.address_id', '=', 'announcements.address_id')
            ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
            ->join('job_types', 'job_types.announcement_id', '=', 'announcements.announcement_id')
            ->join('job_positions', 'job_positions.job_position_id', '=', 'announcements.job_position_id')
            ->where('addresses.address_type', 'announcement')
            ->whereBetween('announcements.created_at', [$start_date, $end_date])
            ->count();

        $users = User::join('roles', 'roles.role_id', '=', 'users.role_id')
            ->select('users.email', 'roles.role_name')
            ->whereBetween('users.created_at', [$start_date, $end_date])
            ->count();

        $stats = array([
            'count_all_companies' => $companies,
            'count_all_announcements' => $announcements,
            'count_all_users' => $users
        ]);

        return collect($stats);
    }

    public function headings() : array
    {
        return [
            'count_all_companies',
            'count_all_announcements',
            'count_all_users'
        ];
    }

    public function title(): string
    {
        return 'Statistic';
    }
}

class DashboardCompanyTypes implements FromCollection, WithHeadings, WithTitle
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $data = $this->request;

        $start_date = $data['start_date']." 00:00:00";
        $end_date = $data['end_date']." 23:59:59";

        $company_types = Company::join('mou', 'mou.company_id', '=', 'companies.company_id')
            ->join('addresses', 'addresses.company_id', '=', 'companies.company_id')
            ->where('addresses.address_type', '=', 'company')
            ->whereBetween('companies.created_at', [$start_date, $end_date])
            ->selectRaw('companies.company_type, count(company_type)')
            ->groupBy('company_type')
            ->get();

        return $company_types;
    }

    public function headings() : array
    {
        return [
            'company_types',
            'count_company_types',
        ];
    }

    public function title(): string
    {
        return 'CompanyTypes';
    }
}

class DashboardAnnouncments implements FromCollection, WithHeadings, WithTitle
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $data = $this->request;

        $start_date = $data['start_date']." 00:00:00";
        $end_date = $data['end_date']." 23:59:59";

        $announcement_job_positions = Announcement::join('job_positions', 'job_positions.job_position_id', '=', 'announcements.job_position_id')
            ->selectRaw('job_positions.job_position, count(job_position)')
            ->groupBy('job_position')
            ->whereBetween('announcements.created_at', [$start_date, $end_date])
            ->get();

        return $announcement_job_positions;
    }

    public function headings() : array
    {
        return [
            'job_positions',
            'count_job_positions',
        ];
    }

    public function title(): string
    {
        return 'AnnouncementsJobPosition';
    }
}
