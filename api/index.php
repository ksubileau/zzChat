<?php

require( './config.php' );

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

require( ABSPATH . '/support/default-constants.php' );
require( ABSPATH . '/support/helpers.php' );
require( ABSPATH . '/vendor/Slim/Slim.php' );

// ZZChat calculates offsets from UTC.
date_default_timezone_set( 'UTC' );

// Set initial default constants.
set_initial_constants( );

// Set debug mode.
set_debug_mode( );

// Register Slim auto loader.
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
    'debug' => ZC_DEBUG,
));

$app->add(new \Slim\Middleware\MethodOverride());
$app->add(new \Slim\Middleware\ContentTypes());

$app->run();