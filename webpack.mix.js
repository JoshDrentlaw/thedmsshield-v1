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
    .copy('node_modules/@pnotify/core/dist/PNotify.js', 'public/js/pnotify.js')
    .copy('node_modules/@pnotify/mobile/dist/PNotifyMobile.js', 'public/js/pnotifyMobile.js')
    .copy('node_modules/leaflet-sidebar-v2/js/leaflet-sidebar.min.js', 'public/js/leaflet-sidebar.min.js')
    .copy('node_modules/@fortawesome/fontawesome-free/js/all.js', 'public/js/fa-all.js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/@pnotify/core/dist/PNotify.css', 'public/css/pnotify.css')
    .copy('node_modules/@pnotify/mobile/dist/PNotifyMobile.css', 'public/css/pnotifyMobile.css')
    .copy('node_modules/leaflet-sidebar-v2/css/leaflet-sidebar.min.css', 'public/css/leaflet-sidebar.min.css')
    .copy('node_modules/@fortawesome/fontawesome-free/css/all.css', 'public/css/fa-all.css')
    .copy('node_modules/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css', 'public/css/select2-bs4.css')
