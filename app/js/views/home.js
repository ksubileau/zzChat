/*!
* zzChat - HTML5 Chat Application
*
* Home page (login) view.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['jquery', 'backbone', 'underscore', 'text!templates/home.html'],
    function($, Backbone, _, homeView){
		var HomeView = Backbone.View.extend({
		    el: $('#main'),
	
		    // Our template for the line of statistics at the bottom of the app.
		    template: _.template(homeView),

		    render: function() {
		      this.$el.html(this.template());
		      return this;
		    },
		});
		return HomeView;
    }
);