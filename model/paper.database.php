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

namespace pinoox\app\com_pinoox_paper\model;

use pinoox\model\PinooxDatabase;

class PaperDatabase extends PinooxDatabase
{

    const prefix = 'com_pinoox_paper_';

    //tables
    const settings = self::prefix . 'settings';
    const post = self::prefix . 'post';
    const post_draft = self::prefix . 'post_draft';
    const post_history = self::prefix . 'post_history';
    const post_tag = self::prefix . 'post_tag';
    const post_statistic = self::prefix . 'post_statistic';
    const tag = self::prefix . 'tag';
    const category = self::prefix . 'category';
    const contact = self::prefix . 'contact';
    const comment = self::prefix . 'comment';
    const statistic = self::prefix . 'statistic';
    const user_paper = self::prefix . 'user';
    const group = self::prefix . 'group';

}
