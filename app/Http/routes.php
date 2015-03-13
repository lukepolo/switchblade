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
	'settings' => 'SettingsController'
    ]);

    // For now we want them to always login
    Route::get('/', 'HomeController@index');

    // Profile Routes
    Route::get('profile', 'Auth\UserController@getProfile');
    Route::post('profile', 'Auth\UserController@postProfile');
    Route::post('profile/image', 'Auth\UserController@postProfileImage');

    Route::get('logout', 'Auth\AuthController@getLogout');
});

// Restful Routes
Route::group(array('prefix' => 'api/v1'), function()
{
    Route::resource('mods', 'API\V1\ModsController');
});


// Single Routes

// Base Route

// Auth Traits
Route::get('login', 'Auth\AuthController@getLogin');
Route::get('register', 'Auth\AuthController@getLogin');
Route::get('logout', 'Auth\AuthController@getLogout');
