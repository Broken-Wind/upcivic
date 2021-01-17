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
    Route::get('/assignments/{assignment}/pdf', 'AssignmentController@pdf')->name('assignments.pdf');
    Route::post('/assignments/{assignment}/signatures', 'AssignmentSignatureController@store')->name('assignments.signatures.store');
    Route::post('/assignments/{assignment}/complete', 'AssignmentPublicController@complete')->name('assignments.public.complete');
    Route::get('/assignments/{assignment}/edit', 'AssignmentPublicController@edit')->name('assignments.public.edit');
    Route::post('/assignments/{assignment}/upload', 'AssignmentPublicController@upload')->name('assignments.public.upload');
    Route::get('/assignments/{assignment}/download', 'AssignmentPublicController@download')->name('assignments.public.download');
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
        Route::post('/billing/subscriptions/', 'BillingController@subscribe')->name('billing.subscribe');
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
        Route::get('/billing/portal', 'BillingController@billingPortal')->name('billing.portal');
        Route::get('/billing/payment', 'BillingController@updatePaymentMethod')->name('billing.payments');
        Route::get('/billing/subscriptions/cancel', 'BillingController@cancelSubscription')->name('billing.subscriptions.cancel');
        Route::get('/settings', 'TenantController@edit')->name('edit');
        Route::patch('/settings', 'TenantController@update')->name('update');
        Route::post('/organizations', 'OrganizationController@store')->name('organizations.store');
        Route::get('/organizations', 'OrganizationController@index')->name('organizations.index');
        Route::get('/organizations/create', 'OrganizationController@create')->name('organizations.create');
        Route::get('/organizations/{organization}/edit', 'OrganizationController@edit')->name('organizations.edit')->middleware('unclaimed');
        Route::put('/organizations/{organization}', 'OrganizationController@update')->name('organizations.update')->middleware('unclaimed');
        Route::post('/organizations/{organization}/administrators', 'OrganizationAdministratorController@store')->name('organizations.administrators.store')->middleware('unclaimed');
        Route::get('/organizations/{organization}/administrators/{administrator}/edit', 'OrganizationAdministratorController@edit')->name('organizations.administrators.edit')->middleware('unclaimed');
        Route::delete('/organizations/{organization}/administrators/{administrator}', 'OrganizationAdministratorController@destroy')->name('organizations.administrators.destroy')->middleware('unclaimed');
        Route::put('/organizations/{organization}/administrators/{administrator}', 'OrganizationAdministratorController@update')->name('organizations.administrators.update')->middleware('unclaimed');
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
        Route::post('/programs/bulk_action', 'ProgramController@bulkAction')->name('programs.bulkAction');
        Route::put('/programs/{program}/enrollments', 'ProgramEnrollmentController@update')->name('programs.enrollments.update');
        Route::put('/programs/{program}', 'ProgramController@update')->name('programs.update');
        Route::delete('/programs/{program}', 'ProgramController@destroy')->name('programs.destroy');
        Route::post('/programs/{program}/send', 'ProgramController@send')->name('programs.send');
        Route::put('/programs/{program}/published', 'ProgramPublishedController@update')->name('programs.published.update');
        Route::put('/programs/{program}/contributors', 'ProgramContributorController@update')->name('programs.contributors.update');
        // Route::post('/programs/{program}/meetings/create', 'ProgramMeetingController@store')->name('programs.meetings.store');
        Route::post('/programs/{program}/meetings/update', 'ProgramMeetingController@update')->name('programs.meetings.update');
        Route::delete('/programs/{program}/contributors/{contributor}', 'ProgramContributorController@destroy')->name('programs.contributors.destroy');
        Route::get('/programs/{program}/proposal_preview', 'ProgramController@proposalPreview')->name('programs.proposal_preview');
        Route::get('/sites', 'SiteController@index')->name('sites.index');
        Route::get('/sites/create', 'SiteController@create')->name('sites.create');
        Route::post('/sites/create', 'SiteController@store')->name('sites.store');
        Route::post('/users/invites/create', 'UserInviteController@store')->name('users.invites.store');
        Route::get('/assignments/create', 'AssignmentController@create')->name('assignments.create');
        Route::get('/assignments/review', 'AssignmentController@review')->name('assignments.review');
        Route::post('/assignments/storeMany', 'AssignmentController@storeMany')->name('assignments.store_many');
        Route::delete('/assignments/{assignment}', 'AssignmentController@destroy')->name('assignments.destroy');
        Route::post('/assignments/{assignment}/complete', 'AssignmentController@complete')->name('assignments.complete');
        Route::post('/assignments/{assignment}/approve', 'AssignmentController@approve')->name('assignments.approve');
        Route::get('/assignments/{assignment}/edit', 'AssignmentController@edit')->name('assignments.edit');
        Route::post('/assignments/{assignment}/files', 'AssignmentFilesController@store')->name('assignments.files.store');
        Route::post('/instructor_assignments/{assignment}/complete', 'InstructorAssignmentController@complete')->name('instructor_assignments.complete');
        Route::post('/instructor_assignments/{assignment}/approve', 'InstructorAssignmentController@approve')->name('instructor_assignments.approve');
        Route::get('/instructor_assignments/{assignment}/edit', 'InstructorAssignmentController@edit')->name('instructor_assignments.edit');
        Route::post('/instructor_assignments/{assignment}/files', 'InstructorAssignmentFilesController@store')->name('instructor_assignments.files.store');
        Route::get('/assignments/outgoing', 'OutgoingAssignmentController@index')->name('assignments.outgoing.index');
        Route::get('/assignments/incoming', 'IncomingAssignmentController@index')->name('assignments.incoming.index');
        Route::get('/assignments/to/organizations/{organization}', 'AssignmentToOrganizationController@index')->name('assignments.to.organizations.index');
        Route::get('/assignments/from/organizations/{organization}', 'AssignmentFromOrganizationController@index')->name('assignments.from.organizations.index');
        // Route::post('/tasks/{task}/assignments', 'TaskAssignmentController@massUpdate')->name('task.assignments.mass_update');
        Route::get('/tasks', 'TaskController@index')->name('tasks.index');
        Route::post('/tasks', 'TaskController@store')->name('tasks.store');
        Route::get('/files/{file}/download', 'FileController@download')->name('files.download');
        Route::delete('/files/{file}', 'FileController@destroy')->name('files.destroy');
        Route::get('/tasks/create', 'TaskController@create')->name('tasks.create');
        Route::get('/tasks/{task}/edit', 'TaskController@edit')->name('tasks.edit');
        Route::post('/tasks/{task}/archive', 'TaskController@archive')->name('tasks.archive');
        Route::delete('/tasks/{task}', 'TaskController@destroy')->name('tasks.destroy');
        Route::put('/tasks/{task}', 'TaskController@update')->name('tasks.update');
        Route::get('/instructors', 'InstructorController@index')->name('instructors.index');
        Route::get('/instructors/create', 'InstructorController@create')->name('instructors.create');
        Route::post('/instructors', 'InstructorController@store')->name('instructors.store');
        Route::get('/instructors/{instructor}/edit', 'InstructorController@edit')->name('instructors.edit');
        Route::delete('/instructors/{instructor}', 'InstructorController@destroy')->name('instructors.destroy');
        Route::put('/instructors/{instructor}', 'InstructorController@update')->name('instructors.update');
        Route::post('/organizations/{organization}/assigned_instructors', 'OrganizationAssignedInstructorsController@massUpdate')->name('organizations.assigned_instructors.mass_update');
    });
});
