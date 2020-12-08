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
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\app\com_pinoox_paper\model\SettingsModel;
use pinoox\component\app\AppProvider;
use pinoox\component\Lang;
use pinoox\component\Request;
use pinoox\component\Response;


class SettingController extends LoginConfiguration
{
    public function __construct()
    {
        parent::__construct();

        $headers = apache_request_headers();
        if (isset($headers['theme_name'])) {
            SettingsModel::setTheme($headers['theme_name']);
        }
    }

    public function get($name)
    {
        $items = SettingsModel::get($name);
        $items = !empty($items) ? $items : [];
        Response::json($items);
    }

    public function getAll()
    {
        $configs = SettingsModel::getAll();
        $configs = !empty($configs) ? $configs : [];
        Response::json($configs);
    }

    public function save($name)
    {
        $inputs = Request::input('*', null, '!empty');
        SettingsModel::save($name, $inputs);
        Response::json(rlang('post.save_successfully'), true);
    }

    public function getViews($lang = null)
    {
        $lang = !empty($lang) ? strtolower($lang) : Lang::current();
        Lang::change($lang);
        $views = SettingsModel::getViews();
        Response::json($views);
    }

    public function changeLang($lang)
    {
        $lang = strtolower($lang);
        AppProvider::set('lang', $lang);
        AppProvider::save();
        Lang::change($lang);
        $lang = LangModel::fetch_all();
        $direction = $lang['paper']['direction'];
        Response::json(['lang' => $lang, 'direction' => $direction]);
    }
}
