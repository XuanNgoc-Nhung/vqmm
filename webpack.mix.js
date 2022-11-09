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
    .js('resources/js/lich-su-vong-quay.js', 'public/js/lich-su-vong-quay.js')
    .js('resources/js/quan-ly-phan-thuong.js', 'public/js/quan-ly-phan-thuong.js')
    .js('resources/js/trang-chu.js', 'public/js/trang-chu.js')
    .options({
        processCssUrls: false
    })
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
