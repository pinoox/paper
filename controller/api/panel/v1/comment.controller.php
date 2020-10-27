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
use pinoox\app\com_pinoox_paper\model\PaperUserModel;
use pinoox\component\Date;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;


class CommentController extends LoginConfiguration
{
    public function get($comment_id)
    {
        $comment = CommentModel::fetch_by_id($comment_id);
        $comment = $this->getInfoComment($comment);
        Response::json($comment);
    }

    public function getLatestComments()
    {
        $comments = CommentModel::fetch_all();
        $comments = array_map(function ($comment) {
            return $comment = $this->getInfoComment($comment);
        }, $comments);

        Response::json($comments);
    }

    public function getAll()
    {
        $form = Request::input('keyword,sort,status,perPage=10,page=1', null, '!empty');

        $this->filterSearch($form);
        $count = CommentModel::fetch_all(null, null, true);

        // pagination
        $pagination = new Pagination($count, $form['perPage']);
        $pagination->setCurrentPage($form['page']);

        $this->filterSearch($form);
        $comments = CommentModel::fetch_all(null, $pagination->getArrayLimit());

        $comments = array_map(function ($comment) {
            return $comment = $this->getInfoComment($comment);
        }, $comments);

        Response::json(['items' => $comments, 'pages' => $pagination->getInfoPage()['page']]);
    }

    private function filterSearch($form)
    {
        CommentModel::where_search($form['keyword']);
        CommentModel::where_status($form['status']);
        CommentModel::sort($form['sort']);
    }

    private function getInfoComment($comment)
    {
        if (empty($comment)) return $comment;
        $comment['approx_insert_date'] = Date::j('l d F Y (H:i)', $comment['insert_date']);
        return $comment;
    }

    public function delete()
    {
        $comment_id = Request::inputOne('comment_id', null, '!empty');

        if (CommentModel::fetch_by_id($comment_id) != false) {
            $status = CommentModel::delete($comment_id);
            if ($status)
                Response::jsonMessage(rlang('panel.delete_successfully'), true);
        }

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    public function changeStatus()
    {
        $data = Request::input('comment_id,status', null, '!empty');

        if (CommentModel::fetch_by_id($data['comment_id']) != false) {
            $data['status'] = ($data['status'] == PaperUserModel::publish) ? PaperUserModel::suspend : PaperUserModel::publish;
            $status = CommentModel::update_status($data['comment_id'], $data['status']);
            if ($status)
                Response::jsonMessage(rlang('panel.edited_successfully'), true);
        }

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

}
