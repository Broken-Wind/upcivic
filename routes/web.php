<?php
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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'IndexController@index')->name('root');
Route::get('/home', 'HomeController@index')->name('home');
Route::middleware(Spatie\Honeypot\ProtectAgainstSpam::class)->group(function () {
    Auth::routes(['verify' => true]);
});

Route::group(['middleware' => 'verified'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/organizations/{organization}/tenant', 'OrganizationTenantController@create')->name('organizations.tenant.create');
    Route::post('/organizations/{organization}/tenant', 'OrganizationTenantController@store')->name('organizations.tenant.store');
    Route::post('/organizations/users', 'OrganizationUserController@store')->name('organizations.users.store');
    Route::get('/tenants/create', 'TenantController@create')->middleware('tenant.null');
    Route::post('/tenants', 'TenantController@store')->middleware('tenant.null');
});
Route::group([
    'prefix' => '/{tenant}',
    'middleware' => ['tenant', 'tenant.auth'],
    'as' => 'tenant:',
], function () {
    Route::get('/', 'PublicTenantController@index');
});
Route::group([
    'prefix' => '/{tenant}',
    'middleware' => ['tenant', 'tenant.public'],
    'as' => 'tenant:',
], function () {
    Route::get('/iframe', 'IframeController@index')->name('iframe.index');
    Route::get('/iframe/{program}', 'IframeController@show')->name('iframe.show');
});
Route::group(['middleware' => 'verified'], function () {
    Route::group([
        'prefix' => '/{tenant}/api',
        'middleware' => ['tenant', 'tenant.auth'],
        'as' => 'tenant:api.',
    ], function () {
        Route::post('/programs/locations', 'ProgramLocationsController@update')->name('programs.locations.update');
        Route::post('/programs/contributors', 'ProgramContributorController@index')->name('programs.contributors.index');
        Route::post('/resource_timeline_meetings/page/', 'ResourceTimelineController@page')->name('resource_timeline_meetings.page');
    });
    Route::group([
        'prefix' => '/{tenant}/admin',
        'middleware' => ['tenant', 'tenant.auth'],
        'as' => 'tenant:admin.',
    ], function () {
        Route::get('/', 'TenantController@index')->name('index');
        Route::get('/home', 'TenantController@index')->name('home');
        Route::post('/demo', 'DemoProgramController@store')->name('demo.store');
        Route::get('/resource_timeline', 'ResourceTimelineController@index')->name('resource_timeline.index');
        Route::get('/resource_timeline_meetings', 'ResourceTimelineController@meetings')->name('resource_timeline.meetings');
        Route::get('/profile', 'UserController@edit')->name('users.edit');
        Route::put('/users/{user}', 'UserController@update')->name('users.update');
        Route::get('/settings', 'TenantController@edit')->name('edit');
        Route::patch('/settings', 'TenantController@update')->name('update');
        Route::post('/organizations', 'OrganizationController@store')->name('organizations.store');
        /**
         * Disabling the ability to add organizations without users.
         * This functionality will most likely return soon, but not for the MVP.
         * Route::get('/organizations', 'OrganizationController@index')->name('organizations.index');
         * Route::get('/organizations/{organization}/edit', 'OrganizationController@edit')->name('organizations.edit')->middleware('unclaimed');
         * Route::put('/organizations/{organization}', 'OrganizationController@update')->name('organizations.update')->middleware('unclaimed');
         * Route::post('/organizations/{organization}/administrators', 'OrganizationAdministratorController@store')->name('organizations.administrators.store')->middleware('unclaimed');
         */
        Route::get('/organizations/{organization}/assigned_by', 'OrganizationOutgoingAssignmentController@index')->name('organizations.assigned_by.index');
        Route::get('/organizations/{organization}/assigned_to', 'OrganizationIncomingAssignmentController@index')->name('organizations.assigned_to.index');

        Route::get('/templates', 'TemplateController@index')->name('templates.index');
        Route::get('/templates/create', 'TemplateController@create')->name('templates.create');
        Route::post('/templates', 'TemplateController@store')->name('templates.store');
        Route::get('/templates/{template}/edit', 'TemplateController@edit')->name('templates.edit');
        Route::put('/templates/{template}', 'TemplateController@update')->name('templates.update');
        Route::delete('/templates/{template}', 'TemplateController@destroy')->name('templates.destroy');
        Route::get('/programs', 'ProgramController@index')->name('programs.index');
        Route::get('/programs/create', 'ProgramController@create')->name('programs.create');
        Route::post('/programs', 'ProgramController@store')->name('programs.store');
        Route::post('/programs/approve', 'ProgramController@approve')->name('programs.approve');
        Route::post('/programs/reject', 'ProgramController@reject')->name('programs.reject');
        Route::get('/programs/{program}/edit', 'ProgramController@edit')->name('programs.edit');
        Route::get('/programs/{program}', 'ProgramController@show')->name('programs.show');
        Route::post('/programs/loa', 'ProgramController@generateLoa')->name('programs.loa');
        Route::put('/programs/{program}/enrollments', 'ProgramEnrollmentController@update')->name('programs.enrollments.update');
        Route::put('/programs/{program}', 'ProgramController@update')->name('programs.update');
        Route::delete('/programs/{program}', 'ProgramController@destroy')->name('programs.destroy');
        Route::post('/programs/{program}/send', 'ProgramController@send')->name('programs.send');
        Route::put('/programs/{program}/published', 'ProgramPublishedController@update')->name('programs.published.update');
        Route::put('/programs/{program}/contributors', 'ProgramContributorController@update')->name('programs.contributors.update');
        // Route::post('/programs/{program}/meetings/create', 'ProgramMeetingController@store')->name('programs.meetings.store');
        Route::post('/programs/{program}/meetings/update', 'ProgramMeetingController@update')->name('programs.meetings.update');
        Route::delete('/programs/{program}/contributors/{contributor}', 'ProgramContributorController@destroy')->name('programs.contributors.destroy');
        Route::get('/sites', 'SiteController@index')->name('sites.index');
        Route::get('/sites/create', 'SiteController@create')->name('sites.create');
        Route::post('/sites/create', 'SiteController@store')->name('sites.store');
        Route::post('/users/invites/create', 'UserInviteController@store')->name('users.invites.store');
        Route::get('/assignments', 'AssignmentController@index')->name('assignments.index');
        Route::post('/tasks/{task}/assignments', 'TaskAssignmentController@massUpdate')->name('task.assignments.mass_update');
        Route::get('/tasks', 'TaskController@index')->name('tasks.index');
        Route::post('/tasks', 'TaskController@store')->name('tasks.store');
        Route::get('/tasks/create', 'TaskController@create')->name('tasks.create');
        Route::get('/tasks/{task}/edit', 'TaskController@edit')->name('tasks.edit');
        Route::post('/tasks/{task}/archive', 'TaskController@archive')->name('tasks.archive');
        Route::delete('/tasks/{task}', 'TaskController@destroy')->name('tasks.destroy');
        Route::put('/tasks/{task}', 'TaskController@update')->name('tasks.update');
    });
});
