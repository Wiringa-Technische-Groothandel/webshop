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

mix.sass("resources/frontend/sass/app.scss", 'public/assets/frontend/css')
    .js('resources/frontend/js/vendors.js', 'public/assets/frontend/js/vendors.js')
    .js('resources/frontend/js/app.js', 'public/assets/frontend/js/app.js')
    .sass('resources/admin/sass/app.scss', 'public/assets/admin/css/')
    .js('resources/admin/js/vendors.js', 'public/assets/admin/js/vendors.js')
    .js('resources/admin/js/app.js', 'public/assets/admin/js/app.js')
    .js('resources/global/js/head.js', 'public/assets/head.js')
    .version();
