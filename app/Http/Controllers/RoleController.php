<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;
use App\Repositories\RolePermissionRepositoryInterface;
use App\Repositories\RoleRepositoryInterface;

class RoleController extends Controller
{
    private $role;
    private $role_permission;

    public function __construct(RoleRepositoryInterface $roleRepo, RolePermissionRepositoryInterface $role_permission_repo)
    {
        $this->role = $roleRepo;
        $this->role_permission = $role_permission_repo;
    }

    public function get(Request $request)
    {
        $jobs = $this->role->getRoles();
        return response()->json($jobs, 200);
    }

    public function getRolePermissions(Request $request)
    {
        $role_permission = $request->all();
        $role_permission = $this->role_permission->getRolePermissions();
        return response()->json($role_permission, 200);
    }
}
