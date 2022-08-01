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

use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\component\interfaces\ServiceInterface;
use pinoox\component\Request;

class ScheduleService implements ServiceInterface
{
    public function _run()
    {
        if (Request::isAjax())
            return;

        $posts = PostModel::fetch_all_schedule_publish();

        if (!empty($posts) && !is_array($posts))
            return;

        foreach ($posts as $post) {
            PostModel::watch_schedule_publish($post);
            PostModel::update_publish_date($post);
        }
    }

    public function _stop()
    {
    }
}