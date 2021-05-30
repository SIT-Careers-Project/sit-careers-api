<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\JopPositionUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosition extends Model
{
    Use JopPositionUuid;
    use SoftDeletes;

    protected $table = 'job_positions';
    protected $primaryKey = 'job_position_id';
    public $incrementing = false;

    protected $keyType = 'uuid';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    public function announcement()
    {
        return $this->hasOne('App\Models\Announcement', 'announcement_id');
    }
}
