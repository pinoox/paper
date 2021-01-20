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


use pinoox\app\com_pinoox_paper\component\Browser;
use pinoox\component\Cookie;
use pinoox\component\Date;
use pinoox\component\HelperHeader;
use pinoox\component\HelperString;
use pinoox\component\Response;

class PostStatisticModel extends PaperDatabase
{
    public static function insert($post)
    {
        self::startTransaction();

        $insert_id = self::$db->insert(self::post_statistic, [
            'post_id' => $post['post_id'],
            'post_type' => $post['post_type'],
            'visits' => 1,
            'insert_date' => Date::g('Y-m-d'),
        ]);

        self::$db->where('post_id', $post['post_id']);
        $status = self::$db->update(self::post, [
            'visits' => self::$db->inc(),
        ]);
        if ($status && $insert_id)
            self::commit();
        return $insert_id;
    }

    private static function fetch_today_by_post_id($post_id)
    {
        self::$db->where('ps.insert_date', Date::g('Y-m-d'));
        self::$db->where('ps.post_id', $post_id);
        return self::$db->getOne(self::post_statistic . ' ps');
    }

    public static function update_visits($post_id)
    {
        self::startTransaction();

        self::$db->where('insert_date', Date::g('Y-m-d'));
        self::$db->where('post_id', $post_id);
        $isOK = self::$db->update(self::post_statistic, [
            'visits' => self::$db->inc(),
        ]);

        self::$db->where('post_id', $post_id);
        $status = self::$db->update(self::post, [
            'visits' => self::$db->inc(),
        ]);
        if ($status && $isOK)
            self::commit();

        return $isOK;
    }

    public static function update_stats($post_id)
    {
        self::startTransaction();

        self::$db->where('insert_date', Date::g('Y-m-d'));
        self::$db->where('post_id', $post_id);
        $isOK = self::$db->update(self::post_statistic, [
            'visits' => self::$db->inc(),
        ]);

        self::$db->where('post_id', $post_id);
        $status = self::$db->update(self::post, [
            'visits' => self::$db->inc(),
        ]);
        if ($status && $isOK)
            self::commit();

        return $isOK;
    }
    
    public static function visit($post_id)
    {
        $post = PostModel::fetch_by_id($post_id);
        if (empty($post)) return;

        $todayRow = self::fetch_today_by_post_id($post_id);

        if (!empty($todayRow)) {
            self::update_stats($post_id);
        } else {
            self::insert($post);
        }
    }

    public static function fetch_visits($post_id, $days = null)
    {
        if (!is_null($post_id))
            self::$db->where('ps.post_id', $post_id);

        self::$db->where('ps.post_type', PostModel::post_type);

        if (!is_null($days)) {
            $fromDate = Date::g('Y-m-d', '-' . $days . ' DAY');
            self::$db->where('ps.insert_date', $fromDate, '>=');
        }
        self::$db->groupBy('date');
        $result = self::$db->get(self::post_statistic . ' ps', null, 'DATE_FORMAT(ps.insert_date, "%Y-%m-%d") AS date, SUM(ps.visits) value');
        if (empty($result)) return null;

        $total = 0;
        foreach ($result as $item)
            $total += $item['value'];

        return ['total' => $total, 'series' => $result];
    }

    public static function fetch_posts_stats($date)
    {
        self::$db->where('ps.insert_date', $date, '=');
        return self::$db->getOne(self::post_statistic . ' ps', 'IFNULL(SUM(ps.visits), 0) visits');
    }

    public static function calc_post_stats_progress_than_yesterday($stats, $yesterday)
    {
        self::$db->where('ps.insert_date', $yesterday, '=');
        $yesterdayStats = self::$db->getOne(self::post_statistic . ' ps', 'IFNULL(SUM(ps.visits), 0) visits');

        $diffVisits = $stats['visits'] - $yesterdayStats['visits'];

        $yesterdayStats['visits'] = $yesterdayStats['visits'] <= 0 ? 1 : $yesterdayStats['visits'];

        $visitsProgress = round($diffVisits * 100 / $yesterdayStats['visits']);

        return ['visits' => $visitsProgress];
    }

    public static function has_stats($post_id)
    {
        self::$db->where('ps.post_id', $post_id);
        $result = self::$db->getOne(self::post_statistic . ' ps');
        return !empty($result);
    }

}