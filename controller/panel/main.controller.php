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
namespace pinoox\app\com_pinoox_paper\controller\panel;

use pinoox\app\com_pinoox_paper\model\ArticleModel;
use pinoox\app\com_pinoox_paper\model\CommentModel;
use pinoox\app\com_pinoox_paper\model\ContactModel;
use pinoox\component\HelperHeader;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Router;
use pinoox\model\UserModel;

class MainController extends MasterConfiguration
{

    public function _main()
    {
        $contactStats = ContactModel::fetch_stats();
        $commentStats = CommentModel::fetch_stats();
        $userStats = UserModel::fetch_stats();
        $articleStats = ArticleModel::fetch_stats();

        self::$template->set('contactStats', $contactStats);
        self::$template->set('commentStats', $commentStats);
        self::$template->set('userStats', $userStats);
        self::$template->set('articleStats', $articleStats);
        self::$template->show('dashboard>main');
    }

    public function assets()
    {
        $url = implode('/', Router::params());
        if ($url === 'src/js/pinoox.js') {
            HelperHeader::contentType('application/javascript', 'UTF-8');
            self::$template->view('assets/src/js/pinoox.js');
        } else {
            self::_main();
        }
    }


    public function loadMostVisitedStats()
    {
        if (Request::isPost()) {
            $result = ArticleModel::fetch_most_visited(10);
            $visits = array_column($result, 'visits');
            $visitors = array_column($result, 'visitors');

            $series = [
                ['name' => rlang('panel.user'), 'data' => $visitors],
                ['name' => rlang('panel.visits'), 'data' => $visits],
            ];
            Response::json(['titles' => [], 'series' => $series]);
        }

    }
}
