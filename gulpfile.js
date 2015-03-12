var gulp = require('gulp'),
concat = require('gulp-concat'),
uglify = require('gulp-uglify'),
rename = require('gulp-rename'),
compass = require('gulp-compass'),
minifyCSS = require('gulp-minify-css'),
imagemin = require('gulp-imagemin'),
notify = require('gulp-notify'),
clean = require('gulp-clean'),
filesize = require('gulp-filesize');
elixir = require('laravel-elixir'),
bower_path = './vendor/bower_components/',
paths = {
    'js' : './resources/assets/js/',
    'jquery' : bower_path + "jquery/dist/",
    'bootstrap' : bower_path + "bootstrap-sass-official/assets/",
    'fontawesome' : bower_path + "fontawesome/"
};

// Laravel E
elixir(function (mix) 
{
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


gulp.task('scripts', function() {
    return gulp.src('src/js/*.js')
        .pipe(concat('main.js')) 
        .pipe(gulp.dest('build/dev/js'))
        .pipe(filesize())
        .pipe(uglify())
        .pipe(gulp.dest('build/production/js'))
        .pipe(filesize())
        .pipe(notify({ message: 'JavaScript task complete' }));
});

gulp.task('compass', function() {
    return gulp.src(['src/sass/**/*.scss'])
        .pipe(compass({
            css: 'src/css',
            sass: 'src/sass',
            image: 'src/images'
        }))
        .pipe(gulp.dest('build/dev/css'))
        .pipe(minifyCSS())
        .pipe(gulp.dest('build/production/css'))
        .pipe(notify({ message: 'Compass task complete' }));
});

gulp.task('images', function() {
    return gulp.src('src/images/**/*')
        .pipe(imagemin({ optimizationLevel: 5, progressive: true, interlaced: true }))
        .pipe(gulp.dest('build/dev/img'))
        .pipe(gulp.dest('build/production/img'))
        .pipe(notify({ message: 'Images task complete' }));
});

gulp.task('watch', function() {
    gulp.watch('src/js/*.js', ['scripts']);
    gulp.watch('src/sass/**/*.scss', ['compass']);
    gulp.watch('src/images/**/*', ['images']);
});


gulp.task('clean', function() {
    return gulp.src(['build/*'], {read: false})
        .pipe(clean());
});