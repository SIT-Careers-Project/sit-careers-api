<?php

namespace App\Http\Controllers;

use Validator;
use Storage;
use Config;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

use App\Http\Controllers\Controller as Controller;
use App\Repositories\ApplicationRepositoryInterface;
use App\Repositories\AnnouncementRepositoryInterface;
use App\Traits\Utils;
use App\Http\RulesValidation\ApplicationRules;


class ApplicationController extends Controller
{
    use ApplicationRules;
    use Utils;

    private $application;
    private $announcement;

    public function __construct(ApplicationRepositoryInterface $applicationRepo, AnnouncementRepositoryInterface $announcementRepo)
    {
        $this->application = $applicationRepo;
        $this->announcement = $announcementRepo;
    }

    public function get(Request $request)
    {
        $applications = $this->application->getApplications();
        return response()->json($applications, 200);
    }

    public function getApplicationById(Request $request, $application_id)
    {
        $applications = $this->application->getApplicationById($application_id);
        if ($applications) {
            return response()->json($applications, 200);
        }
        return response()->json([
            "message" => "Not found."
        ], 404);
    }

    public function create(Request $request)
    {
        try {
            $data = $request->all();
            $validated = Validator::make($data, $this->rulesCreationApplication);
            if ($validated->fails()) {
                return response()->json($validated->messages(), 400);
            }

            $announcement = $this->announcement->getAnnouncementById($data['announcement_id']);
            if ($this->checkDateToDayBetweenStartAndEnd($announcement)) {
                $created = $this->application->createApplication($request);
                return response()->json($created, 200);
            } else {
                if ($announcement['status'] == "OPEN") {
                    $announcement['status'] = "CLOSE";
                    $this->announcement->updateAnnouncement($announcement);
                }
                return response()->json([
                    "message" => "Can not application, because It was expired for application."
                ], 202);
            }
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
            $validated = Validator::make($data, $this->rulesUpdateApplication);
            if ($validated->fails()) {
                return response()->json($validated->messages(), 400);
            }

            $announcement = $this->announcement->getAnnouncementById($data['announcement_id']);
            if ($this->checkDateToDayBetweenStartAndEnd($announcement)) {
                $updated = $this->application->updateApplication($data);
                return response()->json($updated, 200);
            } else {
                if ($announcement['status'] == "OPEN") {
                    $announcement['status'] = "CLOSE";
                    $this->announcement->updateAnnouncement($announcement);
                }
                return response()->json([
                    "message" => "Can not update application, because It was expired for application."
                ], 202);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $th 
            ]
            , 500);
        }
    }

    public function destroy(Request $request, $application_id)
    {
        try {
            $delete = $this->application->deleteApplicationById($application_id);
            $message = $delete;
            if ($delete) {
                $message = 'Application has been deleted.';
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