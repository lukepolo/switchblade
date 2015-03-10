<?php
// Controllers make it easy to access their functions
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

// Only Loggged IN
// Redirects to Login Page
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

// Single Routes

// Base Route

// Auth Traits
Route::get('login', 'Auth\AuthController@getLogin');
Route::get('register', 'Auth\AuthController@getLogin');
Route::get('logout', 'Auth\AuthController@getLogout');
