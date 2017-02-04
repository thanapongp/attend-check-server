const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')

    .combine([
        'public/css/app.css',
        'vendor/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'
    ], 'public/css/app.css')

    .combine([
        'public/js/app.js',
        'vendor/bower_components/moment/min/moment.min.js',
        'vendor/bower_components/moment/locale/th.js',
        'vendor/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
    ], 'public/js/app.js')

    .version(['css/app.css', 'js/app.js']);