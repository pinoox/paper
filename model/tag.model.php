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

class TagModel extends PaperDatabase
{

    public static function insert($tag_name)
    {
        return self::$db->insert(self::tag, [
            'tag_name' => $tag_name,
        ]);
    }

    public static function insert_for_article($article_id, $tags)
    {
        self::delete_all_article_tags($article_id);
        if (empty($tags)) return false;

        foreach ($tags as $t) {
            $tag = self::fetch_by_name($t);
            if (empty($tag)) {
                $tag_id = self::insert($t);
            } else {
                $tag_id = $tag['tag_id'];
            }
            if ($tag_id > 0) self::insert_article_tag($article_id, $tag_id);
        }
        return true;
    }

    private static function insert_article_tag($article_id, $tag_id)
    {
        return self::$db->insert(self::article_tag, [
            'article_id' => $article_id,
            'tag_id' => $tag_id,
        ]);
    }

    private static function delete_all_article_tags($article_id)
    {
        self::$db->where('article_id', $article_id);
        return self::$db->delete(self::article_tag);
    }

    private static function fetch_by_name($tag_name)
    {
        self::$db->where('tag_name', $tag_name);
        return self::$db->getOne(self::tag);
    }

    public static function fetch_all_by_article_id($article_id)
    {
        self::$db->join(self::tag . ' t', 't.tag_id=at.tag_id');
        self::$db->where('article_id', $article_id);
        return self::$db->get(self::article_tag.' at');
    }

}