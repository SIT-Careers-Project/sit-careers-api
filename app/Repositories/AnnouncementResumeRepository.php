<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Mail;

use App\Mail\RequestAnnouncementResume;
use App\Models\Announcement;
use App\Models\AnnouncementResume;
use App\Models\User;
use App\Models\DataOwner;

class AnnouncementResumeRepository implements AnnouncementResumeRepositoryInterface
{
    public function getAllAnnouncementResumes()
    {
        $announcement_resumes = AnnouncementResume::join('resumes', 'resumes.resume_id', '=', 'announcement_resumes.resume_id')
            ->join('announcements', 'announcements.announcement_id', '=', 'announcement_resumes.announcement_id')
            ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
            ->select('companies.company_name_th', 'announcements.announcement_title', 'resumes.*', 'announcement_resumes.*')
            ->get();

        return $announcement_resumes;
    }

    public function getAnnouncementResumeByAnnouncementId($announcement_id)
    {
        $announcement_resume = AnnouncementResume::where('announcement_resumes.announcement_id', $announcement_id)->get();

        return $announcement_resume;
    }

    public function getAnnouncementResumeByCompanyId($id)
    {
        $company = DataOwner::where('user_id', $id)->get();
        if (!$company->isEmpty()) {
            $announcement_resumes = AnnouncementResume::join('resumes', 'resumes.resume_id', '=', 'announcement_resumes.resume_id')
                ->join('announcements', 'announcements.announcement_id', '=', 'announcement_resumes.announcement_id')
                ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
                ->select('companies.company_name_th', 'announcements.announcement_title', 'resumes.*')
                ->where('companies.company_id', '=', $company[0]->company_id)
                ->get();
            return $announcement_resumes;
        }
        return "You not have company data.";
    }

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

    public function getAnnouncementResumeById($id)
    {
        $announcement_resume = AnnouncementResume::join('resumes', 'resumes.resume_id', '=', 'announcement_resumes.resume_id')
            ->join('announcements', 'announcements.announcement_id', '=', 'announcement_resumes.announcement_id')
            ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
            ->where('announcement_resumes.announcement_resume_id', $id)
            ->select('companies.company_name_th', 'announcements.announcement_title', 'resumes.*', 'announcement_resumes.*')
            ->get();

        return $announcement_resume;
    }

    public function getAnnouncementResumeByIdForCompanyId($data, $announcement_resume_id)
    {
        $company = DataOwner::where('user_id', $data['my_user_id'])->get();
        if (!$company->isEmpty()) {
            $announcement_resumes = AnnouncementResume::join('resumes', 'resumes.resume_id', '=', 'announcement_resumes.resume_id')
                ->join('announcements', 'announcements.announcement_id', '=', 'announcement_resumes.announcement_id')
                ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
                ->select('companies.company_name_th', 'announcements.announcement_title', 'resumes.*')
                ->where('companies.company_id', '=', $company[0]->company_id)
                ->where('announcement_resumes.announcement_resume_id', $announcement_resume_id)
                ->get();

            return $announcement_resumes;
        }
        return "You not have company data.";
    }

    public function getAnnouncementResumeByIdForUserId($data, $announcement_resume_id)
    {
        $announcement_resume = AnnouncementResume::join('resumes', 'resumes.resume_id', '=', 'announcement_resumes.resume_id')
            ->join('announcements', 'announcements.announcement_id', '=', 'announcement_resumes.announcement_id')
            ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
            ->where('resumes.student_id', $data['my_user_id'])
            ->where('announcement_resumes.announcement_resume_id', $announcement_resume_id)
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

        return $announcement_resume;
    }

    public function updateAnnouncementRusume($data)
    {
        $announcement_resume = AnnouncementResume::find($data['announcement_resume_id']);
        $announcement_resume->status = $data['status'];
        $announcement_resume->note = $data['note'];
        $announcement_resume->save();

        return $announcement_resume;
    }

    public function NotificationAnnouncementResume($data)
    {
        $announcement = Announcement::join('companies', 'companies.company_id', '=', 'announcements.company_id')
            ->where('announcements.announcement_id', $data['announcement_id'])
            ->first();

        $user_admin_hr = User::join('roles', 'roles.role_id', '=', 'users.role_id')
            ->where('roles.role_name', 'admin')
            ->oRWhere('roles.role_name', 'manager')
            ->orWhere('roles.role_name', 'coordinator')
            ->get();

        for ($i=0; $i < count($user_admin_hr); $i++) {
            $sendMailToRelateUsers = Mail::to($user_admin_hr[$i]->email)->send(new RequestAnnouncementResume($user_admin_hr[$i], $announcement));
        }

        return "Notification sent to the users success";
    }
}
