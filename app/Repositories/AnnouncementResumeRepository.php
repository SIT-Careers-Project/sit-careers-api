<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Mail;

use App\Mail\RequestAnnouncementResume;
use App\Models\Announcement;
use App\Models\AnnouncementResume;
use App\Models\Company;
use App\Models\User;
use App\Models\DataOwner;
use App\Models\Notification;
use App\Models\Resume;

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
                ->select('companies.company_name_th', 'announcements.announcement_title', 'resumes.*', 'announcement_resumes.*')
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
                ->select('companies.company_name_th', 'announcements.announcement_title', 'resumes.*', 'announcement_resumes.*')
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
            ->select('companies.company_name_th', 'announcements.announcement_title', 'resumes.*',
                    'announcement_resumes.announcement_resume_id', 'announcement_resumes.status', 'announcement_resumes.announcement_id')
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

    public function SendMailNotificationAnnouncementResume($data)
    {
        $announcement = Announcement::join('companies', 'companies.company_id', '=', 'announcements.company_id')
            ->where('announcements.announcement_id', $data['announcement_id'])
            ->first();
        $announcement_arr = $announcement->toArray();

        $user_admin = User::join('roles', 'roles.role_id', '=', 'users.role_id')
            ->where('roles.role_name', 'admin')
            ->get();

        for ($i=0; $i < count($user_admin); $i++) {
            $send_mail_to_admin = Mail::to($user_admin[$i]->email)->send(new RequestAnnouncementResume($user_admin[$i], $announcement));
        }

        $company = DataOwner::where('company_id', $announcement_arr['company_id'])->get();
        if(!$company->isEmpty()) {
            for ($i=0; $i < count($company); $i++) {
                $user_company = User::find($company[$i]['user_id']);
                $send_mail_to_company = Mail::to($user_company->email)->send(new RequestAnnouncementResume($user_company, $announcement));
            }
        }

        return "Notification sent to the users success";
    }

    public function CreateAdminNotification($data, $announcement_resume_id)
    {
        $user_admin = User::join('roles', 'roles.role_id', '=', 'users.role_id')
            ->where('roles.role_name', 'admin')->get()->toArray();

        $candidate_name = Resume::where('resume_id', $data['resume_id'])
            ->select('first_name')
            ->get()->toArray();

        $announcement = Announcement::where('announcement_id', $data['announcement_id'])
            ->select('company_id', 'announcement_title')
            ->get()->toArray();

        for ($i=0; $i < count($user_admin); $i++) {
            $notification = new Notification();
            $notification->user_id = $user_admin[$i]['user_id'];
            $notification->message = 'คุณ '.$candidate_name[0]['first_name'].' ส่งคำขอสมัครประกาศ '. $announcement[0]['announcement_title'];
            $notification->url = '/academic-industry/applications/'.$announcement_resume_id;
            $notification->read_at = null;
            $notification->save();
        }
        return $notification;

    }

    public function CreateCompanyNotification($data, $announcement_resume_id)
    {
        $announcement = Announcement::where('announcement_id', $data['announcement_id'])
            ->select('company_id', 'announcement_title')
            ->get()->toArray();

        $company = DataOwner::where('company_id', $announcement[0]['company_id'])->get()->toArray();
        if(!is_null($company)){
            $notification = [];

            $candidate_name = Resume::where('resume_id', $data['resume_id'])
                ->select('first_name')
                ->get()->toArray();

            for ($i=0; $i < count($company); $i++) {
                $notification = new Notification();
                $notification->user_id = $company[$i]['user_id'];
                $notification->message = 'คุณ '.$candidate_name[0]['first_name'].' ส่งคำขอสมัครประกาศ '. $announcement[0]['announcement_title'];
                $notification->url = '/academic-industry/applications/'.$announcement_resume_id;
                $notification->read_at = null;
                $notification->save();
            }
            return $notification;
        }
        return "You not have company data.";
    }

    public function CreateStudentNotification($data, $announcement_resume_id)
    {
        $announcement = Announcement::where('announcement_id', $data['announcement_id'])
            ->select('company_id', 'announcement_title')
            ->get()->toArray();

        $company_name_en = Company::where('company_id', $announcement[0]['company_id'])
            ->select('company_name_en')
            ->get()->toArray();

        $notification = new Notification();
        $notification->user_id = $data['my_user_id'];
        $notification->message = 'คุณส่งคำขอสมัครประกาศ '.$announcement[0]['announcement_title'];
        $notification->url = '/academic-industry/applications/'.$announcement_resume_id;
        $notification->read_at = null;
        $notification->save();

        return $notification;
    }

    public function UpdateStudentNotification($data)
    {
        $announcement_resume = AnnouncementResume::find($data['announcement_resume_id'])->get()->toArray();

        $announcement = Announcement::where('announcement_id', $announcement_resume[0]['announcement_id'])
            ->select('company_id', 'announcement_title')
            ->get()->toArray();

        $student_id = Resume::where('resume_id', $announcement_resume[0]['resume_id'])
            ->select('student_id')
            ->get()->toArray();

        $notification = new Notification();
        $notification->user_id = $student_id[0]['student_id'];
        $notification->message = 'คำขอสมัครประกาศ '.$announcement[0]['announcement_title'].' ที่คุณสมัครมีการอัปเดตสถานะ';
        $notification->url = '/academic-industry/applications/'.$data['announcement_resume_id'];
        $notification->read_at = null;
        $notification->save();

        return $notification;
    }
}
