/*!
* zzChat - HTML5 Chat Application
*
* Home page (login) view.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['jquery', 'backbone', 'underscore', 'i18next', 'models/user', 'text!templates/home.html', 'jquery.placeholder'],
    function($, Backbone, _, i18n, UserModel, homeView){
		'use strict';

		var HomeView = Backbone.View.extend({

		    el: $('#main'),

		    template: _.template(homeView),

		    events : {
		        "click #langAvailable li a": "changeLanguageEvent",
		        "submit #login-form": "login"
		    },

		    initialize: function() {

	    	},

		    render: function() {
		    	// Render view
		    	this.$el.html(this.template({
		    		i18n: i18n,
		    		currentLang: _.findWhere(window.zzchat.options.langAvailable, {"langcode" : window.zzchat.options.currentLang}),
		    		langList: _.reject(window.zzchat.options.langAvailable, function(lang) { return lang.langcode == window.zzchat.options.currentLang; }),
	    		}));

		    	// Placeholder support for IE9 and others fu**ing browers.
	    		$('input, textarea', this.$el).placeholder();

		    	return this;
		    },

		    // Triggered when the user selects a new language.
		    changeLanguageEvent : function(e){
		    	this.changeLanguage($(e.currentTarget).prop("hash").substr(1));
		    	e.preventDefault();
		    },

		    // Triggered when the user click on the "Log In" button.
		    login : function(e){
		    	var self = this;
		    	// Don't let this button submit the form
		    	e.preventDefault();
		    	// Unfocus and disable submit button
		    	$("#loginBtn", '#login-form').blur().prop('disabled', true);

		    	// Hide error message
		    	$("#login-error-message", '#login-form').slideUp();

		        var formValues = {
		        		nickname: $('#nickname', '#login-form').val(),
		        		age: $('#age', '#login-form').val(),
		        		gender: $('input[name=gender]:checked', '#login-form').val()
		        	};

		        // TODO Use Backbone methods instead of jQuery ajax call, as it doesn't use Backbone config.
		        // (API URL, data post format...)
		        $.ajax({
				    contentType: 'application/json',
				    data: JSON.stringify(formValues),
				    dataType: 'json',
				    success: function(response) {
                        window.zzchat.me = new UserModel;
				        window.zzchat.me.set(response.user);
				        // Save the authentication token.
				        // It will be automatically sent back to the server in future requests.
                        window.zzchat.token = response.token;
                        // TODO Change view
				    },
				    error: function() {
				    	// Enable submit button
				    	$("#loginBtn", '#login-form').prop('disabled', false);
				    	// Show error message
				    	$("#login-error-message", '#login-form').slideDown();
				    	// TODO Improve error message accuracy.
				    },
				    processData: false,
				    type: 'POST',
				    url: window.zzchat.options.api.url + '/login'
				});

		        return true;
		    },

		    changeLanguage : function(langCode){
		    	// Record the new language code
		    	window.zzchat.options.currentLang = langCode;
		    	// Load the translation file and re-render the view.
				i18n.setLng(langCode, $.proxy(this.render, this));
		    },

		});
		return HomeView;
    }
);