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

namespace pinoox\app\com_pinoox_paper\service;

use pinoox\app\com_pinoox_paper\model\GroupModel;
use pinoox\app\com_pinoox_paper\model\PermissionModel;
use pinoox\component\Cache;
use pinoox\component\interfaces\ServiceInterface;
use pinoox\component\User;

class PermissionService implements ServiceInterface
{

    public function _run()
    {
        Cache::init('permissions', function () {
            $groups = GroupModel::fetch_all();
            $permissions = [];
            foreach ($groups as $group) {
                $permissions[$group['group_key']] = PermissionModel::build_permission_by_group($group['group_key']);
            }

            return $permissions;
        });
    }

    public function _stop()
    {
        Cache::clean('permissions');
    }
}