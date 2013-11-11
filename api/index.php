<?php
/**
 * Startup file.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */

require( dirname(__FILE__) .'/config.php' );

/** Chemin absolu vers le dossier de ZZChat. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

require( ABSPATH . '/Support/default-constants.php' );
require( ABSPATH . '/Support/helpers.php' );
require( ABSPATH . '/Support/ClassLoader.php' );
require( ABSPATH . '/Vendor/Slim/Slim.php' );

use \Slim\Slim;
use \Slim\Middleware;
use \ZZChat\Support;
use \ZZChat\Support\ClassLoader;
use \ZZChat\Routers\Router;

// ZZChat calculates offsets from UTC.
date_default_timezone_set( 'UTC' );

// Set initial default constants.
Support\set_initial_constants( );

// Set debug mode.
Support\set_debug_mode( );

// Register autoloader
ClassLoader::addDirectories(array(
    ABSPATH.'/Vendor/PhpSecLib',
));
ClassLoader::register();

$app = new Slim(array(
    'debug' => ZC_DEBUG,
));

$app->add(new Middleware\MethodOverride());
$app->add(new Middleware\ContentTypes());

$app->error(function (\Exception $e) use ($app) {
    $app->halt($e->getCode(), $e->getMessage());
});

// Set up routes
$router = new Router($app);
$router->setup();

if(!defined('ZZCHAT_TEST_MODE') || !ZZCHAT_TEST_MODE)
    $app->run();
