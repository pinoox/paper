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

use pinoox\app\com_pinoox_manager\component\Wizard;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\app\com_pinoox_paper\model\UserModel;
use pinoox\component\Config;
use pinoox\component\Dir;
use pinoox\component\interfaces\ServiceInterface;
use pinoox\component\User;
use pinoox\model\FileModel;
use pinoox\model\PinooxDatabase;

class InstallService implements ServiceInterface
{

    public function _run()
    {
        $this->updateDataBase();

        $users = UserModel::fetch_all(null, false);

        if (!empty($users)) {
            foreach ($users as $user) {
                UserModel::insert_self([
                    'user_id' => $user['user_id'],
                    'group_key' => 'administrator',
                ]);
            }
        }
    }

    private function updateDataBase()
    {
        $updateDb = Dir::path('service>app>update.db');
        $package_name = app('package-name');
        Wizard::runQuery($updateDb, $package_name, true, false);
    }

    public function _stop()
    {
    }
}