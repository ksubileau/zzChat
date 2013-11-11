<?php
namespace ZZChat\Models;

/**
 * Model abstract class.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
abstract class Model
{

     function __construct() {
        // Does nothing for now.
     }

    protected function validate() {
        return true;
    }

    abstract public function save();

}