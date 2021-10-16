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

class GroupModel extends PaperDatabase
{
    public static function fetch_by_key($group_key, $old_group_key = null)
    {
        if ($group_key == $old_group_key)
            return false;

        self::$db->where('group_key', $group_key);
        return self::$db->getOne(self::group);
    }

    public static function insert($form)
    {
        return self::$db->insert(self::group, [
            'group_key' => $form['group_key'],
            'group_name' => $form['group_name'],
        ]);
    }

    public static function update($group_key, $form)
    {
        self::$db->where('group_key', $group_key);
        return self::$db->update(self::group, [
            'group_key' => $form['group_key'],
            'group_name' => $form['group_name'],
        ]);
    }

    public static function delete($group_key)
    {
        self::$db->where('group_key', $group_key);
        return self::$db->delete(self::group);
    }

    public static function fetch_all($limit = null, $isCount = false)
    {
        $result = self::$db->get(self::group . ' g', $limit);
        return ($isCount) ? self::$db->count : $result;
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
