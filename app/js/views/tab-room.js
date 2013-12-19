/*!
* zzChat - HTML5 Chat Application
*
* Room tab view.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['jquery', 'underscore', 'backbone', 'i18next', 'views/tab', 'views/userlist', 'views/messagebox', 'text!templates/tab-room.html'],
    function($, _, Backbone, i18n, TabItemView, UserListView, MessageBoxView, roomTabItem){
        'use strict';

        var RoomTabItem = TabItemView.extend({
            tabClassName: 'tab-room',

            template: _.template(roomTabItem),

            events : {
                "click #sendResponse": "sendMessage",
            },

            // Tab properties
            closeable: true,
            icon: "comment",
            room: null,

            title: function () {
                return this.room.get("name");
            },

            href: function() {
                return "room-" + this.getId();
            },

            initialize : function (room) {
                this.room = room;
                this.userlistview = new UserListView(this.room.users, i18n.t("online_users"));
                this.messageboxview = new MessageBoxView(this.room.messages);

                this.on('tab:open', function() {
                    this.room.enter();
                }, this);
                this.on('tab:close', function() {
                    this.room.leave();
                }, this);
                this.on('tab:show', function() {
                    this.userlistview.restoreScroll();
                    this.messageboxview.restoreScroll();
                }, this);
            },

            render: function() {
                this.$el.html(this.template({
                    i18n: i18n,
                }));

                this.$el.attr('id', this.getId());

                // Placeholder support for IE9 and others fu**ing browers.
                $('input, textarea', this.$el).placeholder();

                // Render users list
                this.userlistview.setElement(this.$('.room-user-list')).render();
                this.rendered(this.userlistview);
                // Delegate Events
                this.userlistview.delegateEvents();

                // Render messages list
                this.messageboxview.setElement(this.$('.room-messages')).render();
                this.rendered(this.messageboxview);
                // Delegate Events
                this.messageboxview.delegateEvents();

                return this;
            },

            sendMessage: function(e) {
                e.preventDefault();
                this.room.sendMessage($('#responseText').val());
                $('#responseText').val('');
            }
        });
        return RoomTabItem;
    }
);