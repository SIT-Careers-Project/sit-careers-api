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
                ->select('users.email', 'roles.role_name', 'users.user_id')
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
                'verification.verify', now()->addHours(24), ['user_id' => $user->user_id]
            );

            if (is_null($data->company_id)) {
                Mail::to($user->email)->send(new VerifyEmail($user, $urlVerify));
            } else {
                $company = Company::find($data->company_id);
                $dataOwner = new DataOwner();
                $dataOwner->user_id = $user->user_id;
                $dataOwner->company_id = $company->company_id;
                $dataOwner->request_delete = false;
                $dataOwner->save();

                Mail::to($user->email)->send(new VerifyEmailWithCompany($user, $company, $urlVerify));
            }

            return "Create user successful.";
        }
        return "Not fond role id.";
    }

    public function createUserByManger($data) {
        $role = Role::where('role_name', 'coordinator')->first();
        if ($role) {
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
                'verification.verify', now()->addHours(24), ['user_id' => $user->user_id]
            );

            $dataOwnerManager = DataOwner::where('user_id', $data->my_user_id)->first();

            if (is_null($dataOwnerManager)) {
                Mail::to($user->email)->send(new VerifyEmail($user, $urlVerify));
            } else {
                $company = Company::find($dataOwnerManager->company_id);
                $dataOwner = new DataOwner();
                $dataOwner->user_id = $user->user_id;
                $dataOwner->company_id = $dataOwnerManager->company_id;
                $dataOwner->request_delete = false;
                $dataOwner->save();

                Mail::to($user->email)->send(new VerifyEmailWithCompany($user, $company, $urlVerify));
            }

            return "Create user successful.";
        }
        return "Not fond role id.";
    }

    public function createUserฺStudentByEmail($data, $role)
    {
        $role = Role::where('role_name', $role)->first();
        $user = new User();

        $user->role_id = $role->role_id;
        $user->username = $data->user_id;
        $user->email = $data->email;
        $user->first_name = explode(" ", $data->name_th)[0];
        $user->last_name = explode(" ", $data->name_th)[1];
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
        $user->first_name = explode(" ", $data->name_th)[0];
        $user->last_name = explode(" ", $data->name_th)[1];
        $user->token = $data->token->token;
        $user->created_by = '-';
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