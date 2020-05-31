const mix = require('laravel-mix');

mix.webpackConfig({
    output: {
        publicPath: '/',
        chunkFilename: 'assets/chunks/[name].js?id=[chunkhash]',
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
    .version();
