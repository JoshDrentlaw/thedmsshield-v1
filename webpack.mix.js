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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/tinymce/skins/ui/oxide/skin.min.css', 'public/css/skin.min.css')
    .copy('node_modules/tinymce/skins/ui/oxide/content.min.css', 'public/css/content.min.css')
    .copy('node_modules/tinymce/skins/content/default/content.css', 'public/css/content.css')
