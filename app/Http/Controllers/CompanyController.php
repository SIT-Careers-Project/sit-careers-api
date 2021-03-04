<?php

namespace App\Http\Controllers;

use Validator;
use Storage;
use Config;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

use App\Http\Controllers\Controller as Controller;
use App\Repositories\CompanyRepositoryInterface;
use App\Http\RulesValidation\CompanyRules;


class CompanyController extends Controller
{
    use CompanyRules;
    private $company;

    public function __construct(CompanyRepositoryInterface $company_repo)
    {
        $this->company = $company_repo;
    }

    public function get(Request $request)
    {
        $id = $request->all();
        $validated = Validator::make($id, $this->rulesGetCompanyById);
        if ($validated->fails()) {
            return response()->json($validated->messages(), 400);
        }
        $company = $this->company->getCompanyById($id['company_id']);
        return response()->json($company, 200);
    }

    public function getCompanies(Request $request)
    {
        $companies = $this->company->getAllCompanies();
        return response()->json($companies, 200);
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $validated = Validator::make($data, $this->rulesCreationCompany);
        if ($validated->fails()) {
            return response()->json($validated->messages(), 400);
        }
        $storage = Storage::disk('minio');
        $companyName = str_replace(' ', '_', $data['company_name_en']);
        $companyNamePath = $companyName.'_'.rand(10000, 99999);
        $file = $request->file('company_logo_image');
        if (!is_null($file)) {
            $uploaded = $storage->put('/logo/'.$companyNamePath, file_get_contents($file), 'public');
            $data['logo'] = $companyNamePath;
        }
        $companies = $this->company->createCompany($data);
        return response()->json($companies, 200);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $validated = Validator::make($data, $this->rulesUpdateCompanyById);
        if ($validated->fails()) {
            return response()->json($validated->messages(), 400);
        }
        $companyNamePath = $data['logo'];
        $storage = Storage::disk('minio');
        $file = $request->file('company_logo_image');
        $companyName = str_replace(' ', '_', $data['company_name_en']);
        $companyNamePath = $companyName.'_'.rand(10000, 99999);

        if ($data['logo'] == '-' && !is_null($file)) {
            $uploaded = $storage->put('/logo/'.$companyNamePath, file_get_contents($file), 'public');
            $data['logo'] = $companyNamePath;
        } else if ($data['logo'] !== '-' && !is_null($file)) {
            $uploaded = $storage->delete('/logo/'. $data['logo']);
            $uploaded = $storage->put('/logo/'. $companyNamePath, file_get_contents($file), 'public');
            $data['logo'] = $companyNamePath;
        }

        $updated = $this->company->updateCompanyById($data);
        return response()->json($updated, 200);
    }

    public function destroy(Request $request)
    {
        $id = $request->all()['company_id'];
        $deleted = $this->company->deleteCompanyById($id);
        return response()->json($deleted, 200);
    }
}
