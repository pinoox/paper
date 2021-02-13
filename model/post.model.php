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

use pinoox\component\app\AppProvider;
use pinoox\component\Date;
use pinoox\component\HelperString;
use pinoox\component\Url;
use pinoox\component\User;
use pinoox\model\FileModel;

class PostModel extends PaperDatabase
{
    //status
    const draft_status = "draft";
    const publish_status = "publish";
    const cancel_publish_status = "cancel_publish";
    const synced_status = "synced";

    // status comment
    const open_status = "open";
    const closed_status = "closed";

    //type
    const post_type = "post";
    const page_type = "page";

    public static function insert($data)
    {
        $date = Date::g('Y-m-d H:i:s');
        return self::$db->insert(self::post, [
            'hash_id' => $data['hash_id'],
            'user_id' => isset($data['user_id']) ? $data['user_id'] : User::get('user_id'),
            'summary' => $data['summary'],
            'time_tracking' => $data['time'],
            'status' => self::draft_status,
            'post_key' => !empty($data['post_key']) ? $data['post_key'] : null,
            'post_type' => !empty($data['post_type']) ? $data['post_type'] : self::post_type,
            'image_id' => !empty($data['image']) ? $data['image'] : null,
            'insert_date' => $date,
            'update_date' => $date,
        ]);
    }

    public static function fetch_by_key($post_key, $no_post_id = null)
    {
        if (empty($post_key))
            return null;
        if (!empty($no_post_id))
            self::$db->where('p.post_id', $no_post_id, '!=');
        self::$db->where('p.post_key', $post_key);
        return self::$db->getOne(self::post . ' p');
    }

    public static function save_draft($data)
    {
        $post = self::post_draft_fetch_by_id($data['post_id']);
        if ($post)
            self::post_draft_update($data);
        else
            self::post_draft_insert($data);
    }

    public static function post_draft_fetch_by_id($post_id)
    {
        self::$db->join(self::user . ' u', 'u.user_id=p.user_id', 'LEFT');
        self::$db->join(self::post_draft . ' pd', 'pd.post_id=p.post_id');
        self::$db->where('p.post_id', $post_id);
        return self::$db->getOne(self::post . ' p', 'p.*,pd.title draft_title,pd.context draft_context,pd.synced,CONCAT(u.fname," ",u.lname) full_name,u.avatar_id,pd.characters,pd.words');
    }

    public static function post_draft_update($data)
    {
        $date = Date::g('Y-m-d H:i:s');
        self::$db->where('post_id', $data['post_id']);
        return self::$db->update(self::post_draft, [
            'title' => $data['title'],
            'context' => $data['context'],
            'update_date' => $date,
            'synced' => 0,
            'characters' => !empty($data['characters']) ? $data['characters'] : 0,
            'words' => !empty($data['words']) ? $data['words'] : 0,
        ]);
    }

    public static function post_draft_insert($data)
    {
        $date = Date::g('Y-m-d H:i:s');
        return self::$db->insert(self::post_draft, [
            'post_id' => $data['post_id'],
            'title' => $data['title'],
            'context' => $data['context'],
            'update_date' => $date,
            'synced' => 0,
            'characters' => !empty($data['characters']) ? $data['characters'] : 0,
            'words' => !empty($data['words']) ? $data['words'] : 0,
        ]);
    }

    public static function update_status($post_id, $status)
    {
        $status = ($status === self::publish_status) ? self::publish_status : self::draft_status;
        self::$db->where('post_id', $post_id);
        return self::$db->update(self::post, [
            'status' => $status,
        ]);
    }

    public static function update_setting($post_id, $data)
    {
        $data['comment_status'] = ($data['comment_status'] === self::open_status) ? self::open_status : self::closed_status;
        self::$db->where('post_id', $post_id);
        return self::$db->update(self::post, [
            'comment_status' => $data['comment_status'],
        ]);
    }

    public static function post_draft_update_synced($post_id, $sync = 0)
    {
        self::$db->where('post_id', $post_id);
        return self::$db->update(self::post_draft, [
            'synced' => $sync,
        ]);
    }

    public static function update_category($cat_id, $post_id)
    {
        self::$db->where('post_id', $post_id);
        return self::$db->update(self::post, [
            'cat_id' => $cat_id,
        ]);
    }

    public static function update_publish_post($post_id)
    {
        $post = self::post_draft_fetch_by_id($post_id);
        if (!$post)
            return false;

        $date = !empty($post['publish_date']) ? $post['publish_date'] : Date::g('Y-m-d H:i:s');
        self::$db->where('post_id', $post_id);
        return self::$db->update(self::post, [
            'title' => $post['draft_title'],
            'context' => $post['draft_context'],
            'publish_date' => $date,
            'status' => self::publish_status,
            'characters' => !empty($post['characters']) ? $post['characters'] : 0,
            'words' => !empty($post['words']) ? $post['words'] : 0,
        ]);
    }

    public static function post_history_insert($data, $status)
    {
        $date = Date::g('Y-m-d H:i:s');
        return self::$db->insert(self::post_history, [
            'post_id' => $data['post_id'],
            'user_id' => User::get('user_id'),
            'title' => $data['title'],
            'context' => $data['context'],
            'insert_date' => $date,
            'status' => $status,
        ]);
    }

    public static function fetch_history_by_post_id($post_id, $limit = null)
    {
        self::$db->orderBy('insert_date', 'DESC');
        self::$db->where('post_id', $post_id);
        return self::$db->get(self::post_history, $limit);
    }

    public static function delete_all_history($post_id)
    {
        self::$db->where('post_id', $post_id);
        return self::$db->delete(self::post_history);
    }

    public static function delete_history_by_id($ph_id)
    {
        self::$db->where('ph_id', $ph_id);
        return self::$db->delete(self::post_history);
    }

    public static function where_post_type($post_type)
    {
        $post_type = !empty($post_type) && $post_type === self::page_type ? self::page_type : self::post_type;
        self::$db->where('p.post_type', $post_type);
    }

    public static function update($data)
    {
        $date = Date::g('Y-m-d H:i:s');
        self::$db->where('post_id', $data['post_id']);
        return self::$db->update(self::post, [
            'summary' => !empty($data['summary']) ? $data['summary'] : null,
            'post_key' => !empty($data['post_key']) ? $data['post_key'] : null,
            'image_id' => !empty($data['image']) ? $data['image'] : null,
            'update_date' => $date,
            'time_tracking' => self::$db->inc($data['time']),
        ]);
    }

    public static function getInfoPost($post)
    {
        $placeHolderPost = Url::file('resources/image-placeholder.jpg');
        $placeHolderAvatar = Url::file('resources/avatar.png');

        if (empty($post)) return $post;
        $post['tags'] = self::fetch_tags_by_post_id($post['post_id']);
        if (isset($post['cat_id']))
            $post['category'] = CategoryModel::fetch_by_id($post['cat_id']);
        else
            $post['category'] = null;


        $post['post_key'] = empty($post['post_key']) ? HelperString::replaceSpace($post['title']) : $post['post_key'];
        $post['approx_insert_date'] = Date::j('l d F Y (H:i)', $post['insert_date']);
        $post['publish_date'] = Date::j('Y/m/d H:i', $post['publish_date']);
        $file = FileModel::fetch_by_id($post['image_id']);
        $post['image'] = Url::upload($file, $placeHolderPost);
        $post['thumb_128'] = Url::thumb($file, 128, $placeHolderPost);
        $post['thumb_512'] = Url::thumb($file, 512, $placeHolderPost);
        $post['avatar'] = Url::thumb($post['avatar_id'], 128, $placeHolderAvatar);
        $post['url'] = Url::app().'post/' . $post['post_id'] . '/' . $post['post_key'];
        return $post;
    }

    public static function fetch_tags_by_post_id($post_id)
    {
        self::$db->join(self::tag . ' t', 't.tag_id=pt.tag_id');
        self::$db->where('pt.post_id', $post_id);
        return self::$db->get(self::post_tag . ' pt');
    }

    public static function delete($post_id)
    {
        self::$db->where('post_id', $post_id);
        return self::$db->delete(self::post);
    }

    public static function fetch_by_id($post_id)
    {
        self::$db->join(self::user . ' u', 'u.user_id=p.user_id', 'LEFT');
        self::$db->where('p.post_id', $post_id);
        return self::$db->getOne(self::post . ' p', 'p.*,CONCAT(u.fname," ",u.lname) full_name,u.avatar_id');
    }

    public static function fetch_by_ids($posts)
    {
        if (empty($posts) || !is_array($posts))
            return [];

        self::$db->orderBy('p.post_id', 'ASC', $posts);
        self::$db->join(self::user . ' u', 'u.user_id=p.user_id', 'LEFT');
        self::$db->where('p.post_id', $posts, 'IN');
        return self::$db->get(self::post . ' p', null, 'p.*,CONCAT(u.fname," ",u.lname) full_name,u.avatar_id');
    }

    public static function fetch_all($limit = null, $isCount = false)
    {
        self::$db->join(self::user . ' u', 'u.user_id=p.user_id', 'LEFT');
        self::$db->orderBy('p.insert_date', 'DESC');
        $result = self::$db->get(self::post . ' p', $limit, 'p.post_id,p.title,p.summary,p.status,p.user_id,p.image_id,p.post_key,p.insert_date,p.update_date,p.publish_date,p.visits,CONCAT(u.fname," ",u.lname) full_name,u.username,u.avatar_id');
        if ($isCount) return self::$db->count;
        return $result;
    }

    public static function fetch_all_posts($limit = null, $isCount = false)
    {
        self::$db->join(self::user . ' u', 'u.user_id=p.user_id', 'LEFT');
        self::$db->join(self::post_draft . ' pd', 'pd.post_id=p.post_id', 'LEFT');
        self::$db->orderBy('p.insert_date', 'DESC');
        $result = self::$db->get(self::post . ' p', $limit, 'p.post_id,pd.title,p.summary,p.status,p.user_id,p.image_id,p.post_key,p.insert_date,p.update_date,p.publish_date,p.visits,CONCAT(u.fname," ",u.lname) full_name,u.username,u.avatar_id');
        if ($isCount) return self::$db->count;
        return $result;
    }

    public static function fetcher($ids = [], $option = [])
    {
        $limit = isset($option['limit']) ? $option['limit'] : null;
        $isCount = isset($option['count']) ? $option['count'] : false;

        self::buildWhereForFetcher($ids, $option);

        $columns = 'p.post_id,p.title,p.summary,p.status,p.user_id,p.image_id,p.post_key,p.insert_date,p.update_date,p.publish_date,p.cat_id,p.characters,p.words,p.visits,u.username,u.avatar_id';

        self::$db->groupBy($columns);
        self::$db->join(self::user . ' u', 'u.user_id=p.user_id', 'LEFT');
        self::$db->join(self::post_tag . ' pt', 'pt.post_id=p.post_id', 'LEFT');
        self::$db->join(self::tag . ' t', 't.tag_id=pt.tag_id', 'LEFT');
        $result = self::$db->get(self::post . ' p', $limit, $columns . ',CONCAT(u.fname," ",u.lname) full_name');
        if ($isCount) return self::$db->count;
        return $result;
    }

    private static function buildWhereForFetcher($ids, $option)
    {
        $isAll = $ids === 'ALL' || $ids === 'all' || $ids === '*';
        $key = isset($option['key']) ? $option['key'] : 'post_id';
        $where = isset($option['where']) ? $option['where'] : 'IN';
        $default_order = (!$isAll && $key === 'post_id') ? 'post_id' : 'publish_date';
        $default_order_type = (!$isAll && $key === 'post_id') ? 'ASC' : 'DESC';
        $order = isset($option['order']) ? $option['order'] : $default_order;
        $order = 'p.' . $order;
        $order_type = isset($option['order_type']) ? $option['order_type'] : $default_order_type;

        if (!$isAll) {
            if (HelperString::has(strtoupper($where), 'IN'))
                $ids = is_array($ids) ? $ids : [$ids];
            if (is_array($key)) {
                $keys = [];
                $values = [];
                foreach ($key as $k) {
                    $keys[] = ($k === 'tag_id' || $k === 'tag_name') ? 't.' . $k : 'p.' . $k;
                    $values[] = $ids;
                }
                $key = implode(' ' . $where . ' ? OR ', $keys);
                $key = '(' . $key;
                $key .= ' ' . $where . ' ?)';
            } else {
                $key = ($key === 'tag_id' || $key === 'tag_name') ? 't.' . $key : 'p.' . $key;
                $values = $ids;
            }

            self::$db->where($key, $values, $where);
        }

        if (strtoupper($where) === 'IN')
            self::$db->orderBy($order, $order_type, $ids);
        else
            self::$db->orderBy($order, $order_type);
    }

    public static function insert_tags($post_id, $tags)
    {

        self::delete_tags_by_post_id($post_id);
        if (empty($tags))
            return;
        foreach ($tags as $t) {
            if (is_array($t))
                $t = $t['tag_name'];

            $tag = TagModel::fetch_by_name($t);


            if (empty($tag)) {
                $tag_id = TagModel::insert($t);
            } else {
                $tag_id = $tag['tag_id'];
            }
            if ($tag_id && $tag_id > 0) self::insert_tag($post_id, $tag_id);
        }
    }

    private static function delete_tags_by_post_id($post_id)
    {
        self::$db->where('post_id', $post_id);
        return self::$db->delete(self::post_tag);
    }

    public static function insert_tag($post_id, $tag_id)
    {
        return self::$db->insert(self::post_tag, [
            'post_id' => $post_id,
            'tag_id' => $tag_id,
        ]);
    }

    public static function fetch_all_tags($limit = null, $isCount = false)
    {
        self::$db->join(self::tag . ' t', 't.tag_id=pt.tag_id');
        self::$db->groupBy('t.tag_id,t.tag_name');

        $tags = self::$db->get(self::post_tag . ' pt', $limit, 't.tag_id,t.tag_name');
        return $isCount ? self::$db->count : $tags;
    }

    public static function search_tag_name($keyword, $useJoin = false)
    {
        if (!empty($keyword)) {
            if ($useJoin) {
                self::$db->join(self::post_tag . ' pt', 'p.post_id=pt.post_id');
                self::$db->join(self::tag . ' t', 't.tag_id=pt.tag_id');
                self::$db->groupBy('p.post_id');
            }

            $keyword = '%' . $keyword . '%';
            self::$db->where('t.tag_name LIKE ?', [$keyword]);
        }
    }

    public static function where_tag_name($tag)
    {
        if(!empty($tag))
            self::$db->where('t.tag_name', $tag);
    }

    public static function getHashId($length = 6)
    {
        $id = HelperString::generateLowRandom($length);
        $length++;
        if (self::fetch_by_hash_id($id))
            $id = self::getHashId($length);
        return $id;
    }

    public static function fetch_by_hash_id($hash_id)
    {
        self::$db->where('hash_id', $hash_id);
        return self::$db->getOne(self::post);
    }

    public static function fetch_images($hash_id)
    {
        $package = AppProvider::get('package-name');
        self::$db->where('app', $package);
        self::$db->where('file_group', 'post');
        self::$db->where('file_access', $hash_id);
        return self::$db->get(self::file, null, 'file_id,CONCAT(file_path,file_name) path');
    }

    public static function where_status($status)
    {
        if (!is_null($status) && $status != 'all')
            self::$db->where('p.status', $status);
    }

    public static function sort($sort)
    {
        if (!empty($sort) && isset($sort['field']) && !empty($sort['field'])) {
            if ($sort['field'] === 'approx_insert_date')
                $sort['field'] = 'insert_date';

            self::$db->orderBy($sort['field'], $sort['type']);
        }
    }

    public static function search_keyword($keyword)
    {
        if (!empty($keyword)) {
            $p = '%' . $keyword . '%';
            self::$db->where('(p.title LIKE ? OR p.summary LIKE ?)', [$p, $p]);
        }
    }

    public static function fetch_image($file_id, $hash_id)
    {
        $package = AppProvider::get('package-name');
        self::$db->where('app', $package);
        self::$db->where('file_group', 'post');
        self::$db->where('file_access', $hash_id);
        self::$db->where('file_id', $file_id);
        return self::$db->getOne(self::file);
    }

    public static function fetch_total_time_tracking()
    {
        $result = self::$db->getOne(self::post . ' p', 'SUM(p.time_tracking) time_tracking');
        return (!empty($result)) ? $result['time_tracking'] : 0;
    }

    public static function fetch_total_words()
    {
        $result = self::$db->getOne(self::post_draft . ' pd', 'SUM(pd.words) words');
        return (!empty($result)) ? $result['words'] : 0;
    }

    public static function where_search($query)
    {
        if (!is_null($query)) {
            $p = '%' . $query . '%';
            self::$db->where('p.title LIKE ? OR p.summary LIKE ?', [$p, $p]);
        }

    }

    public static function fetch_all_tags_by_post_id($post_id)
    {
        return [];
    }

    public static function fetch_most_visited($limitMostVisited)
    {
        return [];
    }

    public static function hot_tags($limit = null)
    {
        self::$db->join(self::tag . ' t', 't.tag_id=pt.tag_id', 'INNER');
        self::$db->orderBy('count_use', 'DESC');
        self::$db->groupBy('t.tag_id,t.tag_name');
        return self::$db->get(self::post_tag . ' pt', $limit, 't.tag_id,t.tag_name,count(t.tag_id) count_use');
    }

    public static function fetch_author_info($post_id)
    {
        self::$db->join(self::post . ' p', 'p.user_id=u.user_id', 'INNER');
        self::$db->where('p.post_id', $post_id);
        return self::$db->getOne(self::user . ' u', 'u.user_id,u.username,u.avatar_id,CONCAT_WS(" ",u.fname,u.lname) full_name');
    }

    public static function where_author($username)
    {
        if (!empty($username)) {
            self::$db->where('u.username', $username);
        }
    }
}
