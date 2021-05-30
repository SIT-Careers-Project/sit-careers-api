<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'banner_id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
