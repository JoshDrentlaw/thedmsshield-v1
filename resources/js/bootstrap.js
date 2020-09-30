window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/** 
 * Font Awesome 5
 */
require('@fortawesome/fontawesome-free/js/all.js')

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * PNotify
 * Plugins - Bootstrap 4, Font Awesome 5 + fix
 * */

window.pnotify = require('@pnotify/core/dist/PNotify.js')
window.pnotifyMobile = require('@pnotify/mobile/dist/PNotifyMobile.js')
window.pnotifyBootstrap4 = require('@pnotify/bootstrap4/dist/PNotifyBootstrap4.js')
window.pnotifyFontAwesome5Fix = require('@pnotify/font-awesome5-fix/dist/PNotifyFontAwesome5Fix.js')
window.pnotifyFontAwesome5 = require('@pnotify/font-awesome5/dist/PNotifyFontAwesome5.js')

window.pnotify.defaultModules.set(window.pnotifyMobile, {})
window.pnotify.defaultModules.set(window.pnotifyBootstrap4, {})
window.pnotify.defaultModules.set(window.pnotifyFontAwesome5Fix, {})
window.pnotify.defaultModules.set(window.pnotifyFontAwesome5, {})

/**
 * Leaflet
 * Leaflet Sidebar
 */
window.L = require('leaflet/dist/leaflet.js')
require('leaflet-sidebar-v2/js/leaflet-sidebar.min.js')

/**
 * Select2
 */
window.select2 = require('select2/dist/js/select2.js')

/**
 * luxon
 */
import { DateTime } from 'luxon'
window.luxon = DateTime

import tinymce from 'tinymce'
import 'tinymce/themes/silver/theme'
import 'tinymce/plugins/autosave/plugin'
import 'tinymce/icons/default/icons'
window.tinymce = tinymce

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
