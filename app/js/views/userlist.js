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
                'click .userlist tr': 'rowClick',
            },

            title: '',
            activeOnly: true,
            scrollPos: 0,

            initialize: function(users, title, activeOnly) {
                this.users = users;
                if(title) {
                    this.title = title;
                }
                if(activeOnly) {
                    this.activeOnly = activeOnly;
                }
                this.listenTo(this.users, 'all', _.debounce(this.update, 500, true));
            },

            render: function() {
                this.$el.html(this.template({
                    i18n: i18n,
                    title: this.title,
                    users: this.activeOnly?this.users.where({isActive: true}):this.users.models,
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
                zzChat.router.navigate('private-' + userId, true);
            },

            update: function() {
                this.scrollPos = this.$('.scrollable').scrollTop();
                this.render();
                this.restoreScroll();
            },

            restoreScroll: function() {
                this.$('.scrollable').scrollTop(this.scrollPos);
            },

            onDispose: function() {
                this.scrollPos = this.$('.scrollable').scrollTop();
            }

        });
        return UserListView;
    }
);