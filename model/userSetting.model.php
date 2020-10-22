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

namespace pinoox\app\com_pinoox_paper\model;

use pinoox\component\HelperString;

class UserSettingModel extends PaperDatabase
{
    public static function delete($user_id)
    {
        self::$db->where('user_id', $user_id);
        return self::$db->delete(self::user_setting);
    }

    public static function get_data($user_id, $state = null)
    {
        $user_setting = self::fetch_by_id($user_id);
        $setting = ($user_setting) ? HelperString::decodeJson($user_setting['json_data']) : [];

        if (!is_null($state))
            return isset($setting[$state]) ? $setting[$state] : null;
        else
            return $setting;
    }

    public static function fetch_by_id($user_id)
    {
        self::$db->where('user_id', $user_id);
        return self::$db->getOne(self::user_setting);
    }

    public static function save_data($user_id, $data, $state = null)
    {
        $user_setting = self::fetch_by_id($user_id);
        $setting = ($user_setting) ? HelperString::decodeJson($user_setting['json_data']) : [];
        if (!is_null($state)) {
            if (isset($setting[$state]))
                unset($setting[$state]);
            $setting[$state] = $data;
        } else {
            $setting = $data;
        }

        if ($user_setting)
            return self::update($user_id, $setting);
        else
            return self::insert($user_id, $setting);
    }

    public static function update($user_id, $data)
    {
        self::$db->where('user_id', $user_id);
        return self::$db->update(self::user_setting, [
            'json_data' => HelperString::encodeJson($data),
        ]);
    }

    public static function insert($user_id, $data)
    {
        return self::$db->insert(self::user_setting, [
            'user_id' => $user_id,
            'json_data' => HelperString::encodeJson($data),
        ]);
    }

}
