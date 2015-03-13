var gulp = require('gulp'),
compass = require('gulp-compass'),
gutil = require('gulp-util'),
chalk = require('chalk'),
concat = require('gulp-concat'),
uglify = require('gulp-uglify'),
imagemin = require('gulp-imagemin'),
pngquant = require('imagemin-pngquant'),
elixir = require('laravel-elixir'),

bower_path = './vendor/bower_components/',
resources_path = './resources/';

paths = {
    // App Paths
    'css_public': './public/assets/css/',
    'js_public' : './public/assets/js/',
    'fonts_public' : './public/assets/fonts/',
    'sass': resources_path+'assets/sass/',
    'sass_partials': resources_path+'assets/sass/partials/',
    'js' : resources_path+'assets/js/',
    'img': resources_path+'assets/img/',
    // Vendor Paths
    'jquery' : bower_path + 'jquery/dist/',
    'bootstrap' : bower_path + 'bootstrap-sass-official/assets/',
    'fontawesome' : bower_path + 'fontawesome/',
    'summernote'  : bower_path + 'summernote/'
};

// Minify JS
elixir.extend('minify_js', function() 
{
    gulp.task('minify_js', function() 
    {
	gulp.src([
	    paths.bootstrap + 'javascripts/bootstrap.min.js',
	    paths.summernote + 'dist/summernote.min.js',
	    paths.js+ 'prettify/prettify.js',
	    paths.js+ '**'
	],
	{
	    base: './'
	})
	.pipe(concat('all.js')) 
	.pipe(uglify())
	.pipe(gulp.dest('public/assets/js'));
    });   
    
    if(command == 'watch')
    {
	gutil.log('Starting', '\'' + chalk.cyan('watch-js') + '\'...');
	return this.registerWatcher('minify_js', resources_path+'**/*.js');
    }
    else
    {
	return this.queueTask('minify_js');
    }
});

// Minify CSS
elixir.extend('minify_css', function() 
{
    gulp.task('minify_css', function() 
    {
	gulp.src([paths.sass+'*'])
	.pipe(compass({
	    css: paths.css_public,
	    sass: paths.sass,
	    image: paths.img,
	    logging  : false,
	    comments : false,
	    style    : 'expanded'
	}))
	.pipe(gulp.dest('public/assets/css'))
    });
    
    if(command == 'watch')
    {
	gutil.log('Starting', '\'' + chalk.cyan('watch-sass') + '\'...');
	return this.registerWatcher('minify_css', resources_path+'**/*.scss');
    }
    else
    {
	return this.queueTask('minify_css')
    }
});

// Minify Images
elixir.extend('minify_img', function(command) 
{
    gulp.task('minify_img', function()
    {
	gulp.src(paths.img+'*')
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest('public/assets/img'));
    });
    
    if(command == 'watch')
    {
	gutil.log('Starting', '\'' + chalk.cyan('watch-images') + '\'...');
	return this.registerWatcher('minify_js', resources_path+'**/*.js');
	return this.registerWatcher('minify_img', paths.img+'*');
    }
    else
    {
	return this.queueTask('minify_img');
    }
});

elixir(function (mix) 
{
    command = process.argv.slice(2)[0];
    gutil.log('Command:', '\'' + chalk.cyan(command) + '\'...');

    // Copy Assets from Vendors
    mix.copy(paths.jquery + 'jquery.min.js', paths.js_public+'jquery.min.js')
    .copy(paths.jquery + 'jquery.min.map', paths.js_public+'jquery.min.map')
    .copy(paths.summernote + 'dist/summernote.css', paths.sass_partials+'_summernote.scss')
    .copy(paths.fontawesome + '/fonts', paths.fonts_public)
    .minify_js(command)
    .minify_css(command)
    .minify_img(command);
});