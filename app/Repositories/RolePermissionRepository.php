<?php

namespace App\Repositories;

use App\Models\RolePermission;

class  RolePermissionRepository implements RolePermissionRepositoryInterface
{
    public function getUserRolePermissions($data, $permission)
    {
        $user_role_permission = RolePermission::join('roles', 'roles.role_id', '=', 'role_permissions.role_id')
                                ->join('permissions', 'permissions.permission_id', '=', 'role_permissions.permission_id')
                                ->join('users', 'users.role_id', '=', 'role_permissions.role_id')
                                ->where([
                                    ['users.user_id', '=', $data['my_user_id']],
                                    ['roles.role_id', '=', $data['my_role_id']],
                                    ['permissions.permission_name', $permission]
                                ])->get();
        return $user_role_permission;
    }

    public function getRolePermissions()
    {
        $role_permission = RolePermission::join('roles', 'roles.role_id', '=', 'role_permissions.role_id')
                            ->join('permissions', 'permissions.permission_id', '=', 'role_permissions.permission_id')
                            ->get();
        return $role_permission;
    }

    public function getRolePermissionsByUserId($user_id)
    {
        $user_role_permission = RolePermission::join('roles', 'roles.role_id', '=', 'role_permissions.role_id')
                                ->join('permissions', 'permissions.permission_id', '=', 'role_permissions.permission_id')
                                ->join('users', 'users.role_id', '=', 'role_permissions.role_id')
                                ->where('users.user_id', $user_id)
                                ->select('permissions.*', 'roles.*')
                                ->get();
        return $user_role_permission;
    }
}