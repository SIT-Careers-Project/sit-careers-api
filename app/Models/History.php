<?php

namespace App\Models;

use App\Traits\HistoryUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Model
{
    Use HistoryUuid;
    use SoftDeletes;

    protected $table = 'histories';
    protected $primaryKey = 'history_id';
    public $incrementing = false;

    protected $keyType = 'uuid';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    public function announcement()
    {
        return $this->belongsTo('App\Models\Announcement', 'announcement_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
