<?php

namespace App\Repositories;

interface DashboardRepositoryInterface
{
    public function getStats();
    public function getCompanyTypes();
    public function getStudentJobPositions();
    public function getAnnouncementJobPositions();
}
