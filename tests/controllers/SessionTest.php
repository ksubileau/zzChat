<?php

use ZZChat\Controllers\SessionController;

class SessionTest extends ZZChatTestCase {

    public function testGetAuthTokenUnlogged()
    {
        $authToken = SessionController::getAuthToken();
        $this->assertFalse($authToken);

        return $authToken;
    }

    /*
     * @depends testGetAuthTokenUnlogged
     */
    public function testIsLoginUnlogged()
    {
        $this->assertFalse(SessionController::isLogin());
    }
}