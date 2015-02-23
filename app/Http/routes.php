<?php
Route::get('/', 'WelcomeController@index');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::get('login', 'Auth\AuthController@getLogin');

$router->group(['middleware' => 'auth'], function() {

    // Controllers Go Here
    Route::controllers([

    ]);

    Route::get('home', 'HomeController@index');
});
