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

use pinoox\component\Config;
use pinoox\component\Request;
use pinoox\component\Response;


class SettingController extends LoginConfiguration
{
    public function get($name)
    {
        $items = Config::get('setting>' . $name);
        $items = !empty($items) ? $items : [];
        Response::json($items);
    }

    public function save($name)
    {
        $inputs = Request::input('site_title,site_description', null, '!empty');
        Config::set('setting>' . $name, $inputs);
        Response::json(rlang('post.save_successfully'),true);
    }
}
