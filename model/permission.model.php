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
use pinoox\component\Dir;
use pinoox\component\File;

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

            if (isset($b['api'])) {
                $apis = !is_array($b['api']) ? [$b['api']] : $b['api'];
                foreach ($apis as $api) {
                    $permissions['api'][$api] = true;
                }
            }
        }

        return $permissions;
    }

    public static function save($group_key, $data)
    {
        self::delete($group_key);
        Config::set('permissions>' . $group_key, $data);
        Config::save('permissions>' . $group_key);
    }

    public static function delete($group_key)
    {
        $path = Dir::path('pinker>config>permissions>' . $group_key . '.config.php');
        File::remove_file($path);
    }
}