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

use pinoox\app\com_pinoox_paper\model\CommentModel;
use pinoox\app\com_pinoox_paper\model\ContactModel;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\component\Response;


class DashboardController extends LoginConfiguration
{

    public function getCountNotifies()
    {
        $comments = CommentModel::fetch_all(CommentModel::status_pending, null, true);
        $contacts = ContactModel::fetch_all(ContactModel::status_unseen, null, true);

        Response::json(['comments' => $comments, 'contacts' => $contacts]);
    }

    public function getStats()
    {
        $words = PostModel::fetch_total_words();
        Response::json(['words' => $words]);
    }
}
