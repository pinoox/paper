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
use pinoox\app\com_pinoox_paper\model\SettingsModel;
use pinoox\app\com_pinoox_paper\model\UserModel;
use pinoox\component\Security;
use pinoox\model\UserModel as UserModelCore;
use pinoox\component\Cookie;
use pinoox\component\Lang;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Url;
use pinoox\component\User;
use pinoox\component\Validation;

class AccountController extends MasterConfiguration
{
    public function old_login()
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

            Response::jsonMessage(Lang::get('panel.welcome_to_account'), true, User::$login_key);
        }
        Response::jsonMessage(User::getMessage(), false);
    }

    public function login()
    {
        $inputs = Request::input('user_key,password', null, '!empty');

        $valid = Validation::check($inputs, [
            'user_key' => ['required|length:>=2', Lang::get('panel.user_key')],
            'password' => ['required|length:>=2', Lang::get('panel.password')],
        ]);
        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        if (User::isLoggedIn()) {
            if (Permission::module('panel')) {
                Response::jsonMessage(Lang::get('panel.welcome_to_account'), true, [
                    'token' => User::$login_key,
                ]);
            }
        }

        UserModel::where_status(UserModelCore::active);
        $user = UserModel::fetch_user_by_email_or_username($inputs['user_key']);
        if (empty($user)) {
            Response::jsonMessage(rlang('~user.username_or_password_is_wrong'), false);
        }

        if (!Permission::module('panel', $user['user_id']))
            Response::jsonMessage(rlang('user.err_permission_for_login'), false);

        if (!Security::passVerify($inputs['password'], $user['password'])) {
            Response::jsonMessage(rlang('~user.username_or_password_is_wrong'), false);
        }

        $data = UserModel::get_info_user($user);

        if (User::isLoggedIn()) {
            User::logout(null, false);
        }

        User::setToken($data);

        $token = User::$login_key;

        Response::jsonMessage(Lang::get('panel.welcome_to_account'), true, [
            'token' => $token,
        ]);
    }

    public function get()
    {
        if (User::isLoggedIn() && Permission::module('panel')) {
            $user = $this->getUser();
            Response::json($user, true);
        }

        Response::json(['message' => 'NEED_LOGIN', 'statusCode' => 403], false);
    }

    private function getUser()
    {
        $user_id = User::get('user_id');
        $user = UserModel::fetch_by_id($user_id);
        return UserModel::get_info_user($user, true);
    }

    public function getAllSetting()
    {
        if (User::isLoggedIn() && Permission::module('panel')) {
            $configs = SettingsModel::getAll();
            $configs = !empty($configs) ? $configs : [];
            Response::json($configs);
        } else {
            Response::json([]);
        }
    }

    public function getUserSetting($state = null)
    {
        if (User::isLoggedIn() && Permission::module('panel')) {
            $settings = UserModel::get_setting_data($state);
            $settings = !empty($settings) ? $settings : [];
            Response::json($settings);
        } else {
            Response::json([]);
        }
    }

    public function saveUserSettings($state = null)
    {
        if (User::isLoggedIn() && Permission::module('panel')) {
            $data = Request::inputOne('data', '', '!empty');

            UserModel::save_setting_data($data, $state);
            Response::json(rlang('post.save_successfully'), true);
        } else {
            Response::json([]);
        }
    }

    public function logout()
    {
        User::logout(null, false);
        Cookie::destroy('pinoox_user');
        Response::json(null, true);
    }
}