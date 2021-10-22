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
use pinoox\app\com_pinoox_paper\component\Helper;
use pinoox\app\com_pinoox_paper\model\PaperDatabase;
use pinoox\app\com_pinoox_paper\model\UserSettingModel;
use pinoox\component\Cookie;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Upload;
use pinoox\component\Uploader;
use pinoox\component\Url;
use pinoox\component\User;
use pinoox\component\Validation;
use pinoox\model\FileModel;
use pinoox\model\PinooxDatabase;
use pinoox\app\com_pinoox_paper\model\UserModel;
use pinoox\model\UserModel as UserModelCore;

class UserController extends LoginConfiguration
{
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

    public function getSettings($state = null)
    {
        $settings = UserSettingModel::get_data($state);
        $settings = !empty($settings) ? $settings : [];
        Response::json($settings);
    }

    public function saveSettings($state = null)
    {
        $data = Request::inputOne('data', '', '!empty');

        UserSettingModel::save_data($data, $state);
        Response::json(rlang('post.save_successfully'), true);
    }

    public function logout()
    {
        User::logout(null, false);
        Cookie::destroy('pinoox_user');
        Response::json(null, true);
    }

    public function getAll()
    {
        $form = Request::input('keyword,sort,status=all,perPage=10,page=1', null, '!empty');

        $this->filterSearch($form);
        $count = UserModel::fetch_all(null, true);

        // pagination
        $pagination = new Pagination($count, $form['perPage']);
        $pagination->setCurrentPage($form['page']);

        $this->filterSearch($form);
        $users = UserModel::fetch_all($pagination->getArrayLimit());

        $users = array_map(function ($user) {
            return $this->getUserInfo($user);
        }, $users);

        Response::json(['users' => $users, 'pages' => $pagination->getInfoPage()['page']]);

    }

    private function filterSearch($form)
    {
        UserModelCore::where_search($form['keyword']);
        UserModel::where_status($form['status']);
        UserModel::sort($form['sort']);
    }

    private function getUserInfo($user)
    {
        $placeHolder = Url::file('resources/image-placeholder.jpg');

        if (empty($user)) return $user;
        $user['approx_register_date'] = Helper::getLocaleDate('l F Y (H:i)', $user['register_date']);
        $file = FileModel::fetch_by_id($user['avatar_id']);
        $user['image'] = Url::upload($file, $placeHolder);
        $user['thumb_128'] = Url::thumb($file, 128, $placeHolder);
        $user['full_name'] = $user['fname'] . ' ' . $user['lname'];
        $user['group'] = GroupModel::fetch_by_key($user['group_key']);
        unset($user['password']);

        return $user;
    }

    public function add()
    {
        $input = Request::post([
            'avatar_id',
            'status',
            'fname',
            'lname',
            'username',
            'email',
            'group_key' => GroupModel::getDefault(),
            'password',
            're_password'
        ], null, '!empty');
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

        $input['status'] = $input['status'] === UserModelCore::active ? UserModelCore::active : UserModelCore::suspend;

        $username = UserModelCore::fetch_user_by_email_or_username($input['username']);
        if ($username)
            Response::jsonMessage(rlang('user.username_duplicated'), false);

        $email = UserModelCore::fetch_user_by_email_or_username($input['email']);
        if ($email)
            Response::jsonMessage(rlang('user.email_duplicated'), false);

        PaperDatabase::startTransaction();
        $user_id = UserModel::insert($input);
        PaperDatabase::commit();

        if ($user_id) {
            $this->uploadAvatar($user_id);
            Response::jsonMessage(rlang('user.added_successfully'), true);
        }

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    /**
     * @param $user_id
     * @param int|string|null $old_avatar_id
     * @return bool|Uploader|Upload
     */
    private function uploadAvatar($user_id, $old_avatar_id = null)
    {
        if (Request::isFile('avatar')) {

            PinooxDatabase::startTransaction();

            $up = Uploader::init('avatar', path('uploads/avatar/'))
                ->allowedTypes('png,jpg,jpeg', 5)
                ->changeName('time')
                ->transaction()
                ->thumb(['128f', '256f', '512f'], PINOOX_PATH_THUMB)
                ->insert('none', 'avatar')->finish(true);

            $avatar_id = $up->getInsertId();
            if ($up->isCommit() && UserModel::update_avatar($user_id, $avatar_id)) {
                PinooxDatabase::commit();
                $up->commit();
                if (!empty($old_avatar_id)) {
                    $this->deleteAvatar($old_avatar_id);
                }
            }
            return $up;
        }
        return false;
    }

    private function deleteAvatar($avatar_id)
    {
        if (empty($avatar_id))
            return;
        Uploader::init()->thumb(['128f', '256f', '512f'], PINOOX_PATH_THUMB)->actRemoveRow($avatar_id);

    }

    public function edit()
    {
        $input = Request::post([
            'user_id',
            'avatar_id',
            'status',
            'fname',
            'lname',
            'username',
            'email',
            'group_key' => GroupModel::getDefault(),
            'password',
            're_password',
            'delete_avatar',
        ], null, '!empty');
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

        $input['status'] = $input['status'] === UserModelCore::active ? UserModelCore::active : UserModelCore::suspend;

        $user = UserModel::fetch_by_id($input['user_id']);
        if (!$user)
            Response::jsonMessage(rlang('panel.invalid_request'), false);

        $username = UserModelCore::fetch_user_by_email_or_username($input['username'], $input['user_id']);
        if ($username)
            Response::jsonMessage(rlang('user.username_duplicated'), false);

        $email = UserModelCore::fetch_user_by_email_or_username($input['email'], $input['user_id']);
        if ($email)
            Response::jsonMessage(rlang('user.email_duplicated'), false);

        PaperDatabase::startTransaction();
        $status = UserModel::update($input);

        if ($status) {
            if ($input['delete_avatar'])
                $this->deleteAvatar($user['avatar_id']);

            $this->uploadAvatar($input['user_id']);
            PaperDatabase::commit();
            Response::jsonMessage(rlang('user.edited_successfully'), true);
        }

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
        $username = UserModelCore::fetch_user_by_email_or_username($input['username'], $input['user_id']);
        if ($username)
            Response::json(rlang('user.username_duplicated'), false);

        $email = UserModelCore::fetch_user_by_email_or_username($input['email'], $input['user_id']);
        if ($email)
            Response::json(rlang('user.email_duplicated'), false);

        $status = UserModelCore::update_info($input['user_id'], $input);

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

        if (!UserModelCore::fetch_by_password($user_id, $inputs['old_password'])) {
            Response::json(rlang('user.err_old_password'), false);
        }

        if (UserModelCore::update_password($user_id, $inputs['password'], $inputs['old_password'])) {
            Response::json(rlang('user.edited_successfully'), true);
        }

        Response::json(rlang('user.err_old_password'), false);
    }

    public function removeAvatar()
    {
        if (!User::isLoggedIn())
            $this->error();
        $user = User::get();
        $this->deleteAvatar($user['avatar_id']);
        if (UserModelCore::update_avatar($user['user_id'], null)) {
            $user = $this->getUser();
            Response::json($user, true);
        }

        Response::json(rlang('panel.error_happened'), false);
    }

    public function changeAvatar()
    {
        $old_avatar_id = User::get('avatar_id');
        $user_id = User::get('user_id');
        if ($upload = $this->uploadAvatar($user_id, $old_avatar_id)) {
            if ($result = $upload->result()) {
                Response::json([
                    'avatar' => Url::upload($result),
                    'avatar_thumb' => Url::thumb($result),
                ], true);
            } else {
                Response::json($upload->error('first'), false);
            }
        }

        Response::json(rlang('panel.error_happened'), false);
    }

    public function getGroups()
    {
        $items = GroupModel::fetch_all_by_filter();
        Response::json($items);
    }
}
