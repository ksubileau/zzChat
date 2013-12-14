/*!
* zzChat - HTML5 Chat Application
*
* Message model.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['underscore', 'backbone'],
    function(_, Backbone){
        'use strict';

        var MessageModel = Backbone.Model.extend({
            idAttribute: 'sent_time',

            urlRoot: '/message',

            defaults: {
                author: null,
                text: '',
                sent_time: null,
            },

            getAuthor: function() {
                if(_.isObject(this.get("author"))) {
                    return this.get("author");
                } else {
                    return zzChat.users.get(this.get("author"));
                }
            },

            getAuthorID: function() {
                if(_.isObject(this.get("author"))) {
                    return this.get("author").id;
                } else {
                    return this.get("author");
                }
            },

            getAuthorNick: function(options) {
                options = options || {};
                options.defaultNick = options.defaultNick || 'Anonymous';

                var author = this.getAuthor();
                if(author) {
                    return author.get("nick");
                    //TODO Try feching user from server
                } else {
                    return options.defaultNick;
                }
            },

            validate: function(attrs) {
                errors = [];

                if(errors.length > 0)
                   return errors;
                }
        });
        return MessageModel;
    }
);