<?php

if(Request::root() == env('ABSPLIT_URL'))
{
    $prefix = '';
}
else
{
    $prefix = 'analytics';
}

// Public Routes
Route::group(['prefix' => $prefix, 'namespace' => 'Modules\Analytics\Http\Controllers'], function()
{
    Route::get('/', 'AnalyticsController@index');
});

// Private Routes
// TODO - add middleware to check if subscribed!
Route::group(['middleware' => 'auth' , 'prefix' => $prefix, 'namespace' => 'Modules\Analytics\Http\Controllers'], function()
{
    Route::controllers([
	'/' => 'AnalyticsController',
    ]);
});