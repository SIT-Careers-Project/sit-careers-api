<?php

namespace App\Traits;

use App\Models\Announcement;
use Carbon\Carbon;

trait Utils
{
    public function checkDateToDayBetweenStartAndEnd($data)
    {
        return Carbon::now()->between($data['start_date'], $data['end_date']);
    }

    public function checkDueDateForAnnouncement()
    {
        $announcements = Announcement::get();
        for($i=0; $i < count($announcements); $i++){
            $announcement = $announcements[$i];
            if(!$this->checkDateToDayBetweenStartAndEnd($announcement)){
                $announcement->status = 'CLOSE';
                $announcement->save();
                return 'Expired announcements have been updated status';
            }
        }
        return 'Not have expired announcements';
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
}
