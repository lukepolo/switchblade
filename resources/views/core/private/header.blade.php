<nav class="navbar navbar-default">
    <div class="container-fluid">
	<div class="navbar-header">
	    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		<span class="sr-only">Toggle Navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	    </button>
	    <a class="navbar-brand" href="#">SwitchBlade</a>
	</div>
	@if (count($errors) > 0)
	    <div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
		    @foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		    @endforeach
		</ul>
	    </div>
	@endif
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	    <ul class="nav navbar-nav">
		@if (Auth::check())
		    <li>
			<a href="#">
			    <i class="fa fa-code"></i>
			    A/B Tester
			</a>
		    </li>
		    <li>
			<a href="#">
			    <i class="fa fa-bar-chart"></i>
			    Analytics
			</a>
		    </li>
		    <li>
			<a href="#">
			    <i class="fa fa-fire"></i>
			    Heat Mapping
			</a>
		    </li>
		    <li>
			<a href="#">
			    <i class="fa fa-desktop"></i>
			    Ketch Screen
			</a>
		    </li>
		    <li>
			<a href="#">
			    <i class="fa fa-dashboard"></i>
			    Pinger
			</a>
		    </li>
		    <li>
			<a href="#">
			    <i class="fa fa-exclamation"></i>
			    Error Reporting
			</a>
		    </li>
		    <li>
			<a href="#">
			    <i class="fa fa-share-alt"></i>
			    Shortener
			</a>
		    </li>
		    <li>
			<a href="#">
			    <i class="fa fa-paper-plane"></i>
			    Web Hooks
			</a>
		    </li>
		    <li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			    <i class="fa fa-cog"></i>
			    Dev Menu
			    <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
			    <li>
				<a target="_blank" href="https://switchblade.slack.com">
				    <i class="fa fa-slack"></i>
				    Slack
				</a>
			    </li>
			    <li>
				<a target="_blank" href="https://github.com/lukepolo/switchblade">
				    <i class="fa fa-github-square"></i>
				    GitHub
				</a>
			    </li>
			    <li>
				<a target="_blank" href="http://team.bladeswitch.io">
				    Confluence
				</a>
			    </li>
			    <li>
				<a target="_blank" href="http://box.bladeswitch.io">
				    <i class="fa fa-dropbox"></i>
				    Share Drive
				</a>
			    </li>
			    <li>
				<a target="_blank" href="https://stripe.com">
				    <i class="fa fa-cc-stripe"></i> Stripe</a>
			    </li>
			    <li>
				<a target="_blank" href="https://sendgrid.com">
				    SendGrid
				</a>
			    </li>
			    <li>
				<a href="#">
				    Settings
				</a>
			    </li>
			    <li>
				<a href="#">
				    Enable Profiler
				</a>
			    </li>
			    <li>
				<a href="#">
				    Disable Minify
				</a>
			    </li>
			</ul>
		    </li>
		@endif
	    </ul>
	    <ul class="nav navbar-nav navbar-right">
		@if (Auth::guest())
		    <!--<li><a href="/auth/login">Login</a></li>-->
		@else
		    <li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu">
			    <li><a href="/auth/logout">Logout</a></li>
			</ul>
		    </li>
		@endif
	    </ul>
	</div>
    </div>
</nav>