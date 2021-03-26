<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AnnouncementUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    Use AnnouncementUuid;
    use SoftDeletes;

    protected $table = 'announcements';
    protected $primaryKey = 'announcement_id';
    public $incrementing = false;

    protected $keyType = 'uuid';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function jobPosition()
    {
        return $this->belongsTo('App\Models\JobPosition', 'job_position_id');
    }

    public function jobType()
    {
        return $this->hasMany('App\Models\JobType', 'announcement_id');
    }

    public function AnnouncementResume()
    {
        return $this->hasMany('App\Models\AnnouncementResume', 'announcement_resumes_id');
    }
}
