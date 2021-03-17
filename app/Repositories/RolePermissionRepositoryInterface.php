<?php

namespace App\Repositories;

interface RolePermissionRepositoryInterface
{
    public function getUserRolePermissions($data, $permission);
    public function getRolePermissions();
    public function getRolePermissionsByUserId($user_id);
}