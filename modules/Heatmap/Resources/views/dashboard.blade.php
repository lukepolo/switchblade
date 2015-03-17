@extends('layouts.private.template')
@section('content')
    <h1>
	{{ $screenshot->url }}
    </h1>
    <div id="img">
	<img width="1110" src="{{ asset('assets/img/screenshots').'/'.$screenshot->id }}.jpg">
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
	    heatmapInstance = h337.create({
            container: $('#img')[0],
		radius: 15
	    });

	    var count = 1;
	    $(heatmap_data).each(function(index, point)
	    {
		heatmapInstance.addData({
		    x: point.x,
		    y: point.y,
		    value: count++
		});
	    });
	}
    </script>
    <script src="{{ asset('assets/js/heatmap.min.js') }}"></script>
@stop