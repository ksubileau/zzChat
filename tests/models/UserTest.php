<?php

use ZZChat\Models\User;

class UserTest extends ZZChatTestCase {

    public function testSetGetUsername()
    {
        $user = new User();
        $user->setNick("Testing");

        $this->assertEquals($user->getNick(), "Testing");
    }

    public function testValidation()
    {
        $user = new User();
        $this->assertFalse($user->validate());

        $user->setNick("toto");
        $this->assertFalse($user->validate());

        // To be continued
    }

    /**
     * @dataProvider invalidTokenSet
     */
    public function testGetUIDFromInvalidToken($token)
    {
        $this->assertFalse(User::getUIDFromToken($token));
    }

    /**
     * @dataProvider tokenSet
     */
    public function testGetUIDFromToken($authTokenKey, $uid, $token)
    {
        $this->assertEquals($uid, User::getUIDFromToken($token, $authTokenKey));
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

    /**
     * @depends testGetUIDFromInvalidToken
     * @dataProvider invalidTokenSet
     */
    public function testIsValidTokenWithInvalidToken($token)
    {
        $this->assertFalse(User::isValidToken($token));
    }

    public function tokenSet()
    {
        return array(
                array(
                        "04b457d2b8c996fe57ae92bf779e2847", // Key
                        "c5358dbd5a393a257416946bef3c7979", // UID
                        "4681aa4d96f0773d8f1a480b098074caeb7387640f42d246f483398bd1f78087833ad11725ad4a90a4c698adf4a4fe1a", // Token
                    ),
                array(
                        "04b457d2b8c996fe57ae92bf779e2847",
                        "750b0958f2b549c24f69ec22ae0a036a",
                        // Should also work if trailing spaces are presents
                        "    9ebfa2d49a7f6d5dc35dd261386ab88741cc8b343ae81db34135aaa6ce1d8e9dd287a78936ab85ab74ad7c9e237465b6  ",
                    ),
                array(
                        "04b457d2b8c996fe57ae92bf779e2847",
                        "0d657baa7a900c2a6180c2f4c5b9c12f",
                        "c860c53cf372414a32c71febaa043f042622c4f45e212a69bc998d7141d3cdb10c1cf194144ae8796925d331b0e357f6",
                    ),
                array(
                        "5dc35dd261386ab88741cc8b343ae81d",
                        "dcddd8027d51f8c3b162aa3c0b6d71cf",
                        "b97e8cb25692a8deffb9cfbd3dd6419e4250c304a11728af6ef0ad83f264d2b236de9ad44614c64a6b736ee843f6f169   ",
                    ),
                array(
                        "5dc35dd261386ab88741cc8b343ae81d",
                        "57441f4dc8a96e8eacaaf6b393474ee4",
                        "    30fabdb4553a7d66eec86ce1470913be19d22d1af06dfc59520fa56177b174cc7be0e3a6c9ea90c6c9bf4770e9cb800f",
                    ),
            );
    }

    public function invalidTokenSet()
    {
        return array(array(''), array(false), array('    '), array('foo'), array('$$$$'), array('SELECT'), array('/'), array('../'));
    }
}