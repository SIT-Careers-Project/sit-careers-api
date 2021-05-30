<?php

namespace App\Http\RulesValidation;

use Illuminate\Contracts\Validation\Rule;

trait BannerRules
{
    private $ruleCreationBanner = [
        'path_image' => 'nullable|string',
        'file_banner' => 'nullable|mimes:jpeg,jpg,png,gif|max:5242880'
    ];

    private $ruleDeletionBanner = [
        'banner_id' => 'required|string',
        'path_image' => 'required|string'
    ];
}
