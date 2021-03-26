<?php

namespace App\Http\RulesValidation;

use Illuminate\Contracts\Validation\Rule;

trait ResumeRules
{
    private $rulesCreationResume = [
        'resume_date' => 'nullable|string',
        'name_title' => 'required|string',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'curriculum' => 'required|string',
        'year' => 'required|string',
        'tel_no' => 'required|string',
        'email' => 'required|email',
    ];
    private $rulesUpdateResume = [
        'resume_id' => 'required|string',
    ];

    private $rulesDeleteResume = [
        'resume_id' => 'required|string',
    ];
}
