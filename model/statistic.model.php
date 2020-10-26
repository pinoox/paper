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


use pinoox\component\Cookie;
use pinoox\component\Date;
use pinoox\component\HelperHeader;
use pinoox\component\HelperString;

class StatisticModel extends PaperDatabase
{

    public static function insert($post_id, $data)
    {
        self::startTransaction();

        $insert_id = self::$db->insert(self::statistic, [
            'post_id' => $post_id,
            'user_agent' => HelperHeader::getUserAgent(),
            'visitors' => 1,
            'visits' => 1,
            'insert_date' => Date::g('Y-m-d'),
            'json_data' => isset($data['json_data']) ? $data['json_data'] : null,
        ]);

        self::$db->where('post_id', $post_id);
        $status = self::$db->update(self::post, [
            'visits' => self::$db->inc(),
            'visitors' => self::$db->inc(),
        ]);
        if ($status && $insert_id)
            self::commit();
        return $insert_id;
    }

    private static function fetch_today_by_post_id($post_id)
    {
        self::$db->where('DATE(s.insert_date)', Date::g('Y-m-d'));
        self::$db->where('s.post_id', $post_id);
        return self::$db->getOne(self::statistic . ' s');
    }

    public static function update_visits($post_id)
    {
        self::startTransaction();

        self::$db->where('post_id', $post_id);
        $isOK = self::$db->update(self::statistic, [
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

    public static function add_stats($post_id , $data)
    {
        self::startTransaction();

        self::$db->where('post_id', $post_id);
        $isOK = self::$db->update(self::statistic, [
            'visitors' => self::$db->inc(),
            'visits' => self::$db->inc(),
            'json_data' => $data['json_data'],
        ]);

        self::$db->where('post_id', $post_id);
        $status = self::$db->update(self::post, [
            'visits' => self::$db->inc(),
            'visitors' => self::$db->inc(),
        ]);
        if ($status && $isOK)
            self::commit();

        return $isOK;
    }

    public static function createOrUpdateJson($stats, $saved, $jsonOut = true)
    {
        $saved = HelperString::decodeJson($saved, true);
        $result = [];
        foreach ($stats as $key => $s) {
            if (isset($saved[$key])) {
                $saved[$key][$s] = isset($saved[$key][$s]) ? intval($saved[$key][$s]) + 1 : 1;
            } else {
                $saved[$key] = [
                    $s => 1
                ];
            }
            $result = $saved;
        }
        if ($jsonOut)
            return HelperString::encodeJson($result);

        return $result;
    }

    public static function visit($post_id, $data)
    {
        $post = PostModel::fetch_by_id($post_id);
        if (empty($post)) return;

        $todayRow = self::fetch_today_by_post_id($post_id);
        $data['json_data'] = self::createOrUpdateJson($data, $todayRow['json_data']);

        if (!empty($todayRow)) {
            if (self::is_visited($post_id)) {
                self::update_visits($post_id);
            } else {
                self::add_stats($post_id, $data);
            }
        } else {
            self::insert($post_id, $data);
        }
        self::set_visited($post_id);
    }

    private static function update_visitors($post_id)
    {
        self::$db->where('s.post_id', $post_id);
        self::$db->update(self::statistic . ' s', [
            'visitors' => self::$db->inc()
        ]);

        self::$db->where('p.post_id', $post_id);
        self::$db->update(self::post . ' p', [
            'visitors' => self::$db->inc()
        ]);
    }

    public static function is_visited($post_id)
    {
        $value = Cookie::get('visited_' . $post_id);
        if (empty($value)) return false;

        return true;
    }

    public static function set_visited($post_id)
    {
        $endToday = mktime(24, 0, 0);
        Cookie::set('visited_' . $post_id, '1', $endToday);
    }

    public static function fetch_visits($post_id, $days = 7)
    {
        $fromDate = Date::g('Y-m-d', '-' . $days . ' DAY');
        self::$db->where('s.insert_date', $fromDate, '>=');
        self::$db->where('s.post_id', $post_id);
        self::$db->groupBy('date');
        $result = self::$db->get(self::statistic . ' s', null, 'DATE_FORMAT(s.insert_date, "%Y-%m-%d") AS date, SUM(s.visits) value');
        if (empty($result)) return null;

        $total = 0;
        foreach ($result as $item)
            $total += $item['value'];

        return ['total' => $total, 'series' => $result];
    }

    public static function fetch_visitors($post_id, $days = 7)
    {
        $fromDate = Date::g('Y-m-d', '-' . $days . ' DAY');
        self::$db->where('s.insert_date', $fromDate, '>=');
        self::$db->where('s.post_id', $post_id);
        self::$db->groupBy('date');
        $result = self::$db->get(self::statistic . ' s', null, 'DATE_FORMAT(s.insert_date, "%Y-%m-%d") AS date, COUNT(s.stat_id) value');
        if (empty($result)) return null;

        $total = 0;
        foreach ($result as $item)
            $total += $item['value'];

        return ['total' => $total, 'series' => $result];
    }

    public static function createRangeData($stats, $days, $onlyValue = false)
    {
        $tomorrow = Date::g('Y-m-d', '+1 days');
        $fromDate = Date::g('Y-m-d', '-' . $days . ' days');
        $range = Date::betweenGDate($fromDate, $tomorrow);

        $rangeDate = [];
        foreach ($range as $r) {
            $isEmpty = true;
            if (!empty($stats)) {
                foreach ($stats as $s) {
                    if ($s['date'] == $r) {
                        $isEmpty = false;
                        $rangeDate[] = ['date' => $r, 'value' => $s['value']];
                        break;
                    }
                }
            }
            if ($isEmpty)
                $rangeDate[] = ['date' => $r, 'value' => 0];
        }

        if ($onlyValue)
            $rangeDate = array_column($rangeDate, 'value');

        return $rangeDate;
    }

    public static function fetch_devices($post_id)
    {
        self::$db->where('s.post_id', $post_id);
        self::$db->groupBy('s.device');
        return self::$db->get(self::statistic . ' s', null, 'COUNT(s.device) count,s.device');
    }

    public static function fetch_total_devices($post_id)
    {
        self::$db->where('s.post_id', $post_id);
        $result = self::$db->getOne(self::statistic . ' s', 'COUNT(s.device) count');
        return $result['count'];
    }

    public static function calc_percents($stats, $total)
    {
        if (empty($stats)) return [];

        $result = [];
        foreach ($stats as $s) {
            $s['percent'] = round($s['count'] * 100 / $total);
            $result[] = $s;
        }

        return $result;
    }

}