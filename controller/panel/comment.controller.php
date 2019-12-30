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
namespace pinoox\app\com_pinoox_paper\controller\panel;

use pinoox\app\com_pinoox_paper\model\CommentModel;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;

class CommentController extends MasterConfiguration
{


    public function __construct()
    {
        parent::__construct();
    }

    public function _main()
    {
        $this->show();
    }

    private function show()
    {
        $form = Request::post('page=1,keyword');

        $this->filter($form);
        $page = new Pagination(CommentModel::fetch_all(null, null, true), 10);
        $page->setCurrentPage($form['page']);

        $this->filter($form);
        $comments = CommentModel::fetch_all(null, $page->getArrayLimit());

        self::$template->set('comments', $comments);
        self::$template->set('page', $page);
        self::$template->show('comment>list');
    }

    private function filter($form)
    {
        CommentModel::where_search($form['keyword']);
    }

    public function delete()
    {
        if (Request::isAjax()) {
            $id = Request::postOne('comment_id');
            $cm = CommentModel::fetch_by_id($id);
            if (empty($cm)) self::error404();
            $status = CommentModel::delete($id);

            if ($status)
                Response::json(rlang('panel.delete_successfully'), true);
        }
        Response::json(rlang('panel.error_happened'), false);
    }

    public function updateStatus($cmId)
    {
        if (Request::isPost()) {
            $cm = CommentModel::fetch_by_id($cmId);
            if (empty($cm)) self::error404();
            $cmStatus = ($cm['status'] == CommentModel::pending || $cm['status'] == CommentModel::suspend) ? CommentModel::publish : CommentModel::suspend;
            $result = CommentModel::update_status($cmId, $cmStatus);

            if ($result)
                Response::json(['cmStatus'=>$cmStatus,'title'=>rlang('comment.'.$cmStatus)], true);
        }
        Response::json(rlang('panel.error_happened'), false);
    }


}