<?php


namespace App\Traits;

use App\Models\Announcement;
use Carbon\Carbon;

use Illuminate\Support\Str;
use App\Repositories\AnnouncementRepositoryInterface;

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
                $message = 'Expired announcements have been updated status';
            }else {
                $message = 'Not have expired announcements';
            }
        }
        return $message;
    }
}
