<?php
/**
 *      ****  *  *     *  ****  ****  *    *
 *      *  *  *  * *   *  *  *  *  *   *  *
 *      ****  *  *  *  *  *  *  *  *    *
 *      *     *  *   * *  *  *  *  *   *  *
 *      *     *  *    **  ****  ****  *    *
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       pinoox.com
 * @copyright  pinoox
 */

namespace pinoox\app\com_pinoox_paper\service\app;

use pinoox\app\com_pinoox_paper\model\UpdateModel;
use pinoox\component\interfaces\ServiceInterface;

class UpdateService implements ServiceInterface{

    public function _run()
    {

          UpdateModel::insert_tables();
    }

    public function _stop()
    {
    }
}