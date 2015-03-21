<?php

if(Request::root() == env('TRACER_URL'))
{
    $prefix = '';
}
else
{
    $prefix = 'tracer';
}

// Public Routes
Route::group(['prefix' => $prefix, 'namespace' => 'Modules\Tracer\Http\Controllers'], function()
{
    Route::get('/', 'TracerController@index');
});

// Private Routes
// TODO - add middleware to check if subscribed!
Route::group(['middleware' => 'auth' , 'prefix' => $prefix, 'namespace' => 'Modules\Tracer\Http\Controllers'], function()
{
    Route::controllers([
	'/' => 'TracerController',
    ]);
});

Route::group(['prefix' => 'api/v1', 'namespace' => 'Modules\Tracer\Http\Controllers'], function()
{
    Route::resource('tracer/js', 'API\V1\JsAPI');
});
