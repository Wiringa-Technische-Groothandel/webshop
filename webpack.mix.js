const mix = require('laravel-mix');

mix.webpackConfig({
    output: {
        publicPath: '/',
        chunkFilename: 'assets/chunks/[name].js',
    }
});

mix.options({
    postCss: [
        require('autoprefixer'),
    ],
});

mix
    .js('resources/frontend/main.js', 'public/assets/frontend/main.js')
    .js('resources/admin/main.js', 'public/assets/admin/main.js')
    .js('resources/global/js/base-components.js', 'public/assets/base-components.js')
    .js('resources/global/js/polyfills.js', 'public/assets/polyfills.js')
    .version();
