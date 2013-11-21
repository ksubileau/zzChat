/*!
* zzChat - HTML5 Chat Application
*
* Tab item view management.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['jquery', 'underscore', 'backbone', 'i18next', 'views/disposable'],
    function($, _, Backbone, i18n, DisposableView){
        'use strict';

        var TabItemView = DisposableView.extend({
            tagName: 'div',
            tabClassName: '',

            className: function() {
                return 'tab-pane active' + ' ' + this.tabClassName;
            },

            // Tab properties
            id:null,
            href:null,
            closeable: true,
            icon: null,
            title: null,

            render: function() {
                this.$el.html(this.template({
                    i18n: i18n,
                }));

                this.$el.attr('id', this.getId());

                // Placeholder support for IE9 and others fu**ing browers.
                $('input, textarea', this.$el).placeholder();
                return this;
            },

            getId: function() {
                return _.result(this, 'id');
            },

            getHref: function() {
                return _.result(this, 'href');
            },

            isCloseable: function() {
                return _.result(this, 'closeable');
            },

            getIcon: function() {
                return _.result(this, 'icon');
            },

            getTitle: function() {
                return _.result(this, 'title');
            },

        });
        return TabItemView;
    }
);