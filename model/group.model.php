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

use pinoox\app\com_pinoox_paper\model\PaperDatabase;
use pinoox\component\Config;

class GroupModel extends PaperDatabase
{
    public static function fetch_by_key($group_key, $old_group_key = null)
    {
        if ($group_key == $old_group_key)
            return false;

        return Config::getLinear('groups', $group_key);
    }

    public static function insert($form)
    {
        Config::setLinear('groups', $form['group_key'], [
            'group_key' => $form['group_key'],
            'group_name' => $form['group_name'],
            'is_main' => false,
        ]);
        Config::save('groups');
    }

    public static function update($group_key, $form)
    {
        Config::removeLinear('groups', $group_key);

        Config::setLinear('groups', $form['group_key'], [
            'group_key' => $form['group_key'],
            'group_name' => $form['group_name'],
            'is_main' => $form['is_main'],
        ]);

        Config::save('groups');
    }

    public static function delete($group_key)
    {
        Config::removeLinear('groups', $group_key);
        Config::save('groups');
    }

    public static function fetch_all()
    {
        $items = Config::get('groups');
        if (!empty($items))
            $items = array_values($items);
        return $items;
    }

    public static function fetch_all_by_filter()
    {
        $items = self::fetch_all();
        return array_filter($items,function ($item){
            return!isset($item['hide']) || $item['hide'] != true;
        });
    }

    public static function fetch_all_for_setting()
    {
        $groups = self::fetch_all();
        $result = [];

        if (empty($groups))
            return $result;
        foreach ($groups as $group) {
            $result[$group['group_key']] = $group['group_name'];
        }

        return $result;
    }

    public static function filter($form)
    {
        if (!empty($form['keyword'])) {
            $form['keyword'] = '%' . $form['keyword'] . '%';
            self::$db->where('g.group_key LIKE ? OR g.group_name LIKE ?', [$form['keyword'], $form['keyword']]);
        }
    }

    public static function sort($sort)
    {
        if (!empty($sort) && isset($sort['field']) && !empty($sort['field'])) {
            self::$db->orderBy($sort['field'], $sort['type']);
        }
    }

    public static function where_search($keyword)
    {
        if (empty($keyword)) return;

        $k = '%' . $keyword . '%';
        self::$db->where('(g.group_key LIKE ? OR g.group_name LIKE ?)', [$k, $k]);
    }

    public static function getDefault()
    {
        return 'admin';
    }
}
