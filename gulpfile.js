var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

var paths = {
    'sass' : 'sass/',
    'bootstrap': 'vendor/bootstrap-sass-official/assets/',
    'jquery': 'vendor/jquery/'
};

elixir(function(mix) {
    
    mix.sass(
	paths.sass + 'app.scss', 
	'/public/css/app.css',
	{
	    includePaths: [
		paths.bootstrap + 'stylesheets/'
	    ]
	},
	'resources'
    );
    
    mix.copy(
	paths.bootstrap + 'fonts/bootstrap/**',
	'public/fonts'
    );
    
    mix.scripts(
	[
	    paths.bootstrap + 'javascripts/bootstrap.js'
	],
	'public/js/app.js',
	'resources'
    );
});