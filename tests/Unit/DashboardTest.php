<?php

namespace Tests\Unit;

use App\Traits\CompaniesExport;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_get_stats_success_should_return_status_200()
    {
        $this->get('api/dashboard/stats')->assertStatus(200);
    }

    public function test_get_company_types_success_should_return_status_200()
    {
        $this->get('api/dashboard/company-types')->assertStatus(200);
    }

    public function test_get_student_job_position_success_should_return_status_200()
    {
        $this->get('api/dashboard/students/job-positions')->assertStatus(200);
    }

    public function test_get_announcement_job_position_success_should_return_status_200()
    {
        $this->get('api/dashboard/announcements/job-positions')->assertStatus(200);
    }

    public function test_export_companies_report_with_storage_success_should_return_true()
    {
        Excel::fake();

        $data = [
            'start_date' => '2021-02-19',
            'end_date' => '2021-02-20'
        ];

        $response = $this->call('GET', 'api/dashboard/companies/export', $data);

        $file_name = 'companies'. '_' . Carbon::now()->format('Y-m-d-H-i-s') . '.csv';
        $path = '/reports/companies/';
        $path_file_name = $path.$file_name;

        Excel::assertStored($path_file_name, 'minio', function(CompaniesExport $export)
        {
            return true;
        });
    }
}
