<?php


namespace App\Traits;

use Carbon\Carbon;

use Illuminate\Support\Str;
use App\Repositories\AnnouncementRepositoryInterface;

trait Utils
{
    public function checkDateToDayBetweenStartAndEnd($data)
    {
        return Carbon::now()->between($data['start_date'], $data['end_date']);
    }
}