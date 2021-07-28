<?php

namespace App\Traits;

use App\Models\AnnouncementResume;
use App\Models\DataOwner;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnnouncementResumeByCompanyIdReport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $data = $this->request;

        $id = $data['my_user_id'];
        $start_date = $data['start_date']." 00:00:00";
        $end_date = $data['end_date']." 23:59:59";

        $company = DataOwner::where('user_id', $id)->get();
        if (!$company->isEmpty()) {
            $announcement_resumes = AnnouncementResume::join('resumes', 'resumes.resume_id', '=', 'announcement_resumes.resume_id')
                ->join('announcements', 'announcements.announcement_id', '=', 'announcement_resumes.announcement_id')
                ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
                ->select('announcements.announcement_title', 'announcement_resumes.*', 'resumes.*')
                ->where('companies.company_id', '=', $company[0]->company_id)
                ->whereBetween('companies.created_at', [$start_date, $end_date])
                ->get()
                ->makeHidden(['companies.company_name_th', 'resume_id', 'student_id', 'announcement_resume_id', 'announcement_id', 'path_file', 'created_at', 'deleted_at']);

            return $announcement_resumes;
        }
        return "You not have company data.";
    }

    public function headings() : array
    {
        return array_keys($this->collection()->first()->toArray());
    }
}
