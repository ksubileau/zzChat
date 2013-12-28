#!/bin/bash

if [ -n "$TEST_SUITE" ]
then
    if [ "$TEST_SUITE" = "JavascriptTests" ]
    then
        cd ./javascript
        phantomjs testrunner.js
    else
        cd ./PHPUnit
        if [ "$TEST_SUITE" != "PHPTests" ]
        then
            phpunit --coverage-text --colors --testsuite $TEST_SUITE
        else
            phpunit --coverage-text --colors
        fi
    fi
    cd -
else
    echo 'TEST_SUITE is not defined.'
    exit 1
fi