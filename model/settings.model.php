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

namespace pinoox\app\com_pinoox_paper\model;

use pinoox\app\com_pinoox_paper\component\Setting;
use pinoox\component\app\AppProvider;
use pinoox\component\Dir;
use pinoox\component\Lang;
use pinoox\component\MagicTrait;

class SettingsModel extends PaperDatabase
{
    use MagicTrait;

    const settings = [
        'general',
        'write',
    ];
    /**
     * @var Setting
     */
    private static $setting;

    public static function __init()
    {
        $path = Dir::path('setting');
        self::$setting = Setting::init($path);
    }

    public static function fetch_all()
    {
        return self::$setting->getAll();
    }

    public static function get($name)
    {
        return self::$setting->get($name);
    }

    public static function fetch_views()
    {
        $views = self::$setting->view()->getAll();
        return array_values($views);
    }

    public static function save($name, $data)
    {
//        if ($name === 'general') {
//            Lang::change($data['lang']);
//            AppProvider::set('lang', $data['lang']);
//            AppProvider::save();
//        }
        self::$setting->set($name, $data);
        self::$setting->save($name);
    }
}