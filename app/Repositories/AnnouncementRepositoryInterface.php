<?php

namespace App\Repositories;

interface AnnouncementRepositoryInterface
{
    public function getAnnouncementById($id);
    public function getAllAnnouncements();
    public function getAnnouncementByCompanyId($data);
    public function createAnnouncement($data);
    public function updateAnnouncement($data);
    public function deleteAnnouncementById($id);
}
