<?php

namespace App\Http\RulesValidation;

trait AnnouncementResumeRules
{
    private $ruleCreateAnnouncementResume = [
        'announcement_id' => 'required|string',
        'resume_id' => 'required|string',
        'status' => 'nullable|string',
        'note' => 'nullable|string',
    ];
}
