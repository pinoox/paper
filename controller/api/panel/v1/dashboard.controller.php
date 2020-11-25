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
use pinoox\app\com_pinoox_paper\model\StatisticModel;
use pinoox\component\Date;
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

        $postStats = StatisticModel::fetch_posts_stats($today);
        $postStatsProgress = StatisticModel::calc_post_stats_progress_than_yesterday($postStats, $yesterday);
        $commentStats = CommentModel::fetch_stats();
        $words = PostModel::fetch_total_words();
        $timeTracking = $this->getTimeTrackingRound();

        Response::json([
            'words' => $words,
            'timeTracking' => $timeTracking,
            'postStats' => $postStats,
            'postProgress' => $postStatsProgress,
            'commentStats' => $commentStats
        ]);
    }

    private function getTimeTrackingRound()
    {
        $seconds = PostModel::fetch_total_time_tracking();
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
}
