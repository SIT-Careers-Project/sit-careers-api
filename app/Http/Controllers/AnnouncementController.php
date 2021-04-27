<?php

namespace App\Http\Controllers;

use Validator;
use Storage;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;
use App\Repositories\AnnouncementRepositoryInterface;
use App\Http\RulesValidation\AnnouncementRules;
use Throwable;

class AnnouncementController extends Controller
{
    use AnnouncementRules;
    private $announcement;

    public function __construct(AnnouncementRepositoryInterface $announcement_repo)
    {
        $this->announcement = $announcement_repo;
    }

    public function get(Request $request, $announcement_id)
    {
        $data = $request->all();
        $announcement = $this->announcement->getAnnouncementById($announcement_id);
        if ($announcement) {
            return response()->json($announcement, 200);
        }
        return response()->json([
            "message" => "Not found."
        ], 404);
    }

    public function getAnnouncementByCompanyId(Request $request, $company_id)
    {
        $announcements = $this->announcement->getAnnouncementByCompanyId($company_id);
        return response()->json($announcements, 200);
    }

    public function getAnnouncementByUserId(Request $request)
    {
        $data = $request->all();
        $announcements = $this->announcement->getAnnouncementByUserId($data);
        return response()->json($announcements, 200);
    }

    public function getAnnouncements(Request $request)
    {
        $announcements = $request->all();
        $announcements = $this->announcement->getAllAnnouncements();
        return response()->json($announcements, 200);
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $validated = Validator::make($data, $this->rulesCreationAnnouncement);
        if ($validated->fails()) {
            return response()->json($validated->messages(), 400);
        }
        $storage = Storage::disk('minio');
        $imageName = str_replace(' ', '_', $data['announcement_title']).'_'.rand(10000, 99999);
        $file = $request->file('file_picture');
        if (!is_null($file)) {
            $uploaded = $storage->put('/cover_announcement/'.$imageName, file_get_contents($file), 'public');
            $data['picture'] = $imageName;
        }
        $announcements = $this->announcement->createAnnouncement($data);
        return response()->json($announcements, 200);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            $validated = Validator::make($data, $this->rulesUpdateAnnouncementById);
            if ($validated->fails()) {
                return response()->json($validated->messages(), 400);
            }
            $imageName = $data['picture'];
            $storage = Storage::disk('minio');
            $file = $request->file('file_picture');
            $imageName = str_replace(' ', '_', $data['announcement_title']).'_'.rand(10000, 99999);

            if(!is_null($file)) {
                $uploaded = $storage->delete('/cover_announcement/'.$data['picture']);
                $uploaded = $storage->put('/cover_announcement/'.$imageName, file_get_contents($file), 'public');
                $data['picture'] = $imageName;
            }
            $announcement_updated = $this->announcement->updateAnnouncement($data);
        }catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
        return response()->json($announcement_updated, 200);
    }

    public function destroy(Request $request)
    {
        $id = $request->all(['announcement_id']);
        $announcement_deleted = $this->announcement->deleteAnnouncementById($id);
        return response()->json($announcement_deleted, 200);
    }
}
