/*!
* zzChat - HTML5 Chat Application
*
* Backbone router.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define([
        'jquery',
        'backbone',
        'views/home',
        'views/main',
    ],
    function($, Backbone, HomeView, MainView) {
        'use strict';

        var Router = Backbone.Router.extend({
            currentView: null,

            routes: {
              "": "loginView",
              "home": "homeTab",
              "room-:id": "openRoom" // Room tab
              /*
              // TODO Routes :
              "private-:uid": // Private conversation with specified UID
              "settings": // User settings
              */
            },

            // Constructor
            initialize: function(){
                //Required for Backbone to start listening to hashchange events
                Backbone.history.start();
            },


            changeView: function(view) {
                if(null != this.currentView)
                    this.currentView.dispose();
                this.currentView = view;
                this.currentView.render();
            },

            loginView: function(){
                if(zzChat.isLogin()) {
                    this.navigate("#/home", true);
                    return;
                }
                this.navigate("");
                this.changeView(new HomeView);
            },

            homeTab: function() {
                // Redirect to login page if user is not logged in.
                if(!zzChat.isLogin()) {
                    this.navigate("", true);
                    return;
                }
                if(this.currentView === null || !this.currentView.showTab) {
                  var mainView = new MainView
                  this.changeView(mainView);
                }
                this.currentView.showTab('home');
            },

            openRoom: function(roomId) {
                // Redirect to login page if user is not logged in.
                if(!zzChat.isLogin()) {
                    this.navigate("", true);
                    return;
                }
                if(this.currentView === null || !this.currentView.showTab) {
                  var mainView = new MainView
                  this.changeView(mainView);
                }
                this.currentView.openRoom(roomId);
            }
        });
        return Router;
    }
);