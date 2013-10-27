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
	baseUrl: '/app/js',
	// Disable cache
	urlArgs: "bust=" +  (new Date()).getTime(),
	paths: {
		jquery: 'libs/jquery/jquery.min',
		underscore: 'libs/underscore/underscore.min',
		backbone: 'libs/backbone/backbone.min',
		text: 'libs/require/text',
		bootstrap: 'libs/bootstrap/bootstrap.min',
		i18next: 'libs/i18next/i18next.min',
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
		}
	}
});

window.options = {
	i18next_options : {
		resGetPath: 'locales/__lng__.json',
		useCookie: false,
		fallbackLng: 'en',
		getAsync: false // Synchronous loading in order to avoid uninitialized errors.
	},
	langAvailable : {
		"en":"English",
		"fr":"Français",
		"es":"Español",
	},
	currentLang : "fr",
	apiRoot : "/core",
};

define(['jquery', 'bootstrap', 'i18next', 'views/home'], function($, _bootstrap, i18n, HomeView){
    'use strict';

    /* 
     * Override Backbone.sync in order to add a root URL to all Backbone API request.
     * Inspired from https://coderwall.com/p/8ldaqw
     */
    // Store the original version of Backbone.sync
    var backboneSync = Backbone.sync;
    Backbone.sync = function (method, model, options) {
    	var rootUrl = window.options.apiRoot;
    	var url = _.isFunction(model.url) ? model.url() : model.url;

    	// If no url, don't override, let Backbone.sync do its normal fail
    	if (url) {
    		options = _.extend(options, {
    			url: rootUrl + (rootUrl.charAt(rootUrl.length - 1) === '/' ? '' : '/') 
    						 + (url.charAt(0) === '/' ? url.substr(1) : url)
    		});
        }

        // Call the stored original Backbone.sync method with the new url property
        backboneSync(method, model, options);
    };
    
	// Initialize internationalization
	i18n.init(options.i18next_options);
	
	// Start application
	var home_view = new HomeView;
	home_view.render();
});