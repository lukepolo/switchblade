<?php

Route::group(['prefix' => 'absplit', 'namespace' => 'Modules\Absplit\Http\Controllers'], function()
{
	Route::get('/', 'AbsplitController@index');
});