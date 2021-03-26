<?php


namespace App\Traits;

use Illuminate\Support\Str;

trait AnnouncementResumeUuid
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->announcement_resume_id = (string) Str::uuid();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
