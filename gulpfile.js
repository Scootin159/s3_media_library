"use strict";

var gulp = require('gulp'),
    include = require('gulp-include'),
    //jshint = require('gulp-jshint'),
    path = require('path'),
    prefix = require('gulp-autoprefixer'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    twig = require('gulp-twig'),
    uglify = require('gulp-uglify')

gulp.task('twig', function (){
    return gulp.src(['resources/source/html/*.twig'])
        .pipe(twig()
            .on('error', function(err) { 
                process.stderr.write(err.message + '\n');
                this.emit('end');
            }))
        .pipe(gulp.dest('resources/dist/html'));
});

gulp.task('js', function () {
    return gulp.src(['resources/source/js/*.js'])
        //.pipe(jshint, require('./package.json').jshintConfig)
        //.pipe(jshint.reporter, 'jshint-stylish')
        //.pipe(jshint.reporter('fail'))
        .pipe(include())
        //.pipe(uglify())
        .pipe(gulp.dest('resources/dist/js'));
});

gulp.task('build', gulp.parallel(['twig', 'js']));