var gulp = require('gulp'),
compass = require('gulp-compass'),
gutil = require('gulp-util'),
chalk = require('chalk'),
//autoprefixer = require('gulp-autoprefixer'),
concat = require('gulp-concat'),
uglify = require('gulp-uglify'),
imagemin = require('gulp-imagemin'),
pngquant = require('imagemin-pngquant'),
elixir = require('laravel-elixir'),
bower_path = './vendor/bower_components/',

paths = {
    'sass': './resources/assets/sass/',
    'css': './public/assets/css/',
    'img': './resources/assets/img/',
    'js' : './resources/assets/js/',
    'jquery' : bower_path + "jquery/dist/",
    'bootstrap' : bower_path + "bootstrap-sass-official/assets/",
    'fontawesome' : bower_path + "fontawesome/"
};

// Minify JS
elixir.extend("minify_js", function() 
{
    gulp.task('minify_js', function() 
    {
	gutil.log('Changes Found :', '\'' + chalk.cyan('Scripts') + '\'...');
	gulp.src([
	    paths.js+ 'prettify/prettify.js',
	    paths.js+ '**',
	    paths.bootstrap + 'javascripts/bootstrap.min.js'
	],
	{
	    base: './'
	})
	.pipe(concat('all.js')) 
	.pipe(uglify())
	.pipe(gulp.dest('public/assets/js'));
    });   
    this.registerWatcher("minify_js", "**/*.js");
    return this.queueTask("minify_js");
});

// Run Compass 
elixir.extend("minify_css", function() 
{
    gulp.task('minify_css', function() 
    {
	gutil.log('Changes Found :', '\'' + chalk.cyan('SASS') + '\'...');
	gulp.src([paths.sass+'*'])
	.pipe(compass({
	    css: paths.css,
	    sass: paths.sass,
	    image: paths.img,
	    logging  : false,
	    comments : false,
	    style    : 'expanded'
	}))
	.pipe(gulp.dest('public/assets/css'))
	
    });
    return this.queueTask("minify_css");
});

// Run Compass 
elixir.extend("minify_img", function() 
{
    gulp.task('minify_img', function()
    {
	gutil.log('Changes Found :', '\'' + chalk.cyan('Images') + '\'...');
	
	return gulp.src(paths.img+'*')
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest('public/assets/img'));
    });
    
    return this.queueTask("minify_img");
});

// Copy Assets from Vendors
elixir(function (mix) 
{
    mix.copy(paths.jquery + 'jquery.min.js', "public/assets/js/jquery.min.js");
    mix.copy(paths.fontawesome + '/fonts', 'public/assets/fonts');
    mix.minify_js();
    mix.minify_css();
    mix.minify_img();
});