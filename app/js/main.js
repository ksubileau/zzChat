/*!
* zzChat - HTML5 Chat Application
*
* Application Javascript entry point.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
require.config({
    baseUrl: 'js',
    // Disable cache
    //urlArgs: "t=" +  (new Date()).getTime(),
    paths: {
        jquery: 'libs/jquery/jquery.min',
        underscore: 'libs/underscore/underscore.min',
        backbone: 'libs/backbone/backbone.min',
        text: 'libs/require/text',
        bootstrap: 'libs/bootstrap/bootstrap.min',
        i18next: 'libs/i18next/i18next.min',
        'jquery.placeholder': 'libs/jquery.placeholder/jquery.placeholder',
        'jquery.eventsource': 'libs/jquery.eventsource/jquery.eventsource',
    },
    shim: {
        'underscore': {
            exports: '_'
        },
        'backbone': {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        },
        'bootstrap': {
            deps: ['jquery'],
        },
        'jquery.placeholder': {
            deps: ['jquery'],
        },
        'jquery.eventsource': {
            deps: ['jquery'],
        },
    },
    deps: ['bootstrap'],
});

require(['app'],
    function(ZZChat) {
        ZZChat.init();
    }
);