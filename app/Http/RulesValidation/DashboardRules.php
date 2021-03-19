<?php

namespace App\Http\RulesValidation;

trait DashboardRules
{
    private $ruleExport = [
        'start_date' => 'required|string',
        'end_date' => 'required|string'
    ];
}
