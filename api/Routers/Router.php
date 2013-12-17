<?php
namespace ZZChat\Routers;

use \Slim\Slim;
use \ZZChat\Support\ApiException;
use \ZZChat\Controllers\SessionController;
use \ZZChat\Controllers\UserController;
use \ZZChat\Controllers\RoomController;
use \ZZChat\Controllers\SSEController;

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
    public $app;

     function __construct($app = null) {
            $this->app = ($app instanceof \Slim\Slim) ? $app : \Slim\Slim::getInstance();
     }

    public function setup () {
        $that = $this;

        $this->app->map('/login',array($this, 'login'))->via('POST');

        // Users
        $this->app->map('/users', array($this, 'checkLoginOpt'), array($this, 'getUserList'))->via('GET');
        $this->app->get('/user/:id', array($this, 'checkLoginOpt'), function ($id) {
            echo UserController::getUser($id);
        });

        // Rooms
        $this->app->map('/rooms', array($this, 'checkLoginOpt'),array($this, 'getRoomList'))->via('GET');
        $this->app->get('/room/:id', array($this, 'checkLoginOpt'), function ($id) {
            echo RoomController::getRoom($id);
        });
        $this->app->get('/room/:id/users', array($this, 'checkLoginOpt'), function ($id) {
            echo RoomController::getUsers($id);
        });
        $this->app->get('/room/:id/messages', array($this, 'checkLoginOpt'), function ($id) {
            echo RoomController::getMessages($id);
        });
        $this->app->get('/room/:id/enter', array($this, 'checkLogin'), function ($id) {
            echo RoomController::enter($id);
        });
        $this->app->get('/room/:id/leave', array($this, 'checkLogin'), function ($id) {
            echo RoomController::leave($id);
        });
        $this->app->post('/room/:id/message', array($this, 'checkLogin'), function ($id) use ($that) {
            echo RoomController::postMessage($id, $that->app->request()->getBody());
        });

        // Events
        $this->app->get('/events', array($this, 'checkLogin'), function () use ($that) {
            // Update access time
            // TODO Generalize this action to all API calls
            UserController::updateAccessTime();
            // Start SSE controller.
            $sse = new SSEController();
            $sse->start();
        });
    }

    public function login () {
        echo SessionController::login($this->app->request()->getBody());
    }

    public function getUserList () {
        echo UserController::getUserList();
    }

    public function getRoomList () {
        echo RoomController::getRoomList();
    }

    // Configurable login requirement.
    public function checkLoginOpt() {
        if(ZC_AUTH_ALWAYS_REQUIRED) {
            $this->checkLogin();
        }
    }

    public function checkLogin() {
        if(!SessionController::isLogin()){
            throw new ApiException(403);
        }
    }
}