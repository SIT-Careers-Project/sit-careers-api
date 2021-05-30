<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RolePermissionUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolePermission extends Model
{
    Use RolePermissionUuid;

    protected $table = 'role_permissions';
    protected $primaryKey = 'role_permission_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'uuid';

    public function role()
    {
        return $this->hasOne('App\Models\Role', 'role_id');
    }

    public function permission()
    {
        return $this->hasOne('App\Models\Permission', 'permission_id');
    }
}
