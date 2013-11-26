<?php
namespace ZZChat\Controllers;

use \ZZChat\Models\User;

/**
 * User controller.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
class UserController extends Controller
{
    public static function getUserList()
    {
        $users = User::loadAll();
        return json_encode($users);
    }

    public static function getUser($id) {
        $user = User::load($id);
        if(!$user) {
            throw new ApiException(404, "User not found.");
        }
        return json_encode($user);
    }
}