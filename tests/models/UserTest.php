<?php

class UserTest extends PHPUnit_Framework_TestCase {

    public function testValidation()
    {
        $user = new User();
        $this->assertFalse($user->validate());

        $user->setNick("toto");
        $this->assertFalse($user->validate());

        // To be continued
    }

    public function testGetUIDFromToken()
    {
        $authTokenKey = '04b457d2b8c996fe57ae92bf779e2847';
        $tokenSet = array(
            "4681aa4d96f0773d8f1a480b098074caeb7387640f42d246f483398bd1f78087833ad11725ad4a90a4c698adf4a4fe1a" => "c5358dbd5a393a257416946bef3c7979",
            "9ebfa2d49a7f6d5dc35dd261386ab88741cc8b343ae81db34135aaa6ce1d8e9dd287a78936ab85ab74ad7c9e237465b6" => "750b0958f2b549c24f69ec22ae0a036a",
            'c860c53cf372414a32c71febaa043f042622c4f45e212a69bc998d7141d3cdb10c1cf194144ae8796925d331b0e357f6' => '0d657baa7a900c2a6180c2f4c5b9c12f');

        foreach ($tokenSet as $token => $uid) {
            $this->assertEquals($uid, User::getUIDFromToken($token, $authTokenKey));
        }
    }

    public function testNewUserUIDisNotEmpty()
    {
        $user = new User();
        $this->assertNotEmpty($user->getUID());
    }

    public function testGenerateTokenisNotEmpty()
    {
        // The token will be generate implicitly here.
        $user = new User();

        $this->assertNotEmpty($user->getAuthToken());
    }

    public function testGenerateTokenAndRetrieveUID()
    {
        // The token will be generate implicitly here.
        $user = new User();
        $token = $user->getAuthToken();

        $this->assertEquals($user->getUID(), User::getUIDFromToken($token));
    }
}