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

namespace pinoox\app\com_pinoox_paper\service;

use pinoox\component\app\AppProvider;
use pinoox\component\Dir;
use pinoox\component\interfaces\ServiceInterface;
use pinoox\component\Template;

class TemplateService implements ServiceInterface
{

    public function _run()
    {

        $theme = AppProvider::get('theme');
        $path = Dir::path('theme');
        $t = new Template($path, $theme);
        $isApi = $t->getMeta('api');
        if ($isApi) AppProvider::set('main-controller', 'api');
        else AppProvider::set('main-controller', 'main');
        AppProvider::save();
    }

    public function _stop()
    {
    }
}