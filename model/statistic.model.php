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

class StatisticModel extends PaperDatabase
{

    public static function insert($post_id, $data)
    {
        self::startTransaction();

        $insert_id = self::$db->insert(self::statistic, [
            'post_id' => $post_id,
            'visitors' => 1,
            'visits' => 1,
            'insert_date' => Date::g('Y-m-d'),
            'json_data' => isset($data['json_data']) ? $data['json_data'] : null,
        ]);

        self::$db->where('post_id', $post_id);
        $status = self::$db->update(self::post_type, [
            'visits' => self::$db->inc(),
            'visitors' => self::$db->inc(),
        ]);
        if ($status && $insert_id)
            self::commit();
        return $insert_id;
    }

    private static function fetch_today_by_post_id($post_id)
    {
        self::$db->where('s.insert_date', Date::g('Y-m-d'));
        self::$db->where('s.post_id', $post_id);
        return self::$db->getOne(self::statistic . ' s');
    }

    public static function update_visits($post_id)
    {
        self::startTransaction();

        self::$db->where('insert_date', Date::g('Y-m-d'));
        self::$db->where('post_id', $post_id);
        $isOK = self::$db->update(self::statistic, [
            'visits' => self::$db->inc(),
        ]);

        self::$db->where('post_id', $post_id);
        $status = self::$db->update(self::post_type, [
            'visits' => self::$db->inc(),
        ]);
        if ($status && $isOK)
            self::commit();

        return $isOK;
    }

    public static function update_stats($post_id, $data)
    {
        self::startTransaction();

        self::$db->where('insert_date', Date::g('Y-m-d'));
        self::$db->where('post_id', $post_id);
        $isOK = self::$db->update(self::statistic, [
            'visitors' => self::$db->inc(),
            'visits' => self::$db->inc(),
            'json_data' => $data['json_data'],
        ]);

        self::$db->where('post_id', $post_id);
        $status = self::$db->update(self::post_type, [
            'visits' => self::$db->inc(),
            'visitors' => self::$db->inc(),
        ]);
        if ($status && $isOK)
            self::commit();

        return $isOK;
    }

    public static function analysisJson($stats, $saved, $jsonOut = true)
    {
        $result = [];

        if (!empty($saved)) {
            $saved = HelperString::decodeJson($saved, true);
            foreach ($stats as $key => $arr) {
                foreach ($arr as $k => $V) {
                    $saved[$key][$k] = isset($saved[$key][$k]) ? intval($saved[$key][$k]) + 1 : 1;
                }
                $result = $saved;
            }
        } else {
            $result = $stats;
        }

        if ($jsonOut)
            return HelperString::encodeJson($result);

        return $result;
    }

    public static function visit($post_id)
    {
        $data = self::createStatsObject();
        $post = PostModel::fetch_by_id($post_id);
        if (empty($post)) return;

        $todayRow = self::fetch_today_by_post_id($post_id);
        $data['json_data'] = self::analysisJson($data, $todayRow['json_data']);

        if (!empty($todayRow)) {
            if (self::is_visited($post_id)) {
                self::update_visits($post_id);
            } else {
                self::update_stats($post_id, $data);
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
        self::$db->update(self::post_type . ' p', [
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
        $endToday = strtotime(Date::g('Y-m-d 0:0:0', '+1 days'));
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
        $rows = self::$db->get(self::statistic . ' s');
        if (empty($rows)) return [null,null];

        $result = [];
        foreach ($rows as $r) {
            $json = json_decode($r['json_data'], true);
            foreach ($json as $k => $item) {
                if ($k != 'device') continue;
                foreach ($item as $device => $count) {
                    $result[$device] = isset($result[$device]) ? $result[$device] + $count : $count;
                }
            }
        }
        $total = 0;
        foreach ($result as $i) {
            $total += $i;
        }

        return [$result, $total];
    }

    public static function calc_device_percents($stats, $total)
    {
        if (empty($stats)) return [];
        $result = [];
        foreach ($stats as $k => $s) {
            $p  = $s * 100 / (float) $total;
            $p = floatval( number_format($p, 1));
            $result[] = ['device' => $k, 'percent' => $p];
        }
        return $result;
    }

    public static function createStatsObject()
    {
        $b = new Browser();
        return [
            'browser' => [
                $b->getBrowser() => 1
            ],
            'platform' => [
                $b->getPlatform() => 1
            ],
            'device' => [
                $b->getDevice() => 1
            ],
        ];
    }

}