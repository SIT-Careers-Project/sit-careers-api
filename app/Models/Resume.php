<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ResumeUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resume extends Model
{
    Use ResumeUuid;
    use SoftDeletes;

    protected $table = 'resumes';
    protected $primaryKey = 'resume_id';
    public $incrementing = false;

    protected $keyType = 'uuid';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';
}
