<?php

Route::group(['prefix' => 'analytics', 'namespace' => 'Modules\Analytics\Http\Controllers'], function()
{
	Route::get('/', 'AnalyticsController@index');
});