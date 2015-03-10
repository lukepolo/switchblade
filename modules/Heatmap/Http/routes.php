<?php

if(Request::root() == env('ABSPLIT_URL'))
{
    $prefix = '';
}
else
{
    $prefix = 'heatmap';
}

// Public Routes
Route::group(['prefix' => $prefix, 'namespace' => 'Modules\Heatmap\Http\Controllers'], function()
{
    Route::get('/', 'HeatmapController@index');
});

// Private Routes
// TODO - add middleware to check if subscribed!
Route::group(['middleware' => 'auth' , 'prefix' => $prefix, 'namespace' => 'Modules\Heatmap\Http\Controllers'], function()
{
    Route::controllers([
	'/' => 'HeatmapController',
    ]);
});