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

use pinoox\component\Validation;

class MenuModel extends PaperDatabase
{
    public static function insert($data)
    {
        return self::$db->insert(self::menu, [
            'menu_key' => $data['menu_key'],
            'title' => $data['title'],
            'link' => $data['link'],
            'icon' => $data['icon'],
        ]);
    }

    public static function update($data)
    {
        self::$db->where('menu_id', $data['menu_id']);
        return self::$db->update(self::menu, [
            'menu_key' => $data['menu_key'],
            'title' => $data['title'],
            'link' => $data['link'],
            'icon' => $data['icon'],
        ]);
    }

    public static function fetch_all($key, $limit = null)
    {
        self::$db->where('menu_key', $key);
        return self::getMenu(self::$db->get(self::menu, $limit));
    }

    private static function getMenu($items)
    {
        if(empty($items) && !is_array($items))
            return $items;
        return array_map(function ($menu){
            if(empty($menu['link']) || !Validation::checkOne($menu['link'],'url')){
                $menu['link'] = url($menu['link']);
            }
            return $menu;
        },$items);
    }

    public static function count($key)
    {
        $result = self::fetch_all($key);
        return count($result);
    }

    public static function fetch_by_key($key, $noId = null)
    {
        if (!empty($noId))
            self::$db->where('menu_id', $noId, '!=');

        self::$db->where('menu_key', $key);
        return self::$db->getOne(self::menu);
    }

    public static function fetch_by_id($menu_id)
    {
        self::$db->where('menu_id', $menu_id);
        return self::$db->getOne(self::menu);
    }

    public static function delete($menu_id)
    {
        self::$db->where('menu_id', $menu_id);
        return self::$db->delete(self::menu);
    }

    public static function where_search($keyword)
    {
        if (!empty($keyword)) {
            $k = '%' . $keyword . '%';
            self::$db->where('menu_key LIKE ? OR menu_title LIKE ? ', [$k, $k]);
        }
    }

}