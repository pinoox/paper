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

use pinoox\component\Cache;

class SettingsModel extends PaperDatabase
{

    public static function fetch_all($group = null)
    {
        if (!is_null($group))
            self::$db->where('s_group', $group);
        self::$db->orderBy('settings_id', 'ASC');
        return self::$db->get(self::settings);
    }

    public static function fetch_all_groups()
    {
        self::$db->groupBy('s_group');
        self::$db->orderBy('settings_id', 'ASC');
        return self::$db->get(self::settings, null, 's_key,s_group');
    }

    public static function update($key, $value)
    {
        if (is_numeric($value)) $value = abs(intval($value));
        self::$db->where('s_key', $key);
        return self::$db->update(self::settings, array(
            "s_value" => $value
        ));
    }


    public static function getFromCache($s_key, $default = null)
    {
        $settings = Cache::get('settings');
        return isset($settings[$s_key])? $settings[$s_key] : null;
    }

}