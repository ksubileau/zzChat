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
    ],
    function($, Backbone, HomeView) {
        var Router = Backbone.Router.extend({
            currentView: null,

            routes: {
              "": "loginView",
              "#/home": "homeTab"
              /*
              // TODO Routes :
              "#/room/:id": // Room tab
              "#/private/:uid": // Private conversation with specified UID
              "#/settings": // User settings
              */
            },

            // Constructor
            initialize: function(){
                //Required for Backbone to start listening to hashchange events
                Backbone.history.start();
            },


            changeView: function(view) {
                if(null != this.currentView)
                    this.currentView.undelegateEvents();
                this.currentView = view;
                this.currentView.render();
                //$("#main").html(this.currentView.render().$el).show();
            },

            loginView: function(){
                if(zzChat.isLogin()) {
                    this.navigate("#/home", true);
                    return;
                }
                this.navigate("");
                this.changeView(new HomeView);
            },

            homeTab: function(){
                console.log("Go to home tab!");
            }
        });
        return Router;
    }
);