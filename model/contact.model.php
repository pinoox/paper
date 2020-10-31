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

class ContactModel extends PaperDatabase
{
    /** @const seen,unseen */
    const status_seen = "seen";
    const status_unseen = "unseen";


    public static function insert($data)
    {
        return self::$db->insert(self::contact, [
            'full_name' => $data['full_name'],
            'mobile' => $data['mobile'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => self::unseen,
            'insert_date' => Date::g('Y-m-d H:i:s'),
        ]);
    }

    public static function delete($contact_id)
    {
        self::$db->where('contact_id', $contact_id);
        return self::$db->delete(self::contact);
    }

    public static function fetch_by_id($contact_id)
    {
        self::$db->where('contact_id', $contact_id);
        return self::$db->getOne(self::contact);
    }

    public static function fetch_all($status, $limit = null, $isCount = false)
    {
        if (!is_null($status))
            self::$db->where('c.status', $status);

        self::$db->orderBy('c.insert_date', 'DESC');
        $result = self::$db->get(self::contact . ' c', $limit);
        if ($isCount) return self::$db->count;
        return $result;
    }

    public static function where_search($keyword)
    {
        if (empty($keyword)) return;
        if ($keyword == rlang('panel.seen')) $status = self::status_seen;
        else if ($keyword == rlang('panel.unseen')) $status = self::status_unseen;
        else $status = false;

        $k = '%' . $keyword . '%';
        if ($status !== false)
            self::$db->where('(c.`status` = ? )', [$status]);
        else
            self::$db->where('(c.full_name LIKE ? OR c.subject LIKE ? OR c.message LIKE ?)', [$k, $k, $k]);
    }

    public static function update_status($contact_id, $status)
    {
        self::$db->where('contact_id', $contact_id);
        return self::$db->update(self::contact, [
            'status' => $status
        ]);
    }

    public static function fetch_stats()
    {
        $total = self::fetch_all(null, null, true);
        $seen = self::fetch_all(self::status_seen, null, true);
        $unseen = self::fetch_all(self::status_unseen, null, true);

        return [
            'total' => $total,
            'seen' => $seen,
            'unseen' => $unseen,
        ];
    }


    public static function where_status($status)
    {
        if (!is_null($status))
            self::$db->where('c.status', $status);
    }

    public static function sort($sort)
    {
        if (!empty($sort) && isset($sort['field']) && !empty($sort['field'])) {
            if ($sort['field'] === 'approx_insert_date')
                $sort['field'] = 'insert_date';

            self::$db->orderBy($sort['field'], $sort['type']);
        }
    }
}
