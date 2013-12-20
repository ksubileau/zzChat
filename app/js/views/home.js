/*!
* zzChat - HTML5 Chat Application
*
* Home page (login) view.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['jquery', 'backbone', 'underscore', 'i18next', 'views/disposable', 'models/user', 'text!templates/home.html', 'jquery.placeholder'],
    function($, Backbone, _, i18n, DisposableView, UserModel, homeView){
		'use strict';

		var HomeView = DisposableView.extend({

		    el: $('#main'),

		    template: _.template(homeView),

		    events : {
		        "click #langAvailable li a": "changeLanguageEvent",
		        "submit #login-form": "login"
		    },

		    last_user: null,

		    initialize: function() {
                if(zzChat.options.enableRememberMe && localStorage && localStorage.last_user) {
                	try {
                		var last_user = JSON.parse(localStorage.last_user);
                		this.last_user = new UserModel(last_user);
					} catch (e) { }
                }

	    	},

		    render: function() {
		    	// Render view
		    	this.$el.html(this.template({
		    		i18n: i18n,
		    		currentLang: _.findWhere(zzChat.options.langAvailable, {"langcode" : zzChat.getLanguage()}),
		    		langList: _.reject(zzChat.options.langAvailable, function(lang) { return lang.langcode == zzChat.getLanguage(); }),
		    		enableRememberMe: zzChat.options.enableRememberMe && localStorage
	    		}));

		    	// Placeholder support for IE9 and others fu**ing browers.
	    		$('input, textarea', this.$el).placeholder();

	    		if(this.last_user) {
	    			// Restore last values
	        		this.$('#nickname').val(this.last_user.get('nick'));
	        		this.$('#age').val(this.last_user.get('age'));
	        		if(this.last_user.get('sex') == 2 || this.last_user.get('sex') == 'female') {
	        			this.$('input[type=radio][value="female"]', '#login-form').attr('checked','checked');
	        		}
	        		else {
	        			this.$('input[type=radio][value="male"]', '#login-form').attr('checked','checked');
	        		}
	        		this.$('#rememberme').attr('checked','checked');
	    		}

		    	return this;
		    },

		    // Triggered when the user selects a new language.
		    changeLanguageEvent : function(e){
		    	// Load the translation file and re-render the view.
		    	zzChat.setLanguage($(e.currentTarget).prop("hash").substr(1), $.proxy(this.render, this));
		    	e.preventDefault();
		    },

		    // Triggered when the user click on the "Log In" button.
		    login : function(e){
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

		        var rememberMe = zzChat.options.enableRememberMe && localStorage && this.$('#rememberme').is(":checked");

		        // TODO Use Backbone methods instead of jQuery ajax call, as it doesn't use Backbone config.
		        // (API URL, data post format...)
		        $.ajax({
				    contentType: 'application/json',
				    data: JSON.stringify(formValues),
				    dataType: 'json',
				    success: function(response) {
                        var user = new UserModel;
				        user.set(response.user);
				        // Trigger successful login event
                        zzChat.trigger('zzChat:loginSuccess', {'user':user, 'token':response.token});

                        if(rememberMe) {
                        	// Store user's data
	                        localStorage.setItem("last_user", JSON.stringify(user));
	                    }
	                    else if (localStorage && localStorage.last_user) {
	                    	// Clear previous data
	                    	localStorage.removeItem("last_user");
	                    }
				    },
				    error: function(xhr, textStatus, errorThrown) {
				    	var message;
				    	// Enable submit button
				    	$("#loginBtn", '#login-form').prop('disabled', false);
				    	// Set error message
				    	switch(xhr.status) {
				    		case 409:
				    			message = i18n.t("nick_taken");
				    		break;
				    		default:
				    			message = i18n.t("login_error");
				    		break;
				    	}

				    	$("#login-error-message p", '#login-form').html(message);
				    	// Show error message
				    	$("#login-error-message", '#login-form').slideDown();
				    	// TODO Improve error message accuracy.
				    },
				    processData: false,
				    type: 'POST',
				    url: zzChat.options.api.url + '/login'
				});

		        return true;
		    },

		});
		return HomeView;
    }
);