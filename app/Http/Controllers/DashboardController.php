<?php

namespace App\Http\Controllers;

use App\Repositories\DashboardRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $dashboard;

    public function __construct(DashboardRepositoryInterface $dashboard_repo)
    {
        $this->dashboard = $dashboard_repo;
    }

    public function getStats(Request $request)
    {
        $stats = $request->all();
        $stats = $this->dashboard->getStats();
        return response()->json($stats, 200);
    }

    public function getCompanyTypes(Request $request)
    {
        $company_types = $request->all();
        $company_types = $this->dashboard->getCompanyTypes();
        return response()->json($company_types, 200);
    }

    public function getStudentJobPositions(Request $request)
    {
        $student_job_positions = $request->all();
        $student_job_positions = $this->dashboard->getStudentJobPositions();
        return response()->json($student_job_positions, 200);
    }

    public function getAnnouncementJobPositions(Request $request)
    {
        $announcement_job_position = $request->all();
        $announcement_job_position = $this->dashboard->getAnnouncementJobPositions();
        return response()->json($announcement_job_position, 200);
    }
}
