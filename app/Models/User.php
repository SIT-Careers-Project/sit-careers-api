<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UserUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    Use UserUuid;
    use SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $hidden = ['password', 'first_name', 'last_name', 'created_at', 'updated_at', 'deleted_at'];
    public $incrementing = false;

    protected $keyType = 'uuid';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    public function history()
    {
        return $this->belongsTo('App\Models\History', 'history_id');
    }

    public function Resumes()
    {
        return $this->belongsTo('App\Models\Resume', 'resume_id');
    }

    public function Notification()
    {
        return $this->hasMany('App\Models\Notification', 'notification_id');
    }

    public function DataOwner()
    {
        return $this->hasMany('App\Models\DataOwner', 'user_id');
    }
}
