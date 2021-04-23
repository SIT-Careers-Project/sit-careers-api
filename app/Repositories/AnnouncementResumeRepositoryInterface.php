<?php

namespace App\Repositories;

interface AnnouncementResumeRepositoryInterface
{
    public function getAllAnnouncementResumes();
    public function getAnnouncementResumeByAnnouncementId($announcement_id);
    public function getAnnouncementResumeByUserId($id);
    public function getAnnouncementResumeByCompanyId($id);
    public function getAnnouncementResumeById($id);
    public function getAnnouncementResumeByIdForCompanyId($data, $announcement_resume_id);
    public function getAnnouncementResumeByIdForUserId($data, $announcement_resume_id);
    public function CreateAnnouncementResume($data);
    public function updateAnnouncementRusume($data);
    public function SendMailNotificationAnnouncementResume($data);
    public function CreateAdminNotification($data, $announcement_resume_id);
    public function CreateCompanyNotification($data, $announcement_resume_id);
    public function CreateStudentNotification($data, $announcement_resume_id);
    public function UpdateStudentNotification($data);
}
