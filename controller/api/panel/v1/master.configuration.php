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

use pinoox\app\com_pinoox_paper\controller\api\ApiConfiguration;
use pinoox\component\Date;
use pinoox\component\HelperHeader;
use pinoox\component\Lang;

class MasterConfiguration extends ApiConfiguration
{

    public function __construct()
    {
        parent::__construct();
        HelperHeader::addHeader('Access-Control-Allow-Methods', '*');
        HelperHeader::addHeader('Access-Control-Allow-Origin', '*');
    }

}
    
