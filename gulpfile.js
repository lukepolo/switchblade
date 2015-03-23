var gulp = require('gulp'),
compass = require('gulp-compass'),
autoprefixer = require('gulp-autoprefixer'),
gutil = require('gulp-util'),
concat = require('gulp-concat'),
uglify = require('gulp-uglify'),
sourcemaps = require('gulp-sourcemaps'),
imagemin = require('gulp-imagemin'),
chalk = require('chalk'),
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
    'jquery_ui' : bower_path + 'jquery-ui/',
    'bootstrap' : bower_path + 'bootstrap-sass-official/assets/',
    'fontawesome' : bower_path + 'fontawesome/',
    'summernote'  : bower_path + 'summernote/',
    'chartjs' : bower_path + 'chartjs/',
    'moment' : bower_path + 'moment/'
};

// Minify JS
elixir.extend('minify_js', function() 
{
    gulp.task('minify_js', function() 
    {
	gulp.src([
	    paths.jquery_ui + 'jquery-ui.min.js',
	    paths.bootstrap + 'javascripts/bootstrap.min.js',
	    paths.summernote + 'dist/summernote.min.js',
	    paths.chartjs + 'Chart.js',
	    paths.moment + 'moment.js',
	    paths.js+ 'prettify/prettify.js',
	    paths.js+ '**',
            // Don't include our scripts for outside switchblade!
            '!'+paths.js+'switchblade',
            '!'+paths.js+'switchblade/**'
	],
	{
	    base: './'
	})
	.pipe(concat('all.js')) 
	.pipe(sourcemaps.init())
	.pipe(uglify())
	.pipe(sourcemaps.write('.'))
	.pipe(gulp.dest(paths.js_public));

        gulp.src([
	    paths.js+ 'switchblade/*',
	],
	{
	    base: './'
	})
	.pipe(concat('bladetrace.js')) 
	.pipe(uglify())
	.pipe(gulp.dest(paths.js_public));


        gulp.src([
	    paths.js+ 'switchblade/blade.js',
	],
	{
	    base: './'
	})
	.pipe(concat('blade.js')) 
	.pipe(uglify())
	.pipe(gulp.dest(paths.js_public));


        gulp.src([
	    paths.js+ 'switchblade/_tracer.js',
	],
	{
	    base: './'
	})
	.pipe(concat('tracer.js')) 
	.pipe(uglify())
	.pipe(gulp.dest(paths.js_public));
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
	    image: paths.img
	}))
	.pipe(sourcemaps.init())
	.pipe(autoprefixer({ 
	    browsers: [
		'last 2 version'
	    ] 
	}))
	.pipe(sourcemaps.write('.'))
	.pipe(gulp.dest('public/assets/css'))
    });
    
    if(command == 'watch')
    {
	gutil.log('Starting', '\'' + chalk.cyan('watch-sass') + '\'...');
	return this.registerWatcher('minify_css', resources_path+'**/*.scss');
    }
    else
    {
	return this.queueTask('minify_css');
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
    .copy(paths.jquery_ui + 'themes/base/jquery-ui.min.css', paths.sass_partials+'_jquery-ui-min.scss')
    .copy(paths.jquery_ui + 'themes/base/images', paths.img)
    .copy(paths.summernote + 'dist/summernote.css', paths.sass_partials+'_summernote.scss')
    .copy(paths.fontawesome + 'fonts', paths.fonts_public)
    .minify_js(command)
    .minify_css(command)
    .minify_img(command);
});