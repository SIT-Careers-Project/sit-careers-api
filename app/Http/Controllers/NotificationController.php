<?php

namespace App\Http\Controllers;

use App\Models\AnnouncementResume;
use App\Repositories\AnnouncementResumeRepositoryInterface;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
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
}
