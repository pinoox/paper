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

namespace pinoox\app\com_pinoox_paper\model;

use pinoox\app\com_pinoox_paper\component\Permission;
use pinoox\app\com_pinoox_paper\model\GroupModel;
use pinoox\component\app\AppProvider;
use pinoox\component\HelperString;
use pinoox\component\Url;
use pinoox\component\User;
use pinoox\model\UserModel as UserModelCore;

class UserModel extends PaperDatabase
{
    public static function fetch_by_id($user_id)
    {
        self::$db->join(self::user_paper . ' up', 'u.user_id=up.user_id', 'LEFT');
        self::$db->where('u.user_id', $user_id);
        return self::$db->getOne(self::user . ' u', 'u.*,up.*,u.user_id, CONCAT_WS(" ", u.fname, u.lname) full_name');
    }

    public static function fetch_user_by_email_or_username($username, $notUser = null)
    {
        self::$db->where('u.app', app('package-name'));
        self::$db->where('(u.email = ? OR u.username = ?)', [$username, $username]);

        if (!empty($notUser))
            self::$db->where('u.user_id', $notUser, '!=');

        self::$db->join(self::user_paper . ' up', 'u.user_id=up.user_id', 'LEFT');
        return self::$db->getOne(self::user . ' u', 'u.*,up.*,u.user_id, CONCAT_WS(" ", u.fname, u.lname) full_name');
    }

    public static function fetch_all($limit = null, $isCount = false, $checkApp = true)
    {
        if ($checkApp)
            self::$db->where('u.app', AppProvider::get('package-name'));

        self::$db->join(self::user_paper . ' up', 'u.user_id=up.user_id', 'LEFT');
        $result = self::$db->get(self::user . ' u', $limit, 'u.*,up.*,u.user_id, CONCAT_WS(" ", u.fname, u.lname) full_name');
        if ($isCount) return count($result);
        return $result;
    }

    public static function sort($sort)
    {
        if (!empty($sort) && isset($sort['field']) && !empty($sort['field'])) {
            if ($sort['field'] === 'approx_register_date') {
                $sort['field'] = 'u.register_date';
            } else if ($sort['field'] === 'full_name') {
                $sort['field'] = 'full_name';
            } else {
                $sort['field'] = 'u.' . $sort['field'];
            }

            self::$db->orderBy($sort['field'], $sort['type']);
        }
    }

    public static function where_status($status)
    {
        if (!is_null($status) && $status != 'all')
            self::$db->where('status', $status);
    }

    public static function fetch_by_app()
    {
        $app = AppProvider::get('package-name');
        self::$db->where('app', $app);
        return self::$db->getOne(self::user);
    }

    public static function insert($form)
    {
        $user_id = UserModelCore::insert($form);
        $form['user_id'] = $user_id;

        self::insert_self($form);

        return $user_id;
    }

    public static function update($form)
    {
        UserModelCore::update($form['user_id'], $form);

        self::$db->where('user_id', $form['user_id']);
        self::$db->update(self::user, [
            'username' => @$form['username'],
        ]);

        if (self::fetch_self_by_id($form['user_id'])) {
            return self::update_self($form);
        } else {
            return self::insert_self($form);
        }

    }

    public static function update_self($form)
    {
        self::$db->where('user_id', $form['user_id']);
        return self::$db->update(self::user_paper, [
            'group_key' => isset($form['group_key']) ? $form['group_key'] : GroupModel::getDefault(),
        ]);
    }

    public static function insert_self($form)
    {
        return self::$db->insert(self::user_paper, [
            'user_id' => $form['user_id'],
            'group_key' => isset($form['group_key']) ? $form['group_key'] : GroupModel::getDefault(),
            'setting_data' => @$form['setting_data'],
        ]);
    }

    public static function fetch_self_by_id($user_id)
    {
        self::$db->where('user_id', $user_id);
        return self::$db->getOne(self::user_paper);
    }


    public static function get_setting_data($state = null, $user_id = null)
    {
        $user_id = !empty($user_id) ? $user_id : User::get('user_id');
        $user = self::fetch_by_id($user_id);
        $setting = !empty($user) ? HelperString::decodeJson($user['setting_data']) : [];

        if (!is_null($state))
            return isset($setting[$state]) ? $setting[$state] : null;
        else
            return $setting;
    }

    public static function save_setting_data($data, $state = null, $user_id = null)
    {
        $user_id = !empty($user_id) ? $user_id : User::get('user_id');
        $user_setting = self::fetch_by_id($user_id);
        $setting = ($user_setting) ? HelperString::decodeJson($user_setting['json_data']) : [];
        if (!is_null($state)) {
            if (isset($setting[$state]))
                unset($setting[$state]);
            $setting[$state] = $data;
        } else {
            $setting = $data;
        }

        if ($user_setting) {
            return self::update_setting($user_id, $setting);
        } else {
            return self::insert_self([
                'user_id' => $user_id,
                'setting_data' => $setting,
            ]);
        }
    }

    public static function update_setting($user_id, $data)
    {
        self::$db->where('user_id', $user_id);
        return self::$db->update(self::user, [
            'setting_data' => HelperString::encodeJson($data),
        ]);
    }

    public static function get_info_user($user = null, $isPermission = false)
    {
        $avatar_default = Url::file('resources/avatar.png');
        $result = [
            'isLogin' => true,
            'user_id' => $user['user_id'],
            'fname' => $user['fname'],
            'lname' => $user['lname'],
            'full_name' => $user['full_name'],
            'group_key' => $user['group_key'],
            'username' => $user['username'],
            'email' => $user['email'],
            'is_avatar' => !empty($user['avatar_id']),
            'avatar' => Url::upload($user['avatar_id'], $avatar_default),
            'avatar_thumb' => Url::thumb($user['avatar_id'], 128, $avatar_default),
        ];

        if ($isPermission) {
            $group_key = isset($user['group_key']) ? $user['group_key'] : GroupModel::getDefault();
            $result['permissions'] = Permission::getPermission($group_key);
        }

        return $result;
    }
}