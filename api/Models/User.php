<?php
namespace ZZChat\Models;

use \ZZChat\Support;

/**
 * User model.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
class User extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	const STORAGE_DIR = '/Users';

	/**
	 * The user's nickname (required).
	 *
	 * @var string
	 */
	public $nick;

	/**
	 * The user's age (required).
	 *
	 * @var int
	 */
	public $age;

	/**
	 * The user's gender (1 for men, 2 for women) (required).
	 *
	 * @var int
	 */
	public $sex;

	/**
	 * Login timestamp.
	 *
	 * @var string
	 */
	public $isActive;

	/**
	 * The user's authentication token.
	 *
	 * @var string
	 */
	protected $token;


    function __construct($props = NULL) {
    	parent::__construct($props);
    	if ($props == NULL) { // Only if the object is not load from file
	        $this->generateAuthToken();
    	}
    	$this->isActive = static::isActive($this->id);
    }

	/**
	 * Get the unique authentication token for the user.
	 *
	 * @return string
	 */
	public function getAuthToken()
	{
		if(!$this->token) {
			$this->generateAuthToken();
		}
		return $this->token;
	}

	/**
	 * Generate the unique authentication token for the user.
	 *
	 * @return string
	 */
	private function generateAuthToken($authTokenKey = ZC_AUTH_TOKEN_KEY)
	{
		$cipher = new \Crypt_AES();
		$cipher->setKeyLength(256);
		$cipher->setKey($authTokenKey);
		//$cipher->setIV('...'); // defaults to all-NULLs if not explicitely defined

		$this->token = bin2hex($cipher->encrypt($this->getID()));
	}

	/**
	 * Get the user ID from its authentication token.
	 *
	 * @return string
	 */
	public static function getIDFromToken($token, $authTokenKey = ZC_AUTH_TOKEN_KEY)
	{
		$token = trim($token);
		// A token must be a valid hex number.
		if(!preg_match("/^[0-9A-Fa-f]+$/", $token)) {
			return false;
		}

		$cipher = new \Crypt_AES();
		$cipher->setKeyLength(256);
		$cipher->setKey($authTokenKey);
		//$cipher->setIV('...'); // defaults to all-NULLs if not explicitely defined
		return $cipher->decrypt(Support\hex2bin($token));
	}

	/**
	 * Return true if the token match a valid user.
	 *
	 * @return boolean
	 */
	public static function isValidToken($token)
	{
		$id = static::getIDFromToken($token);
		return static::exists($id);
	}

	public function getNick() {
		return $this->nick;
	}

	public function setNick($nickname) {
		$this->nick = $nickname;
	}

	public function getAge() {
		return $this->age;
	}

	public function setAge($age) {
		if( is_string($age)) {
			$age = trim($age);
			if ((string)intval($age) != $age)
				return false;
			else
				$age = intval($age);
		}
		$this->age = $age;
	}

	public function getSex() {
		return $this->sex;
	}

	public function setSex($sex) {
		$this->sex = $sex;
	}

	/**
	 * Check if the user's data are valid.
	 *
	 * @return bool
	 */
	public function validate()
	{
		if ( ! isset($this->nick) || ! isset($this->age) || ! isset($this->sex)) {
			return false;
		}
		if ( ! preg_match('/\A['.ZC_NICK_ALLOWED_CHARS.']{'.ZC_NICK_LENGTH_MIN.','.ZC_NICK_LENGTH_MAX.'}\z/', $this->nick)) {
			return false;
		}
		if( ! is_int($this->age) || $this->age <= 0 || $this->age <= ZC_AGE_MIN || $this->age >= ZC_AGE_MAX) {
			return false;
		}
		if($this->sex !== 1 && $this->sex !== 2) {
			return false;
		}
		return true;
	}

	/**
	 * Update the last access time to the current timestamp.
	 *
	 * @return boolean
	 */
	public static function updateAccessTime($id)
	{
		if(!static::exists($id))
			return false;
		return static::setTime('atime', $id);
	}

	/**
	 * Get the last access time.
	 *
	 * @return boolean
	 */
	public static function getAccessTime($id)
	{
		return static::getTime('atime', $id);
	}

	/**
	 * Check if the user is currently active.
	 *
	 * @return boolean
	 */
	public static function isActive($id)
	{
		return (time() - static::getAccessTime($id)) < ZC_USER_AUTO_LOGOUT_TIME;
	}

	/**
     * Check if there is some new inactive users since the given timestamp.
     *
     * @return boolean
     */
    public static function hasInactiveUsers($timeref = 0) {
        $ids = static::getAllID();
		$now = time();
        foreach ($ids as $uid) {
        	$inactiveTime = static::getAccessTime($uid) + ZC_USER_AUTO_LOGOUT_TIME;
        	if($timeref < $inactiveTime && $inactiveTime <= $now) {
        		return true;
        	}
        }

        return false;
    }

	/**
	 * Check if the user's session has expired.
	 *
	 * @return boolean
	 */
	public static function isExpired($id)
	{
		return (time() - static::getAccessTime($id)) > ZC_USER_EXPIRE_TIME;
	}

	/**
     * Check if the given nick is taken by one of the users.
     *
     * @param $nick
     */
    public static function nickExists($nick) {
        $ids = static::getAllID();

        foreach ($ids as $uid) {
        	$user = static::load($uid);
        	if($user && $user->nick == $nick) {
        		return true;
        	}
        }

        return false;
    }

    /**
	 * Return the list of persistants properties names.
	 *
	 * @return array
	 */
	protected static function getProperties() {
        return array_merge (
        		parent::getProperties(),
        		array ('nick', 'age', 'sex', 'token')
	        );
    }

    /**
     * Permanently deletes the user's data.
     *
     * @return bool
     */
    public static function deleteByID($id, $cascade = true)
    {
        if(parent::deleteByID($id)) {
        	if ($cascade) {
	        	// Delete also foreign references
	        	Room::leaveAll($id);
  			}
        }
    }

	/**
	 * Remove users whose session has expired.
	 *
	 * @return array
	 */
	public static function clean()
	{
        $ids = static::getAllID();

        $ids = array_filter($ids, array('\ZZChat\Models\User', 'isExpired'));

        $ids = array_walk($ids, function($id, $index) {
        	User::deleteByID($id);
        });

        return $ids;
	}

}
