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


use pinoox\component\Lang;
use pinoox\component\Request;
use pinoox\component\Response;
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
            Response::jsonMessage(Lang::get('panel.welcome_to_account'),true,User::$login_key);
        }
        Response::jsonMessage(User::getMessage(), false);
    }
}