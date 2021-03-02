<?php

namespace App\Repositories;

use App\Models\RolePermission;

class  RolePermissionRepository implements RolePermissionRepositoryInterface
{
    public function getUserRolePermissions($user_id)
    {
        $user_role_permission = RolePermission::join('roles', 'roles.role_id', '=', 'role_permissions.role_id')
                                ->join('permissions', 'permissions.permission_id', '=', 'role_permissions.permission_id')
                                ->join('users', 'users.role_id', '=', 'role_permissions.role_id')
                                ->where('users.user_id', $user_id)->first();
        return $user_role_permission;
    }
}
