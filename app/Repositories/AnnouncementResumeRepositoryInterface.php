<?php

namespace App\Repositories;

interface AnnouncementResumeRepositoryInterface
{
    public function getAnnouncementResumeByUserId($id);
    public function CreateAnnouncementResume($data);
}
