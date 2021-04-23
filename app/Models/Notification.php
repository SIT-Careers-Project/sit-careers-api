<?php

namespace App\Models;

use App\Traits\NotificationUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    Use NotificationUuid;
    use SoftDeletes;

    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    public $incrementing = false;

    protected $keyType = 'uuid';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    public function User()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
