<?php

class SessionTest extends PHPUnit_Framework_TestCase {

    public function testUnlogged()
    {
        $this->assertFalse(SessionController::getAuthToken());
        $this->assertFalse(SessionController::isValidToken(SessionController::getAuthToken()));
        $this->assertFalse(SessionController::isLogin());
    }
}