@extends('screenshot::layouts.master')
@section('content')
    <div class="row">
	<div class="col-lg-4">
	    <img src="{{ action('\Modules\Screenshot\Http\Controllers\ScreenshotController@getLongShot') }}?url={{ $url }}&preview_token={{ $preview_token }}">

	</div>
	<div class="col-lg-4">
	    <img src="{{ action('\Modules\Screenshot\Http\Controllers\ScreenshotController@getShortShot') }}?url={{ $url }}&preview_token={{ $preview_token }}">
	</div>
	<div class="col-lg-4">
	    <img src="{{ action('\Modules\Screenshot\Http\Controllers\ScreenshotController@getMobileShot') }}?url={{ $url }}&preview_token={{ $preview_token }}">
	</div>
    </div>
@stop