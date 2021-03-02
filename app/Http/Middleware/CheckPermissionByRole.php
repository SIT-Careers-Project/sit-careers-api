<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\RolePermissionRepositoryInterface as RolePermissionRepositoryInterface;

class CheckPermissionByRole

{
    private $role_permission;

    public function __construct(RolePermissionRepositoryInterface $role_permission_repo)
    {
        $this->role_permission = $role_permission_repo;
    }

    public function handle($request, Closure $next, $role)
    {
        $data = $request->all();
        $user_id = $data['user_id'];
        $check_role_permission = $this->role_permission->getUserRolePermissions($user_id);

        if (!is_null($check_role_permission) && str_contains($role, $check_role_permission['role_name'])) {
            return $next($request);
        }
        else {
            return response()->json([
                "message" => "Access Denied"
            ], 401);
        }
    }
}
