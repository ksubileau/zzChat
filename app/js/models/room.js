/*!
* zzChat - HTML5 Chat Application
*
* Room model.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['underscore', 'backbone'],
	function(_, Backbone){
    	'use strict';

		var RoomModel = Backbone.Model.extend({
			idAttribute: 'id',

			urlRoot: function() {
				return '/room/' + this.get("id");
			},

		    defaults: {
		        name: '',
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