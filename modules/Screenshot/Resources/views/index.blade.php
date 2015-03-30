@extends('screenshot::layouts.master')
@section('content')
    <div id="header">
	<div class="container-fluid">
	    <div class="row">
		<div class="col-sm-3">
		    <div class="logo">
			<a href="#">
			    <img src="{{ asset('/assets/img/modules/ketchscreen/Ketch_Logo.png') }}" alt="logo">
			</a>
		    </div>
		</div><!-- end col-3 -->
		<div class="col-sm-9">
		</div><!-- end col-9 -->
	    </div><!-- end row -->
	</div><!-- end container-fluid -->
    </div><!-- end #header -->
    <div class="container-fluid cover" id="test">
	<div class="row">
	    <div class="col-sm-5 hidden-xs hidden-sm">
		<div class="screenshot-container">
		    @if(empty($screenshot) === false)
		    <img src="{{ asset('assets/img/screenshots').'/'.$screenshot->id }}.png">
		    @endif
		</div>
	    </div>
	    <div class="col-md-7">
		<div class="form float-label" spellcheck="false">
		    <div class="row">
			<div class="col-sm-12">
			    <h1>Full Length Web Shots!</h1>
			    <h4>Easy. No Queues. No Wait.</h4>
			    <div class="form">
				<div class="control">
				    {!! Form::open(['action' => '\Modules\Screenshot\Http\Controllers\ScreenshotController@postPreview']) !!}
					<input type="text" name="url" placeholder="Enter a URL to Test" tabindex="1" />
					<label for="title">Enter a URL to Test</label>
					<span class="icon-cancel-circle"></span>
					<div class="btn-submit">
					    <button class="btn btn-default btn-block btn-lg">
						Test It Out!
					    </button>
					</div>
				    {!! Form::close() !!}
				</div>
				<div class="help-block">Please enter a valid URL to test.</div>
			    </div><!-- end form -->
			</div>
		    </div>
		    <div class="clearfix"></div>
		</div><!-- end form -->
	    </div><!-- end col-sm-8 -->
	</div><!-- end row -->
    </div><!-- end container test -->
    <div class="container-fluid cover" id="features">
	<div class="row">
	    <div class="col-sm-12">
	    </div><!-- end col-sm-8 -->
	</div><!-- end row -->
    </div><!-- end container test -->
@stop