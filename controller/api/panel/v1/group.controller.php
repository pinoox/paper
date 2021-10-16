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

use pinoox\app\com_pinoox_paper\model\GroupModel;
use pinoox\app\com_pinoox_paper\controller\api\ApiConfiguration;
use pinoox\component\Lang;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Validation;
use pinoox\model\PinooxDatabase;

class GroupController extends ApiConfiguration
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
            GroupModel::update($form['old_group_key'], $form);
        } else {
            GroupModel::insert($form);
        }

        Response::jsonMessage(rlang('user.group_added_successfully'), true);
    }

    public function delete($group_key)
    {
        $group = GroupModel::fetch_by_key($group_key);
        if ($group) {
            GroupModel::delete($group_key);
            Response::jsonMessage(Lang::replace('user.group_successful_delete', $group['group_name']), true);
        }
        Response::jsonMessage(Lang::get('panel.error_happened'), false);
    }
}
