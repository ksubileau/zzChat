/*!
* zzChat - HTML5 Chat Application
*
* Application Javascript entry point.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzchat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/

define([
        'jquery',
        'underscore',
        'backbone',
        'i18next',
        'config',
        'router',
        'poller',
        'collections/user',
        'collections/room',
    ],
    function($, _, Backbone, i18n, config, Router, poller, UserCollection, RoomCollection){
        'use strict';

        var zzChat = {
            router: null,
            token: null,
            me: null,
            currentLang: null,
            options : null,

            users: new UserCollection(),
            rooms: new RoomCollection(),

            init: function() {
                // Register this instance to global scope
                this.registerGlobal();

                // Load configuration from file
                this.options = config;

                // Set poller instance
                this.poller = poller;
                // Listen to poller events
                this.listenTo(this.poller, 'api:user_new api:user_inactive api:user_deleted', _.debounce(this.onUsersChanged, 500, true));
                this.listenTo(this.poller, 'api:room_new', _.debounce(this.onNewRoom, 500, true));

                // Set Backbone options
                Backbone.emulateHTTP = this.options.api.emulateHTTP;
                Backbone.emulateJSON = this.options.api.emulateJSON;

                /*
                 * Override Backbone.sync in order to set the root URL of all Backbone API request
                 * and send the authentication token once the user is connected.
                 * Inspired from https://coderwall.com/p/8ldaqw
                 */
                // Store the original version of Backbone.sync
                Backbone.basicSync = Backbone.sync;
                Backbone.sync = function (method, model, options) {
                    var fullUrl = zzChat.getUrlForModel(model);

                    // If no url, don't override, let Backbone.sync do its normal fail
                    if (fullUrl) {
                        options = _.extend(options, {
                            url: fullUrl,

                            // Automatically send the authentication token in HTTP headers if available.
                            beforeSend: function(xhr) {
                                if(zzChat.isLogin()) {
                                    xhr.setRequestHeader(zzChat.options.api.authHeaderName, zzChat.getAuthToken());
                                }
                            },
                        });
                    }

                    // Call the stored original Backbone.sync method with the new url property
                    return Backbone.basicSync(method, model, options);
                };

                var i18nOpts = zzChat.options.i18next;
                // Configure default language if specified
                if (this.options.defaultLanguage !== null) {
                    if (this.isValidLangCode(this.options.defaultLanguage)) {
                        i18nOpts = _.extend(i18nOpts, {lng:this.options.defaultLanguage});
                    }
                    // TODO Throw error or warning if value is invalid ?
                }
                // Initialize internationalization
                i18n.init(i18nOpts);

                // Listen to succesfull login event
                this.on('zzChat:loginSuccess', this.onLoginSuccess);

                // Start application
                this.router = new Router();
            },

            getUrlForModel: function(model, extension) {
                var rootUrl = zzChat.options.api.url;
                var url = _.isFunction(model.url) ? model.url() : model.url;

                if (url) {
                    url = rootUrl + (rootUrl.charAt(rootUrl.length - 1) === '/' ? '' : '/') +
                                        (url.charAt(0) === '/' ? url.substr(1) : url);
                    if(extension) {
                        url = url + (url.charAt(url.length - 1) === '/' ? '' : '/') +
                                        (extension.charAt(0) === '/' ? extension.substr(1) : extension);
                    }
                }
                return url;
            },

            registerGlobal: function() {
                window.zzChat = this;
            },

            isLogin: function() {
                return this.getCurrentUser() !== null && this.getAuthToken() !== null;
            },

            setAuthToken: function(value) {
                this.token = value;
            },

            getAuthToken: function() {
                return this.token;
            },

            getCurrentUser: function() {
                return this.me;
            },

            setLanguage: function(langCode, callback) {
                return i18n.setLng(langCode, callback);
            },

            getLanguage: function() {
                // Get the current language of i18next.
                var selectedLng = i18n.lng();
                // Check that it corresponds to a valid language code.
                if(this.isValidLangCode(selectedLng)) {
                    return selectedLng;
                }
                // Else try to remove the second part of the language code.
                if(_.contains(selectedLng, '-')) {
                    selectedLng = selectedLng.split('-')[0];
                    if(this.isValidLangCode(selectedLng)) {
                        return selectedLng;
                    }
                }
                return null;
            },

            isValidLangCode: function(langCode) {
                return _.contains(_.pluck(this.options.langAvailable, 'langcode'), langCode);
            },

            onLoginSuccess: function(loginInfo) {
                this.me = loginInfo.user;
                // Save the authentication token.
                // It will be automatically sent back to the server in future requests.
                this.setAuthToken(loginInfo.token);

                // Initialize root collections
                this.users.fetch();
                this.rooms.fetch();

                // Start Poller
                this.poller.start();

                // Open Home tab
                this.router.navigate('/home', {trigger : true});
            },

            onUsersChanged: function() {
                this.users.fetch();
            },

            onNewRoom: function() {
                this.rooms.fetch();
            }
        };

        _.extend(zzChat, Backbone.Events);

        return zzChat;
    }
);