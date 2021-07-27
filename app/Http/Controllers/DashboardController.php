<?php

namespace App\Http\Controllers;

use Validator;

use App\Http\RulesValidation\DashboardRules;
use App\Traits\AnnouncementsExport;
use App\Traits\CompaniesExport;
use App\Repositories\DashboardRepositoryInterface;
use App\Traits\DashboardExport;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use ZipArchive;

class DashboardController extends Controller
{
    use DashboardRules;
    use Utils;
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

    public function getCompaniesByFilterDate($data)
    {
        try {
            $file_name = 'companies' . '_' . $data['start_date'] . '_' . $data['end_date'] . '.xlsx';
            $companies_excel = Excel::download(new CompaniesExport($data), $file_name);
            return $companies_excel;
        } catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function getAnnouncementsByFilterDate($data)
    {
        try {
            $check_due_date = $this->checkDueDateForAnnouncement();

            $file_name = 'announcements' . '_' . $data['start_date'] . '_' . $data['end_date'] . '.xlsx';
            $announcements_excel = Excel::download(new AnnouncementsExport($data), $file_name);
            return $announcements_excel;
        } catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function getDashboardByFilterDate($data)
    {
        try {
            $file_name = 'dashboard' . '_' . $data['start_date'] . '_' . $data['end_date'] . '.xlsx';
            $dashboard_excel = Excel::download(new DashboardExport($data), $file_name);
            return $dashboard_excel;
        } catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function createReportByFilterDate(Request $request)
    {
        $data = $request->all();
        $validated = Validator::make($data, $this->ruleExport);
        if ($validated->fails()){
            return response()->json($validated->messages(), 400);
        }
        $name_report = $data['name_reports'];
        $file_date = $data['start_date'] . '_' . $data['end_date'];
        $file_name_zip = 'SIT_CC_Report.zip';

        $file_company = 'companies' . '_' . $file_date . '.xlsx';
        $file_announcement = 'announcement' . '_' . $file_date . '.xlsx';
        $file_dashboard = 'dashboard' . '_' . $file_date . '.xlsx';

        $clean_old_file = $this->DeleteOldFiles();

        try {
            $sort_arr = sort($name_report);
            $zip = new ZipArchive;

            if ($name_report[0] == 'all' or array_slice($name_report, 0, 3) == ['announcement', 'company', 'dashboard']) {
                if ($zip->open($file_name_zip, ZipArchive::CREATE) === true) {
                    $zip->addFile($this->getCompaniesByFilterDate($data)->getFile(), $file_company);
                    $zip->addFile($this->getAnnouncementsByFilterDate($data)->getFile(), $file_announcement);
                    $zip->addFile($this->getDashboardByFilterDate($data)->getFile(), $file_dashboard);
                    $zip->close();
                }
            } elseif (array_slice($name_report, 0, 2) == ['announcement', 'company']) {
                if ($zip->open($file_name_zip, ZipArchive::CREATE) === true) {
                    $zip->addFile($this->getCompaniesByFilterDate($data)->getFile(), $file_company);
                    $zip->addFile($this->getAnnouncementsByFilterDate($data)->getFile(), $file_announcement);
                    $zip->close();
                }
            } elseif (array_slice($name_report, 0, 2) == ['company', 'dashboard']) {
                if ($zip->open($file_name_zip, ZipArchive::CREATE) === true) {
                    $zip->addFile($this->getCompaniesByFilterDate($data)->getFile(), $file_company);
                    $zip->addFile($this->getDashboardByFilterDate($data)->getFile(), $file_dashboard);
                    $zip->close();
                }
            } elseif (array_slice($name_report, 0, 2) == ['announcement', 'dashboard']){
                if ($zip->open($file_name_zip, ZipArchive::CREATE) === true) {
                    $zip->addFile($this->getAnnouncementsByFilterDate($data)->getFile(), $file_announcement);
                    $zip->addFile($this->getDashboardByFilterDate($data)->getFile(), $file_dashboard);
                    $zip->close();
                }
            } elseif ($name_report[0] == 'company') {
                if ($zip->open($file_name_zip, ZipArchive::CREATE) === true) {
                    $zip->addFile($this->getCompaniesByFilterDate($data)->getFile(), $file_company);
                    $zip->close();
                }
            } elseif ($name_report[0] == 'announcement') {
                if ($zip->open($file_name_zip, ZipArchive::CREATE) === true) {
                    $zip->addFile($this->getAnnouncementsByFilterDate($data)->getFile(), $file_announcement);
                    $zip->close();
                }
            } elseif ($name_report[0] == 'dashboard') {
                if ($zip->open($file_name_zip, ZipArchive::CREATE) === true) {
                    $zip->addFile($this->getDashboardByFilterDate($data)->getFile(), $file_dashboard);
                    $zip->close();
                }
            } else {
                return response()->json([
                    "message" => "Report not found",
                ], 404);;
            }
            return response()->download($file_name_zip)->deleteFileAfterSend(true);
        } catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
