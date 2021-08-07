<?php

namespace App\Repositories;

interface AnnouncementRepositoryInterface
{
    public function getAnnouncementById($id);
    public function getAllAnnouncements();
    public function getAllAnnouncementsForAdminAndViewer();
    public function getAnnouncementByCompanyId($company_id);
    public function getAnnouncementByUserId($data);
    public function createAnnouncement($data);
    public function updateAnnouncement($data);
    public function deleteAnnouncementById($id);
}
