<?php

namespace App\Traits;

use App\Models\Announcement;
use App\Models\Role;
use Carbon\Carbon;

use Throwable;

trait Utils
{
    public function checkDateToDayBetweenStartAndEnd($data)
    {
        return Carbon::now()->between($data->start_date, $data->end_date);
    }

    public function checkDueDateForAnnouncement()
    {
        $announcements = Announcement::get();
        $message = 'Not have expired announcements';
        for($i=0; $i < count($announcements); $i++){
            $announcement = Announcement::find($announcements[$i]["announcement_id"]);
            if($this->checkDateToDayBetweenStartAndEnd($announcement)){
                $announcement->status = 'OPEN';
                $announcement->save();
                $message = 'Expired announcements have been updated status';
            } else if (!$this->checkDateToDayBetweenStartAndEnd($announcement)) {
                $announcement->status = 'CLOSE';
                $announcement->save();
                $message = 'Expired announcements have been updated status';
            }
        }
        return $message;
    }

    public function CheckUniqueAnnouncementResumeWithAnnouncement($announcement_resumes, $new_resume_id)
    {
        $arr_announcement_resumes = $announcement_resumes->toArray();
        for($i=0; $i < count($arr_announcement_resumes); $i++){
            if(!is_null($arr_announcement_resumes) && $arr_announcement_resumes[$i]['resume_id'] == $new_resume_id){
                return 'Exist resume id';
            }
        }
        return 'Not have resume id in announcement';
    }

    public function DeleteOldFiles()
    {
        try {
            if (glob('../../') == 'SITCC_report.zip') {
                $unlink_zip = unlink('../..SITCC_report.zip');
            }
            $unlink_zip = unlink('SITCC_report.zip');
            $excel_files = glob('../storage/framework/laravel-excel/*');
            foreach ($excel_files as $file) {
                if (is_file($file)) {
                    $unlink_excel = unlink($file);
                }
            }
        }catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }

        return 'Remove old files';
    }

    public function CheckRoleViewer($request_role_id)
    {
        $viewer_role_id = Role::where('role_name', 'viewer')->first();
        if($request_role_id == $viewer_role_id['role_id']){
            return 'viewer';
        }
    }

    public function CheckRoleAdmin($request_role_id)
    {
        $admin_role_id = Role::where('role_name', 'admin')->first();
        if($request_role_id == $admin_role_id['role_id']){
            return 'admin';
        }
    }

    public function CheckRoleCoordinator($request_role_id)
    {
        $coordinator_role_id = Role::where('role_name', 'coordinator')->first();
        if($request_role_id == $coordinator_role_id['role_id']){
            return 'coordinator';
        }
    }
}
