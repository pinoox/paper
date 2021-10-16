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

use pinoox\app\com_pinoox_paper\model\GroupModel;
use pinoox\component\app\AppProvider;
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
            if ($sort['field'] === 'approx_register_date')
            {
                $sort['field'] = 'u.register_date';
            }
            else if ($sort['field'] === 'full_name')
            {
                $sort['field'] = 'full_name';
            }
            else{
                $sort['field'] = 'u.'.$sort['field'];
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
        ]);
    }

    public static function fetch_self_by_id($user_id)
    {
        self::$db->where('user_id', $user_id);
        return self::$db->getOne(self::user_paper);
    }


}