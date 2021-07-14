<?php

namespace App\Http\RulesValidation;

use Illuminate\Contracts\Validation\Rule;

trait UserRules
{
    private $rulesCreationUser = [
        'role_id' => 'required|string',
        'email' => 'required|email|unique:users',
        'company_id' => 'required|string',
    ];

    private $rulesUpdateUser = [
        'role_id' => 'required|string',
        'company_id' => 'required|string',
        'user_id' => 'required|string',
        'email' => 'required|email|unique:users'
    ];

    private $rulesCreationViewerUser = [
        'role_id' => 'required|string',
        'email' => 'required|email|unique:users'
    ];
}
