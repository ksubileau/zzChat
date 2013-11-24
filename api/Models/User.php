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
	 * The unique user identifier.
	 *
	 * @var string
	 */
	public $uid;

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
	 * The user's authentication token.
	 *
	 * @var string
	 */
	protected $token;

	/**
	 * Login timestamp.
	 *
	 * @var string
	 */
	protected $loginDate;


    function __construct() {
    	parent::__construct();

        $this->uid = Support\generate_token(ZC_UID_LENGTH);
        $this->generateAuthToken();

        $this->loginDate = time();
    }

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return string
	 */
	public function getUID()
	{
		return $this->uid;
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

		$this->token = bin2hex($cipher->encrypt($this->getUID()));
	}

	/**
	 * Get the user ID from its authentication token.
	 *
	 * @return string
	 */
	public static function getUIDFromToken($token, $authTokenKey = ZC_AUTH_TOKEN_KEY)
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
		$uid = static::getUIDFromToken($token);
		return static::exists($uid);
	}

	/**
	 * Check if the passed UID corresponds to an existing user.
	 *
	 * @return bool
	 */
	public static function exists($uid)
	{
		if (!$uid)
			return false;

		return file_exists(static::getStoragePathForUID($uid));
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
		if( ! is_int($this->age) || $this->age <= 0 || $this->age >= 130) {
			return false;
		}
		if($this->sex !== 1 && $this->sex !== 2) {
			return false;
		}
		return true;
	}

	/**
	 * Load the user's data.
	 *
	 * @return User
	 */
	public static function load($uid)
	{
		$filepath = self::getStoragePathForUID($uid);

		if (!file_exists($filepath)) {
		    return false;
		}

		if(($data = file_get_contents($filepath)) === false) {
			return false;
		}

		return unserialize($data);
	}

	/**
	 * Load all users.
	 *
	 * @return User
	 */
	public static function loadAll()
	{
		$users = array();

		foreach (glob(ZC_STORAGE_DIR . self::STORAGE_DIR . '/*') as $filename) {
			if (is_readable($filename)) {
				$user = self::load(basename($filename));
				if ($user) {
					array_push($users, $user);
				}
			}
		}

		return $users;
	}

	/**
	 * Save the user's data.
	 *
	 * @return bool
	 */
	public function save()
	{
		if ( ! $this->validate()) {
			return false;
		}

		$filepath = $this->getStoragePath();

		if (!file_exists(dirname($filepath))) {
		    mkdir(dirname($filepath), 0777, true);
		}

		if(file_put_contents($filepath, serialize($this)) === false) {
			return false;
		}

		return true;
	}

	/**
	 * Return the user's data file path.
	 *
	 * @return string
	 */
	protected static function getStoragePathForUID($uid) {
		if (!$uid)
			return false;

		return ZC_STORAGE_DIR . self::STORAGE_DIR . '/' . $uid;
	}

	/**
	 * Return the user's data file path.
	 *
	 * @return string
	 */
	protected function getStoragePath() {
		return static::getStoragePathForUID($this->uid);
	}

}
