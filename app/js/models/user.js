/*!
* zzChat - HTML5 Chat Application
*
* User model.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['underscore', 'backbone'],
	function(_, Backbone){
		var UserModel = Backbone.Model.extend({
			idAttribute: 'uid',

			urlRoot: '/user',

		    defaults: {
		        nick: '',
				age: '',
				sex: '',
		    },

		    validate: function(attrs) {
		        errors = [];

		        if(errors.length > 0)
		           return errors;
		        }
		});
		return UserModel;
	}
);