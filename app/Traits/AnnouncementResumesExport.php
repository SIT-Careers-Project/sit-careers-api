<?php

namespace App\Traits;

use App\Models\AnnouncementResume;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnnouncementResumesExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $data = $this->request;

        $start_date = $data['start_date']." 00:00:00";
        $end_date = $data['end_date']." 23:59:59";

        $announcement_resumes = AnnouncementResume::join('announcements', 'announcements.announcement_id', '=', 'announcement_resumes.announcement_id')
            ->join('resumes', 'resumes.resume_id', '=', 'announcement_resumes.resume_id')
            ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
            ->whereBetween('companies.created_at', [$start_date, $end_date])
            ->select('companies.company_name_th', 'announcements.announcement_title', 'announcement_resumes.*', 'resumes.*')
            ->get()
            ->makeHidden(['resume_id', 'student_id', 'announcement_resume_id', 'announcement_id', 'path_file', 'created_at', 'deleted_at']);

        return $announcement_resumes;
    }

    public function headings() : array
    {
        return array_keys($this->collection()->first()->toArray());
    }
}
