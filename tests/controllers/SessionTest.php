<?php

use ZZChat\Controllers\SessionController;

class SessionTest extends ZZChatTestCase {

    public function testUnlogged()
    {
        $this->assertFalse(SessionController::getAuthToken());
        $this->assertFalse(SessionController::isValidToken(SessionController::getAuthToken()));
        $this->assertFalse(SessionController::isLogin());
    }
}