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
use pinoox\app\com_pinoox_paper\model\GroupModel;
use pinoox\component\Cache;
use pinoox\component\Lang;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Validation;

class GroupController extends LoginConfiguration
{
    public function getGroup($group_key)
    {
        $group = GroupModel::fetch_by_key($group_key);
        Response::json($group);
    }

    public function getAll()
    {
        $groups = GroupModel::fetch_all();

        Response::json($groups);
    }

    public function save()
    {
        $form = Request::post('old_group_key,group_key,group_name', null, '!empty');

        $valid = Validation::check($form, [
            'group_name' => ['required|length:>=2', rlang('user.group_name')],
            'group_key' => ['required|username|length:>=2', rlang('user.group_key')],
        ]);

        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        if (GroupModel::fetch_by_key($form['group_key'], $form['old_group_key']))
            Response::jsonMessage(rlang('user.repeat_group_key'), false);

        if (!empty($form['old_group_key'])) {
            {
                $old = GroupModel::fetch_by_key($form['old_group_key']);
                $form['is_main'] = false;

                if ($old['is_main'])
                {
                    $form['group_key'] = $old['group_key'];
                    $form['is_main'] = true;
                }

                GroupModel::update($form['old_group_key'], $form);
            }
        } else {
            GroupModel::insert($form);
        }

        Cache::clean('permissions');

        Response::jsonMessage(rlang('user.group_added_successfully'), true);
    }

    public function delete($group_key)
    {
        $group = GroupModel::fetch_by_key($group_key);
        if ($group && !$group['is_main']) {
            GroupModel::delete($group_key);
            PermissionModel::delete($group_key);
            Cache::clean('permissions');
            Response::jsonMessage(Lang::replace('user.group_successful_delete', $group['group_name']), true);
        }
        Response::jsonMessage(Lang::get('panel.error_happened'), false);
    }
}
