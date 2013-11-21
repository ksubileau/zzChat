/*!
* zzChat - HTML5 Chat Application
*
* Home tab view.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['jquery', 'underscore', 'backbone', 'i18next', 'collections/user', 'views/tab', 'text!templates/tab-home.html'],
    function($, _, Backbone, i18n, UserCollection, TabItemView, homeTabItem){
        'use strict';

        var HomeTabItem = TabItemView.extend({
            tabClassName: 'tab-home',

            template: _.template(homeTabItem),

            userCollection: new UserCollection(),

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

            initialize: function() {
                this.listenTo(this.userCollection, 'all', this.render);
                this.userCollection.fetch();
            },

            render: function() {
                this.$el.html(this.template({
                    i18n: i18n,
                    users: this.userCollection,
                }));

                this.$el.attr('id', this.getId());

                // Placeholder support for IE9 and others fu**ing browers.
                $('input, textarea', this.$el).placeholder();
                return this;
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