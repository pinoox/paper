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

class SettingsModel extends PaperDatabase
{
    /**
     * @var Setting
     */
    private static $setting;
    private static $theme = null;

    /**
     * @return Setting
     */
    private static function getSetting()
    {
        $theme = !empty(self::$theme) ? self::$theme : '~';

        if (empty(self::$setting[$theme])) {
            $path = ($theme === '~') ? Dir::path('setting') : Dir::path('theme/' . $theme . '/setting');
            self::$setting[$theme] = Setting::init($path);
        }
        return self::$setting[$theme];
    }

    public static function setTheme($theme = null)
    {
        self::$theme = $theme;
    }

    public static function fetch_all()
    {
        return self::getSetting()->getAll();
    }

    public static function get($name)
    {
        return self::getSetting()->get($name);
    }

    public static function fetch_views()
    {
        $views = self::getSetting()->view()->getAll();
        return array_values($views);
    }

    public static function save($name, $data)
    {
        self::getSetting()->set($name, $data);
        self::getSetting()->save($name);
    }

    public static function getConfigs()
    {
        $themeDefault = self::$theme;
        $theme = AppProvider::get('theme');
        SettingsModel::setTheme($theme);
        $themeSetting = SettingsModel::fetch_all();
        $themeSetting = !empty($themeSetting) ? $themeSetting : [];
        SettingsModel::setTheme('~');
        $mainSetting = SettingsModel::fetch_all();
        $mainSetting = !empty($mainSetting) ? $mainSetting : [];
        $configs = array_merge($mainSetting,$themeSetting);
        SettingsModel::setTheme($themeDefault);
        return $configs;
    }
}