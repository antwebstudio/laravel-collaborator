const mix = require('laravel-mix');
require('laravel-mix-alias')

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
    /**
     * Alaises
     */
    .alias({
        '@': 'resources/',
        '~': 'resources/',
        '@npm': 'node_modules/'
    })

    /**
     * Config
     */
    .setPublicPath('./public')
    .version()
    .options({
        processCssUrls: false,
    })

    /**
     * Publish
     */
    // .copy('resources/images/*', 'public/images', false)
    .js('resources/js/app.js', 'js')
    .sass('resources/sass/app.scss', 'css')
