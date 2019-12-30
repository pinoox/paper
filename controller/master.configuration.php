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

    private function loadSettings()
    {
        //general
        $siteTitle = SettingsModel::getFromCache('site_title', rlang('front.site_title'));
        $siteDesc = SettingsModel::getFromCache('site_description', rlang('front.site_description'));
        self::$template->set('siteTitle', $siteTitle);
        self::$template->set('siteDesc', $siteDesc);
        self::$template->set('_description', $siteDesc);

        //seo
        $seo_title = SettingsModel::getFromCache('seo_title');
        $seo_description = SettingsModel::getFromCache('seo_description');
        self::$template->set('seo_title', $seo_title);
        self::$template->set('seo_description', $seo_description);
        self::$template->set('seo_description', $seo_description);

        //socials
        $twitter = SettingsModel::getFromCache('twitter_link');
        $instagram = SettingsModel::getFromCache('instagram_link');
        $telegram = SettingsModel::getFromCache('telegram_link');

        self::$template->set('twitter', $twitter);
        self::$template->set('instagram', $instagram);
        self::$template->set('telegram', $telegram);
    }

    private function loadMenus()
    {
        $primary = MenuModel::fetch_all('primary');
        self::$template->set('primaryMenu', $primary);

        $footer = MenuModel::fetch_all('footer');
        self::$template->set('footerMenu', $footer);
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