<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>

        <link href="/assets/css/app.css" rel="stylesheet">
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="/assets/js/jquery.min.js"></script>
    </head>
    <body>
	@include('core/private/header')
	<div class="container">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->any() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
	    @if (Session::has('success'))
                <div class="alert alert-success">
                    <strong>Success!</strong><br><br>
                    <ul>
			{{ Session::get('success') }}
                    </ul>
                </div>
            @endif
	    @yield('content')
	</div>
        <!-- Scripts -->
        @include('core/private/node')
        <script src="/assets/js/all.js"></script>
        <script type="text/javascript">
            $(document).ready(function()
            {
                // Passes the XSRF-TOKEN to PHP
                $(function() {
                    $.ajaxSetup({
                        headers: {
                            'X-XSRF-TOKEN': "{{ isset($_COOKIE['XSRF-TOKEN']) ? $_COOKIE['XSRF-TOKEN'] : '' }}"
                        }
                    });
                });
            });
        </script>
    </body>
</html>
