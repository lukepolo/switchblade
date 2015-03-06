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
    'bootstrap': 'resources/vendor/bootstrap-sass-official/assets'
};

elixir(function(mix) {
    mix.stylesIn(
	'resources/css', 
	'public/css/app.css'
    );
    
    elixir(function(mix) {
	mix.sass(
	    "app.scss", 
	    'public/css/bootstrap.css',
	    {
		includePaths: [
		    paths.bootstrap + 'stylesheets/'
		]
	    }
	)
    });

    mix.scripts([
	'custom_functions.js',
	'prettify/prettify.js'
    ], 'public/js/app.js');
});
