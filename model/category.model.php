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

class CategoryModel extends PaperDatabase
{

    public static function insert($data)
    {
        return self::$db->insert(self::category, [
            'cat_name' => $data['cat_name'],
            'parent_id' => $data['parent_id'],
        ]);
    }

    public static function fetch_by_id($cat_id)
    {
        self::$db->where('cat_id', $cat_id);
        return self::$db->getOne(self::category);
    }

    public static function fetch_by_name($cat_name, $no_id = null)
    {
        if (!is_null($no_id))
            self::$db->where('cat_id', $no_id, '!=');

        self::$db->where('cat_name', $cat_name);
        return self::$db->getOne(self::category);
    }

    public static function delete($cat_id)
    {
        self::$db->where('cat_id', $cat_id);
        return self::$db->delete(self::category);
    }

    public static function fetch_all()
    {
        return self::$db->get(self::category);
    }

    public static function tree($linear, $parent_id = null)
    {
        $result = [];
        foreach ($linear as $node) {
            if ($node['parent_id'] === $parent_id) {

                $children = self::tree($linear, $node['cat_id']);
                if ($children) {
                    $node['children'] = $children;
                }
                $result[] = $node;
                unset($node);
            }
        }
        return $result;
    }

    public static function update_parent($catItem, $parent)
    {
        self::$db->where('cat_id', $catItem['cat_id']);
        return self::$db->update(self::category, [
            'parent_id' => !empty($parent['cat_id']) ? $parent['cat_id'] : null,
        ]);
    }

    public static function update($input)
    {
        self::$db->where('cat_id', $input['cat_id']);
        return self::$db->update(self::category, [
            'cat_name' => $input['cat_name']
        ]);
    }


}