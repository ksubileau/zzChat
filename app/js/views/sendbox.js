/*!
* zzChat - HTML5 Chat Application
*
* Send message box view.
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
        'text!templates/sendbox.html'
    ],
    function($, _, Backbone, i18n, DisposableView, sendbox){
        'use strict';

        var SendBoxView = DisposableView.extend({
            template: _.template(sendbox),

            events : {
                "click #sendResponse": "triggerMessage",
            },

            currentText: '',

            render: function() {
                this.$el.html(this.template({
                    i18n: i18n,
                }));

                // Placeholder support for IE9 and others fu**ing browers.
                $('input, textarea', this.$el).placeholder();

                this.$('#responseText').val(this.currentText);

                return this;
            },

            onDispose: function() {
                this.currentText = this.$('#responseText').val();
            },

            triggerMessage: function(e) {
                e.preventDefault();
                // Signal message ready to send
                this.trigger('sendbox:message', this.$('#responseText').val());
                // Clear input value
                this.$('#responseText').val('');
            },

        });
        return SendBoxView;
    }
);