<?php

namespace App\Http\Controllers;

use Validator;
use Storage;
use Config;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;
use App\Repositories\AnnouncementRepositoryInterface;
use App\Traits\Utils;
use App\Http\RulesValidation\ResumeRules;
use App\Repositories\ResumeRepositoryInterface;

class ResumeController extends Controller
{
    use ResumeRules;
    use Utils;

    private $resume;
    private $announcement;

    public function __construct(ResumeRepositoryInterface $resumeRepo, AnnouncementRepositoryInterface $announcementRepo)
    {
        $this->resume = $resumeRepo;
        $this->announcement = $announcementRepo;
    }

    public function get(Request $request)
    {
        $resumes = $this->resume->getResumes();
        return response()->json($resumes, 200);
    }

    public function getResumeById(Request $request, $resume_id)
    {
        $resume = $this->resume->getResumeById($resume_id);
        if ($resume) {
            return response()->json($resume, 200);
        }
        return response()->json([
            "message" => "Not found."
        ], 404);
    }

    public function getResumeByUserId(Request $request)
    {
        $user_id = $request->all()['my_user_id'];
        $resume = $this->resume->getResumeByUserId($user_id);
        if ($resume) {
            return response()->json($resume, 200);
        }
        return response()->json([
            "message" => "Not found."
        ], 404);
    }

    public function create(Request $request)
    {
        try {
            $data = $request->all();
            $validated = Validator::make($data, $this->rulesCreationResume);
            if ($validated->fails()) {
                return response()->json($validated->messages(), 400);
            }

            $created = $this->resume->createResume($request);
            return response()->json($created, 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $th
            ]
            , 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $validated = Validator::make($data, $this->rulesUpdateResume);
            if ($validated->fails()) {
                return response()->json($validated->messages(), 400);
            }

            $updated = $this->resume->updateResume($request);
            return response()->json($updated, 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $th
            ]
            , 500);
        }
    }

    public function destroy(Request $request, $resume_id)
    {
        try {
            $delete = $this->resume->deleteResumeById($resume_id);
            $message = $delete;
            if ($delete) {
                $message = 'Resume has been deleted.';
            }
            return response()->json([ "message" => $message ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $th
            ]
            , 500);
        }
    }
}
