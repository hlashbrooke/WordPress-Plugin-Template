// Linking npm modules
// npm link gulp gulp-rename gulp-sass gulp-sourcemaps gulp-filter gulp-uglify

// Sass configuration
var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var rename = require('gulp-rename');
var filter = require('gulp-filter');
var uglify = require('gulp-uglify');

var sassOptions = {
  errLogToConsole: true,
  outputStyle: 'compressed'
};

var noPartials = function (file) {
    var path = require('path');
    var dirSeparator = path.sep.replace('\\', '\\\\');
    var relativePath = path.relative(process.cwd(), file.path);
    return !new RegExp('(^|'+dirSeparator+')_').test(relativePath);
};

gulp.task('sass', function() {
   gulp.src('assets/scss/*.scss')
        .pipe(filter(noPartials))//avoid compiling SCSS partials
        .pipe(sourcemaps.init())
        .pipe(sass(sassOptions).on('error', sass.logError))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('./assets/css/'));
});

gulp.task('uglify', function() {
    gulp.src(['assets/js/*.js', '!assets/js/*.min.js'])
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('assets/js'));
});

gulp.task('default', ['sass', 'uglify'], function() {
    gulp.watch('assets/scss/**/*.scss', ['sass']);
    gulp.watch([
        'assets/js/*.js',
        '!assets/js/*.min.js'
    ], ['uglify']);
});
