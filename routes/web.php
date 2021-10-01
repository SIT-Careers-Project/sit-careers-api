<?php

use Illuminate\Support\Facades\Route;
use App\Mail\VerifyEmail;
use App\Mail\VerifyEmailWithCompany;
use App\Mail\RequestDelete;
use App\Mail\RequestAnnouncementResume;
use App\Mail\CompanyDeleted;
use App\Mail\AdminRequestDelete;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email-verify', function()
{
    return new VerifyEmail();
});

Route::get('/email-verify-company', function()
{
    return new VerifyEmailWithCompany();
});

Route::get('/email-request-delete', function()
{
    return new RequestDelete();
});

Route::get('/email-application', function()
{
    return new RequestAnnouncementResume();
});

Route::get('/email-company-deleted', function()
{
    return new CompanyDeleted();
});

Route::get('/email-admin-deleted', function()
{
    return new AdminRequestDelete();
});