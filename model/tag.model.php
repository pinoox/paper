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

    public static function fetch_by_name($tag_name)
    {
        self::$db->where('tag_name', $tag_name);
        return self::$db->getOne(self::tag);
    }

}