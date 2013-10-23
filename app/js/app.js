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
	    bootstrap: 'libs/bootstrap/bootstrap.min'
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


define(['jquery', 'bootstrap', 'views/home'], function($, _bootstrap, HomeView){
	var home_view = new HomeView;
	home_view.render();
});