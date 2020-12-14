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
use pinoox\component\Url;

class SettingsModel extends PaperDatabase
{
    /**
     * @var Setting
     */
    private static $setting;
    private static $theme = null;
    private static $data = null;

    public static function saveMain($name, $data)
    {
        $themeDefault = self::$theme;
        self::setTheme('~');
        $mainView = self::getView($name);
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

    public static function getView($name)
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
            $path = self::getPath();
            self::$setting[$theme] = Setting::init($path);
        }
        return self::$setting[$theme];
    }

    private static function getPath()
    {
        $theme = !empty(self::$theme) ? self::$theme : '~';
        return ($theme === '~') ? Dir::path('setting') : Dir::path('theme/' . $theme . '/setting');

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
        $values = self::getSetting()->get($name);
        return $values;
    }

    public static function getViewsMain()
    {
        $themeDefault = self::$theme;
        $theme = AppProvider::get('theme');
        self::setTheme($theme);
        $themeViews = self::getViews();
        $themeViews = !empty($themeViews) ? $themeViews : [];
        $themeViews = array_map(function ($view) {
            $view['sort'] = !empty($view['sort']) ? $view['sort'] : 0;
            $view['sort'] = 100 + intval($view['sort']);
            return $view;
        }, $themeViews);
        self::setTheme('~');
        $mainViews = self::getViews();
        $mainViews = !empty($mainViews) ? $mainViews : [];
        $views = array_merge($themeViews, $mainViews);
        self::setTheme($themeDefault);
        return $views;
    }

    public static function getViews()
    {
        $views = self::getSetting()->view()->getAll();
        return array_values($views);
    }

    public static function fetch_images()
    {
        self::$db->where('app', AppProvider::get('package-name'));
        self::$db->where('file_group', 'setting');
        return self::$db->get(self::file);
    }

    public static function getData($name = null, $theme = null)
    {
        if (empty($theme)) {
             self::$data = (empty(self::$data)) ? self::getAllMain() : self::$data;
            $result = self::$data;
            if(!empty($name))
            {
                $name = explode('.',$name);
                foreach ($name as $value) {
                    if (isset($result[$value])) {
                        $result = $result[$value];
                    } else {
                        $result = null;
                        break;
                    }
                }
            }

        } else {
            self::setTheme($theme);
            $result = self::get($name);
        }

        return $result;
    }

    public static function getAllMain()
    {
        $themeDefault = self::$theme;
        $theme = AppProvider::get('theme');
        self::setTheme($theme);
        $themeSetting = self::getAll();
        $themeSetting = !empty($themeSetting) ? $themeSetting : [];
        self::setTheme('~');
        $mainSetting = self::getAll();
        $mainSetting = !empty($mainSetting) ? $mainSetting : [];
        $configs = array_merge($themeSetting, $mainSetting);
        self::setTheme($themeDefault);
        return $configs;
    }

    public static function getAll()
    {
        $settings = self::getSetting()->getAll();
        foreach ($settings as $key => $values) {
            $settings[$key] = self::getValuesByPattern($values);
        }
        return $settings;
    }

    private static function getImageValue($value)
    {
        $value = str_replace('/', DIRECTORY_SEPARATOR, $value);
        $path = dirname(self::getPath());
        $path = $path . DIRECTORY_SEPARATOR . $value;
        return is_file($path) ? Url::link($path) : null;
    }

    private static function getTheme()
    {
        return !empty(self::$theme) ? self::$theme : '~';
    }
}