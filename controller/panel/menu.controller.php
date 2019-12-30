<?php
/**
 *      ****  *  *     *  ****  ****  *    *
 *      *  *  *  * *   *  *  *  *  *   *  *
 *      ****  *  *  *  *  *  *  *  *    *
 *      *     *  *   * *  *  *  *  *   *  *
 *      *     *  *    **  ****  ****  *    *
 * @author   Pinoox
 * @license  https://opensource.org/licenses/MIT MIT License
 */
namespace pinoox\app\com_pinoox_paper\controller\panel;

use pinoox\app\com_pinoox_paper\model\MenuModel;
use pinoox\component\Config;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Validation;

class MenuController extends MasterConfiguration
{

    public function _main()
    {
        $this->show();
    }

    private function show()
    {
        $menus = Config::get('menu');
        self::$template->set('menus', $menus);
        self::$template->show('menu>main');
    }

    public function build($key)
    {
        $menus = Config::get('menu');
        if (!array_key_exists($key, $menus)) self::error404();

        $items = MenuModel::fetch_all($key);

        self::$template->set('key', $key);
        self::$template->set('items', $items);
        self::$template->show('menu>build');
    }

    public function add()
    {
        if (!Request::isAjax()) self::error404();

        $form = Request::post('title,link,icon,menu_key');
        $valid = Validation::check($form, [
            'title' => ['required|length:>2', rlang('panel.menu_title')],
            'menu_key' => ['required', rlang('panel.menu_key')],
        ]);
        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        $status = MenuModel::insert($form);
        if ($status)
            Response::jsonMessage(rlang('panel.added_successfully'), true);

        Response::jsonMessage(rlang('panel.error_happened'), false);

    }

    public function edit()
    {
        $form = Request::post('menu_id,title,link,icon,menu_key');
        $valid = Validation::check($form, [
            'menu_id' => ['required', rlang('panel.invalid_request')],
            'title' => ['required|length:>2', rlang('panel.menu_title')],
            'menu_key' => ['required', rlang('panel.menu_key')],
        ]);
        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        $status = MenuModel::update($form);
        if ($status)
            Response::jsonMessage(rlang('panel.added_successfully'), true);

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    public function delete()
    {
        if (Request::isAjax()) {
            $id = Request::postOne('menu_id');
            $menu = MenuModel::fetch_by_id($id);
            if (empty($menu)) self::error404();

            $status = MenuModel::delete($id);
            if ($status) {
                Response::json(rlang('panel.delete_successfully'), true);
            }
        }
        Response::json(rlang('panel.error_happened'), false);
    }
}