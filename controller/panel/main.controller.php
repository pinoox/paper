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
    public function dist()
    {
        $url = implode('/', Router::params());
        if ($url === 'paper/pinoox.js') {
            HelperHeader::contentType('application/javascript', 'UTF-8');
            self::$template->view('dist/paper/pinoox.js');
        } else {
            self::error404();
        }
    }
}