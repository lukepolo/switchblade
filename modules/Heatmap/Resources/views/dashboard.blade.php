@extends('layouts.private.template')
@section('content')
    @foreach($urls as $url)
        <div>
            <a href="{{ action('\Modules\Heatmap\Http\Controllers\HeatmapController@getMap', ['id' => $url->id]) }}">{{ trim($url->url, '/') }}</a>
        </div>
    @endforeach
@stop