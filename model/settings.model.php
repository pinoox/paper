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

    public static function saveMain($name, $data)
    {
        $themeDefault = self::$theme;
        self::setTheme('~');
        $mainView = self::fetch_view($name);
        $settings = !empty($mainView['setting']) ? $mainView['setting'] : [];
        $settings = array_column($settings, 'key');
        $mainSettings = [];
        $themeSettings = [];
        foreach ($data as $key => $item) {
            if (in_array($key, $settings))
                $mainSettings[] = $item;
            else
                $themeSettings[] = $item;
        }
        self::save($name, $mainSettings);
        $theme = AppProvider::get('theme');
        self::setTheme($theme);
        self::save($name, $themeSettings);
        self::setTheme($themeDefault);
    }

    public static function setTheme($theme = null)
    {
        self::$theme = $theme;
    }

    public static function fetch_view($name)
    {
        $views = self::getSetting()->view()->get($name);
        return array_values($views);
    }

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

    public static function save($name, $data)
    {
        self::getSetting()->set($name, $data);
        self::getSetting()->save($name);
    }

    public static function getMain($name)
    {
        $themeDefault = self::$theme;
        $theme = AppProvider::get('theme');
        self::setTheme($theme);
        $themeSetting = self::get($name);
        $themeSetting = !empty($themeSetting) ? $themeSetting : [];
        self::setTheme('~');
        $mainSetting = self::get($name);
        $mainSetting = !empty($mainSetting) ? $mainSetting : [];
        $config = array_merge($themeSetting, $mainSetting);
        self::setTheme($themeDefault);
        return $config;
    }

    public static function get($name)
    {
        return self::getSetting()->get($name);
    }

    public static function getMainConfigs()
    {
        $themeDefault = self::$theme;
        $theme = AppProvider::get('theme');
        self::setTheme($theme);
        $themeSetting = self::fetch_all();
        $themeSetting = !empty($themeSetting) ? $themeSetting : [];
        self::setTheme('~');
        $mainSetting = self::fetch_all();
        $mainSetting = !empty($mainSetting) ? $mainSetting : [];
        $configs = array_merge($themeSetting, $mainSetting);
        self::setTheme($themeDefault);
        return $configs;
    }

    public static function fetch_all()
    {
        return self::getSetting()->getAll();
    }

    public static function getMainViews()
    {
        $themeDefault = self::$theme;
        $theme = AppProvider::get('theme');
        self::setTheme($theme);
        $themeViews = self::fetch_views();
        $themeViews = !empty($themeViews) ? $themeViews : [];
        self::setTheme('~');
        $mainViews = self::fetch_views();
        $mainViews = !empty($mainViews) ? $mainViews : [];
        $views = array_merge($themeViews, $mainViews);
        self::setTheme($themeDefault);
        return $views;
    }

    public static function fetch_views()
    {
        $views = self::getSetting()->view()->getAll();
        return array_values($views);
    }
}