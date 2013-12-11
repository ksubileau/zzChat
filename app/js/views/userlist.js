/*!
* zzChat - HTML5 Chat Application
*
* User list.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['jquery', 'underscore', 'backbone', 'i18next', 'views/disposable', 'text!templates/userlist.html'],
    function($, _, Backbone, i18n, DisposableView, userlist){
        'use strict';

        var UserListView = DisposableView.extend({
            template: _.template(userlist),

            events : {
                "click .userlist tr": "rowClick",
            },

            title: "",

            initialize: function(users, title) {
                this.users = users;
                if(title) {
                    this.title = title;
                }
                this.listenTo(this.users, 'all', this.render);
            },

            render: function() {
                this.$el.html(this.template({
                    i18n: i18n,
                    title: this.title,
                    users: this.users,
                }));

                return this;
            },

            rowClick: function(e) {
                e.preventDefault();
                var userId = $(e.currentTarget).data('user-id');
                this.trigger('userlist:click', userId);
                this.showPrivate(userId);
            },

            // Triggered when the user wants to chat in private with another user.
            showPrivate : function(userId){
                zzChat.router.navigate("private-" + userId, true);
            },

        });
        return UserListView;
    }
);