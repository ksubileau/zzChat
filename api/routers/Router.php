<?php
namespace ZZChat\Routers;

use \Slim\Slim;
use \ZZChat\Controllers\SessionController;

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
        $this->app->map('/user',array($this, 'login'))->via('POST');
    }

    public function login () {
        echo SessionController::login($this->app->request()->getBody());
    }

}