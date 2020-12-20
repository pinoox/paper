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

use pinoox\component\Cookie;
use pinoox\component\interfaces\ServiceInterface;
use pinoox\component\User;

class UserService implements ServiceInterface
{

    public function _run()
    {
        User::lifeTime(90, 'day');
        User::type(User::JWT);
    }

    public function _stop()
    {
        User::type(User::COOKIE);
    }
}