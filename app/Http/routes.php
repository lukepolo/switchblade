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
