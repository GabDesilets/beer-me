var gulp = require('gulp');

var plugins = require('gulp-load-plugins')();

// Define paths variables
var dest_path =  'web';
var bower_src = './bower_components/';

gulp.task('default', function() {

    gulp.src([
        bower_src + 'AdminLTE/dist/js/app.js',
        bower_src + 'AdminLTE/plugins/slimScroll/jquery.slimscroll.js',
        bower_src + 'AdminLTE/plugins/fastclick/fastclick.js'
    ])
        .pipe(plugins.sourcemaps.init())
        .pipe(gulp.dest(dest_path + '/js/'))
        .pipe(plugins.concat('adminlte.js'))
        .pipe(plugins.uglify())
        .pipe(plugins.rename({extname: '.min.js'}))
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest(dest_path + '/js/'));

    gulp.src([
            bower_src + 'AdminLTE/dist/css/AdminLTE.css',
            bower_src + 'AdminLTE/dist/css/skins/skin-blue.css'
        ])
        .pipe(plugins.sourcemaps.init())
        .pipe(gulp.dest(dest_path + '/css/'))
        .pipe(plugins.concat('adminlte.css'))
        .pipe(plugins.minifyCss())
        .pipe(plugins.rename({extname: '.min.css'}))
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest(dest_path + '/css/'));

    gulp.src([
        bower_src + 'bootstrap/dist/css/bootstrap.min.css',
        bower_src + 'font-awesome/css/font-awesome.min.css'
    ])
        .pipe(gulp.dest(dest_path + '/css/'));

    gulp.src([
        bower_src + 'bootstrap/dist/js/bootstrap.min.js',
        bower_src + 'jquery/dist/jquery.min.js',
        bower_src + 'html5shiv/dist/html5shiv.min.js',
        bower_src + 'respond/dest/respond.min.js'
    ])
        .pipe(gulp.dest(dest_path + '/js/'));

    gulp.src(bower_src + 'font-awesome/fonts/*')
        .pipe(gulp.dest(dest_path + '/fonts/'));

});