// Get modules
var gulp 		= require('gulp');
var sass 		= require('gulp-sass');
var uglify 		= require('gulp-uglify');
var rename 		= require("gulp-rename");
var imagemin 	= require('gulp-imagemin');
var livereload 	= require('gulp-livereload');
var concat 		= require('gulp-concat');

gulp.task('scripts', function() {
    gulp.src(['./bower_components/jquery/dist/jquery.js',
              './private/js/main.js'])
    	.pipe(concat('all.js'))
        .pipe(uglify())
        .pipe(rename('all.min.js'))
        .pipe(gulp.dest('public/js'));
});

gulp.task('images', function () {
    gulp.src('./private/img/*.{png,gif,jpg}')
        .pipe(imagemin())
        .pipe(gulp.dest('./public/img/'));
});

gulp.task('watch', function () {
    var server = livereload();

    gulp.watch('./private/scss/*.scss', ['styles']);
    gulp.watch('./private/js/*.js', ['scripts']);
    gulp.watch('./private/img/*', ['images']);
});

gulp.task('styles', function () {
	gulp.src('./bower_components/pure/pure-min.css')
		.pipe(gulp.dest('public/css/'));
    gulp.src('./private/scss/*.scss')
        .pipe(sass())
        .pipe(gulp.dest('public/css/'))
        .pipe(livereload());
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', ['styles', 'scripts', 'images','watch']);
