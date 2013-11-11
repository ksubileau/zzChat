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
		$user->setNick($data['nickname']);
		$user->setAge(intval($data['age']));
		if(isset($data['gender'])) {
			if($data['gender'] == "male") {
				$user->setSex(1);
			}
			elseif ($data['gender'] == "female") {
				$user->setSex(2);
			}
		}

		if( ! $user->validate()) {
			throw new ApiException(400, "Invalid or incomplete input.");
		}

		if( ! $user->save()) {
			throw new ApiException(500, "Unable to save user's data.");
		}

		return json_encode(array("user" => $user, "auth_token" => $user->getAuthToken()));
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
		if( isset($_SERVER[ZC_AUTH_HEADER_KEY]) ) {
			return $_SERVER[ZC_AUTH_HEADER_KEY];
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