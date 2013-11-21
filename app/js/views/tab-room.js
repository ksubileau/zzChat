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

            events : {
                "click #sendResponse": "sendResponse",
            },

            // Tab properties
            id:"5890f6de9f72a9ae3313ffa34f17da2a",
            closeable: true,
            icon: "comment",
            title: "Isima",

            href: function() {
                return "room-" + this.getId();
            },

            sendResponse: function(e) {
                e.preventDefault();
                console.log('Réponse : '+ $('#responseText').val());
            }
        });
        return RoomTabItem;
    }
);