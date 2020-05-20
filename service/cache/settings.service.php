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
namespace pinoox\app\com_pinoox_paper\service\cache;

use pinoox\app\com_pinoox_paper\model\SettingsModel;
use pinoox\component\Cache;
use pinoox\component\interfaces\ServiceInterface;

class SettingsService implements ServiceInterface
{

    public function _run()
    {
        Cache::init('settings',function (){
            $configs = SettingsModel::fetch_all();
            $result = [];
            foreach ($configs as $config)
            {
                $result[$config['s_key']] = $config['s_value'];
            }
            return $result;
        },24);
    }

    public function _stop()
    {
    }
}