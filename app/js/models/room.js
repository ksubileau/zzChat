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
		'underscore',
		'backbone',
        'collections/user',
        'collections/message',
    ],
	function(_, Backbone, UserCollection, MessageCollection){
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
				    success: function(response) {
						that.users.fetch();
						that.messages.fetch();
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
				    success: function(response) {
				    },
				    error: function() {
				    	// TODO Handle errors
				    },
				    processData: false,
				    type: 'GET',
				    url: zzChat.getUrlForModel(that, '/leave'),
				});
		    },

		    sendMessage: function(message) {
		    	var that = this;
		    	// TODO Use Message model
		        $.ajax({
				    contentType: 'application/json',
				    data: JSON.stringify({"text":message}),
				    //dataType: 'json',
		        	beforeSend: function(xhr) {
                        xhr.setRequestHeader(zzChat.options.api.authHeaderName, zzChat.getAuthToken());
                    },
				    success: function(response) {
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

		    validate: function(attrs) {
		        errors = [];

		        if(errors.length > 0)
		           return errors;
		        }
		});
		return RoomModel;
	}
);