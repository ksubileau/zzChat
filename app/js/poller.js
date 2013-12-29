/*!
* zzChat - HTML5 Chat Application
*
* Events poller.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzchat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/

define([
        'jquery',
        'underscore',
        'backbone',
        'jquery.eventsource',
    ],
    function($, _, Backbone){
        'use strict';

        var poller = {
            active : false,

            start: function (options) {
                var that = this;
                if (!this.active) {
                    options && options.silent || this.trigger('poller:start');
                    this.active = true;

                    $.eventsource({
                        // Assign a label to this event source
                        label: 'zzchat-poller',

                        // Set the file to receive data from the server
                        url: zzChat.options.api.url + '/events?auth_token=' + zzChat.getAuthToken(),

                        // Set the type of data you expect to be returned
                        dataType: 'json',

                        // Set a callback to fire when the event source is opened
                        open: function( /* data */ ) {
                            options && options.silent || that.trigger('poller:opened');
                        },

                        // Trigger a Backbone event when a message is received
                        message: function( data ) {
                            that.trigger('api:' + (_.has(data, 'event')?data.event:'message'), data.data);
                        }
                    });
                }
                return this;
            },

            stop: function (options) {
                options && options.silent || this.trigger('poller:stop');
                this.active = false;
                $.eventsource('close', 'zzchat-poller');
                return this;
            },
        };

        _.extend(poller, Backbone.Events);

        return poller;
    }
);