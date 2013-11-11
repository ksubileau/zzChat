<?php
namespace ZZChat\Routers;

use \Slim\Slim;
use \ZZChat\Controllers\SessionController;

/**
 * Router class.
 *
 * @package ZZChat
 *
 */
class Router {
    protected $app;

     function __construct($app = null) {
            $this->app = ($app instanceof \Slim\Slim) ? $app : \Slim\Slim::getInstance();
     }

    public function setup () {
        $this->app->map('/login',array($this, 'login'))->via('POST');
    }

    public function login () {
        echo SessionController::login($this->app->request()->getBody());
    }

}