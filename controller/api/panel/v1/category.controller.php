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
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Uploader;
use pinoox\component\Validation;
use pinoox\model\PinooxDatabase;

class CategoryController extends LoginConfiguration
{
    public function get($cat_id)
    {
        $category = CategoryModel::fetch_by_id($cat_id);
        $category = CategoryModel::getInfo($category, true);

        Response::json($category);
    }

    public function getAllByParent()
    {
        $form = Request::input('cat_id,keyword,sort,perPage=20,page=1', null, '!empty');

        $form['perPage'] = ($form['perPage'] > 0 && $form['perPage'] <= 50) ? $form['perPage'] : 20;

        $this->filterSearch($form);
        $count = CategoryModel::fetch_all(null, true);

        // pagination
        $pagination = new Pagination($count, $form['perPage']);
        $pagination->setCurrentPage($form['page']);

        $this->filterSearch($form);
        $items = CategoryModel::fetch_all($pagination->getArrayLimit());

        $breadcrumb = CategoryModel::get_breadcrumb($form['cat_id']);

        Response::json(['breadcrumb' => $breadcrumb, 'items' => $items, 'pages' => $pagination->getInfoPage()['page']]);
    }

    private function filterSearch($form)
    {
        CategoryModel::where_parent($form['cat_id']);
        CategoryModel::where_search($form['keyword']);
        CategoryModel::sort($form['sort']);
    }

    public function getTree()
    {
        $keyword = Request::inputOne('keyword', '', '!empty');

        $cats = CategoryModel::search_tree($keyword);

        $cats = array_map(function ($cat) {
            return CategoryModel::getInfo($cat);
        }, $cats);

        Response::json($cats);
    }

    public function save()
    {
        $input = Request::input([
            'imageData',
            'delete_image' => false,
            'cat_id',
            'cat_name',
            'cat_key',
            'parent',
            'parent_id',
        ], null, '!empty');

        $valid = Validation::check($input, [
            'cat_name' => ['required|length:>=2', rlang('panel.category_name')],
            'cat_key' => ['required|length:>=2', rlang('panel.category_key')],
        ]);

        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);


        $cat_key = CategoryModel::fetch_by_name($input['cat_key'], $input['cat_id']);
        if ($cat_key)
            Response::jsonMessage(rlang('panel.cat_key_is_duplicated'), false);

        $cat_name = CategoryModel::fetch_by_name($input['cat_name'], $input['cat_id']);
        if ($cat_name)
            Response::jsonMessage(rlang('panel.cat_name_is_duplicated'), false);

        $input['parent_id'] = @$input['parent']['cat_id'];

        if (empty($input['cat_id']))
            $input['cat_id'] = CategoryModel::insert($input);
        else
            CategoryModel::update($input);

        if ($input['cat_id']) {

            if ($input['delete_image'])
                $this->deleteImage($input['cat_id']);

            // upload image
            $this->uploadImage($input['cat_id'], $input['imageData']);

            // result
            Response::jsonMessage(rlang('panel.saved_successfully'), true);
        }

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    private function deleteImage($category)
    {
        $category = !empty($category) && is_array($category) ? $category : CategoryModel::fetch_by_id($category);
        $image_id = @$category['image_id'];

        if (empty($image_id))
            return;

        Uploader::init()->thumb(['128f', '256f', '512f'], PINOOX_PATH_THUMB)->actRemoveRow($image_id);
    }

    private function uploadImage($cat_id, $image)
    {
        if (!empty($image)) {

            PinooxDatabase::startTransaction();

            $up = Uploader::init($image, path('uploads/category/'))
                ->base64()
                ->allowedTypes('png,jpg,jpeg', 5)
                ->changeName('time')
                ->transaction()
                ->thumb(['128f', '256f', '512f'], PINOOX_PATH_THUMB)
                ->insert('none', 'category')->finish(true);

            $category = CategoryModel::fetch_by_id($cat_id);
            $image_id = $up->getInsertId();
            if ($up->isCommit() && CategoryModel::update_image($cat_id, $image_id)) {
                PinooxDatabase::commit();
                $up->commit();
                if (!empty($category['image_id'])) {
                    $this->deleteImage($category);
                }
            }
            return $up;
        }
        return false;
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

        $cat = CategoryModel::fetch_by_id($cat_id);
        if ($cat) {
            $status = CategoryModel::delete($cat_id);
            if ($status) {
                if (!empty($cat['image_id']))
                    $this->deleteImage($cat);
                Response::jsonMessage(rlang('panel.delete_successfully'), true);
            }
        }

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }
}
