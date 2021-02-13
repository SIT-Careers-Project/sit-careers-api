<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('company', 'CompanyController@get');
Route::post('company', 'CompanyController@create');
Route::put('company', 'CompanyController@update');
Route::delete('company', 'CompanyController@destroy');

Route::prefix('academic-industry')->group(function () {
    Route::get('job-positions', 'JobPositionController@get');
    Route::get('announcement', 'AnnouncementController@get');
    Route::get('announcements/{company_id}', 'AnnouncementController@getAnnouncementByCompanyId');
    Route::get('announcements', 'AnnouncementController@getAnnouncements');
    Route::post('announcement', 'AnnouncementController@create');
    Route::put('announcement', 'AnnouncementController@update');
    Route::delete('announcement', 'AnnouncementController@destroy');

    Route::get('applications', 'ApplicationController@get');
    Route::get('application/{application_id}', 'ApplicationController@getApplicationById');
    Route::post('application', 'ApplicationController@create');
    Route::put('application', 'ApplicationController@update');
    Route::delete('application/{application_id}', 'ApplicationController@destroy');
});

// keep for dashboard feature
Route::get('companies', 'CompanyController@getCompanies');

Route::get('users', 'UserController@get');
Route::get('user/{user_id}', 'UserController@getUserById');
Route::post('user', 'UserController@create');
Route::put('user', 'UserController@update');
Route::delete('user/{user_id}', 'UserController@destroy');

Route::get('roles', 'RoleController@get');

Route::get('histories', 'HistoryController@getHistories');

Route::get('banners', 'BannerController@get');
Route::get('banner', 'BannerController@getBannerById');
