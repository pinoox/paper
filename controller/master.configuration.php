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

use pinoox\app\com_pinoox_paper\component\TemplateHelper;
use pinoox\app\com_pinoox_paper\model\LangModel;
use pinoox\app\com_pinoox_paper\model\SettingsModel;
use pinoox\component\app\AppProvider;
use pinoox\component\Dir;
use pinoox\component\HelperHeader;
use pinoox\component\HelperString;
use pinoox\component\interfaces\ControllerInterface;
use pinoox\component\Lang;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Template;
use pinoox\component\User;

class MasterConfiguration implements ControllerInterface
{
    /**
     * @var Template
     */
    protected static $template;
    /**
     * @var array
     */
    protected static $config;

    public function __construct()
    {
        User::type(User::COOKIE);
        $this->initTemplate();
        $this->setLang();
        $this->getAssets();
        $this->loadMenus();
    }

    private function initTemplate()
    {
        self::$template = new Template();
        self::$template->set('_site', url('~'));
        self::$template->set('_app', url());
        self::$template->set('_direction', rlang('front.direction'));
        self::$template->set('_translate', Lang::current());
        self::$template->set('_menu', setting('general.menu'));
        self::$template->setPageTitle(setting('general.site_title'));
        TemplateHelper::initData();
    }

    private function loadMenus()
    {
        self::$template->set('primaryMenu', []);

        self::$template->set('footerMenu', []);
    }

    public function _main()
    {
        self::$template->show('index');
    }

    private function setLang()
    {
        $lang = ['front' => Lang::get('front')];
        self::$template->set('_lang', HelperString::encodeJson($lang, true));
    }

    private function getAssets()
    {
        $vendor_css = 'vendor.css';
        $vendor_js = 'vendor.js';
        $main_css = 'main.css';
        $main_js = 'main.js';
        $path = Dir::theme('dist/manifest.json');
        if (is_file($path)) {
            $manifest = file_get_contents($path);
            $manifest = HelperString::decodeJson($manifest);

            $this->changeScalarToArray($manifest, 'main');
            foreach ($manifest['main'] as $item) {
                if (HelperString::has($item, 'main.js'))
                    $main_js = $item;
                else if (HelperString::has($item, 'main.css'))
                    $main_css = $item;
            }
            $this->changeScalarToArray($manifest, 'vendor');
            foreach ($manifest['vendor'] as $item) {
                if (HelperString::has($item, 'vendor.js'))
                    $vendor_js = $item;
                else if (HelperString::has($item, 'vendor.css'))
                    $vendor_css = $item;
            }
        }

        self::$template->assets = [
            'vendor_css' => $vendor_css,
            'vendor_js' => $vendor_js,
            'main_css' => $main_css,
            'main_js' => $main_js,
        ];
    }


    private function changeScalarToArray(&$array, $key)
    {
        if (!isset($array[$key])) return;

        $copy = $array[$key];
        if (!is_array($copy)) {
            unset($array[$key]);
            $array[$key][] = $copy;
        }
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
        self::_main();
    }

    public function _header()
    {
        $this->loadMenus();
    }

}