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

class CommentModel extends PaperDatabase
{

    //status
    const status_suspend = "suspend";
    const status_pending = "pending";
    const status_publish = "publish";

    public static function insert($data)
    {
        return self::$db->insert(self::comment, [
            'post_id' => $data['post_id'],
            'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
            'user_id' => isset($data['user_id']) ? $data['user_id'] : null,
            'full_name' => $data['full_name'],
            'email' => isset($data['email']) ? $data['email'] : null,
            'mobile' => isset($data['mobile']) ? $data['mobile'] : null,
            'message' => $data['message'],
            'status' => self::status_pending,
            'insert_date' => Date::g('Y-m-d H:i:s'),
        ]);
    }

    public static function update_status($comment_id, $status)
    {
        self::$db->where('comment_id', $comment_id);
        return self::$db->update(self::comment, [
            'status' => $status
        ]);
    }

    public static function delete($comment_id)
    {
        self::$db->where('comment_id', $comment_id);
        return self::$db->delete(self::comment);
    }

    public static function fetch_by_id($cm_id)
    {
        self::$db->where('comment_id', $cm_id);
        return self::$db->getOne(self::comment);
    }

    public static function fetch_all_by_post($post_id, $status = self::status_publish, $limit = null)
    {
        self::$db->join(self::user . ' u', 'u.user_id=c.user_id', 'LEFT');
        self::$db->join(self::post . ' p', 'p.post_id=c.post_id', 'LEFT');
        self::$db->where('c.post_id', $post_id);
        self::$db->where('c.status', $status);
        return self::$db->get(self::comment . ' c', $limit, 'c.*,CONCAT(u.fname," ",u.lname) user_full_name,u.avatar_id,u.email user_email,p.title post_title');
    }

    public static function fetch_all($status = null, $limit = null, $isCount = false)
    {
        if (!is_null($status))
            self::$db->where('c.status', $status);

        self::$db->join(self::user . ' u', 'u.user_id=c.user_id', 'LEFT');
        self::$db->join(self::post . ' p', 'p.post_id=c.post_id', 'LEFT');
        self::$db->orderBy('c.insert_date', 'DESC');
        self::$db->groupBy('c.comment_id');
        $result = self::$db->get(self::comment . ' c', $limit, 'c.*,CONCAT(u.fname," ",u.lname) user_full_name,u.avatar_id,u.email user_email,p.title');
        if ($isCount) return count($result);
        return $result;
    }

    public static function where_status($status)
    {
        if (!is_null($status) && $status != 'all')
            self::$db->where('c.status', $status);
    }

    public static function where_search($keyword)
    {
        if (empty($keyword)) return;
        if ($keyword == rlang('comment.publish')) $status = self::status_publish;
        else if ($keyword == rlang('comment.pending')) $status = self::status_pending;
        else if ($keyword == rlang('comment.suspend')) $status = self::status_suspend;
        else $status = false;

        $k = '%' . $keyword . '%';
        if ($status !== false)
            self::$db->where('(c.`status` = ? )', [$status]);
        else
            self::$db->where('(c.full_name LIKE ? OR c.message LIKE ? OR c.email LIKE ?)', [$k, $k, $k]);
    }

    public static function fetch_stats()
    {
        $total = self::fetch_all(null, null, true);
        $publish = self::fetch_all(self::status_publish, null, true);
        $pending = self::fetch_all(self::status_pending, null, true);
        $suspend = self::fetch_all(self::status_suspend, null, true);

        return [
            'total' => $total,
            'publish' => $publish,
            'pending' => $pending,
            'suspend' => $suspend,
        ];
    }


    public static function sort($sort)
    {
        if (!empty($sort) && isset($sort['field']) && !empty($sort['field'])) {
            if ($sort['field'] === 'approx_insert_date')
                $sort['field'] = 'insert_date';

            self::$db->orderBy($sort['field'], $sort['type']);
        }
    }

    public static function where_post_id($post_id)
    {
        if (!is_null($post_id))
            self::$db->where('c.post_id', $post_id);
    }

}