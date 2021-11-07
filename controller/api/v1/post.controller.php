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
use pinoox\app\com_pinoox_paper\model\CommentModel;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\app\com_pinoox_paper\model\PostStatisticModel;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Tree;
use pinoox\component\Url;
use pinoox\component\User;
use pinoox\component\Validation;


class PostController extends MasterConfiguration
{
    public function get()
    {
        $form = Request::input(['post_id', 'post_key', 'date_format' => 'l d F Y'], null, '!empty');

        //check post available for guest users
        if (!User::isLoggedIn()) {
            PostModel::where_status(PostModel::publish_status);
        }

        PostModel::where_post_type(PostModel::post_type);
        $post = PostModel::fetch_by_id($form['post_id']);
        $post = PostModel::getInfoPost($post, $form['date_format']);

        if (!$post || $post['post_key'] != $form['post_key'])
            Response::json($post, false);


        if ($post) {
            $post['visits']++;
            PostStatisticModel::visit($form['post_id']);
            Response::json($post, true);
        } else {
            Response::json($post, false);
        }
    }

    public function getAll()
    {
        $form = Request::input([
            'page' => 1,
            'keyword',
            'tag',
            'username',
            'rows' => 10,
            'date_format' => 'd F Y'
        ], null, '!empty');

        $this->filterSearch($form);
        $count = posts('all', [
            'count' => true,
        ]);

        $form['rows'] = is_numeric($form['rows']) ? $form['rows'] : 10;
        $form['rows'] = $form['rows'] < 50 ? $form['rows'] : 50;
        $pagination = new Pagination($count, $form['rows']);
        $pagination->setCurrentPage($form['page']);

        $this->filterSearch($form);
        $posts = posts('all', [
            'limit' => $pagination->getArrayLimit(),
            'date_format' => $form['date_format'],
        ]);

        Response::json(['posts' => $posts, 'pages' => $pagination->getInfoPage()['page']]);
    }

    private function filterSearch($form)
    {
        if (!empty($form['tag']))
            PostModel::where_tag_name($form['tag']);
        if (!empty($form['keyword']))
            PostModel::where_search($form['keyword']);
        if (!empty($form['username']))
            PostModel::where_author($form['username']);
    }

    public function getPage($page_key)
    {
        PostModel::where_post_type(PostModel::page_type);
        $page = PostModel::fetch_by_key($page_key, PostModel::publish_status);
        if ($page)
            Response::json($page, true);
        else
            Response::json(null, false);

    }

    public function getMostVisited()
    {
        $date_format = Request::inputOne('date_format', 'd F Y', '!empty');

        $posts = posts('*', [
            'limit' => 5,
            'order' => 'visits',
            'date_format' => $date_format,
        ]);

        Response::json($posts);
    }

    public function getLatestComments()
    {
        $date_format = Request::inputOne('date_format', 'd F Y', '!empty');
        $comments = CommentModel::fetch_all(CommentModel::status_publish, 5);
        $comments = array_map(function ($cm) use ($date_format) {
            return $this->getCommentInfo($cm, $date_format, true);
        }, $comments);

        Response::json($comments);
    }

    private function getCommentInfo($comment, $date_format = null, $isPost = false)
    {
        if (empty($comment)) return $comment;

        $cm['comment_id'] = $comment['comment_id'];
        $cm['parent_id'] = $comment['parent_id'];
        $cm['message'] = $comment['message'];
        $cm['full_name'] = $comment['full_name'];
        $cm['insert_date'] = Helper::getLocaleDate('Y/m/d H:i', $comment['insert_date']);
        $cm['approx_date'] = Helper::getLocaleDate($date_format, $comment['insert_date']);
        $cm['image'] = Url::file('resources/avatar.png');

        if ($isPost) {
            $post = PostModel::fetch_by_id($comment['post_id']);
            $post = PostModel::getInfoPost($post, $date_format);
            $cm['post'] = $post;
        }

        $cm['isReply'] = false;

        return $cm;
    }

    public function getComments($post_id)
    {
        $date_format = Request::inputOne('date_format', 'd F Y', '!empty');

        PostModel::where_post_type(PostModel::post_type);
        $post = PostModel::fetch_by_id($post_id);

        if($post['comment'] === PostModel::open_status)
        {
            $comments = CommentModel::fetch_all_by_post($post_id, CommentModel::status_publish);

            $comments = array_map(function ($cm) use ($date_format) {
                return $this->getCommentInfo($cm, $date_format);
            }, $comments);

            $tree = new Tree();
            $treeComments = $tree->createTree($comments, 'parent_id', 'comment_id');

            Response::json(['count' => count($comments), 'items' => $treeComments]);
        }
        else
        {
            Response::json(['count' => 0, 'items' => []]);
        }

    }

    public function leaveComment()
    {
        $form = Request::input('post_id,parent_id,full_name,email,message', null, '!empty');
        $valid = Validation::check($form, [
            'post_id' => ['required', ''],
            'full_name' => ['required|length:>2', rlang('front.full_name')],
            'email' => ['required|email', rlang('front.email')],
            'message' => ['required|length:>10', rlang('front.message')],
        ], [
            'post_id:required' => rlang('front.invalid_request')
        ]);

        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        $comment_id = CommentModel::insert($form);
        if ($comment_id > 0)
            Response::jsonMessage(rlang('front.comment_inserted_successfully'), true);

        Response::jsonMessage(rlang('front.error_happened'), false);

    }
}
