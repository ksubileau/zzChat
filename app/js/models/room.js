/*!
* zzChat - HTML5 Chat Application
*
* Room model.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define([
        'jquery',
        'underscore',
        'backbone',
        'collections/user',
        'collections/message',
    ],
    function($, _, Backbone, UserCollection, MessageCollection){
        'use strict';

        var RoomModel = Backbone.Model.extend({
            idAttribute: 'id',

            urlRoot: '/room',

            defaults: {
                name: '',
                users: null,
                messages: null,
            },

            initialize: function() {
                var that = this;
                this.users = new UserCollection([], {
                    url: function() {
                        return that.url() +  '/users';
                    }
                });
                this.messages = new MessageCollection([], {
                    url: function() {
                        return that.url() +  '/messages';
                    }
                });
            },

            enter: function() {
                var that = this;
                $.ajax({
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader(zzChat.options.api.authHeaderName, zzChat.getAuthToken());
                    },
                    success: function(/* response */) {
                        that.users.fetch();
                        that.messages.fetch();
                        that.listenTo(zzChat.poller, 'api:rooms', that.parseEvent);
                        that.listenTo(zzChat.poller, 'api:user_inactive', _.debounce(that.onUsersChanged, 500, true));
                    },
                    error: function() {
                        // TODO Handle errors
                    },
                    processData: false,
                    type: 'GET',
                    url: zzChat.getUrlForModel(that, '/enter'),
                });
            },

            leave: function() {
                var that = this;
                $.ajax({
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader(zzChat.options.api.authHeaderName, zzChat.getAuthToken());
                    },
                    success: function(/* response */) {
                        that.stopListening(zzChat.poller);
                    },
                    error: function() {
                        // TODO Handle errors
                    },
                    processData: false,
                    type: 'GET',
                    url: zzChat.getUrlForModel(that, '/leave'),
                });
            },

            parseEvent: function(event) {
                if(_.has(event, this.id)) {
                    event = event[this.id];

                    if(_.has(event, 'users_enter') || _.has(event, 'users_leave')) {
                        this.users.fetch();
                    } else if(_.has(event, 'messages_new')) {
                        this.messages.fetch();
                    }
                }
            },

            onUsersChanged: function(/* data */) {
                this.users.fetch();
            },

            sendMessage: function(message, format) {
                var that = this;
                // TODO Use Message model
                if(!format) {
                    format = 'text';
                }
                $.ajax({
                    contentType: 'application/json',
                    data: JSON.stringify({'text':message, 'format':format}),
                    //dataType: 'json',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader(zzChat.options.api.authHeaderName, zzChat.getAuthToken());
                    },
                    success: function(/* response */) {
                        that.trigger('room:messageSent');
                        that.messages.fetch();
                    },
                    error: function() {
                        // TODO Handle errors
                    },
                    processData: false,
                    type: 'POST',
                    url: zzChat.getUrlForModel(that, '/message'),
                });
            },

            validate: function(/* attrs */) {
                var errors = [];

                if(errors.length > 0) {
                    return errors;
                }
            }
        });
        return RoomModel;
    }
);