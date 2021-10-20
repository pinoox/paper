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


use pinoox\app\com_pinoox_paper\component\Permission;
use pinoox\app\com_pinoox_paper\model\GroupModel;
use pinoox\app\com_pinoox_paper\model\UserModel;
use pinoox\component\Cookie;
use pinoox\component\Lang;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Url;
use pinoox\component\User;
use pinoox\component\Validation;

class AccountController extends MasterConfiguration
{
    public function login()
    {
        $inputs = Request::input('user_key,password', null, '!empty');

        $valid = Validation::check($inputs, [
            'user_key' => ['required|length:>=2', Lang::get('panel.user_key')],
            'password' => ['required|length:>=2', Lang::get('panel.password')],
        ]);
        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        if (User::login($inputs['user_key'], $inputs['password'])) {

            $token = User::getTokenKey();
            Cookie::set('pinoox_user', $token, 999999999);

            Response::jsonMessage(Lang::get('panel.welcome_to_account'),true,User::$login_key);
        }
        Response::jsonMessage(User::getMessage(), false);
    }

    public function get()
    {
        $user = $this->getUser();
        $group_key = isset($user['group_key']) ? $user['group_key'] : GroupModel::getDefault();
        $user['permissions'] = Permission::getPermission($group_key);

        Response::json($user, true);
    }

    private function getUser()
    {
        $user_id = User::get('user_id');
        $user = UserModel::fetch_by_id($user_id);

        return [
            'is_avatar' => !empty($user['avatar_id']),
            'avatar' => Url::upload($user['avatar_id'], Url::file('resources/avatar.png')),
            'avatar_thumb' => Url::thumb($user['avatar_id'], 128, Url::file('resources/avatar.png')),
            'fname' => $user['fname'],
            'lname' => $user['lname'],
            'full_name' => $user['full_name'],
            'username' => $user['username'],
            'group_key' => $user['group_key'],
            'email' => $user['email'],
        ];
    }
}