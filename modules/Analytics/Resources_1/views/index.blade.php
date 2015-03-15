@extends('absplit::layouts.public')

@section('content')

	<h1>Hello World</h1>

	<p>
		This view is loaded from module: {!! config('absplit.name') !!}
	</p>

@stop
