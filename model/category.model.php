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

use pinoox\component\Url;
use pinoox\model\FileModel;

class CategoryModel extends PaperDatabase
{

    public static function insert($data)
    {
        return self::$db->insert(self::category, [
            'cat_key' => $data['cat_key'],
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

    public static function fetch_by_key($cat_key)
    {
        self::$db->where('cat_key', $cat_key);
        return self::$db->getOne(self::category);
    }

    public static function delete($cat_id)
    {
        self::$db->where('cat_id', $cat_id);
        return self::$db->delete(self::category);
    }

    public static function fetch_all($limit = null, $isCount = false)
    {
        $return = self::$db->get(self::category . ' c', $limit);
        return ($isCount) ? self::$db->count : $return;
    }

    public static function tree($linear, $parent_id = null)
    {
        $result = [];
        foreach ($linear as $key => $node) {
            $node['children'] = [];
            if ($node['parent_id'] === $parent_id) {
                $children = self::tree($linear, $node['cat_id']);
                if ($children) {
                    $node['children'] = $children;
                }
                $result[] = $node;
                unset($linear[$key]);
            }
        }
        return $result;
    }

    public static function update_image($cat_id, $image_id)
    {
        self::$db->where('cat_id', $cat_id);
        return self::$db->update(self::category, array(
            'image_id' => $image_id
        ));
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
            'cat_key' => $input['cat_key'],
            'cat_name' => $input['cat_name'],
            'parent_id' => $input['parent_id'],
        ]);
    }

    public static function where_search($keyword)
    {
        if (!is_null($keyword)) {
            $keyword = '%' . $keyword . '%';
            self::$db->where('c.cat_name LIKE ? OR c.cat_key LIKE ?', [$keyword, $keyword]);
        }
    }

    public static function where_key($key)
    {
        if (!is_null($key))
            self::$db->where('c.cat_key', $key);
    }

    public static function where_parent($parent_id)
    {
        self::$db->whereWithNull('c.parent_id', $parent_id);
    }

    public static function sort($sort)
    {
        if (!empty($sort) && isset($sort['field']) && !empty($sort['field'])) {
            self::$db->orderBy($sort['field'], $sort['type']);
        }
    }

    public static function getInfo($cat, $isFull = false)
    {
        if (empty($cat)) return $cat;

        $placeHolder = Url::file('resources/image-placeholder.jpg');
        $file = FileModel::fetch_by_id($cat['image_id']);
        $cat['image'] = Url::upload($file, $placeHolder);
        $cat['thumb_128'] = Url::thumb($file, 128, $placeHolder);

        if ($isFull) {
            $cat['parent'] = self::fetch_by_id($cat['parent_id']);
        }

        return $cat;
    }

    public static function fetch_root($cat)
    {
        if ($cat['parent_id'] == null)
            return $cat;

        self::$db->where('c.cat_id', $cat['parent_id']);
        $parent = self::$db->getOne(self::category . ' c');
        return self::fetch_root($parent);
    }

    public static function get_breadcrumb_by_key($cat_key)
    {
        $cat = CategoryModel::fetch_by_key($cat_key);
        return self::get_breadcrumb($cat['cat_id']);
    }

    public static function get_breadcrumb($cat_id)
    {
        $breadcrumb = [];
        $category = CategoryModel::fetch_by_id($cat_id);
        if (!empty($category)) {
            CategoryModel::build_parent($category, $breadcrumb);
            $breadcrumb = array_reverse($breadcrumb);
        }
        return $breadcrumb;
    }

    public static function build_parent($cat, &$path)
    {
        if (empty($cat))
            return [];

        $cat['active'] = (empty($path));

        $path[] = $cat;
        if (empty($cat['parent_id']))
            return $cat;

        self::$db->where('c.cat_id', $cat['parent_id']);
        $parent = self::$db->getOne(self::category . ' c');

        return self::build_parent($parent, $path);
    }

    public static function fetch_all_by_parent_id($cat_id)
    {
        self::$db->where('c.parent_id', $cat_id);
        return self::$db->get(self::category . ' c');
    }

    public static function fetch_all_ids_children_deeper($cat_id, &$ids = null)
    {
        $child = self::fetch_all_by_parent_id($cat_id);

        if (!empty($child)) {
            $cat_ids = array_column($child, 'cat_id');
            foreach ($cat_ids as $c) $ids[] = $c;

            foreach ($child as $c) {
                self::fetch_all_ids_children_deeper($c['cat_id'], $ids);
            }
        }
        return $ids;
    }


    public static function fetch_all_by_siblings($parent_id, $no_id = null)
    {
        if (!is_null($no_id))
            self::$db->where('c.cat_id', $no_id, '!=');
        self::$db->where('c.parent_id', $parent_id);
        return self::$db->get(self::category . ' c');
    }

    public static function fetch_prev_parent($parent_id)
    {
        self::$db->where('c.cat_id', $parent_id);
        return self::$db->getOne(self::category . ' c');
    }

    public static function search($keyword, $limit = null, $isCount = false)
    {
        $keyword = '%' . $keyword . '%';
        self::$db->where('c.cat_name LIKE ? OR c.cat_key LIKE ?', [$keyword, $keyword]);
        $return = self::$db->get(self::category . ' c', $limit);
        return ($isCount) ? self::$db->count : $return;
    }

    public static function search_tree($keyword, $limit = null, $isCount = false)
    {
        $items = self::search($keyword, $limit, $isCount);
        if ($isCount)
            return $items;

        $result = [];

        if ($items) {
            self::get_all_by_closet($items, $result);
            $result = array_values(array_filter($result));
        }

        return $result;
    }

    private static function get_all_by_closet($items, &$result = [])
    {
        $result = array_merge($result, $items);
        $result = array_unique($result, SORT_REGULAR);
        $ids = array_filter(array_column($items, 'parent_id'));
        $ids = array_diff($ids, array_column($result, 'cat_id'));
        $ids = array_values(array_unique($ids));

        $items = null;
        if ($ids) {
            $items = self::fetch_all_ids($ids);
        }

        if ($items) {
            self::get_all_by_closet($items, $result);
        }

        return $result;
    }

    public static function fetch_all_ids($ids)
    {
        self::$db->where('c.cat_id', $ids, 'IN');
        return self::$db->get(self::category . ' c');
    }


    public static function fetch_all_child_ids($cat_id, &$results)
    {
        $results[] = $cat_id;
        $children= self::fetch_all_by_parent_id($cat_id);
        $count = count($children);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                self::fetch_all_child_ids($children[$i]['cat_id'], $results);
            }
        }
    }
}