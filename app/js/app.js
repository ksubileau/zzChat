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
	    i18next: 'libs/i18next/i18next'
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

var i18n_options = { 
		resGetPath: 'locales/__lng__.json',
		useCookie: false,
		fallbackLng: 'en',
		getAsync: false // Synchronous loading in order to avoid uninitialized errors.
};


define(['jquery', 'bootstrap', 'i18next', 'views/home'], function($, _bootstrap, i18n, HomeView){
	i18n.init(i18n_options);
	window.i18n = i18n;
	var home_view = new HomeView;
	home_view.render();
});