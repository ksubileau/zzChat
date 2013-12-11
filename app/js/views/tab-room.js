/*!
* zzChat - HTML5 Chat Application
*
* Room tab view.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['jquery', 'underscore', 'backbone', 'i18next', 'views/tab', 'text!templates/tab-room.html'],
    function($, _, Backbone, i18n, TabItemView, roomTabItem){
        'use strict';

        var RoomTabItem = TabItemView.extend({
            tabClassName: 'tab-room',

            template: _.template(roomTabItem),

            room: null,

            initialize : function (room) {
                this.room = room;
                this.on('tab:open', function() {
                    this.room.enter();
                }, this);
                this.on('tab:close', function() {
                    this.room.leave();
                }, this);
            },

            events : {
                "click #sendResponse": "sendMessage",
            },

            // Tab properties
            closeable: true,
            icon: "comment",

            title: function () {
                return this.room.get("name");
            },

            href: function() {
                return "room-" + this.getId();
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