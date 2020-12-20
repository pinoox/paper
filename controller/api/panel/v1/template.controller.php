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

use pinoox\component\app\AppProvider;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Template;

class TemplateController extends MasterConfiguration
{
    public function getAll()
    {
        $t = new Template();
        $templates = $t->getTemplates(['panel']);

        if (!empty($templates)) {
            //sort by enable
            $isEnable = array_column($templates, 'is_enable');
            array_multisort($isEnable, SORT_DESC, $templates);
        }

        Response::json($templates);
    }

    public function activate()
    {
        $template = Request::inputOne('template', null, '!empty');
        if (empty($template)) Response::json(false);

        AppProvider::set('theme', $template['name']);
        AppProvider::save();

        Response::json(true);
    }
}
