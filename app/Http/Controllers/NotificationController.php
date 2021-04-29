<?php

namespace App\Http\Controllers;

use Validator;

use App\Http\RulesValidation\NotificationRules;
use App\Models\AnnouncementResume;
use App\Repositories\AnnouncementResumeRepositoryInterface;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;
use Throwable;

class NotificationController extends Controller
{
    use NotificationRules;

    private $notification;
    private $announcement_resume;

    public function __construct(NotificationRepository $noti_repo, AnnouncementResume $announcement_resume_repo)
    {
        $this->notification = $noti_repo;
        $this->announcement_resume = $announcement_resume_repo;
    }

    public function get(Request $request)
    {
        $data = $request->all();
        $notification = $this->notification->getNotificationByUserId($data);
        return response()->json($notification, 200);
    }

    public function update(Request $request)
    {
        try{
            $data = $request->all();

            $validated = Validator::make($data, $this->rulesUpdateNotification);
            if ($validated->fails()){
                return response()->json($validated->messages(), 400);
            }

            $notification = $this->notification->updateNotificationByUserId($data);
            if ($notification) {
                return response()->json($notification, 200);
            }
            return response()->json([
                'message' => 'Not found your notification'
            ], 404);
        }catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
