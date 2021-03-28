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

Route::post('login', 'AuthController@login');
Route::post('/sit-login', 'AuthController@SITLogin');

Route::group(['middleware' => ['checkAuth']], function () {
    // Route::middleware(['role.permission:admin'])->group(function () {
        Route::get('/me', 'AuthController@me');

        Route::get('company', 'CompanyController@get')->middleware(['role.permission:access_company']);
        Route::post('company', 'CompanyController@create')->middleware(['role.permission:create_company']);
        Route::put('company', 'CompanyController@update')->middleware(['role.permission:update_company']);
        Route::put('company/request-delete', 'CompanyController@requestDelete')->middleware(['role.permission:update_company']);
        Route::delete('company/{company_id}', 'CompanyController@destroy')->middleware(['role.permission:delete_company']);

        Route::prefix('academic-industry')->group(function () {
            Route::get('job-positions', 'JobPositionController@get')->middleware(['role.permission:access_academic_announcement']);
            Route::get('announcement', 'AnnouncementController@get')->middleware(['role.permission:access_academic_announcement']);
            Route::get('announcements/{company_id}', 'AnnouncementController@getAnnouncementByCompanyId')->middleware(['role.permission:access_academic_announcement']);
            Route::get('announcements', 'AnnouncementController@getAnnouncements')->middleware(['role.permission:access_academic_announcement']);
            Route::post('announcement', 'AnnouncementController@create')->middleware(['role.permission:create_academic_announcement']);
            Route::put('announcement', 'AnnouncementController@update')->middleware(['role.permission:update_academic_announcement']);
            Route::delete('announcement', 'AnnouncementController@destroy')->middleware(['role.permission:delete_academic_announcement']);

            Route::get('resumes', 'ResumeController@get')->middleware(['role.permission:access_resume']);
            Route::get('resume/{resume_id}', 'ResumeController@getResumeById')->middleware(['role.permission:access_resume']);
            Route::get('resume', 'ResumeController@getResumeByUserId')->middleware(['role.permission:access_resume']);
            Route::post('resume', 'ResumeController@create')->middleware(['role.permission:create_resume']);
            Route::put('resume', 'ResumeController@update')->middleware(['role.permission:update_resume']);
            Route::delete('resume/{resume_id}', 'ResumeController@destroy')->middleware(['role.permission:delete_resume']);

            Route::get('application', 'AnnouncementResumesController@getAnnouncementResumeByUserId')->middleware(['role.permission:access_resume']);
            Route::post('application', 'AnnouncementResumesController@create')->middleware(['role.permission:access_resume']);
            Route::put('application', 'AnnouncementResumesController@update')->middleware(['role.permission:access_resume']);
        });

        // keep for dashboard feature
        Route::get('companies', 'CompanyController@getCompanies')->middleware(['role.permission:access_company']);

        Route::get('users', 'UserController@get')->middleware(['role.permission:access_user']);
        Route::get('user/{user_id}', 'UserController@getUserById')->middleware(['role.permission:access_user']);
        Route::post('user', 'UserController@create')->middleware(['role.permission:create_user']);
        Route::put('user', 'UserController@update')->middleware(['role.permission:update_user']);
        Route::delete('user/{user_id}', 'UserController@destroy')->middleware(['role.permission:delete_user']);

        Route::get('roles', 'RoleController@get');
        Route::get('role-permissions', 'RoleController@getRolePermissions');

        Route::get('histories', 'HistoryController@get')->middleware(['role.permission:access_history']);

        Route::get('banners', 'BannerController@get')->middleware(['role.permission:access_academic_banner']);
        Route::get('banner', 'BannerController@getBannerById')->middleware(['role.permission:access_academic_banner']);
        Route::post('banner', 'BannerController@create')->middleware(['role.permission:create_academic_banner']);
        Route::delete('banner', 'BannerController@destroy')->middleware(['role.permission:delete_academic_banner']);

        Route::group(['prefix' => 'dashboard', 'middleware' => ['role.permission:access_dashboard']], function () {
            Route::get('stats', 'DashboardController@getStats');
            Route::get('company-types', 'DashboardController@getCompanyTypes');
            Route::get('students/job-positions', 'DashboardController@getStudentJobPositions');
            Route::get('announcements/job-positions', 'DashboardController@getAnnouncementJobPositions');
            Route::get('companies/export', 'DashboardController@getCompaniesByFilterDate');
            Route::get('announcements/export', 'DashboardController@getAnnouncementsByFilterDate');
            Route::get('dashboard/export', 'DashboardController@getDashboardByFilterDate');
        });
    // });
});
