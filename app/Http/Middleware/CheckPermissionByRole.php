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

    public function handle($request, Closure $next, $permission)
    {
        $data = $request->all();
        $check_role_permission = $this->role_permission->getUserRolePermissions($data, $permission);

        if (!$check_role_permission->isEmpty()) {
            if (str_contains($permission, $check_role_permission[0]['permission_name'])) {
                return $next($request);
            }
        }
        return response()->json([
            "message" => "Access Denied"
        ], 401);
    }
}
