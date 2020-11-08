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

use pinoox\app\com_pinoox_paper\model\LangModel;
use pinoox\app\com_pinoox_paper\model\SettingsModel;
use pinoox\component\Lang;
use pinoox\component\Request;
use pinoox\component\Response;


class SettingController extends LoginConfiguration
{
    public function get($name)
    {
        $items = SettingsModel::get($name);
        $items = !empty($items) ? $items : [];
        Response::json($items);
    }

    public function getAll()
    {
        $configs = SettingsModel::fetch_all();
        $configs = !empty($configs) ? $configs : [];
        Response::json($configs);
    }

    public function save($name)
    {
        $inputs = Request::input('*', null, '!empty');
        SettingsModel::save($name, $inputs);
        Response::json(rlang('post.save_successfully'), true);
    }

    public function getViews()
    {
        $views = SettingsModel::fetch_views();
        Response::json($views);
    }

    public function getLang($lang)
    {
        Lang::change($lang);
        $lang = LangModel::fetch_all();
        Response::json($lang);
    }
}
