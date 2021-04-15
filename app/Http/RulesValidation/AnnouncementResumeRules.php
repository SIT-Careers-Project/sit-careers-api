<?php

namespace App\Http\RulesValidation;

use Illuminate\Validation\Rule;

trait AnnouncementResumeRules
{
    private $ruleCreateAnnouncementResume = [
        'announcement_id' => 'required|string',
        'resume_id' => 'required|string',
        'status' => 'nullable|string',
        'note' => 'nullable|string',
    ];

    private $ruleUpdateAnnouncementResume = [
        'announcement_resume_id' => 'required|string',
        'status' => 'nullable|string',
        'note' => 'nullable|string',
    ];
}
