<?php
namespace ZZChat\Models;

/**
 * Model abstract class.
 *
 * @package ZZChat
 *
 */
abstract class Model {

     function __construct() {
        // Does nothing for now.
     }

    protected function validate() {
        return true;
    }

    public abstract function save();

}