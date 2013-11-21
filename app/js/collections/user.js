/*!
* zzChat - HTML5 Chat Application
*
* Users collection.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['underscore', 'backbone', 'models/user'],
    function(_, Backbone, UserModel){
        'use strict';

        var UserCollection = Backbone.Collection.extend({
            model: UserModel,
            url: '/users',
        });

        return UserCollection;
    }
);