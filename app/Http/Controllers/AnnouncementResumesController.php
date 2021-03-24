<?php

namespace App\Http\Controllers;

use Validator;

use App\Repositories\AnnouncementResumeRepositoryInterface;
use App\Http\RulesValidation\AnnouncementResumeRules;
use Illuminate\Http\Request;

class AnnouncementResumesController extends Controller
{
    use AnnouncementResumeRules;
    private $announcement_resume;

    public function __construct(AnnouncementResumeRepositoryInterface $announcementResume_repo)
    {
        $this->announcement_resume = $announcementResume_repo;
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
        $application = $this->announcement_resume->CreateAnnouncementResume($data);
        return response()->json($application, 200);
    }
}
