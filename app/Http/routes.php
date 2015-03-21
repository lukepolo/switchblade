<?php

// Controllers make it easy to access their functions
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

// Only Loggged IN - Redirects to Login Page if not logged in
Route::group(['middleware' => 'auth'], function()
{
    // Controllers Go Here
    Route::controllers([
	'payment' => 'PaymentController',
	'settings' => 'SettingsController',
	'profile' => 'Auth\UserController'
    ]);

    Route::get('logout', 'Auth\AuthController@getLogout');

    // For now we want them to always login
    Route::get('/', 'HomeController@index');

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

// Restful Routes
Route::group(['prefix' => 'api/v1'], function()
{
    Route::resource('mods', 'API\V1\ModsAPI');
});

// Auth Traits
Route::get('login', 'Auth\AuthController@getLogin');
Route::get('register', 'Auth\AuthController@getLogin');
Route::get('logout', 'Auth\AuthController@getLogout');
