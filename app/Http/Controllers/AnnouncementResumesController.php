<?php

namespace App\Http\Controllers;

use Validator;

use App\Repositories\AnnouncementResumeRepositoryInterface;
use App\Http\RulesValidation\AnnouncementResumeRules;
use App\Repositories\AnnouncementRepositoryInterface;
use App\Traits\Utils;
use Illuminate\Http\Request;

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

    public function getAnnouncementResumeByUserId(Request $request)
    {
        $id = $request->all();
        $announcement_resume = $this->announcement_resume->getAnnouncementResumeByUserId($id);
        return response()->json($announcement_resume, 200);
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $validated = Validator::make($data, $this->ruleCreateAnnouncementResume);
        if ($validated->fails()) {
            return response()->json($validated->messages(), 400);
        }

        $announcement = $this->announcement->getAnnouncementById($data['announcement_id']);
        if ($this->checkDateToDayBetweenStartAndEnd($announcement)) {
            $create_application = $this->announcement_resume->CreateAnnouncementResume($data);
            return response()->json($create_application, 200);
        } else {
            return response()->json([
                "message" => "Can not application, because It has expired for application."
            ], 202);
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $validated = Validator::make($data, $this->ruleUpdateAnnouncementResume);
        if ($validated->fails()) {
            return response()->json()($validated->messages(), 400);
        }

        $update_application = $this->announcement_resume->updateAnnouncementRusume($data);
        return response()->json($update_application, 200);
    }
}
