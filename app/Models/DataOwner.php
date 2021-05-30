<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\DataOwnerUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataOwner extends Model
{
    Use DataOwnerUuid;
    use SoftDeletes;

    protected $table = 'data_owner';
    protected $primaryKey = 'data_owner_id';
    public $incrementing = false;

    protected $keyType = 'uuid';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
