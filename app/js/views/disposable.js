/*!
* zzChat - HTML5 Chat Application
*
* A base view class for a better subview management.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define(['jquery', 'underscore', 'backbone'],
    function($, _, Backbone){
        'use strict';

        var DisposableView = Backbone.View.extend({

            // Register a child view that have been rendered.
            rendered: function(subView) {
                if (!this.renderedSubViews) {
                    this.renderedSubViews = [];
                }

                if (_(this.renderedSubViews).indexOf(subView) === -1) {
                    this.renderedSubViews.push(subView);
                }

                if (subView.onRendered) {
                    subView.onRendered();
                }

                return subView;
            },

            // Deregister a child view that have been disposed.
            disposed: function(subView) {
                this.renderedSubViews = _(this.renderedSubViews).without(subView);
            },

            // Cleanly disposes this view and all of it's rendered subviews
            dispose: function() {
                if (this.onDispose) {
                    this.onDispose();
                }

                if (this.renderedSubViews) {
                    for (var i = 0; i < this.renderedSubViews.length; i++) {
                        this.renderedSubViews[i].dispose();
                    }
                }

                this.$el.empty();
                this.undelegateEvents();
            }
        });
        return DisposableView;
    }
);