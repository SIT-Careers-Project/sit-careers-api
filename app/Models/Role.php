<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RoleUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    Use RoleUuid;

    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'uuid';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function rolePermission()
    {
        return $this->belongsTo('App\Models\RolePermission', 'role_permission_id');
    }
}
