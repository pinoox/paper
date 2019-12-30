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

use pinoox\component\Date;

class PageModel extends PaperDatabase
{
    public static function insert($page_key)
    {
        return self::$db->insert(self::page, [
            'page_key' => $page_key,
            'status' => self::draft,
            'insert_date' => Date::g('Y-m-d H:i:s'),
        ]);
    }

    public static function update($page_id, $data)
    {
        self::$db->where('page_id', $page_id);
       return self::$db->update(self::page, [
            'page_key' => $data['page_key'],
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => $data['status'],
        ]);
    }

    public static function delete($page_id)
    {
        self::$db->where('page_id', $page_id);
        return self::$db->delete(self::page);
    }

    public static function fetch_by_page_key($page_key, $status = null)
    {
        if (!is_null($status))
            self::$db->where('status', $status);
        self::$db->where('page_key', $page_key);
        return self::$db->getOne(self::page);
    }

    public static function fetch_by_id($page_id)
    {
        self::$db->where('page_id', $page_id);
        return self::$db->getOne(self::page);
    }

    public static function fetch_all($limit = null, $isCount = false)
    {
        self::$db->orderBy('p.insert_date', 'DESC');
        $result = self::$db->get(self::page . ' p', $limit);
        if ($isCount) return self::$db->count;
        return $result;
    }

    public static function where_search($keyword)
    {
        if (empty($keyword)) return;
        if ($keyword == rlang('panel.publish')) $status = self::publish;
        else if ($keyword == rlang('panel.draft')) $status = self::draft;
        else if ($keyword == rlang('panel.suspend')) $status = self::suspend;
        else $status = false;

        $k = '%' . $keyword . '%';
        if ($status !== false)
            self::$db->where('(p.`status` = ? )', [$status]);
        else
            self::$db->where('(p.title LIKE ? OR p.page_key LIKE ?)', [$k, $k]);
    }

    public static function update_status($page_id, $status)
    {
        self::$db->where('page_id', $page_id);
        return self::$db->update(self::page, [
            'status' => $status
        ]);
    }

    public static function update_visit($page_id)
    {
        self::$db->where('page_id', $page_id);
        return self::$db->update(self::page, [
            'visits' => self::$db->inc('+1'),
        ]);
    }

    public static function update_visitor($page_id)
    {
        self::$db->where('page_id', $page_id);
        return self::$db->update(self::page, [
            'visitors' => self::$db->inc('+1'),
        ]);
    }
}
