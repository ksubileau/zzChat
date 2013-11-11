<?php
namespace ZZChat\Controllers;

/**
 * Controller abstract class.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
abstract class Controller
{
    protected $app;

     function __construct($app = null) {
            $this->app = ($app instanceof Slim) ? $app : Slim::getInstance();
     }

}