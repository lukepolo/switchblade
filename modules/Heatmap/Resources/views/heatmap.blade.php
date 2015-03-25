@extends('layouts.private.template')
@section('content')
    @if(isset($screenshot))
	<h1>
	    {{ \Request::get('url') }}
            <small class="pull-right"><span id="total_points" data-points="{{ $total_points }}">{{ $total_points }}</span> points by {{ $total_users }} users</small>
	</h1>
        <br>
	<div id="img">
	    <img class="img-responsive" src="{{ asset('assets/img/screenshots').'/'.$screenshot->id }}.jpg">
	</div>

	<script>
	    var heatmap_data = {!! $data !!};
	    var heatmapInstance;
	    var img_loaded = false;
            
            var image_width;
            var image_natural_width;
            var image_natural_height;
            var image_height;
                
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
                image_width = $('#img img').width();
		image_width_natural = $('#img img')[0].naturalWidth;
                image_width_ratio = image_width / image_width_natural
                image_half_width = (image_width / 2) / image_width_ratio;
                
                image_height = $('#img img').height();
		image_height_natural = $('#img img')[0].naturalHeight;
                image_height_ratio = image_height / image_height_natural;

		heatmapInstance = h337.create({
		container: $('#img')[0],
		    radius: 5
		});

		$(heatmap_data).each(function(index, point)
		{
                    add_data(point);
		});
	    }
            
            function add_data(point)
            {
                // we gotta do some math now
                point.x = (image_half_width - ((point.width / 2) - point.x)) * image_width_ratio;
                        
                point.y = image_height * (point.y / point.height);
                
                if(point.x >= 0 && point.x <= image_width)
                {
                    heatmapInstance.addData({
                        x: point.x,
                        y: point.y,
                        value: point.count
                    });
                }
            }
            
            function add_realtime_data(data)
            {
                $(data).each(function(index, point)
		{
                    add_data(point);
                    $('#total_points').data('points', $('#total_points').data('points') + 1);
                    $('#total_points').html($('#total_points').data('points'));
		});
            }
	</script>
	<script src="{{ asset('assets/js/heatmap.min.js') }}"></script>
    @else
    <h1 class="text-center">{{ $reason }}</h1>
    @endif

@stop