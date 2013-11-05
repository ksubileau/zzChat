<?php

/**
 * Model abstract class.
 *
 * @package ZZChat
 *
 */
abstract class Model {

    protected function validate() {
        return true;
    }

    public abstract function save();

}