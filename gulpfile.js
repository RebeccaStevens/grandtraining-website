/*
Copyright (c) 2015 The Polymer Project Authors. All rights reserved.
This code may only be used under the BSD style license found at http://polymer.github.io/LICENSE.txt
The complete set of authors may be found at http://polymer.github.io/AUTHORS.txt
The complete set of contributors may be found at http://polymer.github.io/CONTRIBUTORS.txt
Code distributed by Google as part of the polymer project is also
subject to an additional IP rights grant found at http://polymer.github.io/PATENTS.txt
*/

'use strict';

// config
var wwwConfig = {
  devPath:  'client/www',
  tempPath: '.tmp/www',
  distPath: 'server/www/views/~dist'
};

var adminConfig = {
  devPath:  'client/admin',
  tempPath: '.tmp/admin',
  distPath: 'server/admin/views/~dist'
};

// Include Gulp & tools we'll use
var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var del = require('del');
var runSequence = require('run-sequence');
var browserSync = require('browser-sync');
var reload = browserSync.reload;
var merge = require('merge-stream');
var path = require('path');
var fs = require('fs');
var glob = require('glob');
var historyApiFallback = require('connect-history-api-fallback');
var proxyMiddleware = require('http-proxy-middleware');
var packageJson = require('./package.json');
var crypto = require('crypto');

var AUTOPREFIXER_BROWSERS = [
  'ie >= 10',
  'ie_mob >= 10',
  'ff >= 30',
  'chrome >= 34',
  'safari >= 7',
  'opera >= 23',
  'ios >= 7',
  'android >= 4.4',
  'bb >= 10'
];

var styleTask = function(config, stylesPath, srcs) {
  return gulp.src(srcs.map(function(src) {
      return path.join(config.devPath, stylesPath, src);
    }))
    .pipe($.changed(stylesPath, {extension: '.css'}))
    .pipe($.autoprefixer(AUTOPREFIXER_BROWSERS))
    .pipe(gulp.dest(config.tempPath + '/' + stylesPath))
    .pipe($.cssmin())
    .pipe(gulp.dest(config.distPath + '/' + stylesPath))
    .pipe($.size({title: stylesPath}));
};

var jshintTask = function(src) {
  return gulp.src(src)
    .pipe($.jshint.extract()) // Extract JS from .html files
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.if(!browserSync.active, $.jshint.reporter('fail')));
};

var imageOptimizeTask = function(src, dest) {
  return gulp.src(src)
    .pipe($.cache($.imagemin({
      progressive: true,
      interlaced: true
    })))
    .pipe(gulp.dest(dest))
    .pipe($.size({title: 'images'}));
};

var optimizeHtmlTask = function(src, dest) {
  var assets = $.useref.assets();

  return gulp.src(src)
    // Replace path for vulcanized assets
    .pipe($.if('*.html', $.replace('elements/elements.html', 'elements/elements.vulcanized.html')))
    .pipe(assets)
    // Concatenate and minify JavaScript
    .pipe($.if('*.js', $.uglify({preserveComments: 'some'})))
    // Concatenate and minify styles
    // In case you are still using useref build blocks
    .pipe($.if('*.css', $.cssmin()))
    .pipe(assets.restore())
    .pipe($.useref())
    // Minify any HTML
    .pipe($.if('*.html', $.minifyHtml({
      quotes: true,
      empty: true,
      spare: true
    })))
    // Output files
    .pipe(gulp.dest(dest))
    .pipe($.size({title: 'html'}));
};

// Transpile JS to ES5.
var jsTask = function(config) {
  return gulp.src([config.devPath + '/**/*.{js,html}'])
    .pipe($.sourcemaps.init())
    .pipe($.if('*.html', $.crisper())) // Extract JS from .html files
    .pipe($.if('*.js', $.babel()))
    .pipe($.sourcemaps.write('.'))
    .pipe(gulp.dest(config.tempPath + '/'))
    .pipe(gulp.dest(config.distPath + '/'));
};

// Lint JavaScript
var jshintTask = function(config) {
  return jshintTask([
      config.devPath + '/scripts/**/*.js',
      config.devPath + '/elements/**/*.js',
      config.devPath + '/elements/**/*.html',
      'gulpfile.js'
    ])
    .pipe($.jshint.extract()) // Extract JS from .html files
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.if(!browserSync.active, $.jshint.reporter('fail')));
};

var copyTask = function(config) {
  var app = gulp.src([
    config.devPath + '/*',
    '!' + config.devPath + '/test',
    '!' + config.devPath + '/cache-config.json'
  ], {
    dot: true
  }).pipe(gulp.dest(config.distPath));

  var bower = gulp.src([
    'bower_components/**/*'
  ]).pipe(gulp.dest(config.distPath + '/bower_components'));

  var elements = gulp.src([config.devPath + '/elements/**/*.html',
                           config.devPath + '/elements/**/*.css',
                           config.devPath + '/elements/**/*.js'])
    .pipe(gulp.dest(config.distPath + '/elements'));

  var swBootstrap = gulp.src(['bower_components/platinum-sw/bootstrap/*.js'])
    .pipe(gulp.dest(config.distPath + '/elements/bootstrap'));

  var swToolbox = gulp.src(['bower_components/sw-toolbox/*.js'])
    .pipe(gulp.dest(config.distPath + '/sw-toolbox'));

  var vulcanized = gulp.src([config.devPath + '/elements/elements.html'])
    .pipe($.rename('elements.vulcanized.html'))
    .pipe(gulp.dest(config.distPath + '/elements'));

  return merge(app, bower, elements, vulcanized, swBootstrap, swToolbox)
    .pipe($.size({title: 'copy'}));
};

var fontsTask = function(config) {
  return gulp.src([config.devPath + '/fonts/**'])
    .pipe(gulp.dest(config.distPath + '/fonts'))
    .pipe($.size({title: 'fonts'}));
};

var htmlTask = function(config) {
  return optimizeHtmlTask(
    [config.distPath + '/**/*.html', '!' + config.distPath + '/{elements,test}/**/*.html'],
    config.distPath);
};

var vulcanizeTask = function(config) {
  var DEST_DIR = config.distPath + '/elements';
  return gulp.src(config.distPath + '/elements/elements.vulcanized.html')
    .pipe($.vulcanize({
      stripComments: true,
      inlineCss: true,
      inlineScripts: true
    }))
    .pipe(gulp.dest(DEST_DIR))
    .pipe($.size({title: 'vulcanize'}));
};

// Generate config data for the <sw-precache-cache> element.
// This include a list of files that should be precached, as well as a (hopefully unique) cache
// id that ensure that multiple PSK projects don't share the same Cache Storage.
// This task does not run by default, but if you are interested in using service worker caching
// in your project, please enable it within the 'default' task.
// See https://github.com/PolymerElements/polymer-starter-kit#enable-service-worker-support
// for more context.
var cacheConfigTask = function(dir, callback) {
  var config = {
    cacheId: packageJson.name || path.basename(__dirname),
    disabled: false
  };

  glob('{elements,scripts,styles}/**/*.*', {cwd: dir}, function(error, files) {
    if (error) {
      callback(error);
    } else {
      files.push('index.html', './', 'bower_components/webcomponentsjs/webcomponents-lite.min.js');
      config.precache = files;

      var md5 = crypto.createHash('md5');
      md5.update(JSON.stringify(config.precache));
      config.precacheFingerprint = md5.digest('hex');

      var configPath = path.join(dir, 'cache-config.json');
      fs.writeFile(configPath, JSON.stringify(config), callback);
    }
  });
};

//

// Compile and automatically prefix stylesheets
gulp.task('styles:www', function() {
  return styleTask(wwwConfig, 'styles', ['**/*.css']);
});
gulp.task('styles:admin', function() {
  return styleTask(adminConfig, 'styles', ['**/*.css']);
});
gulp.task('styles',  ['styles:www', 'styles:admin']);

// Transpile all JS to ES5.
gulp.task('js:www', function() {
  return jsTask(wwwConfig);
});
gulp.task('js:admin', function() {
  return jsTask(adminConfig);
});
gulp.task('js', ['js:www', 'js:admin']);

//
gulp.task('elements:www', function() {
  return styleTask(wwwConfig, 'elements', ['**/*.css']);
});
gulp.task('elements:admin', function() {
  return styleTask(adminConfig, 'elements', ['**/*.css']);
});
gulp.task('elements', ['elements:www', 'elements:admin']);

// Lint JavaScript
gulp.task('jshint:www', function() {
  return jshintTask(wwwConfig);
});
gulp.task('jshint:admin', function() {
  return jshintTask(adminConfig);
});
gulp.task('jshint', ['jshint:www', 'jshint:admin']);

// Optimize images
gulp.task('images:www', function() {
  return imageOptimizeTask(wwwConfig.devPath + '/images/**/*', wwwConfig.distPath + '/images');
});
gulp.task('images:admin', function() {
  return imageOptimizeTask(adminConfig.devPath + '/images/**/*', adminConfig.distPath + '/images');
});
gulp.task('images', ['images:www', 'images:admin']);

// Copy all files at the root level (app)
gulp.task('copy:www', function() {
  return copyTask(wwwConfig);
});
gulp.task('copy:admin', function() {
  return copyTask(adminConfig);
});
gulp.task('copy', ['copy:www', 'copy:admin']);

// Copy web fonts to dist
gulp.task('fonts:www', function() {
  return fontsTask(wwwConfig);
});
gulp.task('fonts:admin', function() {
  return fontsTask(adminConfig);
});
gulp.task('fonts', ['fonts:www', 'fonts:admin']);

// Scan your HTML for assets & optimize them
gulp.task('html:www', function() {
  return htmlTask(wwwConfig);
});
gulp.task('html:admin', function() {
  return htmlTask(adminConfig);
});
gulp.task('html', ['html:www', 'html:admin']);

// Vulcanize granular configuration
gulp.task('vulcanize:www', function() {
  vulcanizeTask(wwwConfig);
});
gulp.task('vulcanize:admin', function() {
  vulcanizeTask(adminConfig);
});
gulp.task('vulcanize', ['vulcanize:www', 'vulcanize:admin']);

// Generate cache-config.json
gulp.task('cache-config-dev:www', function(callback) {
  cacheConfigTask(wwwConfig.devPath, callback);
});
gulp.task('cache-config-dist:www', function(callback) {
  cacheConfigTask(wwwConfig.distPath, callback);
});
gulp.task('cache-config-dev:admin', function(callback) {
  cacheConfigTask(adminConfig.devPath, callback);
});
gulp.task('cache-config-dist:admin', function(callback) {
  cacheConfigTask(adminConfig.distPath, callback);
});
gulp.task('cache-config:www', ['cache-config-dev:www', 'cache-config-dist:www']);
gulp.task('cache-config:admin', ['cache-config-dev:admin', 'cache-config-dist:admin']);
gulp.task('cache-config', ['cache-config:www', 'cache-config:admin']);

// Clean output directory
gulp.task('clean:www', function(cb) {
  del([wwwConfig.tempPath, wwwConfig.distPath], cb);
});
gulp.task('clean:admin', function(cb) {
  del([adminConfig.tempPath, adminConfig.distPath], cb);
});
gulp.task('clean', ['clean:www', 'clean:admin']);

// Watch files for changes and reload
gulp.task('serve:www', ['styles:www', 'elements:www', 'images:www', 'js:www', 'cache-config:www'], function() {
  var proxy = proxyMiddleware('http://grandtraining.local/courses/**/*.json', {changeOrigin: true});

  browserSync({
    port: 5000,
    notify: false,
    logPrefix: 'gtwww',
    snippetOptions: {
      rule: {
        match: '<span id="browser-sync-binding"></span>',
        fn: function(snippet) {
          return snippet;
        }
      }
    },
    // https: true,
    server: {
      baseDir: [wwwConfig.tempPath, wwwConfig.devPath],
      middleware: [proxy, historyApiFallback()],
      routes: {
        '/bower_components': 'bower_components'
      }
    }
  });

  gulp.watch([wwwConfig.devPath + '/**/*.html'], ['js', reload]);
  gulp.watch([wwwConfig.devPath + '/styles/**/*.css'], ['styles', reload]);
  gulp.watch([wwwConfig.devPath + '/elements/**/*.css'], ['elements', reload]);
  gulp.watch([wwwConfig.devPath + '/{scripts,elements}/**/{*.js,*.html}'], ['jshint', 'js']);
  gulp.watch([wwwConfig.devPath + '/images/**/*'], reload);
});

// Watch files for changes & reload
gulp.task('serve:admin', ['styles:admin', 'elements:admin', 'js:admin', 'cache-config:admin'],
    function() {
  var proxy = proxyMiddleware('http://admin.grandtraining.local/courses/**/*.json', {changeOrigin: true});

  browserSync({
    port: 5000,
    notify: false,
    logPrefix: 'gtadmin',
    snippetOptions: {
      rule: {
        match: '<span id="browser-sync-binding"></span>',
        fn: function(snippet) {
          return snippet;
        }
      }
    },
    // https: true,
    server: {
      baseDir: [adminConfig.tempPath, adminConfig.devPath],
      middleware: [proxy, historyApiFallback()],
      routes: {
        '/bower_components': 'bower_components'
      }
    }
  });

  gulp.watch([adminConfig.devPath + '/**/*.html'], ['js', reload]);
  gulp.watch([adminConfig.devPath + '/styles/**/*.css'], ['styles', reload]);
  gulp.watch([adminConfig.devPath + '/elements/**/*.css'], ['elements', reload]);
  gulp.watch([adminConfig.devPath + '/{scripts,elements}/**/{*.js,*.html}'], ['jshint', 'js']);
  gulp.watch([adminConfig.devPath + '/images/**/*'], reload);
});

// Build and serve the output from the dist build
gulp.task('serve:www:dist', ['build:www'], function() {
  browserSync({
    port: 5001,
    notify: false,
    logPrefix: 'gtwwwdist',
    snippetOptions: {
      rule: {
        match: '<span id="browser-sync-binding"></span>',
        fn: function(snippet) {
          return snippet;
        }
      }
    },
    // https: true,
    server: wwwConfig.distPath,
    middleware: [historyApiFallback()]
  });
});

// Build and serve the output from the dist build
gulp.task('serve:admin:dist', ['build:admin'], function() {
  browserSync({
    port: 5001,
    notify: false,
    logPrefix: 'gtadmindiest',
    snippetOptions: {
      rule: {
        match: '<span id="browser-sync-binding"></span>',
        fn: function(snippet) {
          return snippet;
        }
      }
    },
    // https: true,
    server: adminConfig.distPath,
    middleware: [historyApiFallback()]
  });
});

// Build production files
gulp.task('build:www', ['clean:www'], function(cb) {
  runSequence(
    ['copy:www', 'styles:www'],
    ['elements:www', 'js:www'],
    ['jshint:www', 'images:www', 'fonts:www', 'html:www'],
    'vulcanize:www',
    'cache-config:www',
    cb);
});
gulp.task('build:admin', ['clean:admin'], function(cb) {
  runSequence(
    ['copy:admin', 'styles:admin'],
    ['elements:admin', 'js:admin'],
    ['jshint:admin', 'images:admin', 'fonts:admin', 'html:admin'],
    'vulcanize:admin',
    'cache-config:admin',
    cb);
});
gulp.task('build', ['build:www', 'build:admin']);

// the default task
gulp.task('default', ['build']);

// Load tasks for web-component-tester
// Adds tasks for `gulp test:local` and `gulp test:remote`
require('web-component-tester').gulp.init(gulp);

// Load custom tasks from the `tasks` directory
try { require('require-dir')('tasks'); } catch (err) {}
