<?php

namespace App\Http\Controllers;

use Validator;

use App\Repositories\AnnouncementResumeRepositoryInterface;
use App\Http\RulesValidation\AnnouncementResumeRules;
use App\Repositories\AnnouncementRepositoryInterface;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Throwable;

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

            $announcement = $this->announcement->getAnnouncementById($data['announcement_id']);
            if ($this->checkDateToDayBetweenStartAndEnd($announcement)) {
                $create_application = $this->announcement_resume->CreateAnnouncementResume($data);
                $noti_application = $this->announcement_resume->NotificationAnnouncementResume($data);
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
        return response()->json($update_application, 200);
    }
}
