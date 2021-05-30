<?php

namespace App\Models;

use App\Traits\JopTypeUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobType extends Model
{
    Use JopTypeUuid;
    use SoftDeletes;

    protected $table = 'job_types';
    protected $primaryKey = 'job_id';
    public $incrementing = false;

    protected $keyType = 'uuid';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    public function announcemnet()
    {
        return $this->belongTo('App\Models\Announcement', 'announcement_id');
    }
}
