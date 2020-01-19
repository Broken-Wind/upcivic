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
Route::get('/', function () {
    return view('welcome');
})->name('root');
Route::middleware(Spatie\Honeypot\ProtectAgainstSpam::class)->group(function() {
    Auth::routes(['verify' => true]);
});
Route::group(['middleware' => 'verified'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/organizations/{organization}/tenant', 'OrganizationTenantController@create')->name('organizations.tenant.create');
    Route::post('/organizations/{organization}/tenant', 'OrganizationTenantController@store')->name('organizations.tenant.store');
    Route::post('/organizations/users', 'OrganizationUserController@store')->name('organizations.users.store');
    Route::get('/tenants/create', 'TenantController@create')->middleware('tenant.null');
    Route::post('/tenants', 'TenantController@store')->middleware('tenant.null');
    Route::group([
        'prefix' => '/{tenant}',
        'middleware' => ['tenant', 'tenant.public'],
        'as' => 'tenant:',
    ], function () {
        Route::get('/iframe', 'IframeController@index')->name('iframe.index');
        Route::get('/iframe/{program}', 'IframeController@show')->name('iframe.show');
    });
    Route::group([
        'prefix' => '/{tenant}/admin',
        'middleware' => ['tenant', 'tenant.auth'],
        'as' => 'tenant:admin.',
    ], function () {
        Route::get('/home', 'TenantController@index')->name('home');
        Route::get('/profile', 'UserController@edit')->name('users.edit');
        Route::put('/users/{user}', 'UserController@update')->name('users.update');
        Route::get('/settings', 'TenantController@edit')->name('edit');
        Route::patch('/settings', 'TenantController@update')->name('update');
        Route::get('/organizations', 'OrganizationController@index')->name('organizations.index');
        Route::post('/organizations', 'OrganizationController@store')->name('organizations.store');
        Route::get('/organizations/{organization}/edit', 'OrganizationController@edit')->name('organizations.edit')->middleware('unclaimed');
        Route::put('/organizations/{organization}', 'OrganizationController@update')->name('organizations.update')->middleware('unclaimed');
        Route::post('/organizations/{organization}/administrators', 'OrganizationAdministratorController@store')->name('organizations.administrators.store')->middleware('unclaimed');
        Route::get('/templates', 'TemplateController@index')->name('templates.index');
        Route::get('/templates/create', 'TemplateController@create')->name('templates.create');
        Route::post('/templates', 'TemplateController@store')->name('templates.store');
        Route::get('/templates/{template}/edit', 'TemplateController@edit')->name('templates.edit');
        Route::put('/templates/{template}', 'TemplateController@update')->name('templates.update');
        Route::delete('/templates/{template}', 'TemplateController@destroy')->name('templates.destroy');
        Route::get('/programs', 'ProgramController@index')->name('programs.index');
        Route::get('/programs/create', 'ProgramController@create')->name('programs.create');
        Route::post('/programs', 'ProgramController@store')->name('programs.store');
        Route::get('/programs/{program}/edit', 'ProgramController@edit')->name('programs.edit');
        Route::put('/programs/{program}', 'ProgramController@update')->name('programs.update');
        Route::delete('/programs/{program}', 'ProgramController@destroy')->name('programs.destroy');
        Route::put('/programs/{program}/published', 'ProgramPublishedController@update')->name('programs.published.update');
        Route::put('/programs/{program}/contributors', 'ProgramContributorController@update')->name('programs.contributors.update');
        Route::post('/programs/{program}/meetings/create', 'ProgramMeetingController@store')->name('programs.meetings.store');
        Route::post('/programs/{program}/meetings/update', 'ProgramMeetingController@update')->name('programs.meetings.update');
        Route::delete('/programs/{program}/contributors/{contributor}', 'ProgramContributorController@destroy')->name('programs.contributors.destroy');
        Route::get('/sites', 'SiteController@index')->name('sites.index');
        Route::get('/sites/create', 'SiteController@create')->name('sites.create');
        Route::post('/sites/create', 'SiteController@store')->name('sites.store');
        Route::get('/users/invites/create', 'UserInviteController@create')->name('users.invites.create');
        Route::post('/users/invites/create', 'UserInviteController@store')->name('users.invites.store');



    });
});
