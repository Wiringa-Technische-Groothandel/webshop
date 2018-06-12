const mix = require('laravel-mix');

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

mix
    .sass("resources/assets/sass/app.scss", 'public/assets/css')
    .sass('resources/assets/sass/admin/app.scss', 'public/assets/css/admin/')
    .styles([
        'node_modules/animate.css/animate.min.css',
    ], 'public/assets/css/vendor.css')
    .js('resources/assets/js/app.js', 'public/assets/js/app.js')
    .js('resources/assets/js/admin/app.js', 'public/assets/js/admin/app.js')
    .version();

