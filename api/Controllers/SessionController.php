<?php
namespace ZZChat\Controllers;

use \ZZChat\Models\User;
use \ZZChat\Support\ApiException;

/**
 * Session controller.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
class SessionController extends Controller
{

	/**
	 * Login User.
	 *
	 * @return string
	 */
	public static function login($data = NULL)
	{
		$user = new User();
		$user->setNick(trim($data['nickname']));
		$user->setAge($data['age']);

		if(isset($data['gender'])) {
			if($data['gender'] == "male" || $data['gender'] == "1") {
				$user->setSex(1);
			}
			elseif ($data['gender'] == "female" || $data['gender'] == "2") {
				$user->setSex(2);
			}
		}

		if( ZC_NICK_CHECK_UNICITY && User::nickExists($user->getNick()) ) {
			throw new ApiException(409, "Nickname already taken.");
		}

		if( ! $user->validate()) {
			throw new ApiException(400, "Invalid or incomplete input.");
		}

		if( ! $user->save()) {
			throw new ApiException(500, "Unable to save user's data.");
		}

		// Update access time
		User::updateAccessTime($user->id);

		return json_encode(array("user" => $user, "token" => $user->getAuthToken()));
	}

	/**
	 * Check if the user is logged in.
	 *
	 * @return boolean
	 */
	public static function isLogin()
	{
		$token = static::getAuthToken();
		return User::isValidToken($token);
	}

	/**
	 * Fetch the authentication token and return it.
	 *
	 * @return boolean|string The auth token, or false if the token does not exist.
	 */
	public static function getAuthToken()
	{
		// TODO Test on Nginx
		$headers = array();
		$header_key = strtoupper(ZC_AUTH_HEADER_KEY);
		if( function_exists('apache_request_headers') ) {
			$headers = array_change_key_case(apache_request_headers(), CASE_UPPER);
		}
		else {
			$headers = array_change_key_case($_SERVER, CASE_UPPER);
		}
		if( isset($headers[$header_key] ) ) {
			return $headers[$header_key];
		}

		if( isset($_GET[ZC_AUTH_PARAM_KEY]) ) {
			return $_GET[ZC_AUTH_PARAM_KEY];
		}

		if( isset($_POST[ZC_AUTH_PARAM_KEY]) ) {
			return $_POST[ZC_AUTH_PARAM_KEY];
		}

		return false;
	}
}