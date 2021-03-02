<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
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

}
