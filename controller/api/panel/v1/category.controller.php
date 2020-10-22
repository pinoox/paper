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

use pinoox\app\com_pinoox_paper\model\CategoryModel;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Validation;

class CategoryController extends MasterConfiguration
{

    public function getTree()
    {
        $category = CategoryModel::fetch_all();
        $tree = CategoryModel::tree($category);
        Response::json($tree);
    }

    public function add()
    {
        $input = Request::input('cat_name,parent_id', null, '!empty');

        $valid = Validation::check($input, [
            'cat_name' => ['required', rlang('post.title')],
        ]);
        if ($valid->isFail())
            Response::json($valid->first(), false);

        if (CategoryModel::fetch_by_name($input['cat_name']) != false)
            Response::json(rlang('post.cat_name_is_duplicated'), false);

        $cat_id = CategoryModel::insert($input);
        if ($cat_id)
            Response::json(rlang('panel.added_successfully'), true);

        Response::json(rlang('panel.error_happened'), false);
    }

    public function edit()
    {
        $input = Request::input('cat_id,cat_name', null, '!empty');

        $valid = Validation::check($input, [
            'cat_id' => ['required', ''],
            'cat_name' => ['required', rlang('post.title')],
        ], [
            'cat_id:required' => rlang('panel.invalid_request'),
        ]);
        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        if (CategoryModel::fetch_by_name($input['cat_name'], $input['cat_id']) != false)
            Response::jsonMessage(rlang('post.cat_name_is_duplicated'), false);

        $status = CategoryModel::update($input);
        if ($status)
            Response::json(rlang('panel.edited_successfully'), true);

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    public function saveChanges()
    {
        $input = Request::input('cat,parent', null, '!empty');
        $valid = Validation::check($input, [
            'cat' => ['required', rlang('panel.invalid_request')],
        ]);
        if ($valid->isFail())
            Response::json($valid->first(), false);

        $status = CategoryModel::update_parent($input['cat'], $input['parent']);
        Response::json($status);
    }

    public function delete()
    {
        $cat_id = Request::inputOne('cat_id', null, '!empty');

        if (CategoryModel::fetch_by_id($cat_id) != false) {
            $status = CategoryModel::delete($cat_id);
            if ($status)
                Response::jsonMessage(rlang('panel.delete_successfully'), true);
        }

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

}
