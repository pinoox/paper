<?php
/**
 *      ****  *  *     *  ****  ****  *    *
 *      *  *  *  * *   *  *  *  *  *   *  *
 *      ****  *  *  *  *  *  *  *  *    *
 *      *     *  *   * *  *  *  *  *   *  *
 *      *     *  *    **  ****  ****  *    *
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       pinoox.com
 * @copyright  pinoox
 */

namespace pinoox\app\com_pinoox_paper\component;

use pinoox\component\Date;
use pinoox\component\Lang;

class Helper
{
    public static function getLocaleDate($format = null, $date = 'now')
    {
        $format = is_null($format) ? 'l d F Y (H:i)' : $format;

        if (Lang::current() == 'en') {
            $format = str_replace('F', 'M', $format);//month
            $format = str_replace('l', 'D', $format);// week
            return Date::g($format, $date);
        } else {
            return Date::j($format, $date);
        }
    }

    public static function timePrint($seconds)
    {
        $seconds = !empty($seconds) ? $seconds : 0;
        $seconds = intval($seconds);
        $base = 999;
        $minute = 60;
        $hour = 60 * 60;
        $day = 24 * $hour;
        $month = 30 * $day;
        $year = 12 * $month;

        if ($base >= $seconds) {
            $value = $seconds;
            $type = 'sec';
        } else if (($base * $minute) >= $seconds) {
            $value = floor($seconds / $minute);
            $type = 'minute';
        } else if (($base * $hour) >= $seconds) {
            $value = floor($seconds / $hour);
            $type = 'hour';
        } else if (($base * $day) >= $seconds) {
            $value = floor($seconds / $day);
            $type = 'day';
        } else if (($base * $month) >= $seconds) {
            $value = floor($seconds / $month);
            $type = 'month';
        } else {
            $value = floor($seconds / $year);
            $type = 'year';
        }

        return ['value' => $value, 'type' => $type];
    }

    public static function createRangeDate($stats, $days, $onlyValue = false)
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


}