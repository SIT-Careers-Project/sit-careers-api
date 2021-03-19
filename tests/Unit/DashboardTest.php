<?php

namespace Tests\Unit;

use App\Traits\AnnouncementsExport;
use App\Traits\CompaniesExport;
use App\Traits\DashboardExport;
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

    public function test_export_companies_report_with_storage_failed_should_return_error_message()
    {
        $data = [];

        $response = $this->call('GET', 'api/dashboard/companies/export', $data);
        $expected = json_decode($response->content(), true);

        $assertion = [
            "start_date" => [
                "The start date field is required."
            ],
            "end_date" => [
                "The end date field is required."
            ]
        ];

        $response->assertStatus(400);
        $this->assertEquals($assertion, $expected);
    }

    public function test_export_announcements_report_with_storage_success_should_return_true()
    {
        Excel::fake();

        $data = [
            'start_date' => '2021-02-19',
            'end_date' => '2021-02-24'
        ];

        $response = $this->call('GET', 'api/dashboard/announcements/export', $data);

        $file_name = 'announcements'. '_' . Carbon::now()->format('Y-m-d-H-i-s') . '.csv';
        $path = '/reports/announcements/';
        $path_file_name = $path.$file_name;

        Excel::assertStored($path_file_name, 'minio', function(AnnouncementsExport $export)
        {
            return true;
        });
    }

    public function test_export_announcments_report_with_storage_failed_should_return_error_message()
    {
        $data = [];

        $response = $this->call('GET', 'api/dashboard/announcements/export', $data);
        $expected = json_decode($response->content(), true);

        $assertion = [
            "start_date" => [
                "The start date field is required."
            ],
            "end_date" => [
                "The end date field is required."
            ]
        ];

        $response->assertStatus(400);
        $this->assertEquals($assertion, $expected);
    }

    public function test_export_dashboard_report_with_storage_success_should_return_true()
    {
        Excel::fake();

        $data = [
            'start_date' => '2021-02-19',
            'end_date' => '2021-02-24'
        ];

        $response = $this->call('GET', 'api/dashboard/dashboard/export', $data);

        $file_name = 'dashboard'. '_' . Carbon::now()->format('Y-m-d-H-i-s') . '.xlsx';
        $path = '/reports/dashboard/';
        $path_file_name = $path.$file_name;

        Excel::assertStored($path_file_name, 'minio', function(DashboardExport $export)
        {
            return true;
        });
    }

    public function test_export_dashboard_report_with_storage_failed_should_return_error_message()
    {
        $data = [];

        $response = $this->call('GET', 'api/dashboard/dashboard/export', $data);
        $expected = json_decode($response->content(), true);

        $assertion = [
            "start_date" => [
                "The start date field is required."
            ],
            "end_date" => [
                "The end date field is required."
            ]
        ];

        $response->assertStatus(400);
        $this->assertEquals($assertion, $expected);
    }
}
