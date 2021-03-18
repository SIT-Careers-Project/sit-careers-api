<?php

namespace App\Http\Controllers;

use Validator;

use App\Http\RulesValidation\DashboardRules;
use App\Traits\AnnouncementsExport;
use App\Traits\CompaniesExport;
use App\Repositories\DashboardRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    use DashboardRules;
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

    public function getCompaniesByFilterDate(Request $request)
    {
        $data = $request->all();

        $validated = Validator::make($data, $this->ruleExport);
        if ($validated->fails()){
            return response()->json($validated->messages(), 400);
        }

        $file_name = 'companies'. '_' . Carbon::now()->format('Y-m-d-H-i-s') . '.csv';
        $path = '/reports/companies/';
        $path_file_name = $path.$file_name;

        $companies_excel = Excel::store(
            new CompaniesExport($data),
            $path_file_name,
            'minio',
            $writerType=\Maatwebsite\Excel\Excel::CSV,
        );

        return response()->json([
            "message" => $path_file_name,
            "status_uploaded_file" => $companies_excel
        ], 200
        );
    }

    public function getAnnouncementsByFilterDate(Request $request)
    {
        $data = $request->all();

        $validated = Validator::make($data, $this->ruleExport);
        if ($validated->fails()){
            return response()->json($validated->messages(), 400);
        }

        $file_name = 'announcements'. '_' . Carbon::now()->format('Y-m-d-H-i-s') . '.csv';
        $path = '/reports/announcements/';
        $path_file_name = $path.$file_name;

        $announcements_excel = Excel::store(
            new AnnouncementsExport($data),
            $path_file_name,
            'minio',
            $writerType=\Maatwebsite\Excel\Excel::CSV,
        );

        return response()->json([
            "message" => $path_file_name,
            "status_uploaded_file" => $announcements_excel
        ], 200
        );
    }
}
