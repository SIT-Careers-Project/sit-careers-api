<?php

namespace App\Http\RulesValidation;

use Illuminate\Contracts\Validation\Rule;

trait AnnouncementRules
{
    private $rulesCreationAnnouncement = [
        'announcement_title' => 'required|string',
        'job_description' => 'required|string',
        'property' => 'required|string',
        'file_picture' => 'nullable|mimes:jpeg,jpg,png,gif|max:5242880',
        'picture' => 'nullable|string',
        'start_date' => 'required|string',
        'end_date' => 'required|string',
        'salary' => 'required|string',
        'welfare' => 'required|string',
        'status' => 'required|string',
        'job_type' => 'required|array|min:1|max:4',
        'address_one' => 'required|string',
        'address_two' => 'nullable|string',
        'lane' => 'nullable|string',
        'road' => 'nullable|string',
        'sub_district' => 'required|string',
        'district' => 'required|string',
        'province' => 'required|string',
        'postal_code' => 'required|string',
        'start_business_day' => 'required|string|max:20',
        'end_business_day' => 'required|string|max:20',
        'start_business_time' => 'required|string|max:6',
        'end_business_time' => 'required|string|max:6'
    ];

    private $rulesGetAnnouncementById = [
        'announcement_id' => 'required|string'
    ];

    private $rulesUpdateAnnouncementById = [
        'announcement_id' => 'required|string',
        'announcement_title' => 'required|string',
        'job_description' => 'required|string',
        'property' => 'required|string',
        'file_picture' => 'nullable|mimes:jpeg,jpg,png,gif|max:5242880',
        'picture' => 'nullable|string',
        'start_date' => 'required|string',
        'end_date' => 'required|string',
        'salary' => 'required|string',
        'welfare' => 'required|string',
        'status' => 'required|string',
        'job_type' => 'required|array|min:1|max:4',
        'job_position_id' => 'required|string',
        'address_id' => 'required|string',
        'address_one' => 'required|string',
        'address_two' => 'nullable|string',
        'lane' => 'nullable|string',
        'road' => 'nullable|string',
        'sub_district' => 'required|string',
        'district' => 'required|string',
        'province' => 'required|string',
        'postal_code' => 'required|string',
        'start_business_day' => 'required|string|max:20',
        'end_business_day' => 'required|string|max:20',
        'start_business_time' => 'required|string|max:6',
        'end_business_time' => 'required|string|max:6'
    ];
}
