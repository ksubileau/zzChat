<?php

require( dirname(__FILE__) .'/config.php' );

/** Chemin absolu vers le dossier de ZZChat. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

require( ABSPATH . '/support/default-constants.php' );
require( ABSPATH . '/support/helpers.php' );
require( ABSPATH . '/support/ClassLoader.php' );
require( ABSPATH . '/vendor/Slim/Slim.php' );

// ZZChat calculates offsets from UTC.
date_default_timezone_set( 'UTC' );

// Set initial default constants.
set_initial_constants( );

// Set debug mode.
set_debug_mode( );

// Register Slim's auto loader.
\Slim\Slim::registerAutoloader();

// Register non-Slim autoloader
ClassLoader::addDirectories(array(
    ABSPATH.'/controllers',
    ABSPATH.'/models',
    ABSPATH.'/routers',
    ABSPATH.'/support',
));
ClassLoader::register();

$app = new \Slim\Slim(array(
    'debug' => ZC_DEBUG,
));

$app->add(new \Slim\Middleware\MethodOverride());
$app->add(new \Slim\Middleware\ContentTypes());

if(!defined('ZZCHAT_TEST_MODE') || !ZZCHAT_TEST_MODE)
    $app->run();