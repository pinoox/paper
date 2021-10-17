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

namespace pinoox\app\com_pinoox_paper\component;

use pinoox\app\com_pinoox_paper\model\UserModel;
use pinoox\component\Cache;
use pinoox\component\HelperString;
use pinoox\component\Router;
use pinoox\component\User;

class Permission
{
    public static function module($permission_key = null, $user_id = null)
    {
        $group_key = self::getGroup($user_id);
        return (!empty($group_key)) ? self::module_group($permission_key, $group_key) : false;
    }

    public static function getGroup($user_id = null)
    {
        $group_key = 'guest';
        if (is_null($user_id)) {
            if (User::isLoggedIn())
                $user_id = User::get('user_id');
        }

        if (!empty($user_id)) {
            $user = UserModel::fetch_by_id($user_id);
            $group_key = !empty($user['group_key']) ? $user['group_key'] : 'user';
        }

        return $group_key;
    }

    public static function module_group($permission_key, $group_key)
    {
        $permission_key = (!is_null($permission_key)) ? $permission_key : Router::url();
        $key = $permission_key;
        $key = str_replace(['\\', ':', '@', '>'], '/', $key) . '/';

        $permissions = Cache::get('permissions.' . $group_key . '.module');

        if (!empty($permissions)) {
            foreach ($permissions as $_key => $permission) {
                $_key = str_replace(['\\', ':', '@', '>'], '/', $_key) . '/';
                if (HelperString::firstHas($key, $_key)) {
                    if (!$permission) return false;
                }
            }
        }
        return true;
    }

    public static function option($permission_key, $user_id = null)
    {
        $group_key = self::getGroup($user_id);
        return (!empty($group_key)) ? self::option_group($permission_key, $group_key) : false;
    }

    public static function option_group($permission_key, $group_key)
    {
        $status = Cache::get('permissions.' . $group_key . '.option.' . $permission_key);
        return (is_null($status)) ? true : $status;
    }

    public static function getPermission($group_key)
    {

        $permission = [
            'module' => [],
            'option' => [],
        ];

        $modules = Cache::get('permissions.' . $group_key  . '.module');
        $options = Cache::get('permissions.' . $group_key . '.option');

        if (!empty($modules)) {
            foreach ($modules as $key => $module) {
                if (!$module)
                    $permission['module'][] = $key;
            }
        }

        if (!empty($options)) {
            foreach ($options as $key => $option) {
                if (!$option)
                    $permission['option'][] = $key;
            }
        }

        return $permission;
    }

}