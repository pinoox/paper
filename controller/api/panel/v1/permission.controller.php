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

use pinoox\app\com_pinoox_paper\model\PermissionModel;
use pinoox\component\Cache;
use pinoox\component\Config;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\User;
use pinoox\component\Validation;

class PermissionController extends MasterConfiguration
{
    public function getAll()
    {
        $group_key = Request::inputOne('group_key', null, '!empty');
        $permissions = Config::get('permissions>' . $group_key);

        Response::json($permissions);
    }

    public function save()
    {
        $data = Request::input('group_key,tree', null, '!empty');

        $valid = Validation::check($data, [
            'group_key' => ['required', ''],
            'tree' => ['required', ''],
        ], [
            'group_key:required' => rlang('panel.invalid_request'),
            'tree:required' => rlang('panel.invalid_request')
        ]);

        if ($valid->isFail()) Response::jsonMessage($valid->first(), false);

        PermissionModel::save($data['group_key'], $data['tree']);
        Cache::clean('permissions');
        Response::jsonMessage(rlang('panel.edited_successfully'), true);
    }

    public function getUserPermissions()
    {
        $user_id = User::get('user_id');
        $user = UserPandaModel::fetch_by_id($user_id);
        $permissions = Permission::getPermission($user['group_key']);
        Response::json($permissions);
    }
}
