<?php
namespace ZZChat\Controllers;

/**
 * Controller abstract class.
 *
 * @package ZZChat
 *
 */
abstract class Controller {
    protected $app;

     function __construct($app = null) {
            $this->app = ($app instanceof Slim) ? $app : Slim::getInstance();
     }

}