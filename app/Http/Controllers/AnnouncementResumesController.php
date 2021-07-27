<?php

namespace App\Http\Controllers;

use Validator;

use App\Repositories\AnnouncementResumeRepositoryInterface;
use App\Http\RulesValidation\AnnouncementResumeRules;
use App\Repositories\AnnouncementRepositoryInterface;
use App\Traits\AnnouncementResumeByCompanyIdReport;
use App\Traits\AnnouncementResumesExport;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use ZipArchive;

class AnnouncementResumesController extends Controller
{
    use AnnouncementResumeRules;
    use Utils;

    private $announcement_resume;
    private $announcement;

    public function __construct(AnnouncementResumeRepositoryInterface $announcementResume_repo, AnnouncementRepositoryInterface $announcement_repo)
    {
        $this->announcement_resume = $announcementResume_repo;
        $this->announcement = $announcement_repo;
    }

    public function get(Request $request)
    {
        $data = $request->all();
        $announcement_resume = $this->announcement_resume->getAllAnnouncementResumes();
        return response()->json($announcement_resume, 200);
    }

    public function getAnnouncementResumeByUserId(Request $request)
    {
        $id = $request->all();
        $announcement_resume = $this->announcement_resume->getAnnouncementResumeByUserId($id);
        return response()->json($announcement_resume, 200);
    }

    public function getAnnouncementResumeByCompanyId(Request $request)
    {
        $id = $request->all()['my_user_id'];
        $announcement_resume = $this->announcement_resume->getAnnouncementResumeByCompanyId($id);
        return response()->json($announcement_resume, 200);
    }

    public function getAnnouncementResumeById(Request $request, $announcement_resume_id)
    {
        $announcement_resume = $this->announcement_resume->getAnnouncementResumeById($announcement_resume_id);
        return response()->json($announcement_resume, 200);
    }

    public function getAnnouncementResumeByIdForCompanyId(Request $request, $announcement_resume_id)
    {
        $data = $request->all();
        $announcement_resume = $this->announcement_resume->getAnnouncementResumeByIdForCompanyId($data, $announcement_resume_id);
        return response()->json($announcement_resume, 200);
    }

    public function getAnnouncementResumeByIdForUserId(Request $request, $announcement_resume_id)
    {
        $data = $request->all();
        $announcement_resume = $this->announcement_resume->getAnnouncementResumeByIdForUserId($data, $announcement_resume_id);
        return response()->json($announcement_resume, 200);
    }

    public function create(Request $request)
    {
        try {
            $data = $request->all();
            $validated = Validator::make($data, $this->ruleCreateAnnouncementResume);
            if ($validated->fails()) {
                return response()->json($validated->messages(), 400);
            }

            $announcement_resumes = $this->announcement_resume->getAnnouncementResumeByAnnouncementId($data['announcement_id']);
            if(!$announcement_resumes->isEmpty()){
                $exist_announcement_resumes = $this->CheckUniqueAnnouncementResumeWithAnnouncement($announcement_resumes, $data['resume_id']);
                if ($exist_announcement_resumes == 'Exist resume id') {
                    return response()->json([
                        "message" => "Resume id has already exist"
                    ], 409);
                }
            }

            $announcement = $this->announcement->getAnnouncementById($data['announcement_id'])->first();
            if ($this->checkDateToDayBetweenStartAndEnd($announcement)) {
                $create_application = $this->announcement_resume->CreateAnnouncementResume($data);
                $send_mail_application = $this->announcement_resume->SendMailNotificationAnnouncementResume($data);
                $create_admin_noti = $this->announcement_resume->CreateAdminNotification($data, $create_application['announcement_resume_id']);
                $create_company_noti = $this->announcement_resume->CreateCompanyNotification($data, $create_application['announcement_resume_id']);
                $create_student_noti = $this->announcement_resume->CreateStudentNotification($data, $create_application['announcement_resume_id']);
                return response()->json($create_application, 200);
            } else {
                return response()->json([
                    "message" => "Can not application, because It has expired for application."
                ], 202);
            }
        }catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $validated = Validator::make($data, $this->ruleUpdateAnnouncementResume);
        if ($validated->fails()) {
            return response()->json($validated->messages(), 400);
        }

        $update_application = $this->announcement_resume->updateAnnouncementRusume($data);
        $update_student_noti = $this->announcement_resume->UpdateStudentNotification($data);
        return response()->json($update_application, 200);
    }

    public function getAnnouncementResumeReport($data)
    {
        try {
            $file_name = 'applications' . '_' . $data['start_date'] . '_' . $data['end_date'] . '.xlsx';
            $announcement_resume_excel = Excel::download(new AnnouncementResumesExport($data), $file_name);
            return $announcement_resume_excel;
        } catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function getAnnouncementResumeByCompanyIdReport($data)
    {
        try {
            $file_name = 'SIT_CC_Applications' . '_' . $data['start_date'] . '_' . $data['end_date'] . '.xlsx';
            $announcement_resume_excel = Excel::download(new AnnouncementResumeByCompanyIdReport($data), $file_name);
            return $announcement_resume_excel;
        } catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function createReport(Request $request)
    {
        $data = $request->all();
        $validated = Validator::make($data, $this->ruleExportAnnouncementResume);
        if ($validated->fails()){
            return response()->json($validated->messages(), 400);
        }
        $file_date = $data['start_date'] . '_' . $data['end_date'];
        $file_name_zip = 'SIT_CC_Application_Report.zip';

        $file_name = 'applications' . '_' . $file_date . '.xlsx';

        $clean_old_file = $this->DeleteOldFiles();

        try {
            $zip = new ZipArchive;
            if ($zip->open($file_name_zip, ZipArchive::CREATE) === true) {
                $zip->addFile($this->getAnnouncementResumeReport($data)->getFile(), $file_name);
                $zip->close();
            }
            return response()->download($file_name_zip)->deleteFileAfterSend(true);
        } catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function createReportByCompanyId(Request $request)
    {
        $data = $request->all();
        $validated = Validator::make($data, $this->ruleExportAnnouncementResume);
        if ($validated->fails()){
            return response()->json($validated->messages(), 400);
        }
        $file_date = $data['start_date'] . '_' . $data['end_date'];
        $file_name_zip = 'SIT_CC_Application_Report.zip';

        $file_name = 'applications' . '_' . $file_date . '.xlsx';

        $clean_old_file = $this->DeleteOldFiles();

        try {
            $zip = new ZipArchive;
            if ($zip->open($file_name_zip, ZipArchive::CREATE) === true) {
                $zip->addFile($this->getAnnouncementResumeByCompanyIdReport($data)->getFile(), $file_name);
                $zip->close();
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
