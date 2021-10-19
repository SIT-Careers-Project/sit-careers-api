<?php

namespace App\Repositories;

use Carbon\Carbon;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

use App\Mail\VerifyEmail;
use App\Mail\VerifyEmailWithCompany;

use App\Models\User;
use App\Models\Role;
use App\Models\DataOwner;
use App\Models\Company;

class UserRepository implements UserRepositoryInterface
{
    public function getUsers()
    {
        $users = User::join('roles', 'roles.role_id', '=', 'users.role_id')
                ->join('data_owner', 'data_owner.user_id', '=', 'users.user_id')
                ->join('companies', 'companies.company_id', '=', 'data_owner.company_id')
                ->select('users.email', 'roles.role_name', 'users.user_id', 'companies.company_name_th', 'companies.company_name_en', 'users.status')
                ->get();
        return $users;
    }

    public function getUserById($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function getUserByEmail($email)
    {
        $user = User::join('roles', 'roles.role_id', '=', 'users.role_id')
                ->where('email', $email)->first();
        return $user;
    }

    public function getUserByManager($data) {
        $users = User::join('roles', 'roles.role_id', '=', 'users.role_id')
                ->where('created_by', '=', $data->my_user_id)->get();
        return $users;
    }

    public function createUser($data)
    {
        $role = Role::find($data->role_id);
        $company = Company::find($data->company_id);
        if ($role && !is_null($company->company_id)) {
            $user = new User();
            $user->role_id = $data->role_id;
            $user->username = $data->username ? $data->username : $data->email;
            $user->password = $data->password ? Hash::make($data->password) : '-';
            $user->first_name = $data->first_name ? $data->first_name : '-';
            $user->last_name = $data->last_name ? $data->last_name : '-';
            $user->email = $data->email;
            $user->status = 'deactivate';
            $user->created_by = $data->my_user_id;
            $user->save();

            $urlVerify = URL::temporarySignedRoute(
                'verification.verify', now()->addDays(7), ['user_id' => $user->user_id]
            );

            $dataOwner = new DataOwner();
            $dataOwner->user_id = $user->user_id;
            $dataOwner->company_id = $company->company_id;
            $dataOwner->request_delete = false;
            $dataOwner->save();

            Mail::to($user->email)->send(new VerifyEmailWithCompany($user, $company, $urlVerify));

            return "Create user successful.";
        }
        return "Not fond role or company";
    }

    public function createViewerUser($data)
    {
        $role = Role::find($data->role_id);
        if ($role) {
            $user = new User();
            $user->role_id = $data->role_id;
            $user->username = $data->username ? $data->username : $data->email;
            $user->password = $data->password ? Hash::make($data->password) : '-';
            $user->first_name = $data->first_name ? $data->first_name : '-';
            $user->last_name = $data->last_name ? $data->last_name : '-';
            $user->email = $data->email;
            $user->status = 'deactivate';
            $user->created_by = $data->my_user_id;
            $user->save();

            $urlVerify = URL::temporarySignedRoute(
                'verification.verify', now()->addDays(7), ['user_id' => $user->user_id]
            );

            Mail::to($user->email)->send(new VerifyEmail($user, $urlVerify));

            return "Create user successful.";
        }
        return "Not fond role id";
    }

    public function createUserByManger($data) {
        $role = Role::where('role_name', 'coordinator')->first();
        $dataOwnerManager = DataOwner::where('user_id', $data->my_user_id)->first();
        $companyOwner = DataOwner::where('company_id', $dataOwnerManager->company_id)->get();
        if (count($companyOwner) >= 4) {
            return "Limit add user. Please contact admin.";
        }
        $company = Company::find($dataOwnerManager->company_id);

        if ($role && !is_null($company->company_id)) {
            $user = new User();
            $user->role_id = $role->role_id;
            $user->username = $data->username ? $data->username : $data->email;
            $user->password = $data->password ? Hash::make($data->password) : '-';
            $user->first_name = $data->first_name ? $data->first_name : '-';
            $user->last_name = $data->last_name ? $data->last_name : '-';
            $user->email = $data->email;
            $user->status = 'deactivate';
            $user->created_by = $data->my_user_id;
            $user->save();

            $urlVerify = URL::temporarySignedRoute(
                'verification.verify', now()->addDays(7), ['user_id' => $user->user_id]
            );

            $dataOwner = new DataOwner();
            $dataOwner->user_id = $user->user_id;
            $dataOwner->company_id = $dataOwnerManager->company_id;
            $dataOwner->request_delete = false;
            $dataOwner->save();

            Mail::to($user->email)->send(new VerifyEmailWithCompany($user, $company, $urlVerify));

            return "Create user successful.";
        }
        return "Not found role id.";
    }

    public function createUserStudentByEmail($data, $role)
    {
        $role = Role::where('role_name', $role)->first();
        $user = new User();

        $user->role_id = $role->role_id;
        $user->username = $data->user_id;
        $user->email = $data->email;
        $user->first_name = explode(" ", $data->name_en)[0];
        $user->last_name = explode(" ", $data->name_en)[1];
        $user->token = $data->token->token;
        $user->created_by = '-';
        $user->save();

        return $user;
    }

    public function updateUser($data)
    {
        $user = User::find($data->user_id);
        $user->role_id = $data->role_id;
        $user->username = $data->username ? $data->username : '-';
        $user->password = $data->password ? Hash::make($data->password) : '-';
        $user->first_name = $data->first_name ? $data->first_name : '-';
        $user->last_name = $data->last_name ? $data->last_name : '-';
        $user->email = $data->email;
        $user->status = $data->status ? $data->status : '-';
        $user->created_by = $data->created_by ? $data->created_by : '-';
        $user->save();

        return $user;
    }

    public function updateStaff($data)
    {
        $user = User::where('email', $data->email)->first();
        $user->username = $data->user_id;
        $user->first_name = explode(" ", $data->name_en)[0];
        $user->last_name = explode(" ", $data->name_en)[1];
        $user->token = $data->token->token;
        $user->created_by = '-';
        $user->status = "active";
        $user->save();

        return $user;
    }

    public function updateUserStudent($data, $role)
    {
        $user = User::where([
            'username' => $data->user_id,
            'email' => $data->email
        ])->first();

        $user->role_id = $user->role_id;
        $user->username = $data->user_id;
        $user->password = '-';
        $user->email = $data->email;
        $user->first_name = explode(" ", $data->name_en)[0];
        $user->last_name = explode(" ", $data->name_en)[1];
        $user->token = $data->token->token;
        $user->created_by = '-';
        $user->status = "active";
        $user->save();

        return $user;
    }

    public function updateUserFirstTime($data)
    {
        $user = User::find($data->my_user_id);
        $user->role_id = $user->role_id;
        $user->password = Hash::make($data->password);
        $user->status = 'active';
        $user->save();

        return $user;
    }

    public function deleteUserByUserId($data)
    {
        $users = $data['data'];
        for ($i=0; $i < count($users); $i++) {
            $user = User::find($users[$i]['user_id']);
            if ($user) {
                $user = $user->delete();
            } else {
                return "Find not found User.";
            }
        }

        return true;
    }
}
