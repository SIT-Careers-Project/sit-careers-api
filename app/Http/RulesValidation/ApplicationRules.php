<?php

namespace App\Http\RulesValidation;

use Illuminate\Contracts\Validation\Rule;

trait ApplicationRules
{
    private $rulesCreationApplication = [
        'announcement_id' => 'required|string',
        'application_date' => 'nullable|string',
        'status' => 'nullable|string',
        'name_title' => 'required|string',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'curriculum' => 'required|string',
        'year' => 'required|string',
        'tel_no' => 'required|string',
        'email' => 'required|email',
    ];
    private $rulesUpdateApplication = [
        'announcement_id' => 'required|string',
        'application_id' => 'required|string',
    ];

    private $rulesDeleteApplication = [
        'announcement_id' => 'required|string',
    ];
}
