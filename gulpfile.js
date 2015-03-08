var elixir = require('laravel-elixir');
var bower_path = './vendor/bower_components/';
var paths = {
    'js' : './resources/assets/js/',
    'jquery' : bower_path + "jquery/dist/",
    'bootstrap' : bower_path + "bootstrap-sass-official/assets/",
    'fontawesome' : bower_path + "fontawesome/"
};

elixir(function (mix) 
{
    mix.sass("app.scss", "public/assets/css", {
	includePaths: [
	    paths.bootstrap + 'stylesheets',
	    paths.fontawesome + 'scss'
	]
    });

    // Load Jquery In
    mix.scripts(
	paths.jquery + 'jquery.min.js', 
	"public/assets/js/jquery.min.js",
	'.'
    );
    
    mix.scripts([
	// Needs to load first!
	paths.js+ 'prettify/prettify.js',
	paths.js+ '**',
	paths.bootstrap + 'javascripts/bootstrap.min.js'
    ], 'public/assets/js', '.');

    mix.copy(paths.fontawesome + '/fonts', 'public/assets/fonts');
});