<?php
/**
 *      ****  *  *     *  ****  ****  *    *
 *      *  *  *  * *   *  *  *  *  *   *  *
 *      ****  *  *  *  *  *  *  *  *    *
 *      *     *  *   * *  *  *  *  *   *  *
 *      *     *  *    **  ****  ****  *    *
 * @author   Pinoox
 * @link https://www.pinoox.com/
 * @license  https://opensource.org/licenses/MIT MIT License
 */

namespace pinoox\app\com_pinoox_paper\controller\api\panel\v1;

use pinoox\app\com_pinoox_paper\model\UserSettingModel;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Url;
use pinoox\component\User;

class UserController extends LoginConfiguration
{
    public function get()
    {
        $user = $this->getUser();
        Response::json($user, true);
    }

    private function getUser()
    {
        $user = User::get();
        return [
            'avatar' => Url::upload($user['avatar_id'], Url::file('resources/avatar.png')),
            'avatar_thumb' => Url::thumb($user['avatar_id'], 128, Url::file('resources/avatar.png')),
            'isAvatar' => !empty($user['avatar_id']),
            'fname' => $user['fname'],
            'lname' => $user['lname'],
            'full_name' => $user['full_name'],
            'username' => $user['username'],
            'email' => $user['email'],
        ];
    }

    public function logout()
    {
        User::logout(null, false);
        Response::json(null, true);
    }
}
