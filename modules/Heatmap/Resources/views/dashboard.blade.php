@extends('layouts.private.template')
@section('content')
    @foreach($urls as $url)
    <div>
	<a href="{{ action('\Modules\Heatmap\Http\Controllers\HeatmapController@getHeatmap') }}?url={{ $url[0] }}">{{ trim($url[0], '/') }}</a>
    </div>
    @endforeach
@stop