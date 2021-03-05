<?php

namespace App\Repositories;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use App\Models\Application;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function getApplicationById($application_id)
    {
        $application = Application::find($application_id);
        return $application;
    }

    public function getApplications()
    {
        $applications = Application::join('announcements', 'announcements.announcement_id','=', 'applications.announcement_id')
                        ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
                        ->select('companies.company_name_th', 'announcements.announcement_title', 'applications.*')
                        ->get();
        return $applications;
    }

    public function createApplication($data)
    {
        $application = new Application();
        $application->announcement_id = $data->announcement_id;
        $application->student_id = $data->my_user_id;
        $application->application_date = Carbon::now();
        $application->status = 'เรียกสัมภาษณ์';
        $application->note = $data->note;
        $application->name_title = $data->name_title;
        $application->first_name = $data->first_name;
        $application->email = $data->email;
        $application->last_name = $data->last_name;
        $application->curriculum = $data->curriculum;
        $application->year = $data->year;
        $application->tel_no = $data->tel_no;
        $application->resume_link = $data->resume_link;
        $application->path_file = $data->path_file;
        $application->save();

        return $application;
    }

    public function updateApplication($data)
    {
        $application = Application::where('application_id', $data->application_id)->first();
        $application->student_id = $data->my_user_id;
        $application->status = $data->status;
        $application->note = $data->note;
        $application->name_title = $data->name_title;
        $application->first_name = $data->first_name;
        $application->email = $data->email;
        $application->last_name = $data->last_name;
        $application->curriculum = $data->curriculum;
        $application->year = $data->year;
        $application->tel_no = $data->tel_no;
        $application->resume_link = $data->resume_link;
        $application->path_file = $data->path_file;
        $application->updated_at = Carbon::now();
        $application->save();

        return $application;
    }

    public function deleteApplicationById($id)
    {
        $application = Application::find($id);
        if ($application) {
            $deleted = $application->delete();
            return $deleted;
        }
        return "Find not found Application.";
    }
}
