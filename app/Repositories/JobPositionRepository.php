<?php

namespace App\Repositories;

use Carbon\Carbon;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

use App\Models\JobPosition;

class JobPositionRepository implements JobPositionRepositoryInterface
{
    public function getJobPositions()
    {
        $jobPosition = JobPosition::all();
        return $jobPosition;
    }
}