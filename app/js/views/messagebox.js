/*!
* zzChat - HTML5 Chat Application
*
* Message list box.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define([
        'jquery',
        'underscore',
        'backbone',
        'i18next',
        'views/disposable',
        'text!templates/messagebox.html'
    ],
    function($, _, Backbone, i18n, DisposableView, messagebox){
        'use strict';

        var MessageBoxView = DisposableView.extend({
            template: _.template(messagebox),

            initialize: function(messagelist) {
                this.messagelist = messagelist;

                this.listenTo(this.messagelist, 'all', _.debounce(this.render, 500, true));
            },

            render: function() {
                this.$el.html(this.template({
                    i18n: i18n,
                    messages: this.messagelist,
                    me: zzChat.me,
                }));

                return this;
            },

        });
        return MessageBoxView;
    }
);