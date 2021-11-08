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

namespace pinoox\app\com_pinoox_paper\controller;

use pinoox\app\com_pinoox_paper\component\TemplateHelper;
use pinoox\app\com_pinoox_paper\model\CategoryModel;
use pinoox\app\com_pinoox_paper\model\CommentModel;
use pinoox\app\com_pinoox_paper\model\ContactModel;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\app\com_pinoox_paper\model\PostStatisticModel;
use pinoox\component\Dir;
use pinoox\component\File;
use pinoox\component\HelperHeader;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Router;
use pinoox\component\Tree;
use pinoox\component\Url;
use pinoox\component\User;
use pinoox\component\Validation;

class MainController extends MasterConfiguration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function _exception($page_key = null)
    {
        $this->page($page_key);
    }

    private function page($page_key)
    {
        PostModel::where_post_type(PostModel::page_type);
        $page = PostModel::fetch_by_key($page_key, PostModel::publish_status);
        if (empty($page)) self::error404();

        //store visits
        PostStatisticModel::visit($page['post_id']);

        TemplateHelper::title($page['title']);
        TemplateHelper::description($page['summary']);

        self::$template->set('page', $page);
        self::_show('pages>page');
    }

    public function dist()
    {
        $url = implode('/', Router::params());
        if ($url === 'pinoox.js') {
            self::visitStatus(false);
            HelperHeader::contentType('application/javascript', 'UTF-8');
            self::$template->offView('index');
            self::$template->view('dist/pinoox.js');
        } else {
            self::error404();
        }
    }

    public function _main()
    {
        $data = $this->getPosts();
        self::$template->set('posts', $data['posts']);
        self::$template->set('page', $data['page']);
        self::_show('pages>home');
    }

    private function getPosts($page = 1, $form = null)
    {
        if (self::$api) {
            return ['posts' => [], 'page' => [], 'count' => 0];
        }

        $this->filterSearch($form);
        $count = posts('all', [
            'count' => true,
        ]);
 
        $countRows = PostModel::getCountRows();
        $pagination = new Pagination($count, $countRows);
        $pagination->setCurrentPage($page);

        $this->filterSearch($form);
        $posts = posts('all', [
            'limit' => $pagination->getArrayLimit(),
        ]);

        return ['posts' => $posts, 'page' => $pagination, 'count' => $count];
    }

    private function filterSearch($form)
    {

        if (isset($form['tag']))
            PostModel::where_tag_name($form['tag']);
        if (isset($form['q']))
            PostModel::where_search($form['q']);
        if (isset($form['cat'])) {
            $cat = CategoryModel::fetch_by_key($form['cat']);
            $ids = [];
            CategoryModel::fetch_all_child_ids($cat['cat_id'], $ids);
            PostModel::where_category_ids($ids);
        }

    }

    public function search($page = 1)
    {
        $form = Request::get('q,tag,cat', null, '!empty', true);

        $query = Url::queryString();

        $query = !empty($query) ? '?' . $query : $query;

        $data = $this->getPosts($page, $form);

        $title = rlang('front.search');
        if (isset($form['tag']) && !empty($form['tag']))
            $title .= " (#" . $form['tag'] . ")";
        else if (isset($form['q']) && !empty($form['q']))
            $title .= " (" . $form['q'] . ")";

        TemplateHelper::title($title);

        self::$template->set('fields', $form);
        self::$template->set('count', $data['count']);
        self::$template->set('query', $query);
        self::$template->set('page', $data['page']);
        self::$template->set('posts', $data['posts']);
        self::_show('pages>search');
    }

    public function post($post_id, $title = null)
    {
        if (func_num_args() > 2)
            self::error404();

        //check post available for guest users
        if (!User::isLoggedIn()) {
            PostModel::where_status(PostModel::publish_status);
        }

        PostModel::where_post_type(PostModel::post_type);
        $post = PostModel::fetch_by_id($post_id);
        if (empty($post)) self::error404();

        $post = PostModel::getInfoPost($post);

        if ($post['post_key'] != $title)
            Response::redirect(Url::app() . 'post/' . $post_id . '/' . $post['post_key']);


        TemplateHelper::title($post['title']);
        TemplateHelper::description($post['summary']);
        TemplateHelper::setProperty('og:image', $post['thumb_512']);

        if (!self::$api) {
            $post['visits']++;
            PostStatisticModel::visit($post_id);

            $isOpenComment = $post['comment_status'] === PostModel::open_status;

            //load comments
            $comments = $isOpenComment ? CommentModel::fetch_all_by_post($post_id, CommentModel::status_publish) : null;
            $cmCount = $isOpenComment ? count($comments) : 0;
            $tree = new Tree();
            $treeComments = $isOpenComment ? $tree->createTree($comments, 'parent_id', 'comment_id') : null;

            self::$template->set('cmCount', $cmCount);
            self::$template->set('comments', $treeComments);
        }

        self::$template->set('tags', $post['tags']);
        self::$template->set('post', $post);
        self::_show('pages>post');
    }

    public function sendContact()
    {
        if (!Request::isPost()) self::error404();

        $formData = Request::post('full_name,email,subject,message', null, '!empty');
        $valid = Validation::check($formData, [
            'full_name' => ['required', rlang('front.full_name')],
            'email' => ['required|email', rlang('front.email')],
            'subject' => ['required', rlang('front.subject')],
            'message' => ['required|length:>5', rlang('front.message')],
        ]);

        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        $insert_id = ContactModel::insert($formData);
        if ($insert_id > 0) {
            Response::jsonMessage(rlang('front.contact_message_received_successfully'), true);
        }
        Response::jsonMessage(rlang('front.error_to_send_contact'), true);
    }

    public function sendComment($post_id)
    {
        if (!Request::isPost()) self::error404();

        PostModel::where_status(PostModel::publish_status);
        $post = PostModel::fetch_by_id($post_id);
        if (empty($post)) self::error404();

        $formData = Request::post('parent_id,full_name,email,message', null, '!empty');
        if (User::isLoggedIn()) {
            $valid = Validation::check($formData, [
                'message' => ['required|length:>5', rlang('front.message')],
            ]);
            $formData['user_id'] = User::get('user_id');
        } else {
            $valid = Validation::check($formData, [
                'full_name' => ['required', rlang('front.full_name')],
                'email' => ['required|email', rlang('front.email')],
                'message' => ['required|length:>5', rlang('front.message')],
            ]);
        }
        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        $formData['post_id'] = $post_id;
        $insert_id = CommentModel::insert($formData);
        if ($insert_id > 0) {
            Response::jsonMessage(rlang('front.comment_inserted_successfully'), true);
        }
        Response::jsonMessage(rlang('front.error_to_insert_comment'), true);
    }

    public function contact()
    {
        TemplateHelper::title(rlang('front.contact_us'));
        self::_show('pages>contact');
    }
}
