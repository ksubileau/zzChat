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
                format: 'text'
            },

            getAuthor: function() {
                if(_.isObject(this.get('author'))) {
                    return this.get('author');
                } else {
                    return zzChat.users.get(this.get('author'));
                }
            },

            getAuthorID: function() {
                if(_.isObject(this.get('author'))) {
                    return this.get('author').id;
                } else {
                    return this.get('author');
                }
            },

            getAuthorNick: function(options) {
                options = options || {};
                options.defaultNick = options.defaultNick || 'Anonymous';

                var author = this.getAuthor();
                if(author) {
                    return author.get('nick');
                    //TODO Try feching user from server
                } else {
                    return options.defaultNick;
                }
            },

            getFormattedText: function() {
                if(this.get('format') === 'bbcode') {
                    return this.bbcodeDecode();
                }
                else { //Simple text or unknown format
                    return this.get('text');
                }

            },

            bbcodeDecode: function() {
                var message = this.get('text');
                var formatSearch =  [
                    /\[b\](.*?)\[\/b\]/ig,
                    /\[i\](.*?)\[\/i\]/ig,
                    /\[u\](.*?)\[\/u\]/ig,
                    /\[s\](.*?)\[\/s\]/ig
                ];

                // The array of strings to replace matches with
                var formatReplace = [
                    '<strong>$1</strong>',
                    '<em>$1</em>',
                    '<span style="text-decoration: underline;">$1</span>',
                    '<span style="text-decoration: line-through">$1</span>'
                ];

                // Perform the conversion
                for (var i = 0; i < formatSearch.length; i++) {
                    message = message.replace(formatSearch[i], formatReplace[i]);
                }

                return message;
            },

            validate: function(/* attrs */) {
                var errors = [];

                if(errors.length > 0) {
                    return errors;
                }
            }
        });
        return MessageModel;
    }
);