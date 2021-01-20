<?php
/**
 *      ****  *  *     *  ****  ****  *    *
 *      *  *  *  * *   *  *  *  *  *   *  *
 *      ****  *  *  *  *  *  *  *  *    *
 *      *     *  *   * *  *  *  *  *   *  *
 *      *     *  *    **  ****  ****  *    *
 * @author   Pinoox
 * @link https://www.pinoox.com/
 * @license  https://opensource.org/licenses/MIT MIT License
 */

namespace pinoox\app\com_pinoox_paper\controller\api\panel\v1;

use pinoox\app\com_pinoox_paper\component\Helper;
use pinoox\app\com_pinoox_paper\model\CommentModel;
use pinoox\app\com_pinoox_paper\model\ContactModel;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\app\com_pinoox_paper\model\PostStatisticModel;
use pinoox\app\com_pinoox_paper\model\StatisticModel;
use pinoox\component\Date;
use pinoox\component\Request;
use pinoox\component\Response;


class DashboardController extends MasterConfiguration
{

    public function getTime()
    {
        $datetime = Helper::getLocaleDate();
        Response::json($datetime);
    }

    public function getCountNotifies()
    {
        $comments = CommentModel::fetch_all(CommentModel::status_pending, null, true);
        $contacts = ContactModel::fetch_all(ContactModel::status_unseen, null, true);

        Response::json(['comments' => $comments, 'contacts' => $contacts]);
    }

    public function getStats()
    {
        $today = Date::g('Y-m-d');
        $yesterday = Date::g('Y-m-d', '-1 DAY');

        $visitStats = StatisticModel::fetch_stats($today);
        $statsProgress = StatisticModel::calc_stats_progress_than_yesterday($visitStats, $yesterday);
        $commentStats = CommentModel::fetch_stats();
        $words = PostModel::fetch_total_words();
        $timeTracking = $this->getTimeTrackingRound();

        Response::json([
            'words' => $words,
            'timeTracking' => $timeTracking,
            'stats' => $visitStats,
            'progress' => $statsProgress,
            'commentStats' => $commentStats
        ]);
    }

    public function getDevices()
    {
        list($devices, $total) = StatisticModel::fetch_devices();
        $percents = StatisticModel::calc_device_percents($devices, $total);
        $data = [
            'percents' => array_column($percents, 'percent'),
            'labels' => array_column($percents, 'device'),
            'total' => $total,
        ];

        Response::json($data);
    }

    public function getMonthly()
    {
        $days = 6;

        $rangeDate = Date::betweenGDate(Date::g('Y-m-d', '-' . $days . ' days'), Date::g('Y-m-d', '+1 days'));
        $rangeDate = array_map(function ($d) {
            return Helper::getLocaleDate('F d', $d);
        }, $rangeDate);

        $rangeDate[count($rangeDate) - 1] = rlang('post.today');
        $rangeDate[count($rangeDate) - 2] = rlang('post.yesterday');

        //visits
        $visits = StatisticModel::fetch_visits($days);
        $visitsSeries = Helper::createRangeDate(@$visits['series'], $days, true);

        //visitors
        $visitors = StatisticModel::fetch_visitors($days);
        $visitorsSeries = StatisticModel::createRangeData($visitors['series'], $days, true);

        $result = [
            [
                'name' => rlang('post.visits'),
                'data' => $visitsSeries
            ],
            [
                'name' => rlang('post.visitors'),
                'data' => $visitorsSeries
            ],
        ];

        Response::json(['series' => $result, 'date' => $rangeDate]);
    }

    private function getTimeTrackingRound()
    {
        $seconds = PostModel::fetch_total_time_tracking();
        return Helper::timePrint($seconds);
    }
}
