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
        'use strict';

        var UserModel = Backbone.Model.extend({
            idAttribute: 'id',

            urlRoot: '/user',

            defaults: {
                nick: '',
                age: '',
                sex: '',
                isActive: true,
            },

            validate: function(/* attrs */) {
                var errors = [];

                if(errors.length > 0) {
                    return errors;
                }
            }
        });
        return UserModel;
    }
);