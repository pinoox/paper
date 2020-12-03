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

use pinoox\component\Dir;
use pinoox\component\HelperHeader;
use pinoox\component\Router;

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