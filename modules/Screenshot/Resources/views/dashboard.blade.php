@extends('layouts.private.template')

@section('content')
    @if (isset($screenshots))
    <div class="row">
	<pre class="text-center"><code>http://get.ketchurl.com/?apikey={{ \Auth::user()->api_key  }}&url={{ $domain }}</code></pre>
	@foreach($screenshots as $screenshot)
	    <div class="col-sm-3">
		<div class="img-container">
		    <img src="{{ asset('assets/img/screenshots').'/'.$screenshot->id }}.png">
		</div>
	    </div>
	@endforeach
    </div>
    @else
    <h1 class="text-center">You do not have any screenshots! So go take one :-)</h1>
    <pre class="text-center"><code>http://get.ketchurl.com/?apikey={{ \Auth::user()->api_key  }}&url={{ $domain }}</code></pre>
    @endif
@stop