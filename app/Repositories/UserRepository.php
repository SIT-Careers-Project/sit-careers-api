<?php

namespace App\Repositories;

use Carbon\Carbon;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Role;

class UserRepository implements UserRepositoryInterface
{
    public function getUsers()
    {
        $users = User::join('roles', 'roles.role_id', '=', 'users.role_id')
                ->select('users.email', 'roles.role_name')
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
        $user = User::where('email', $email)->first();
        return $user;
    }

    public function createUser($data)
    {
        $user = new User();
        $user->role_id = $data->role_id;
        $user->username = $data->username ? $data->username : '-';
        $user->password = $data->password ? Hash::make($data->password) : '-';
        $user->first_name = $data->first_name ? $data->first_name : '-';
        $user->last_name = $data->last_name ? $data->last_name : '-';
        $user->email = $data->email;
        $user->created_by = $data->created_by ? $data->created_by : '-';
        $user->save();

        return $user;
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
        $user->created_by = $data->created_by ? $data->created_by : '-';
        $user->save();

        return $user;
    }

    public function deleteUserByUserId($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            $user = $user->delete();
            return $user;
        }
         
        return "Find not found User.";
    }
}