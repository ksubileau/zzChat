/*!
* zzChat - HTML5 Chat Application
*
* Home page (login) view.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['jquery', 'backbone', 'underscore', 'i18next', 'text!templates/home.html'],
    function($, Backbone, _, i18n, homeView){
		var HomeView = Backbone.View.extend({
			
		    el: $('#main'),
	
		    template: _.template(homeView),
		    
		    events : {
		        "click #langAvailable li a": "changeLanguageEvent"
		    },

		    render: function() {
		    	// Render view
		    	this.$el.html(this.template({
		    		i18n: i18n,
		    		currentLangCode: options.currentLang,
		    		currentLangName: options.langAvailable[options.currentLang],
		    		langList: _.omit(options.langAvailable, options.currentLang)
	    		}));
	    		
		    	return this;
		    },
		    
		    // Triggered when the user selects a new language.
		    changeLanguageEvent : function(e){
		    	this.changeLanguage($(e.currentTarget).prop("hash").substr(1));
		    	e.preventDefault();
		    },
		    
		    changeLanguage : function(langCode){
		    	// Record the new language code
		    	options.currentLang = langCode;
		    	// Load the translation file and re-render the view.
				i18n.setLng(langCode, $.proxy(this.render, this));
		    }
		});
		return HomeView;
    }
);