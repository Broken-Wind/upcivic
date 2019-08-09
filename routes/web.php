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
});

Auth::routes(['verify' => true]);

Route::group(['middleware' => 'verified'], function () {

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/organizations/create', 'OrganizationController@create')->middleware('tenant.null');

    Route::post('/organizations', 'OrganizationController@store')->middleware('tenant.null');

    Route::group([

        'prefix' => '/{tenant}/admin',

        'middleware' => ['tenant', 'tenant.auth'],

        'as' => 'tenant:admin.',

    ], function () {

        Route::get('/home', 'TenantController@index')->name('home');

        Route::get('/profile', 'UserController@edit')->name('users.edit');

        Route::put('/users/{user}', 'UserController@update')->name('users.update');

        Route::get('/organization_settings', 'OrganizationController@edit')->name('organizations.edit');

        Route::patch('/organizations', 'OrganizationController@update')->name('organizations.update');

        Route::get('/templates', 'TemplateController@index')->name('templates.index');

        Route::get('/templates/create', 'TemplateController@create')->name('templates.create');

        Route::post('/templates/create', 'TemplateController@store')->name('templates.store');

        Route::get('/templates/{template}/edit', 'TemplateController@edit')->name('templates.edit');

        Route::put('/templates/{template}', 'TemplateController@update')->name('templates.update');

        Route::get('/programs', 'ProgramController@index')->name('programs.index');

        Route::get('/programs/create', 'ProgramController@create')->name('programs.create');

        Route::post('/programs/create', 'ProgramController@store')->name('programs.store');

        Route::get('/programs/{program}/edit', 'ProgramController@edit')->name('programs.edit');

        Route::put('/programs/{program}', 'ProgramController@update')->name('programs.update');

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
