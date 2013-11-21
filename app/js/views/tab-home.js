/*!
* zzChat - HTML5 Chat Application
*
* Home tab view.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['jquery', 'underscore', 'backbone', 'i18next', 'views/tab', 'text!templates/tab-home.html'],
    function($, _, Backbone, i18n, TabItemView, homeTabItem){
        'use strict';

        var HomeTabItem = TabItemView.extend({
            tabClassName: 'tab-home',

            template: _.template(homeTabItem),

            events : {
                "click #roomlist tr": "showRoom",
                "click #userlist tr": "showPrivate"
            },

            // Tab properties
            id:"home",
            href:"home",
            closeable: false,
            icon: "home",
            title: function() {
                return i18n.t("home");
            },

            // Triggered when the user wants to enter a room.
            showRoom : function(e){
                e.preventDefault();
                var roomId = $(e.currentTarget).data('room-id');
                zzChat.router.navigate("room-" + roomId, true);
            },

            // Triggered when the user wants to chat in private with another user.
            showPrivate : function(e){
                e.preventDefault();
                var userId = $(e.currentTarget).data('user-id');
                zzChat.router.navigate("private-" + userId, true);
            },
        });
        return HomeTabItem;
    }
);