<?php

namespace App\Http\RulesValidation;

trait ResumeRules
{
    private $rulesCreationResume = [
        'resume_date' => 'nullable|string',
        'my_user_id' => 'unique:resumes,student_id',
        'name_title' => 'required|string',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'curriculum' => 'required|string',
        'year' => 'required|string',
        'tel_no' => 'required|string',
        'email' => 'required|email'
    ];
    private $rulesUpdateResume = [
        'resume_id' => 'required|string',
    ];

    private $rulesDeleteResume = [
        'resume_id' => 'required|string',
    ];
}
