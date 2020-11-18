"use strict";

var gulp = require('gulp'),
    path = require('path'),
    twig = require('gulp-twig'),
    prefix = require('gulp-autoprefixer'),
    sass = require('gulp-sass'),
    plumber = require('gulp-plumber'),
    sourcemaps = require('gulp-sourcemaps'),
    watch = require('gulp-watch');

var paths = {
    
};

gulp.task('twig', function (){
    return gulp.src(['resources/source/html/*.twig'])
        .pipe(twig()
            .on('error', function(err) { 
                process.stderr.write(err.message + '\n');
                this.emit('end');
            }))
        .pipe(gulp.dest('resources/dist/html'));
});

gulp.task('build', gulp.parallel(['twig']));

gulp.task('watch', gulp.series(['build', function () {
    return watch('resources/source/html/*.twig')
        .pipe(gulp.dest('twig'));
}]));