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
	const STORAGE_DIR = '/users';

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
		$cipher = new \Crypt_AES();
		$cipher->setKeyLength(256);
		$cipher->setKey($authTokenKey);
		//$cipher->setIV('...'); // defaults to all-NULLs if not explicitely defined
		return $cipher->decrypt(Support\hex2bin($token));
	}

	public function getNick() {
		return $this->nickname;
	}

	public function setNick($nickname) {
		$this->nick = $nickname;
	}

	public function getAge() {
		return $this->age;
	}

	public function setAge($age) {
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
	 * Save the user's data.
	 *
	 * @return bool
	 */
	public function save()
	{
		if ( ! $this->validate()) {
			return false;
		}

		if (!file_exists(ZC_STORAGE_DIR.self::STORAGE_DIR)) {
		    mkdir(ZC_STORAGE_DIR.self::STORAGE_DIR, 0777, true);
		}

		if(file_put_contents(ZC_STORAGE_DIR.self::STORAGE_DIR.'/'.$this->uid, serialize($this)) === false) {
			return false;
		}

		return true;
	}

}