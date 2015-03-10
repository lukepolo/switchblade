<?php

if(Request::root() == env('SCREENSHOT_URL'))
{
    $prefix = '';
}
else
{
    $prefix = 'screenshot';
}

// Public Routes
Route::group(['prefix' => $prefix, 'namespace' => 'Modules\Screenshot\Http\Controllers'], function()
{
    Route::get('/', 'ScreenshotController@index');
    Route::get('prices', 'ScreenshotController@getPrices');
});

// Private Routes
// TODO - add middleware to check if subscribed!
Route::group(['middleware' => 'auth' , 'prefix' => $prefix, 'namespace' => 'Modules\Screenshot\Http\Controllers'], function()
{
    Route::controllers([
	'/' => 'ScreenshotController',
    ]);
});