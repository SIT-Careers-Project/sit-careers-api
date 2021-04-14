<?php

namespace App\Http\Controllers;

use Validator;
use Storage;
use Config;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;
use App\Repositories\AnnouncementRepositoryInterface;
use App\Traits\Utils;
use App\Http\RulesValidation\ResumeRules;
use App\Repositories\ResumeRepositoryInterface;
use Throwable;

class ResumeController extends Controller
{
    use ResumeRules;
    use Utils;

    private $resume;
    private $announcement;

    public function __construct(ResumeRepositoryInterface $resumeRepo, AnnouncementRepositoryInterface $announcementRepo)
    {
        $this->resume = $resumeRepo;
        $this->announcement = $announcementRepo;
    }

    public function get(Request $request)
    {
        $resumes = $this->resume->getResumes();
        return response()->json($resumes, 200);
    }

    public function getResumeById(Request $request, $resume_id)
    {
        $resume = $this->resume->getResumeById($resume_id);
        if ($resume) {
            return response()->json($resume, 200);
        }
        return response()->json([
            "message" => "Not found."
        ], 404);
    }

    public function getResumeByUserId(Request $request)
    {
        $user_id = $request->all()['my_user_id'];
        $resume = $this->resume->getResumeByUserId($user_id);
        if ($resume) {
            return response()->json($resume, 200);
        }
        return response()->json([
            "message" => "Not found."
        ], 404);
    }

    public function create(Request $request)
    {
        try {
            $data = $request->all();
            $validated = Validator::make($data, $this->rulesCreationResume);
            if ($validated->fails()) {
                return response()->json($validated->messages(), 400);
            }

            $storage = Storage::disk('minio');
            $file = $request->file('file_resume');
            $file_type = '.pdf';
            $resume_path = $data['my_user_id'].'_'.$data['first_name'].'_'.'resume'.$file_type;
            if(!is_null($file)){
                $uploaded = $storage->put('/resume/'.$resume_path, file_get_contents($file), 'public');
                $data['path_file'] = $resume_path;
            }

            $created = $this->resume->createResume($data);
            return response()->json($created, 200);
        } catch (Throwable $th) {
            return 'Something Wrong!'.$th;
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $validated = Validator::make($data, $this->rulesUpdateResume);
            if ($validated->fails()) {
                return response()->json($validated->messages(), 400);
            }

            $storage = Storage::disk('minio');
            $file = $request->file('file_resume');
            $file_type = '.pdf';
            $resume_path = $data['my_user_id'].'_'.$data['first_name'].'_'.'resume'.$file_type;
            if(!is_null($file)){
                $uploaded = $storage->put('/resume/'.$resume_path, file_get_contents($file), 'public');
                $data['path_file'] = $resume_path;
            }

            $updated = $this->resume->updateResume($data);
            return response()->json($updated, 200);
        } catch (Throwable $th) {
            return 'Something Wrong!'.$th;
        }
    }

    public function destroy(Request $request, $resume_id)
    {
        try {
            $storage = Storage::disk('minio');
            $get_resume = $this->resume->getResumeById($resume_id);

            if($get_resume != 'Find not found resume.'){
                $resume_data = $get_resume->first();
                $file_type = '.pdf';
                $resume_path = $resume_data['student_id'].'_'.$resume_data['first_name'].'_'.'resume'.$file_type;

                $delete = $this->resume->deleteResumeById($resume_id);
                $message = $delete;

                if($resume_data['path_file'] == '-') {
                    $message = 'Resume has been deleted.';
                }else{
                    $deleted_resume_file = $storage->delete('/resume/'.$resume_path);
                    $message = 'Resume has been deleted.';
                }
            }else{
                return $get_resume;
            }

            return response()->json([ "message" => $message ], 200);
        } catch (Throwable $th) {
                return 'Something Wrong! '.$th;
        }
    }
}
