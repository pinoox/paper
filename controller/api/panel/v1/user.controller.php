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

use pinoox\app\com_pinoox_paper\component\Helper;
use pinoox\app\com_pinoox_paper\model\PaperUserModel;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Url;
use pinoox\component\User;
use pinoox\component\Validation;
use pinoox\model\FileModel;
use pinoox\model\UserModel;

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

    public function getAll()
    {
        $form = Request::input('keyword,sort,status,perPage=10,page=1', null, '!empty');

        $this->filterSearch($form);
        $count = UserModel::fetch_all(null, true);

        // pagination
        $pagination = new Pagination($count, $form['perPage']);
        $pagination->setCurrentPage($form['page']);

        $this->filterSearch($form);
        $users = UserModel::fetch_all($pagination->getArrayLimit());

        $users = array_map(function ($user) {
            return $user = $this->getUserInfo($user);
        }, $users);

        Response::json(['users' => $users, 'pages' => $pagination->getInfoPage()['page']]);

    }

    private function getUserInfo($user)
    {
        $placeHolder = Url::file('resources/image-placeholder.jpg');

        if (empty($user)) return $user;
        $user['approx_register_date'] = Helper::getLocalDate('l F Y (H:i)', $user['register_date']);
        $file = FileModel::fetch_by_id($user['avatar_id']);
        $user['image'] = Url::upload($file, $placeHolder);
        $user['thumb_128'] = Url::thumb($file, 128, $placeHolder);
        $user['full_name'] = $user['fname'] . ' ' . $user['lname'];
        unset($user['password']);

        return $user;
    }

    public function add()
    {
        $input = Request::input('avatar_id,status,fname,lname,username,email,password,re_password', null, '!empty');
        $valid = Validation::check($input, [
            'fname' => ['required|length:>2', rlang('user.fname')],
            'lname' => ['required|length:>2', rlang('user.lname')],
            'username' => ['required|username', rlang('user.username')],
            'email' => ['required|email', rlang('user.email')],
            'password' => ['required|length:>4', rlang('user.password')],
            're_password' => ['match:==[password]', rlang('user.password')],
        ], [
            're_password:match' => rlang('user.passwords_not_matched'),
        ]);
        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);


        $username = UserModel::fetch_user_by_email_or_username($input['username']);
        if ($username)
            Response::jsonMessage(rlang('user.username_duplicated'), false);

        $email = UserModel::fetch_user_by_email_or_username($input['email']);
        if ($email)
            Response::jsonMessage(rlang('user.email_duplicated'), false);

        $user_id = UserModel::insert($input);

        if ($user_id)
            Response::jsonMessage(rlang('user.added_successfully'), true);

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    public function edit()
    {
        $input = Request::input('user_id,avatar_id,status,fname,lname,username,email,password,re_password', null, '!empty');
        $valid = Validation::check($input, [
            'user_id' => ['required', rlang('user.fname')],
            'fname' => ['required|length:>2', rlang('user.fname')],
            'lname' => ['required|length:>2', rlang('user.lname')],
            'username' => ['required|username', rlang('user.username')],
            'email' => ['required|email', rlang('user.email')],
            'password' => ['length:>4', rlang('user.password')],
            're_password' => ['match:==[password]', rlang('user.password')],
        ], [
            'user_id:required' => rlang('panel.invalid_request'),
            're_password:match' => rlang('user.passwords_not_matched'),
        ]);
        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        $username = UserModel::fetch_user_by_email_or_username($input['username'], $input['user_id']);
        if ($username)
            Response::jsonMessage(rlang('user.username_duplicated'), false);

        $email = UserModel::fetch_user_by_email_or_username($input['email'], $input['user_id']);
        if ($email)
            Response::jsonMessage(rlang('user.email_duplicated'), false);

        $status = UserModel::update($input['user_id'], $input);

        if ($status)
            Response::jsonMessage(rlang('user.edited_successfully'), true);

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    public function delete()
    {
        $user_id = Request::inputOne('user_id', null, '!empty');

        if (UserModel::fetch_by_id($user_id) != false) {
            $status = UserModel::delete($user_id);
            if ($status)
                Response::jsonMessage(rlang('panel.delete_successfully'), true);
        }

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    private function filterSearch($form)
    {
        UserModel::where_search($form['keyword']);
        PaperUserModel::where_status($form['status']);
        PaperUserModel::sort($form['sort']);
    }

    public function saveUserProfile()
    {
        $input = Request::input('fname,lname,username,email', null, '!empty');
        $valid = Validation::check($input, [
            'fname' => ['required|length:>2', rlang('user.fname')],
            'lname' => ['required|length:>2', rlang('user.lname')],
            'username' => ['required|username', rlang('user.username')],
            'email' => ['required|email', rlang('user.email')],
        ]);
        if ($valid->isFail())
            Response::json($valid->first(), false);

        $input['user_id'] = User::get('user_id');
        $username = UserModel::fetch_user_by_email_or_username($input['username'], $input['user_id']);
        if ($username)
            Response::json(rlang('user.username_duplicated'), false);

        $email = UserModel::fetch_user_by_email_or_username($input['email'], $input['user_id']);
        if ($email)
            Response::json(rlang('user.email_duplicated'), false);

        $status = UserModel::update_info($input['user_id'], $input);

        if ($status)
            Response::json(rlang('user.edited_successfully'), true);

        Response::json(rlang('panel.error_happened'), false);
    }

    public function changePassword()
    {
        if (!User::isLoggedIn())
            $this->error();

        $inputs = Request::input('password,re_password,old_password', null, '!empty');

        $valid = Validation::check($inputs, [
            'old_password' => ['required', rlang('user.password')],
            'password' => ['required|length:5>|match:!=[old_password]', rlang('user.new_password')],
            're_password' => ['required|match:==[password]', rlang('user.re_new_password')],
        ]);

        if ($valid->isFail())
            Response::json($valid->first(), false);

        $user_id = User::get('user_id');

        if (!UserModel::fetch_by_password($user_id, $inputs['old_password'])) {
            Response::json(rlang('user.err_old_password'), false);
        }

        if (UserModel::update_password($user_id, $inputs['password'], $inputs['old_password'])) {
            Response::json(rlang('user.edited_successfully'), true);
        }

        Response::json(rlang('user.err_old_password'), false);
    }
}
