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

use pinoox\component\app\AppProvider;
use pinoox\component\interfaces\ControllerInterface;
use pinoox\component\Lang;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Router;
use pinoox\component\Template;
use pinoox\component\User;

class MasterConfiguration implements ControllerInterface
{
    /**
     * @var Template
     */
    protected static $template;

    public function __construct()
    {
        $this->checkLogin();
        $this->initTemplate();
    }

    private function checkLogin()
    {
        if (!User::isLoggedIn() && Router::simpleController() != 'account') {
            Response::redirect(url());
        }
    }

    private function initTemplate()
    {
        self::$template = new Template(null, AppProvider::get('theme-panel'));
        self::$template->addToAfterHeader('toolbar');
        self::$template->addToAfterHeader('sidebar');
        $app = url('panel/');
        self::$template->set('_site', url('~'));
        self::$template->set('_app', $app);
        self::$template->set('_lang', Lang::get('panel'));
        self::$template->set('_direction', rlang('paper.direction'));
    }

    public function _main()
    {
        self::$template->show('index');
    }

    public function _exception()
    {
        self::error404();
    }

    public function error404()
    {
        if (Request::isAjax()) exit(rlang('panel.invalid_request'));
        self::$template->show('error404');
        exit;
    }

}