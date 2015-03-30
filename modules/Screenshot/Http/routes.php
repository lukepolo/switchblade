<?php

if(Request::root() == env('SCREENSHOT_URL'))
{
    $prefix = '';
}
else
{
    $prefix = 'screenshot';
}

// Private Routes
Route::group(['middleware' => 'auth' , 'prefix' => $prefix, 'namespace' => 'Modules\Screenshot\Http\Controllers'], function()
{
    Route::get('dashboard', 'ScreenshotController@getDashboard');
});

// Local Routes ONLY
Route::group(['middleware' => 'screenshots' , 'prefix' => $prefix, 'namespace' => 'Modules\Screenshot\Http\Controllers'], function()
{
    Route::get('short-shot', 'ScreenshotController@getShortShot');
    Route::get('long-shot', 'ScreenshotController@getLongShot');
    Route::get('mobile-shot', 'ScreenshotController@getMobileShot');
});

// Public Routes
Route::group(['prefix' => $prefix, 'namespace' => 'Modules\Screenshot\Http\Controllers'], function()
{
    Route::controllers([
	'/' => 'ScreenshotController',
    ]);
});

