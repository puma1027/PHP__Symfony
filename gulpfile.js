'use strict';

const gulp = require('gulp');
const fs = require('fs');
const sass = require('gulp-sass');
const plumber = require('gulp-plumber');
const notify = require('gulp-notify');
const autoprefixer = require('gulp-autoprefixer');
const browser = require('browser-sync');
const htmlhint = require('gulp-htmlhint');
const phplint = require('gulp-phplint');
const rename = require('gulp-rename');
const postcss = require('gulp-postcss');
// const pug = require('gulp-pug');
const uglify = require('gulp-uglify');
// const webpackstream = require('webpack-stream');
// const webpack = require('webpack');

// const webpackconfig = require('./webpack.config.js');

//var localhost = 'localhost:8004';//ローカルホストのポートを設定

//browser sync
/*
gulp.task('server', function(){
	return browser({
		proxy: localhost

	});
});
*/

//ワードプレスを使わないときはこっち使う
gulp.task('server', function(){
	return browser({
		server: {
			baseDir: 'www/'
		}
	});
});

gulp.task('reload', function(){
	browser.reload();
});

//sass
gulp.task('sass', function(){
	return gulp.src('sass/**/*scss')
		.pipe(plumber({
			errorHandler: notify.onError('Error: <%= error.message %>') //<-
		}))
		.pipe(sass())
		.pipe(autoprefixer())
		.pipe(gulp.dest('www'))
		.pipe(gulp.dest('html'))
		.pipe(postcss([
			require('cssnano'),
			require('css-mqpacker') //media queryを1つにまとめてくれる。
			]))
		.pipe(rename({
			extname:'.min.css'
			}))
		.pipe(gulp.dest('www'))
		.pipe(gulp.dest('html'))
		.pipe(browser.reload({stream:true}));
});

//html 構文チェック
gulp.task('html', function(){
	return gulp.src('./www/**/*.html')
		.pipe(plumber({
			errorHandler: notify.onError('Error: <%= error.message %>') //<-
		}))
		.pipe(htmlhint())
		.pipe(htmlhint.failReporter())
		.pipe(browser.reload({stream:true}));
});

//js圧縮
// gulp.task('uglify', function(){
// 	return gulp.src(['./www/article/assets/***/*.js','!./www/assets/***/*.min.js'])
// 		.pipe(uglify({preserveComments: 'some'}))
// 		.pipe(rename({
// 			extname:'.min.js'
// 			}))
// 		.pipe(gulp.dest('./www/article/assets/'));
// });

//phplint チェッカー。defaultではまわさない。必要なときに実行すること。
// gulp.task('phplint', function(){
// 	return gulp.src('./www/wordpress/wp-content/themes/**/*.php')
// 		.pipe(phplint())
// 		.pipe(browser.reload({stream:true}));
// });

//pug
// gulp.task('pug', () => {
// 	let option = {
// 		pretty: '\t'
// 	}
// 	return gulp.src(['./pug/**/*.pug', '!./pug/**/_*.pug'])
// 		.pipe(plumber({
// 			errorHandler: notify.onError('Error: <%= error.message %>')
// 		}))
// 		.pipe(pug(option))
// 		.pipe(gulp.dest('./www/'))
// 		.pipe(browser.reload({stream:true}));
// });

//webpack
// gulp.task('webpack', () => {
// 	return webpackstream(webpackconfig, webpack)
// 		.pipe(gulp.dest('./www/user_data/packages/new_mobile/js/'))
// });

gulp.task('default',['server'], function(){
		gulp.watch('sass/**/*.scss',['sass']);
		// gulp.watch('./www/**/*.html',['html']);
		// gulp.watch('./www/**/*.php',['reload']);
		// gulp.watch('pug/**/*.pug',['pug']);
		// gulp.watch('./src/**/*.js',['webpack']);
});
