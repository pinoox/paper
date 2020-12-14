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
use pinoox\app\com_pinoox_paper\model\CommentModel;
use pinoox\app\com_pinoox_paper\model\ContactModel;
use pinoox\app\com_pinoox_paper\model\PageModel;
use pinoox\app\com_pinoox_paper\model\PaperDatabase;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\app\com_pinoox_paper\model\StatisticModel;
use pinoox\component\Cookie;
use pinoox\component\HelperHeader;
use pinoox\component\HelperString;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Router;
use pinoox\component\Tree;
use pinoox\component\Url;
use pinoox\component\User;
use pinoox\component\Validation;
use pinoox\model\PinooxDatabase;

class MainController extends MasterConfiguration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function _exception($page_key = null)
    {
        if(empty($page_key))

        $this->page($page_key);
    }

    public function dist()
    {
        $url = implode('/', Router::params());
        if ($url === 'pinoox.js') {
            HelperHeader::contentType('application/javascript', 'UTF-8');
            self::$template->view('dist/pinoox.js');
        } else {
            self::error404();
        }
    }

    public function _main()
    {
        self::$template->set('features', $this->featuresArticles());
        self::$template->set('newest', $this->newestArticles());
        self::$template->show('pages>home');
    }

    public function search($pageIndex = 1)
    {
        $posts = [];
        $page = null;
        $queryValue = null;
        $resultCount = 0;
        $query = Url::queryString();

        $parts = explode('=', $query);
        if (empty($query)) self::error404();

        if (!is_numeric($pageIndex))
            $query = $pageIndex;

        $resultLimit = 4;
        $queryType = $parts[0];
        $queryValue = $parts[1];
        if (!empty($queryValue)) {
            if ($queryType == 'q') {
                $this->filterSearch($queryType, $queryValue);
                $resultCount = PostModel::fetch_all(null, true);
                $page = new Pagination($resultCount, $resultLimit);
                $page->setCurrentPage($pageIndex);
                $this->filterSearch($queryType, $queryValue);
                $posts = PostModel::fetch_all($page->getArrayLimit());
            }
            if ($queryType == 'tag') {
                $this->filterSearch();
                $resultCount = PostModel::fetch_by_tag_name($queryValue, null, true);
                $page = new Pagination($resultCount, $resultLimit);
                $page->setCurrentPage($pageIndex);
                $this->filterSearch();
                $posts = PostModel::fetch_by_tag_name($queryValue, $page->getArrayLimit());
            }
        }

        self::$template->set('resultCount', $resultCount);
        self::$template->set('queryString', $query);
        self::$template->set('queryValue', $queryValue);
        self::$template->set('page', $page);
        self::$template->set('posts', $posts);
        self::$template->show('pages>search');
    }

    private function filterSearch($type = null, $query = null)
    {
        if ($type == 'q') {
            PostModel::where_search($query);
        }
        PostModel::where_status(PostModel::publish_status);
    }

    public function post($post_id, $title = null)
    {
        if(func_num_args() > 2)
            self::error404();

        //check post available for guest users
        if (!User::isLoggedIn()) {
            PostModel::where_status(PostModel::publish_status);
        }

        $post = PostModel::fetch_by_id($post_id);
        if (empty($post)) self::error404();
        $post_title = HelperString::replaceSpace($post['title']);
        if ($post_title != $title)
            Response::redirect(Url::app() . 'post/' . $post_id . '/' . $post_title);

        //load tags
        $tags = PostModel::fetch_all_tags_by_post_id($post_id);

        //load comments
        $comments = CommentModel::fetch_all_by_post($post_id, CommentModel::status_publish);
        $cmCount = count($comments);
        $tree = new Tree();
        $treeComments = $tree->createTree($comments, 'parent_id', 'comment_id');

        //store visits
        //PostModel::update_visit($post_id);
        $key = '_pinoox_post_' . $post_id;
        if (Cookie::get($key) != 'visited') {
        //    PostModel::update_visitor($post_id);
            Cookie::set($key, 'visited', 60 * 24);//expire after 1 day
        }

        TemplateHelper::title($post['title']);
        self::$template->set('tags', $tags);
        self::$template->set('cmCount', $cmCount);
        self::$template->set('comments', $treeComments);
        self::$template->set('post', $post);
        self::$template->show('pages>post');
    }

    private function newestArticles()
    {
        $limitCount = 10;
        PostModel::where_status(PostModel::publish_status );
        return PostModel::fetch_all($limitCount);
    }

    private function featuresArticles()
    {
        PostModel::where_status(PostModel::publish_status);
        return PostModel::fetch_all(8);
    }

    public function sendContact(){
        if (!Request::isPost()) self::error404();

        $formData = Request::post('full_name,mobile,subject,message', null, '!empty');
        $valid = Validation::check($formData, [
            'full_name' => ['required', rlang('front.full_name')],
            'mobile' => ['required|mobile', rlang('front.mobile')],
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

    private function page($page_key)
    {
        PostModel::where_post_type(PostModel::page_type);
        $page = PostModel::fetch_by_key($page_key, PostModel::publish_status);
        if (empty($page)) self::error404();

        //store visits
        StatisticModel::visit($page['post_id']);

        self::$template->set('page', $page);
        self::$template->show('pages>page');
    }

    public function contact()
    {
        self::$template->show('pages>contact');
    }

}
