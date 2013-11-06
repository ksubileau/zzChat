<?php

/**
 * Session controller.
 *
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
class SessionController extends Controller  {

	/**
	 * Check if the user is logged in.
	 *
	 * @return boolean
	 */
	static public function isLogin()
	{
		$token = static::getAuthToken();
		return static::isValidToken($token);
	}

	/**
	 * Fetch the authentication token and return it.
	 *
	 * @return boolean|string The auth token, or false if the token does not exist.
	 */
	static public function getAuthToken()
	{
		if( isset($_SERVER[AUTH_HEADER_KEY]) ) {
			return $_SERVER[AUTH_HEADER_KEY];
		}

		if( isset($_GET[AUTH_PARAM_KEY]) ) {
			return $_GET[AUTH_PARAM_KEY];
		}

		if( isset($_POST[AUTH_PARAM_KEY]) ) {
			return $_POST[AUTH_PARAM_KEY];
		}

		return false;
	}

	/**
	 * Return true if the token match a valid user.
	 *
	 * @return boolean
	 */
	static public function isValidToken($token)
	{
		return ! empty($token);
	}
}