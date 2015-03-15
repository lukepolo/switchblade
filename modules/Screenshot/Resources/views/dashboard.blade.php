@extends('layouts.private.template')

@section('content')
    <div class="row">
	@foreach($screenshots as $screenshot)
	    <div class="col-sm-3">
		<div class="img-container">
		    <img src="{{ asset('assets/img/screenshots').'/'.$screenshot->id }}.jpg">
		</div>
	    </div>
	@endforeach
    </div>
@stop