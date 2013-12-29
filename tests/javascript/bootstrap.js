require.config({
    baseUrl: '../../app/js/',
    urlArgs: 'cb=' + Math.random(),
    paths: {
        jquery: 'libs/jquery/jquery.min',
        underscore: 'libs/underscore/underscore.min',
        backbone: 'libs/backbone/backbone.min',
        text: 'libs/require/text',
        bootstrap: 'libs/bootstrap/bootstrap.min',
        i18next: 'libs/i18next/i18next.min',
        'jquery.placeholder': 'libs/jquery.placeholder/jquery.placeholder',
        'jquery.eventsource': 'libs/jquery.eventsource/jquery.eventsource',
        sinon: '../../tests/javascript/libs/sinon',
        tests: '../../tests/javascript/'
    },
    shim: {
        underscore: {
            exports: '_'
        },
        backbone: {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        },
        bootstrap: {
            deps: ['jquery'],
        },
        'jquery.placeholder': {
            deps: ['jquery'],
        },
        'jquery.eventsource': {
            deps: ['jquery'],
        },
        sinon: {
            exports: 'sinon'
        }
    },
    deps: ['bootstrap']
});

require([
        'jquery',
        'underscore',
        'backbone',
        'sinon'
    ],
    function($) {
        'use strict';
        var tests = [];

        tests.push('tests/models/UserTest');

        $(function(){
            require(tests, function() {
                // Start tests
                QUnit.start();
            });
        });

    }
);