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
    ],
	function(_, Backbone, UserCollection){
    	'use strict';

		var RoomModel = Backbone.Model.extend({
			idAttribute: 'id',

			urlRoot: '/room',

		    defaults: {
		        name: '',
		        users: null,
		    },

		    initialize: function() {
		    	var that = this;
		    	this.users = new UserCollection([], {
		    		url: function() {
		    			return that.url() +  '/users';
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
		        $.ajax({
				    contentType: 'application/json',
				    data: JSON.stringify({"text":message}),
				    dataType: 'json',
		        	beforeSend: function(xhr) {
                        xhr.setRequestHeader(zzChat.options.api.authHeaderName, zzChat.getAuthToken());
                    },
				    success: function(response) {
                        that.trigger('room:messageSent');
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