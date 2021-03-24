<?php

namespace App\Models;

use App\Traits\AnnouncementResumeUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnnouncementResume extends Model
{
    Use AnnouncementResumeUuid;
    use SoftDeletes;

    protected $table = 'announcement_resumes';
    protected $primaryKey = 'announcement_resumes_id';
    public $incrementing = false;

    protected $keyType = 'uuid';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    public function Announcement()
    {
        return $this->hashOne('App\Models\Announcement', 'announcement_id');
    }

    public function Resume()
    {
        return $this->hashOne('App\Models\Resume', 'resume_id');
    }
}
