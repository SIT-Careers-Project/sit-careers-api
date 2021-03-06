<?php

namespace App\Http\RulesValidation;

trait DashboardRules
{
    private $ruleExport = [
        'start_date' => 'required|string',
        'end_date' => 'required|string',
        'name_reports' => 'required|array|min:1|max:3'
    ];
}
