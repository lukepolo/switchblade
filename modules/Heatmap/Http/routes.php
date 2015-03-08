<?php

Route::group(['prefix' => 'heatmap', 'namespace' => 'Modules\Heatmap\Http\Controllers'], function()
{
	Route::get('/', 'HeatmapController@index');
});