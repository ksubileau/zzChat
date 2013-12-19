/*!
* zzChat - HTML5 Chat Application
*
* Room tab view.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define([
        'jquery',
        'underscore',
        'backbone',
        'i18next',
        'views/tab',
        'views/userlist',
        'views/messagebox',
        'views/sendbox',
        'text!templates/tab-room.html'
    ],
    function($, _, Backbone, i18n, TabItemView, UserListView, MessageBoxView, SendBoxView, roomTabItem){
        'use strict';

        var RoomTabItem = TabItemView.extend({
            tabClassName: 'tab-room',

            template: _.template(roomTabItem),

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
                this.sendboxview = new SendBoxView();

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

                this.listenTo(this.sendboxview, 'sendbox:message', this.sendMessage);
            },

            render: function() {
                this.$el.html(this.template({
                    i18n: i18n,
                }));

                this.$el.attr('id', this.getId());

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

                // Render send box view
                this.sendboxview.setElement(this.$('.sendbox-wrapper')).render();
                this.rendered(this.sendboxview);
                // Delegate Events
                this.sendboxview.delegateEvents();

                return this;
            },

            sendMessage: function(message) {
                this.room.sendMessage(message);
            }
        });
        return RoomTabItem;
    }
);