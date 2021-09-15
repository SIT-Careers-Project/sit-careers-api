<?php

namespace App\Repositories;

use Carbon\Carbon;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Mail\RequestDelete;
use App\Mail\CompanyDeleted;
use App\Mail\AdminRequestDelete;

use App\Models\Address;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Models\DataOwner;
use App\Models\MOU;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function getCompanyById($id)
    {
        $company = Company::join('mou', 'mou.company_id', '=', 'companies.company_id')
        ->join('addresses', 'addresses.company_id', '=', 'companies.company_id')
        ->where('companies.company_id', $id)
        ->first();
        return $company;
    }

    public function getAllCompanies()
    {
        $companies = Company::join('mou', 'mou.company_id', '=', 'companies.company_id')
            ->join('addresses', 'addresses.company_id', '=', 'companies.company_id')
            ->where('addresses.address_type', '=', 'company')
            ->get();
        return $companies;
    }

    public function getCompaniesByUserId($user_id) {
        $companies = Company::join('data_owner', 'data_owner.company_id', '=', 'companies.company_id')
                    ->join('mou', 'mou.company_id', '=', 'companies.company_id')
                    ->where('data_owner.user_id', '=', $user_id)
                    ->get();
        return $companies;
    }

    public function createCompany($data)
    {
        $company = new Company();
        $company->company_name_th = $data['company_name_th'];
        $company->company_name_en = $data['company_name_en'];
        $company->company_type = $data['company_type'] == "" ? "": $data['company_type'];
        $company->description = $data['description'] == "" ? "": $data['description'];
        $company->about_us = $data['about_us'] == "" ? "": $data['about_us'];
        $company->logo = $data['logo'] == "" ? "-": $data['logo'];
        $company->e_mail_manager = $data['e_mail_manager'] == "" ? "": $data['e_mail_manager'];
        $company->e_mail_coordinator = $data['e_mail_coordinator'] == "" ? "": $data['e_mail_coordinator'];
        $company->tel_no = $data['tel_no'] == "" ? "-": $data['tel_no'];
        $company->phone_no = $data['phone_no'] == "" ? "-": $data['phone_no'];
        $company->website = $data['website'] == "" ? "-": $data['website'];
        $company->start_business_day = $data['start_business_day'] == "" ? "": $data['start_business_day'];
        $company->end_business_day = $data['end_business_day'] == "" ? "": $data['end_business_day'];
        $company->start_business_time = $data['start_business_time'] == "" ? "": $data['start_business_time'];
        $company->end_business_time = $data['end_business_time'] == "" ? "": $data['end_business_time'];
        $company->save();

        $address = new Address();
        $address->address_one = $data['address_one'] == "" ? "": $data['address_one'];
        $address->address_two = $data['address_two'] == "" ? "-": $data['address_two'];
        $address->lane = $data['lane'] == "" ? "-": $data['lane'];
        $address->road = $data['road'] == "" ? "-": $data['road'];
        $address->sub_district = $data['sub_district'] == "" ? "": $data['sub_district'];
        $address->district = $data['district'] == "" ? "": $data['district'];
        $address->province = $data['province'] == "" ? "": $data['province'];
        $address->postal_code = $data['postal_code'] == "" ? "": $data['postal_code'];
        $address->address_type = 'company';
        $address->company_id = $company->company_id;
        $address->save();

        $userID = array_key_exists('user_id', $data) ? $data['user_id'] : $data['my_user_id'];

        $user = User::find($userID);
        $role = Role::find($user->role_id);
        if ($role->role_name === 'admin') {
            $mou = new MOU();
            $mou->company_id = $company->company_id;
            $mou->mou_link = $data['mou_link'] == "" ? "-": $data['mou_link'];
            $mou->mou_type = $data['mou_type'] == "" ? "-": $data['mou_type'];
            $mou->start_date_mou = $data['start_date_mou'] == "" ? "-": $data['start_date_mou'];
            $mou->end_date_mou = $data['end_date_mou'] == "" ? "-": $data['end_date_mou'];
            $mou->save();
        } else {
            $mou = new MOU();
            $mou->company_id = $company->company_id;
            $mou->mou_link = '-';
            $mou->mou_type = '-';
            $mou->start_date_mou = '-';
            $mou->end_date_mou = '-';
            $mou->save();
        }

        return array_merge($company->toArray(),  $address->toArray(), $mou->toArray());
    }

    public function updateCompanyById($data)
    {
        $id = $data['company_id'];
        $company = Company::find($id);
        $roleAdminId = Role::where('role_name', 'admin')->first();

        if ($roleAdminId->role_id == $data['my_role_id']) {
            $company->company_name_th = $data['company_name_th'];
            $company->company_name_en = $data['company_name_en'];
        }

        $company->company_type = $data['company_type'];
        $company->description = $data['description'];
        $company->about_us = $data['about_us'];
        $company->logo = $data['logo'] == "" ? "-": $data['logo'];
        $company->e_mail_manager = $data['e_mail_manager'];
        $company->e_mail_coordinator = $data['e_mail_coordinator'];
        $company->tel_no = $data['tel_no'] == "" ? "-": $data['tel_no'];
        $company->phone_no = $data['phone_no'] == "" ? "-": $data['phone_no'];
        $company->website = $data['website'] == "" ? "-": $data['website'];
        $company->start_business_day = $data['start_business_day'];
        $company->end_business_day = $data['end_business_day'];
        $company->start_business_time = $data['start_business_time'];
        $company->end_business_time = $data['end_business_time'];
        $company->save();

        $address = Address::where([
            ['company_id', $id],
            ['address_type', 'company']
        ])->first();
        $address->address_one = $data['address_one'];
        $address->address_two = $data['address_two'] == "" ? "-": $data['address_two'];
        $address->lane = $data['lane'] == "" ? "-": $data['lane'];
        $address->road = $data['road'] == "" ? "-": $data['road'];
        $address->sub_district = $data['sub_district'];
        $address->district = $data['district'];
        $address->province = $data['province'];
        $address->postal_code = $data['postal_code'];
        $address->address_type = 'company';
        $address->company_id = $company->company_id;
        $address->save();

        $user = User::find($data['my_user_id']);
        $role = Role::find($user->role_id);
        if ($role->role_name === 'admin') {
            $mou = MOU::where('company_id', $id)->first();
            $mou->company_id = $company->company_id;
            $mou->mou_link = $data['mou_link'] == "" ? "-": $data['mou_link'];
            $mou->mou_type = $data['mou_type'] == "" ? "-": $data['mou_type'];
            $mou->start_date_mou = $data['start_date_mou'] == "" ? "-": $data['start_date_mou'];
            $mou->end_date_mou = $data['end_date_mou'] == "" ? "-": $data['end_date_mou'];
            $mou->save();
            return array_merge($company->toArray(),  $address->toArray(), $mou->toArray());
        }

        return array_merge($company->toArray(),  $address->toArray());
    }

    public function requestDelete($data)
    {
        $user_id = $data['my_user_id'];
        $dataOwner = DataOwner::where('user_id', $user_id)->first();

        if(is_null($dataOwner)){
            return "Find not found company or user.";
        }

        $dataOwner->user_id = $dataOwner->user_id;
        $dataOwner->company_id = $dataOwner->company_id;
        $dataOwner->request_delete = true;
        $dataOwner->save();

        $userRequest = User::find($dataOwner->user_id);
        $company = Company::find($dataOwner->company_id);
        $dataOwnerCompany = DataOwner::where('company_id', $company->company_id)->get();

        for ($i=0; $i < count($dataOwnerCompany); $i++) {
            $user = User::find($dataOwnerCompany[$i]->user_id);
            $sendMailToCompany = Mail::to($user->email)->send(new RequestDelete($user, $userRequest, $company));
        }

        $userAdmin = User::join('roles', 'roles.role_id', '=', 'users.role_id')->where('roles.role_name', 'admin')->get();
        for ($i=0; $i < count($userAdmin); $i++) {
            $sendMailToAdmin = Mail::to($userAdmin[$i]->email)->send(new AdminRequestDelete($userAdmin[$i], $userRequest, $company));
        }

        return "Request to delete has been success.";
    }

    public function deleteCompanyById($id)
    {
        $company = Company::find($id);
        $address = Address::where('company_id', $id)->first();
        $mou = MOU::where('company_id', $id)->first();

        $dataOwnerCompany = DataOwner::where('company_id', $id)->get();
        if($company && $address && $mou && $dataOwnerCompany) {
            $deleted_company = $company->delete();
            $deleted_address = $address->delete();
            $deleted_mou = $mou->delete();

            for ($i=0; $i < count($dataOwnerCompany); $i++) {
                $user = User::find($dataOwnerCompany[$i]->user_id);
                $sendMailToCompany = Mail::to($user->email)->send(new CompanyDeleted($user, $company));
            }

            return $deleted_company && $deleted_address && $deleted_mou;
        }

        return "Find not found company or mou or address.";
    }
}
