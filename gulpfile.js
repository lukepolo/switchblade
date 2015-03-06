var elixir = require('laravel-elixir');

var bower_path = "./vendor/bower_components";
var paths = {
  'jquery'     : bower_path + "/jquery/dist",
  'bootstrap'  : bower_path + "/bootstrap-sass-official/assets",
  'fontawesome': bower_path + "/fontawesome"
};

elixir(function (mix) 
{
    mix.sass("app.scss", "public/assets/css", {
	includePaths: [
	    paths.bootstrap + '/stylesheets',
	    paths.fontawesome + '/scss'
	]
    });

    mix.scripts("app.js", "public/assets/js/app.js", "resources/assets/js");
    mix.scripts([
	paths.jquery + '/jquery.min.js',
	paths.bootstrap + '/javascripts/bootstrap.min.js'
    ], 'public/assets/js/dependencies.js', '.');

    mix.copy(paths.fontawesome + '/fonts', 'public/assets/fonts');
});