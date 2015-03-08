<?php

Route::group(['prefix' => 'screenshot', 'namespace' => 'Modules\Screenshot\Http\Controllers'], function()
{
	Route::get('/', 'ScreenshotController@index');
});