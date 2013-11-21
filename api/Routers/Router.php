<?php
namespace ZZChat\Routers;

use \Slim\Slim;
use \ZZChat\Controllers\SessionController;
use \ZZChat\Controllers\UserController;

/**
 * Router class.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
class Router
{
    protected $app;

     function __construct($app = null) {
            $this->app = ($app instanceof \Slim\Slim) ? $app : \Slim\Slim::getInstance();
     }

    public function setup () {
        $this->app->map('/login',array($this, 'login'))->via('POST');
        $this->app->map('/users',array($this, 'getUserList'))->via('GET');
    }

    public function login () {
        echo SessionController::login($this->app->request()->getBody());
    }

    public function getUserList () {
        echo UserController::getUserList();
    }

}