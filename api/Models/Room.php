<?php
namespace ZZChat\Models;

use \ZZChat\Support;
use \ZZChat\Support\ApiException;

/**
 * Room model.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
class Room extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	const STORAGE_DIR = '/Rooms';

	/**
	 * The room's name (required).
	 *
	 * @var string
	 */
	public $name;


    function __construct($props = NULL) {
    	parent::__construct($props);
    }

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Register an user as present on the room.
	 *
	 * @return bool
	 */
	public function enter($user) {
		$uid = is_object($user)?$user->getID():$user;
		$users = $this->getUsers(true);
		if(!in_array($uid, $users)){
	        array_push($users, $uid);
			$this->storeUsers($users);
            static::setTime('entertime', $this->id);
	    }
	}

	/**
	 * Unregister an user on the room.
	 *
	 * @return bool
	 */
	public function leave($user) {
		$uid = is_object($user)?$user->getID():$user;
		$users = $this->getUsers(true);
		if(($key = array_search($uid, $users)) === false) {
			return false;
		}
		unset($users[$key]);
		$this->storeUsers($users);
        static::setTime('leavetime', $this->id);
		return true;
	}

    /**
     * Unregister an user on all rooms.
     *
     * @return bool
     */
    public static function leaveAll($user) {
        $ids = static::getAllID();

        $ids = array_walk($ids, function($roomid, $index) use ($user) {
            $room = Room::load($roomid);
            if($room) {
                $room->leave($user);
            }
        });
    }

	/**
	 * Return the list of present users on the room.
	 *
	 * @return array
	 */
	public function getUsers($idOnly = false) {
        $path = $this->getStoragePath();

        if (!file_exists($path . '/users')) {
            return array();
        }

        if(($data = file_get_contents($path . '/users')) === false) {
            throw new ApiException(500, "Unable to read users from file. Please check permissions.");
        }

        $users = json_decode($data, true);
        if ($users === NULL) {
            throw new ApiException(500, "Failed to decode the users file.", Support\json_last_error_msg().'.');
        }

        if(!$idOnly)
            $users = User::loadAll($users);

        return $users;
	}

	/**
	 * Store users list.
	 */
	protected function storeUsers($users) {
        $path = $this->getStoragePath();

        $data = json_encode($users, ZC_STORAGE_JSON_PRETTY_PRINT?JSON_PRETTY_PRINT:0);

        if($data === NULL) {
            throw new ApiException(500, "Failed to encode data.", Support\json_last_error_msg().'.');
        }

        if(file_put_contents($path . '/users', $data) === false) {
            throw new ApiException(500, "Unable to write users file. Please check permissions.");
        }
	}

	/**
	 * Return the list of messages for this room.
	 *
	 * @return array
	 */
	public function getMessages() {
		// TODO Filtering, time limit
		// TODO Automatically fetch niknames ? Option ?
        $path = $this->getStoragePath();

        if (!file_exists($path . '/messages')) {
            return array();
        }

        if(($data = file_get_contents($path . '/messages')) === false) {
            throw new ApiException(500, "Unable to read messages from file. Please check permissions.");
        }

        $messages = json_decode($data, true);
        if ($messages === NULL) {
            throw new ApiException(500, "Failed to decode the messages file.", Support\json_last_error_msg().'.');
        }

        return $messages;
	}

	/**
	 * Return the list of messages for this room.
	 *
	 * @return array
	 */
	public function postMessages($author, $text, $format = 'text', $sent_time = NULL) {
		$message = new \stdClass;
		$message->author = is_object($author)?$author->getID():$author;
		$message->text = $text;
		$message->sent_time = $sent_time!=NULL?$sent_time:time();
        $message->format = $format;

        $messages = $this->getMessages();

        array_push($messages, $message);

        $path = $this->getStoragePath();

        $data = json_encode($messages, ZC_STORAGE_JSON_PRETTY_PRINT?JSON_PRETTY_PRINT:0);

        if($data === NULL) {
            throw new ApiException(500, "Failed to encode data.", Support\json_last_error_msg().'.');
        }

        if(file_put_contents($path . '/messages', $data) === false) {
            throw new ApiException(500, "Unable to write messages file. Please check permissions.");
        }

        static::setTime('messagetime', $this->id);
	}

    /**
     * Compute a list of available events for all rooms
     *
     * @param $timeref
     */
    public static function checkEvents($timeref) {
        $ids = static::getAllID();

        $events = array();

        foreach ($ids as $roomid) {
            $thisevents = array();
            if(static::isMoreRecent($timeref, 'entertime', $roomid))
            {
                $thisevents['users_enter'] = '';
            }
            if(static::isMoreRecent($timeref, 'leavetime', $roomid))
            {
                $thisevents['users_leave'] = '';
            }
            if(static::isMoreRecent($timeref, 'messagetime', $roomid))
            {
                $thisevents['messages_new'] = '';
            }
            if(!empty($thisevents)) {
                $events[$roomid] = $thisevents;
            }
        }

        return $events;
    }

	/**
	 * Check if the room's data are valid.
	 *
	 * @return bool
	 */
	public function validate()
	{
		if ( empty($this->name) ) {
			return false;
		}
		return true;
	}

    protected static function getProperties() {
        return array_merge (
        		parent::getProperties(),
        		array ('name')
	        );
    }

}
