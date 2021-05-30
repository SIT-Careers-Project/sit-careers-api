<?php

namespace App\Http\RulesValidation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Validator;

trait CompanyRules
{
    private $rulesCreationCompany = [
        'company_name_th' => 'required|string',
        'company_name_en' => 'required|string',
        'company_type' => 'required|string',
        'company_image_logo' => 'nullable|mimes:jpeg,jpg,png,gif|max:5242880',
        'description' => 'required|string',
        'about_us' => 'required|string',
        'start_business_day' => 'required|string',
        'end_business_day' => 'required|string',
        'start_business_time' => 'required|string',
        'end_business_time' => 'required|string',
        'e_mail_coordinator' => 'required|email|unique:companies',
        'e_mail_manager' => 'required|email|unique:companies',
        'website' => 'required|string',
        'tel_no' => 'required|max:10',
        'phone_no' => 'required|max:10',
        "address_one" => "required|string",
        "address_two" => "nullable|string",
        "lane" => "nullable|string",
        "road" => "nullable|string",
        "sub_district" => "required|string",
        "district" => "required|string",
        "province" => "required|string",
        "postal_code" => "required|string|max:5",
        "mou_link" => "nullable|string",
        "mou_type" => "nullable|string",
        "start_date_mou" => "nullable|string",
        "end_date_mou" => "nullable|string"
    ];

    private $rulesGetCompanyById = [
        'company_id' => 'required|string'
    ];

    private $rulesGetCompanyByUserId = [
        'my_user_id' => 'required|string'
    ];

    private $rulesUpdateCompanyById = [
        'company_id' => 'required|string'
    ];

    private $rulesRequestDelete = [
        'my_user_id' => 'required|string'
    ];
}
