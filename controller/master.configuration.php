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
namespace pinoox\app\com_pinoox_paper\controller;

use pinoox\app\com_pinoox_paper\model\MenuModel;
use pinoox\app\com_pinoox_paper\model\SettingsModel;
use pinoox\component\app\AppProvider;
use pinoox\component\HelperHeader;
use pinoox\component\interfaces\ControllerInterface;
use pinoox\component\Lang;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Template;
use pinoox\component\Validation;

class MasterConfiguration implements ControllerInterface
{
    /**
     * @var Template
     */
    protected static $template;

    public function __construct()
    {
        $this->initTemplate();
        $this->loadConfig();
        $this->loadSettings();
        $this->loadMenus();
    }

    private function initTemplate()
    {
        self::$template = new Template();
        self::$template->set('_site', url('~'));
        self::$template->set('_app', url());
        self::$template->set('_lang', Lang::get('front'));
        self::$template->set('_direction', rlang('paper.direction'));

    }

    private function loadConfig()
    {
        $configs = SettingsModel::getConfigs();
        self::$template->setConfig($configs);
    }

    private function loadSettings()
    {
        //general
        $siteTitle = 'sample title';
        $siteDesc = 'sample description';
        self::$template->set('siteTitle', $siteTitle);
        self::$template->set('siteDesc', $siteDesc);
        self::$template->set('_description', $siteDesc);

        //seo
        $seo_title ='seo_title';
        $seo_description = 'seo_description';
        self::$template->set('seo_title', $seo_title);
        self::$template->set('seo_description', $seo_description);
        self::$template->set('seo_description', $seo_description);

        //socials
        self::$template->set('twitter', 'twitter');
        self::$template->set('instagram', 'instagram');
        self::$template->set('telegram', 'telegram');
    }

    private function loadMenus()
    {
        self::$template->set('primaryMenu', []);

        self::$template->set('footerMenu', []);
    }

    public function _main()
    {
        self::error404();
    }

    public function error404()
    {
        HelperHeader::generateStatusCodeHTTP('404 Not Found');

        if (Request::isAjax())
            Response::json(rlang('panel.invalid_request'), false);

        self::$template->show('pages>error404');
        exit;
    }

    public function _exception()
    {
        self::error404();
    }

    public function _header()
    {
        $this->loadMenus();
    }

}