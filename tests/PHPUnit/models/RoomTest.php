<?php

use ZZChat\Models\Room;

class RoomTest extends ZZChatTestCase {

    public function testSetGetName()
    {
        $room = new Room();
        $room->setName("Testing");

        $this->assertEquals($room->getName(), "Testing");
    }
}