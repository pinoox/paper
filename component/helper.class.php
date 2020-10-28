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

namespace pinoox\app\com_pinoox_paper\component;

use pinoox\component\Date;
use pinoox\component\Lang;

class Helper
{

    public static function getLocalDate($format, $date = 'now')
    {
        if (Lang::current() == 'en') {
            $format = str_replace('F','M',$format);//month
            $format = str_replace('l','D',$format);// week
            return Date::g($format, $date);
        } else {
            return Date::j($format, $date);
        }


    }

}