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

namespace pinoox\app\com_pinoox_paper\controller\api\v1;

use pinoox\app\com_pinoox_paper\controller\api\ApiConfiguration;
use pinoox\component\Template;

class MasterConfiguration extends ApiConfiguration
{

    public function __construct()
    {
        parent::__construct();
        $template = new Template();
        $api = $template->getMeta('api');
        if(!$api)
            self::error();
    }

    protected function printError($data)
    {
    }

    protected function printResult($data)
    {
    }

}
    
