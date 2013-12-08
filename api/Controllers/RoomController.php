<?php
namespace ZZChat\Controllers;

use \ZZChat\Models\Room;
use \ZZChat\Models\User;
use \ZZChat\Controllers\SessionController;
use \ZZChat\Support\ApiException;

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

    public static function getRoom($id) {
        $room = Room::load($id);
        if(!$room) {
            throw new ApiException(404, "Room not found.");
        }
        return json_encode($room);
    }

    public static function getUsers($id) {
        $room = Room::load($id);
        if(!$room) {
            throw new ApiException(404, "Room not found.");
        }
        $users = $room->getUsers();
        return json_encode($users);
    }

    public static function getMessages($id) {
        $room = Room::load($id);
        if(!$room) {
            throw new ApiException(404, "Room not found.");
        }
        $messages = $room->getMessages();
        return json_encode($messages);
    }

    public static function enter($id) {
        $room = Room::load($id);
        if(!$room) {
            throw new ApiException(404, "Room not found.");
        }

        $token = SessionController::getAuthToken();
        $uid = User::getIDFromToken($token);

        $room->enter($uid);

        // TODO return something
        //return json_encode($messages);
    }

    public static function leave($id) {
        $room = Room::load($id);
        if(!$room) {
            throw new ApiException(404, "Room not found.");
        }

        $token = SessionController::getAuthToken();
        $uid = User::getIDFromToken($token);

        $room->leave($uid);

        // TODO return something
        //return json_encode($messages);
    }

    public static function postMessage($id, $postData) {
        if(empty($postData['text']))
            throw new ApiException(400, "Invalid or incomplete input.");

        //TODO Input text sanitizing

        $room = Room::load($id);
        if(!$room) {
            throw new ApiException(404, "Room not found.");
        }

        $token = SessionController::getAuthToken();
        $uid = User::getIDFromToken($token);

        //TODO check that the user is register on the room

        $room->postMessages($uid, $postData['text']);

        // TODO return something
        //return json_encode($messages);
    }
}