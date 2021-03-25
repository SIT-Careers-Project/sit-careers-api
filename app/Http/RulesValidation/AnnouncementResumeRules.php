<?php

namespace App\Http\RulesValidation;

trait AnnouncementResumeRules
{
    private $ruleCreateAnnouncementResume = [
        'announcement_id' => 'required|string|unique:announcement_resumes',
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
