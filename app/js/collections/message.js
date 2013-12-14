/*!
* zzChat - HTML5 Chat Application
*
* Messages collection.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['underscore', 'backbone', 'models/message'],
    function(_, Backbone, MessageModel){
        'use strict';

        var MessageCollection = Backbone.Collection.extend({
            model: MessageModel,
            url: '/messages',

            initialize: function(models, options) {
                options = options || {};
                this.url = options.url || '/messages';
            }

        });

        return MessageCollection;
    }
);