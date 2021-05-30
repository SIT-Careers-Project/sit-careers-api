<?php

namespace App\Repositories;

use Carbon\Carbon;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

use App\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function getRoles()
    {
        $roles = Role::all();
        return $roles;
    }
}