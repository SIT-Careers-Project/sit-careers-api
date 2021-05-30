<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;
use App\Repositories\JobPositionRepositoryInterface;
use App\Http\RulesValidation\CompanyRules;


class JobPositionController extends Controller 
{
    private $jobPosition;

    public function __construct(JobPositionRepositoryInterface $jobPositionRepo)
    {
        $this->jobPosition = $jobPositionRepo;
    }

    public function get(Request $request)
    {
        $jobs = $this->jobPosition->getJobPositions();
        return $jobs;
    }

}