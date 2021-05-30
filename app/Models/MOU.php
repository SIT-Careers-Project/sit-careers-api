<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MOUUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class MOU extends Model
{
    Use MOUUuid;
    use SoftDeletes;

    protected $table = 'mou';
    protected $primaryKey = 'mou_id';
    public $incrementing = false;

    protected $keyType = 'uuid';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at'; 
    const DELETED_AT = 'deleted_at';

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
}
