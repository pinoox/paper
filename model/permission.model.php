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

use pinoox\component\Config;

class PermissionModel extends PaperDatabase
{
    public static function build_permission_by_group($group_key)
    {
        $tree = Config::get('permissions>' . $group_key);
        $branches = @$tree['branches'];
        $nodes = @$tree['nodes'];

        $permissions = Config::get('permission');

        if (empty($branches) || !is_array($branches))
            return $permissions;

        foreach ($branches as $b) {
            $type = isset($b['type']) ? $b['type'] : 'module';
            $key = $b['id'];
            $permissions[$type][$key] = true;
        }

        return $permissions;
    }

    public static function save($group_key,$data)
    {
        Config::remove('permissions>'.$group_key);
        Config::set('permissions>'.$group_key,$data);
        Config::save('permissions>'.$group_key);
    }
}