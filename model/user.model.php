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

use pinoox\component\app\AppProvider;
use pinoox\model\UserModel as UserModelCore;

class UserModel extends UserModelCore
{
    public static function sort($sort)
    {
        if (!empty($sort) && isset($sort['field']) && !empty($sort['field'])) {
            if ($sort['field'] === 'approx_register_date')
                $sort['field'] = 'register_date';

            if ($sort['field'] === 'full_name')
                $sort['field'] = 'fname';

            self::$db->orderBy($sort['field'], $sort['type']);
        }
    }

    public static function where_status($status)
    {
        if (!is_null($status) && $status != 'all')
            self::$db->where('status', $status);
    }

    public static function fetch_by_app()
    {
        $app = AppProvider::get('package-name');
        self::$db->where('app', $app);
        return self::$db->getOne(self::user);
    }
}