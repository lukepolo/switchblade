<?php

if(Request::root() == env('ABSPLIT_URL'))
{
    $prefix = '';
}
else
{
    $prefix = 'absplit';
}

// Public Routes
Route::group(['prefix' => $prefix, 'namespace' => 'Modules\Absplit\Http\Controllers'], function()
{
    Route::get('/', 'AbsplitController@index');
});

// Private Routes
// TODO - add middleware to check if subscribed!
Route::group(['middleware' => 'auth' , 'prefix' => $prefix, 'namespace' => 'Modules\Absplit\Http\Controllers'], function()
{
    Route::controllers([
	'proxy' => 'ProxyController',
	'/' => 'AbsplitController',
    ]);

    Route::get('proxy/asset/(:any)', 'ProxyController@getAsset');
});