/*!
* zzChat - HTML5 Chat Application
*
* Home tab view.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['jquery', 'underscore', 'backbone', 'i18next', 'views/tab', 'views/userlist', 'text!templates/tab-home.html'],
    function($, _, Backbone, i18n, TabItemView, UserListView, homeTabItem){
        'use strict';

        var HomeTabItem = TabItemView.extend({
            tabClassName: 'tab-home',

            template: _.template(homeTabItem),

            events : {
                "click #roomlist tr": "showRoom",
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
                this.userlistview = new UserListView(zzChat.users, i18n.t("online_users"));
                this.listenTo(zzChat.rooms, 'all', _.debounce(this.render, 500, true));
            },

            render: function() {
                this.$el.html(this.template({
                    i18n: i18n,
                    rooms: zzChat.rooms,
                }));

                this.$el.attr('id', this.getId());

                // Placeholder support for IE9 and others fu**ing browers.
                $('input, textarea', this.$el).placeholder();

                // Render users list
                this.userlistview.setElement(this.$('#home-user-list')).render();
                this.rendered(this.userlistview);
                // Delegate Events
                this.userlistview.delegateEvents();

                return this;
            },

            // Triggered when the user wants to enter a room.
            showRoom : function(e){
                e.preventDefault();
                var roomId = $(e.currentTarget).data('room-id');
                zzChat.router.navigate("room-" + roomId, true);
            },
        });
        return HomeTabItem;
    }
);