<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthRepository implements AuthRepositoryInterface
{
    public function getUser($data)
    {
        return User::where('username', $data['username'])->first();
    }
}