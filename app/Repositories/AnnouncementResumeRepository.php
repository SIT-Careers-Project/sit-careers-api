<?php

namespace App\Repositories;

use App\Models\AnnouncementResume;

class AnnouncementResumeRepository implements AnnouncementResumeRepositoryInterface
{
    public function getAnnouncementResumeByUserId($id)
    {
        $announcement_resume = AnnouncementResume::join('resumes', 'resumes.resume_id', '=', 'announcement_resumes.resume_id')
            ->join('announcements', 'announcements.announcement_id', '=', 'announcement_resumes.announcement_id')
            ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
            ->where('resumes.student_id', $id['my_user_id'])
            ->select('companies.company_name_th', 'announcements.announcement_title', 'resumes.*')
            ->get();

        return $announcement_resume;
    }

    public function CreateAnnouncementResume($data)
    {
        $announcement_resume = new AnnouncementResume();
        $announcement_resume->announcement_id = $data['announcement_id'];
        $announcement_resume->resume_id = $data['resume_id'];
        $announcement_resume->status = $data['status'];
        $announcement_resume->note = $data['note'];
        $announcement_resume->save();

        return array($announcement_resume->toArray());
    }
}
