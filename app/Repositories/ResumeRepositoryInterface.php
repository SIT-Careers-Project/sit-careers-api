<?php

namespace App\Repositories;

interface ResumeRepositoryInterface
{
    public function getResumes();
    public function getResumeById($id);
    public function getResumeByUserId($user_id);
    public function createResume($data);
    public function updateResume($data);
    public function deleteResumeById($id);
}
