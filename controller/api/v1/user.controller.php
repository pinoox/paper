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

use pinoox\app\com_pinoox_paper\model\UserModel;
use pinoox\component\Response;
use pinoox\component\Url;
use pinoox\model\FileModel;


class UserController extends MasterConfiguration
{
    public function getProfile($username)
    {
        $user = UserModel::fetch_by_username($username);
        $user = $this->getInfoUser($user);
        Response::json($user);
    }

    private function getInfoUser($user)
    {
        $placeHolder = Url::file('resources/image-placeholder.jpg');

        $file = FileModel::fetch_by_id($user['avatar_id']);
        $user['image'] = Url::upload($file, $placeHolder);
        $user['thumb_128'] = Url::thumb($file, 128, $placeHolder);

        return $user;
    }
}