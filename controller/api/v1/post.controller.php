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
use pinoox\app\com_pinoox_paper\model\CategoryModel;
use pinoox\app\com_pinoox_paper\model\CommentModel;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\component\Date;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Tree;
use pinoox\component\Url;
use pinoox\component\Validation;
use pinoox\model\FileModel;


class PostController extends MasterConfiguration
{
    public function get($post_id = null)
    {
        PostModel::where_status(PostModel::publish_status);
        $post = PostModel::fetch_by_id($post_id);
        $post = $this->getPostInfo($post);
        Response::json($post);
    }

    public function getAll()
    {
        PostModel::where_status(PostModel::publish_status);
        $posts = PostModel::fetch_all();
        $posts = array_map(function ($post) {
            $post = $this->getPostInfo($post);
            return $post;
        }, $posts);

        Response::json($posts);
    }

    public function getAllByUsername($username)
    {
        PostModel::where_status(PostModel::publish_status);
        PostModel::where_author($username);
        $posts = PostModel::fetch_all();
        $posts = array_map(function ($post) {
            $post = $this->getPostInfo($post);
            return $post;
        }, $posts);

        Response::json($posts);
    }

    public function getMostVisited()
    {
        PostModel::sort(['field' => 'visits', 'type' => 'DESC']);
        PostModel::where_status(PostModel::publish_status);
        $posts = PostModel::fetch_all();
        $posts = array_map(function ($post) {
            $post = $this->getPostInfo($post);
            return $post;
        }, $posts);

        Response::json($posts);
    }

    public function getLatestComments()
    {
        $comments = CommentModel::fetch_all(CommentModel::status_publish, 5);
        $comments = array_map(function ($cm) {
            return $this->getCommentInfo($cm);
        }, $comments);

        Response::json($comments);
    }

    public function search()
    {
        $keyword = Request::inputOne('keyword', null, '!empty');

        PostModel::where_status(PostModel::publish_status);
        PostModel::search_keyword($keyword);
        $posts = PostModel::fetch_all(20);
        $posts = array_map(function ($post) {
            $post = $this->getPostInfo($post);
            return $post;
        }, $posts);

        Response::json($posts);
    }

    public function getByTag()
    {
        $tagName = Request::inputOne('tag_name', null, '!empty');
        PostModel::where_status(PostModel::publish_status);
        PostModel::where_tag_name($tagName, true);
        $posts = PostModel::fetch_all(20);
        $posts = array_map(function ($post) {
            $post = $this->getPostInfo($post);
            return $post;
        }, $posts);

        Response::json($posts);
    }

    public function getComments($post_id)
    {
        $comments = CommentModel::fetch_all_by_post($post_id, CommentModel::status_publish);

        $comments = array_map(function ($cm) {
            return $this->getCommentInfo($cm);
        }, $comments);

        $tree = new Tree();
        $treeComments = $tree->createTree($comments, 'parent_id', 'comment_id');

        Response::json(['count' => count($comments), 'items' => $treeComments]);
    }

    public function leaveComment()
    {
        $form = Request::input('post_id,parent_id,full_name,email,message', null, '!empty');
        $valid = Validation::check($form, [
            'post_id' => ['required', ''],
            'full_name' => ['required|length:>2', rlang('user.full_name')],
            'email' => ['required|email', rlang('user.email')],
            'message' => ['required|length:>10', rlang('comment.message')],
        ], [
            'post_id:required' => rlang('front.invalid_request')
        ]);

        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        $comment_id = CommentModel::insert($form);
        if ($comment_id > 0)
            Response::jsonMessage(rlang('comment.comment_inserted_successfully'), true);

        Response::jsonMessage(rlang('comment.error_happened'), false);

    }

    private function createSocialLinks($post)
    {
        $title = $post['title'];
        $summary = $post['summary'];
        $url = $post['meta']['url'];
        $tags = $post['meta']['tags'];
        return [
            'twitter' => 'http://twitter.com/share?text=' . $title . '&url=' . $url . '&hashtags=' . $tags,
            'linkedin' => 'http://www.linkedin.com/shareArticle?mini=true&url=' . $url,
            'facebook' => 'http://www.facebook.com/share.php?v=4&src=bm&u=' . $url,
            'telegram' => 'tg://msg_url?url=' . $url,
            'whatsapp' => 'whatsapp://send?text=' . $url,
        ];
    }

    private function getCommentInfo($comment)
    {
        if (empty($comment)) return $comment;

        $cm['comment_id'] = $comment['comment_id'];
        $cm['parent_id'] = $comment['parent_id'];
        $cm['message'] = $comment['message'];
        $cm['full_name'] = $comment['full_name'];
        $cm['approx_insert_date'] = Date::approximateDate($comment['insert_date']);
        $cm['image'] = Url::file('resources/avatar.png');
        $cm['post_title'] = isset($comment['title']) ? $comment['title'] : null;
        $cm['post_url'] = Url::app() . 'post/' . $comment['post_id'];
        $cm['isReply'] = false;

        return $cm;
    }

    private function getPostInfo($post)
    {
        $placeHolder = Url::file('resources/image-placeholder.jpg');

        if (empty($post)) return $post;
        /*
         * Post
         */
        $post['tags'] = PostModel::fetch_tags_by_post_id($post['post_id']);

        $post['publish_date_time'] = Helper::getLocaleDate('Y-m-d H:i', $post['publish_date']);
        $post['publish_date'] = Helper::getLocaleDate('d F Y', $post['publish_date']);
        if (isset($post['cat_id']))
            $post['category'] = CategoryModel::fetch_by_id($post['cat_id']);
        else $post['category'] = null;
        $file = FileModel::fetch_by_id($post['image_id']);
        $post['image'] = Url::upload($file, $placeHolder);
        $post['thumb_128'] = Url::thumb($file, 128, $placeHolder);

        /*
         *  Meta
         */
        $post['meta']['tags'] = implode(',', array_column($post['tags'], 'tag_name'));
        $post['meta']['url'] = Url::app() . 'post/' . $post['post_id'];
        $post['meta']['socials'] = $this->createSocialLinks($post);

        /*
         *  Author
         */
        $author = PostModel::fetch_author_info($post['post_id']);
        $avatar = FileModel::fetch_by_id($author['avatar_id']);
        $author['image'] = Url::upload($avatar, $placeHolder);
        $author['thumb_128'] = Url::thumb($avatar, 128, $placeHolder);
        $post['author'] = $author;


        return [
            'post_id' => $post['post_id'],
            'title' => $post['title'],
            'summary' => $post['title'],
            'context' => isset($post['context']) ? $post['context'] : null,
            'image' => $post['image'],
            'thumb_128' => $post['thumb_128'],
            'meta' => $post['meta'],
            'publish_date' => $post['publish_date'],
            'publish_date_time' => $post['publish_date_time'],
            'author' => $post['author'],
            'tags' => $post['tags'],
        ];
    }
}
