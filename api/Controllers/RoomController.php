<?php
namespace ZZChat\Controllers;

use \ZZChat\Models\Room;

/**
 * Room controller.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
class RoomController extends Controller
{
    public static function getRoomList()
    {
        $rooms = Room::loadAll();
        return json_encode($rooms);
    }
}