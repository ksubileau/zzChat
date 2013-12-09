/*!
* zzChat - HTML5 Chat Application
*
* Rooms collection.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['underscore', 'backbone', 'models/room'],
    function(_, Backbone, RoomModel){
        'use strict';

        var RoomCollection = Backbone.Collection.extend({
            model: RoomModel,
            url: '/rooms',
        });

        return RoomCollection;
    }
);