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

use pinoox\app\com_pinoox_paper\component\Helper;
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
        return [
            'user_id' => $user['user_id'],
            'fname' => $user['fname'],
            'lname' => $user['lname'],
            'fullname' => $user['full_name'],
            'email' => $user['email'],
            'username' => $user['username'],
            'register_date' => Helper::getLocaleDate('Y/m/d H:i:s',$user['register_date']),
            'avatar_id' => $user['avatar_id'],
            'image' => Url::upload($file, $placeHolder),
            'thumb_128' => Url::thumb($file, 128, $placeHolder),
        ];
    }
}