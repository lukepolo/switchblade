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

    Route::get('/heatmap/heatmap', array('after' => 'no-cache', 'uses' => 'HeatmapController@getHeatmap'));

    Route::filter('no-cache', function($route, $request, $response)
    {
        $response->header("Cache-Control","no-cache,no-store, must-revalidate");
        $response->header("Pragma", "no-cache"); //HTTP 1.0
        $response->header("Expires"," Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    });
});


Route::group(['prefix' => 'api/v1', 'namespace' => 'Modules\Heatmap\Http\Controllers'], function()
{
    Route::resource('heatmap/point', 'API\V1\HeatmapPointsAPI');
    Route::resource('heatmap/click', 'API\V1\HeatmapClicksAPI');
});