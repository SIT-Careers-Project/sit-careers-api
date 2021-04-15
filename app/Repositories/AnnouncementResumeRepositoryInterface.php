<?php

namespace App\Repositories;

interface AnnouncementResumeRepositoryInterface
{
    public function getAllAnnouncementResumes();
    public function getAnnouncementResumeByAnnouncementId($announcement_id);
    public function getAnnouncementResumeByUserId($id);
    public function getAnnouncementResumeByCompanyId($id);
    public function CreateAnnouncementResume($data);
    public function updateAnnouncementRusume($data);
    public function NotificationAnnouncementResume($data);
}
