<?php

namespace App\Repositories;

use Carbon\Carbon;

use App\Models\Resume;

class ResumeRepository implements ResumeRepositoryInterface
{
    public function getResumeById($resume_id)
    {
        $resume = Resume::find($resume_id);
        if($resume){
            return $resume;
        }
        return "Find not found resume.";
    }

    public function getResumeByUserId($user_id)
    {
        $resume = Resume::where('student_id', $user_id)->get();
        return $resume;
    }

    public function getResumes()
    {
        $resumes = Resume::join('announcements', 'announcements.announcement_id', '=', 'resumes.resume_id')
                        ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
                        ->select('companies.company_name_th', 'announcements.announcement_title', 'resumes.*')
                        ->get();
        return $resumes;
    }

    public function createResume($data)
    {
        $resume = new Resume();
        $resume->student_id = $data['my_user_id'];
        $resume->resume_date = Carbon::now();
        $resume->name_title = $data['name_title'];
        $resume->first_name = $data['first_name'];
        $resume->email = $data['email'];
        $resume->last_name = $data['last_name'];
        $resume->curriculum = $data['curriculum'];
        $resume->year = $data['year'];
        $resume->tel_no = $data['tel_no'];
        $resume->resume_link = $data['resume_link'];
        $resume->path_file = $data['path_file'] ? $data['path_file'] : '-';
        $resume->save();

        return $resume;
    }

    public function updateResume($data)
    {
        $resume = Resume::where('resume_id', $data['resume_id'])->first();
        $resume->student_id = $data['my_user_id'];
        $resume->name_title = $data['name_title'];
        $resume->first_name = $data['first_name'];
        $resume->email = $data['email'];
        $resume->last_name = $data['last_name'];
        $resume->curriculum = $data['curriculum'];
        $resume->year = $data['year'];
        $resume->tel_no = $data['tel_no'];
        $resume->resume_link = $data['resume_link'];
        $resume->path_file = $data['path_file'];
        $resume->updated_at = Carbon::now();
        $resume->save();

        return $resume;
    }

    public function deleteResumeById($id)
    {
        $resume = Resume::find($id);
        if ($resume) {
            $deleted = $resume->delete();
            $resume->path_file = '';
            $resume->save();
            return $deleted;
        }
        return "Find not found resume.";
    }
}
