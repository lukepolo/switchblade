@extends('layouts.private.template')
@section('content')
    @if(isset($screenshot))
	<h1>
	    {{ \Request::get('url') }}
	    <small class="pull-right">{{ $total_points }} points by {{ $total_users }} users</small>
	</h1>
	<div id="img">
	    <img class="img-responsive" src="{{ asset('assets/img/screenshots').'/'.$screenshot->id }}.jpg">
	</div>

	<script>
	    var heatmap_data = {!! $data !!};
	    var heatmapInstance;
	    var img_loaded = false;
	    $('#img img').load(function()
	    {
		img_loaded = true;
	    });
	    $(document).ready(function()
	    {
	       if(img_loaded)
	       {
		   render_heatmap();
	       }
	       else
	       {
		   $('#img img').load(function()
		    {
			render_heatmap();
		    });
	       }
	    });

	    function render_heatmap()
	    {
		var image_width = $('#img img').width();
		var image_natural_width = $('#img img')[0].naturalWidth;
		var image_natural_height = $('#img img')[0].naturalHeight;
		var image_height = $('#img img').height();

		var distance_to_middle_img = image_width / 2;

		console.log('Img Natural Height: '+image_natural_height);
		console.log('Img Natural Width: '+image_natural_width);
		console.log(image_width);

		heatmapInstance = h337.create({
		container: $('#img')[0],
		    radius: 5
		});

		$(heatmap_data).each(function(index, point)
		{
		    if(point.reset)
		    {
			count = 1;
		    }
		    else
		    {
			// we gotta do some math now
			point.x =  point.x * (point.width / image_natural_width) - (point.width - image_natural_width) / 2;
			point.x =  image_width * (point.x / point.width);

			point.y =  point.y * (point.height / image_natural_height) - (point.height - image_natural_height);
			point.y = image_height * (point.y / point.height);

			if(point.x <= image_width)
			{
			    heatmapInstance.addData({
				x: point.x,
				y: point.y,
				value: count++
			    });
			}
		    }
		});
	    }
	</script>
	<script src="{{ asset('assets/js/heatmap.min.js') }}"></script>
    @else
    <h1 class="text-center">{{ $reason }}</h1>
    @endif

@stop