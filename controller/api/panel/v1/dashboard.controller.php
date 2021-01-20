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

    private function getTimeTrackingRound()
    {
        $seconds = PostModel::fetch_total_time_tracking();
        return Helper::timePrint($seconds);
    }
}
