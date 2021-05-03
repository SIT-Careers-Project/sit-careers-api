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

    public function test_export_companies_report_success_should_return_true()
    {
        Excel::fake();

        $data = [
            'start_date' => '2021-02-19',
            'end_date' => '2021-02-20',
            'name_reports' => ["company"]
        ];

        $response = $this->postJson('api/dashboard/report', $data);
        $file_name = 'companies'. '_' . $data['start_date'] . '_' . $data['end_date'] . '.xlsx';

        Excel::assertDownloaded($file_name, function(CompaniesExport $export)
        {
            return true;
        });
    }

    public function test_export_announcements_report_success_should_return_true()
    {
        Excel::fake();

        $data = [
            'start_date' => '2021-03-19',
            'end_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'name_reports' => ["announcement"]
        ];

        $response = $this->postJson('api/dashboard/report', $data);

        $file_name = 'announcements'. '_' . $data['start_date'] . '_' . $data['end_date'] . '.xlsx';

        Excel::assertDownloaded($file_name, function(AnnouncementsExport $export)
        {
            return true;
        });
    }


    public function test_export_dashboard_report_success_should_return_true()
    {
        Excel::fake();

        $data = [
            'start_date' => '2021-02-19',
            'end_date' => '2021-02-24',
            'name_reports' => ["dashboard"]
        ];

        $response = $this->postJson('api/dashboard/report', $data);

        $file_name = 'dashboard'. '_' . $data['start_date'] . '_' . $data['end_date'] . '.xlsx';

        Excel::assertDownloaded($file_name, function(DashboardExport $export)
        {
            return true;
        });
    }

    public function test_export_dashboard_report_failed_should_return_error_message()
    {
        $data = [];

        $response = $this->postJson('api/dashboard/report', $data);
        $expected = json_decode($response->content(), true);

        $assertion = [
            "start_date" => [
                "The start date field is required."
            ],
            "end_date" => [
                "The end date field is required."
            ],
            "name_reports" => [
                "The name reports field is required."
            ]
        ];

        $response->assertStatus(400);
        $this->assertEquals($assertion, $expected);
    }

    public function test_export_company_announcement_report_success_should_return_true()
    {
        Excel::fake();

        $data = [
            'start_date' => '2021-02-19',
            'end_date' => '2021-02-24',
            'name_reports' => ["company", "announcement"]
        ];

        $file_name = 'SITCC_report.zip';

        $response = $this->postJson('api/dashboard/report', $data);

        $header = $response->headers->get('content-disposition');
        $response->assertStatus(200);
        $this->assertEquals($header, "attachment; filename=".$file_name);
    }

    public function test_export_company_dashboard_report_success_should_return_true()
    {
        Excel::fake();

        $data = [
            'start_date' => '2021-02-19',
            'end_date' => '2021-02-24',
            'name_reports' => ["company", "dashboard"]
        ];

        $file_name = 'SITCC_report.zip';

        $response = $this->postJson('api/dashboard/report', $data);

        $header = $response->headers->get('content-disposition');
        $response->assertStatus(200);
        $this->assertEquals($header, "attachment; filename=".$file_name);
    }

    public function test_export_announcement_dashboard_report_success_should_return_true()
    {
        Excel::fake();

        $data = [
            'start_date' => '2021-02-19',
            'end_date' => '2021-02-24',
            'name_reports' => ["announcement", "dashboard"]
        ];

        $file_name = 'SITCC_report.zip';

        $response = $this->postJson('api/dashboard/report', $data);

        $header = $response->headers->get('content-disposition');
        $response->assertStatus(200);
        $this->assertEquals($header, "attachment; filename=".$file_name);
    }

    public function test_export_all_report_success()
    {
        Excel::fake();

        $data = [
            'start_date' => '2021-02-19',
            'end_date' => '2021-02-24',
            'name_reports' => ["all"]
        ];

        $file_name = 'SITCC_report.zip';

        $response = $this->postJson('api/dashboard/report', $data);

        $header = $response->headers->get('content-disposition');
        $response->assertStatus(200);
        $this->assertEquals($header, "attachment; filename=".$file_name);
    }

    public function test_insert_wrong_array_value_should_return_error_message()
    {
        Excel::fake();

        $data = [
            'start_date' => '2021-02-19',
            'end_date' => '2021-02-24',
            'name_reports' => ["test"]
        ];

        $response = $this->postJson('api/dashboard/report', $data);
        $expected = json_decode($response->content(), true);

        $assertion = [
            "message" => "Report not found"
        ];

        $response->assertStatus(404);
        $this->assertEquals($assertion, $expected);
    }

    public function test_insert_array_over_limit_should_return_error_message()
    {
        Excel::fake();

        $data = [
            'start_date' => '2021-02-19',
            'end_date' => '2021-02-24',
            'name_reports' => ["company", "announcement", "dashboard", "all"]
        ];

        $response = $this->postJson('api/dashboard/report', $data);
        $expected = json_decode($response->content(), true);

        $assertion = [
            "name_reports" => [
                "The name reports may not have more than 3 items."
            ],
        ];

        $response->assertStatus(400);
        $this->assertEquals($assertion, $expected);
    }
}
