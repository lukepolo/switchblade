@extends('layouts.private.template')

@section('content')
    <h1>Your Visitors</h1>
    <canvas id="chart"></canvas>
    <script>
	 $(document).ready(function()
	 {
	    var analytics = {!! $data !!};
	    var data = {
		labels: analytics.labels,
		datasets: [
		    {
			label: "Visitors",
			fillColor: "rgba(151,187,205,0.2)",
			strokeColor: "rgba(151,187,205,1)",
			pointColor: "rgba(151,187,205,1)",
			pointStrokeColor: "#fff",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgba(151,187,205,1)",
			data: analytics.visitors
		    },
		    {
			label: "Views",
			fillColor: "rgba(220,220,220,0.2)",
			strokeColor: "rgba(220,220,220,1)",
			pointColor: "rgba(220,220,220,1)",
			pointStrokeColor: "#fff",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgba(220,220,220,1)",
			data: analytics.views
		    }
		]
	    };

	    var chart = new Chart($("#chart").get(0).getContext("2d")).Line(data,{
		bezierCurve : true,
		multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>",
		responsive: true,
		scaleShowVerticalLines: false,
	    });

	    // socket.io watch vistors
	});
    </script>
@stop