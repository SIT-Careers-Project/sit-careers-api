<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PermissionUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    Use PermissionUuid;

    protected $table = 'permissions';
    protected $primaryKey = 'permission_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'uuid';

    public function rolePermission()
    {
        return $this->belongsTo('App\Models\RolePermission', 'role_permission_id');
    }
}
