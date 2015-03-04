<?php
// Controllers make it easy to access their functions
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

// Only Loggged IN
// Redirects to Login Page
$router->group(['middleware' => 'auth'], function()
{
    // For now we want them to always login
    Route::get('/', 'HomeController@index');
    
    // Profile Routes
    Route::get('profile', 'Auth\UserController@getProfile');
    Route::post('profile', 'Auth\UserController@postProfile');
    Route::post('profile/image', 'Auth\UserController@postProfileImage');

    Route::get('logout', 'Auth\AuthController@getLogout');

    // Controllers Go Here
    Route::controllers([

    ]);
});

// Single Routes

// Base Route

// Auth Traits
Route::get('login', 'Auth\AuthController@getLogin');
Route::get('register', 'Auth\AuthController@getLogin');
Route::get('logout', 'Auth\AuthController@getLogout');
