<?php

namespace App\Repositories;

interface RolePermissionRepositoryInterface
{
    public function getUserRolePermissions($user_id);
    public function getRolePermissions();
    public function getRolePermissionsByUserId($user_id);
    public function getRolePermissions();
}
