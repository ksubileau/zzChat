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
		    	this.users = new UserCollection();
		    },

		    enter: function() {
		        $.ajax({
		        	beforeSend: function(xhr) {
                        xhr.setRequestHeader(zzChat.options.api.authHeaderName, zzChat.getAuthToken());
                    },
				    success: function(response) {
						//TODO this.users.fetch...
				    },
				    error: function() {
				    	// TODO Handle errors
				    },
				    processData: false,
				    type: 'GET',
				    url: zzChat.getUrlForModel(this, '/enter'),
				});
		    },

		    leave: function() {
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
				    url: zzChat.getUrlForModel(this, '/leave'),
				});
		    },

		    sendMessage: function(message) {
		        $.ajax({
				    contentType: 'application/json',
				    data: JSON.stringify({"text":message}),
				    dataType: 'json',
		        	beforeSend: function(xhr) {
                        xhr.setRequestHeader(zzChat.options.api.authHeaderName, zzChat.getAuthToken());
                    },
				    success: function(response) {
                        this.trigger('room:messageSent');
				    },
				    error: function() {
				    	// TODO Handle errors
				    },
				    processData: false,
				    type: 'POST',
				    url: zzChat.getUrlForModel(this, '/message'),
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