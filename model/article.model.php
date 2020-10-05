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
use pinoox\component\User;

class ArticleModel extends PaperDatabase
{
    public static function insert($title)
    {
        return self::$db->insert(self::article, [
            'user_id' => User::get('user_id'),
            'title' => $title,
            'status' => self::draft,
            'insert_date' => Date::g('Y-m-d H:i:s'),
        ]);
    }

    public static function update($article_id, $data)
    {
        self::$db->where('article_id', $article_id);
        return self::$db->update(self::article, [
            'image_id' => $data['image_id'],
            'title' => $data['title'],
            'summary' => $data['summary'],
            'content' => $data['content'],
            'status' => $data['status'],
        ]);
    }

    public static function delete($article_id)
    {
        self::$db->where('article_id', $article_id);
        return self::$db->delete(self::article);
    }

    public static function fetch_by_id($article_id)
    {
        self::$db->join(self::user . ' u', 'u.user_id=a.user_id', 'LEFT');
        self::$db->where('article_id', $article_id);
        return self::$db->getOne(self::article . ' a', 'a.*,CONCAT(u.fname," ",u.lname) full_name,u.avatar_id');
    }

    public static function fetch_all($limit = null, $isCount = false)
    {
        self::$db->join(self::user . ' u', 'u.user_id=a.user_id', 'LEFT');
        self::$db->orderBy('insert_date', 'DESC');
        $result = self::$db->get(self::article . ' a', $limit, 'a.*,CONCAT(u.fname," ",u.lname) full_name,u.avatar_id');
        if ($isCount) return self::$db->count;
        return $result;
    }

    public static function fetch_most_visited($limit = null, $isCount = false)
    {

        self::$db->orderBy('visits', 'DESC');
        return self::fetch_all($limit, $isCount);
    }

    public static function hot_tags($limit = null)
    {
        $articles = self::fetch_most_visited(50);
        if (empty($articles)) return null;
        $ids = array_column($articles, 'article_id');

        self::$db->where('at.article_id', $ids, 'IN');
        self::$db->where('a.status', self::publish);
        self::$db->join(self::tag . ' t', 't.tag_id=at.tag_id', 'INNER');
        self::$db->join(self::article . ' a', 'a.article_id=at.article_id', 'INNER');
        self::$db->groupBy('t.tag_id,t.tag_name');
        return self::$db->get(self::article_tag . ' at', $limit, 't.tag_id,t.tag_name');

    }

    public static function fetch_by_tag_name($tag_name, $limit = null, $isCount = false)
    {
        self::$db->join(self::article_tag . ' at', 'at.article_id=a.article_id', 'LEFT');
        self::$db->join(self::tag . ' t', 't.tag_id=at.tag_id', 'LEFT');
        self::$db->join(self::user . ' u', 'u.user_id=a.user_id', 'LEFT');
        self::$db->where('t.tag_name', $tag_name);
        self::$db->orderBy('insert_date', 'desc');
        $result = self::$db->get(self::article . ' a', $limit, 'a.*,CONCAT(u.fname," ",u.lname) full_name,u.avatar_id');
        if ($isCount) return self::$db->count;
        return $result;
    }

    public static function update_feature($article_id)
    {
        self::$db->where('article_id', $article_id);
        return self::$db->update(self::article, [
            'feature' => self::$db->not()
        ]);
    }

    public static function where_search($keyword)
    {
        if (empty($keyword)) return;
        if ($keyword == rlang('panel.publish')) $status = self::publish;
        else if ($keyword == rlang('panel.pending')) $status = self::pending;
        else if ($keyword == rlang('panel.suspend')) $status = self::suspend;
        else $status = false;

        $k = '%' . $keyword . '%';
        if ($status !== false)
            self::$db->where('(a.`status` = ? )', [$status]);
        else
            self::$db->where('(a.title LIKE ? OR a.summary LIKE ?)', [$k, $k]);
    }

    public static function where_is_feature($status)
    {
        self::$db->where('a.feature', $status ? 1 : 0);
    }

    public static function where_status($status)
    {
        self::$db->where('a.status', $status);
    }

    public static function sort($field, $val)
    {
        self::$db->orderBy($field, $val);
    }

    public static function fetch_all_tags_by_article_id($article_id)
    {
        self::$db->where('at.article_id', $article_id);
        self::$db->join(self::article . ' a', 'at.article_id=a.article_id');
        self::$db->join(self::tag . ' t', 'at.tag_id=t.tag_id');
        return self::$db->get(self::article_tag . ' at', null, 't.tag_id,t.tag_name');
    }

    public static function update_visit($article_id)
    {
        self::$db->where('article_id', $article_id);
        return self::$db->update(self::article, [
            'visits' => self::$db->inc('+1'),
        ]);
    }

    public static function update_visitor($article_id)
    {
        self::$db->where('article_id', $article_id);
        return self::$db->update(self::article, [
            'visitors' => self::$db->inc('+1'),
        ]);
    }

    public static function fetch_stats()
    {
        $total = self::fetch_all(null, true);

        self::where_status(self::publish);
        $publishes = self::fetch_all(null, true);

        self::where_status(self::draft);
        $drafts = self::fetch_all(null, true);

        self::where_status(self::suspend);
        $suspends = self::fetch_all(null, true);

        return [
            'total' => $total,
            'publishes' => $publishes,
            'drafts' => $drafts,
            'suspends' => $suspends
        ];
    }

    public static function update_status($article_id, $status)
    {
        self::$db->where('article_id', $article_id);
        return self::$db->update(self::article, [
            'status' => $status
        ]);
    }

}
