<?php

namespace App\Repositories;

interface RolePermissionRepositoryInterface
{
    public function getUserRolePermissions($user_id);
}
