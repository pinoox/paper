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

use pinoox\app\com_pinoox_paper\model\ArticleModel;
use pinoox\app\com_pinoox_paper\model\CommentModel;
use pinoox\app\com_pinoox_paper\model\ContactModel;
use pinoox\app\com_pinoox_paper\model\PageModel;
use pinoox\app\com_pinoox_paper\model\SettingsModel;
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

class MainController extends MasterConfiguration
{

    public function __construct()
    {
        parent::__construct();

        $limitMostVisited = SettingsModel::getFromCache('most_visited_count', 0);
        $limitHotTags = SettingsModel::getFromCache('hot_tags_count', 0);
        ArticleModel::where_status(ArticleModel::publish);

        self::$template->set('mostVisited', ArticleModel::fetch_most_visited($limitMostVisited));
        self::$template->set('hotTags', ArticleModel::hot_tags($limitHotTags));


    }

    public function _exception()
    {
        $page_key = Request::params(0);
        $this->page($page_key);
    }

    public function assets()
    {
        $url = implode('/', Router::params());
        if ($url === 'js/pinoox.js') {
            HelperHeader::contentType('application/javascript', 'UTF-8');
            self::$template->view('assets/js/pinoox.js');
        } else {
            self::_main();
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
        $articles = [];
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
                $resultCount = ArticleModel::fetch_all(null, true);
                $page = new Pagination($resultCount, $resultLimit);
                $page->setCurrentPage($pageIndex);
                $this->filterSearch($queryType, $queryValue);
                $articles = ArticleModel::fetch_all($page->getArrayLimit());
            }
            if ($queryType == 'tag') {
                $this->filterSearch();
                $resultCount = ArticleModel::fetch_by_tag_name($queryValue, null, true);
                $page = new Pagination($resultCount, $resultLimit);
                $page->setCurrentPage($pageIndex);
                $this->filterSearch();
                $articles = ArticleModel::fetch_by_tag_name($queryValue, $page->getArrayLimit());
            }
        }

        self::$template->set('resultCount', $resultCount);
        self::$template->set('queryString', $query);
        self::$template->set('queryValue', $queryValue);
        self::$template->set('page', $page);
        self::$template->set('articles', $articles);
        self::$template->show('pages>search');
    }

    private function filterSearch($type = null, $query = null)
    {
        if ($type == 'q') {
            ArticleModel::where_search($query);
        }
        ArticleModel::where_status(ArticleModel::publish);
    }

    public function article($article_id, $title = null)
    {
        if(func_num_args() > 2)
            self::error404();

        //check article available for guest users
        if (!User::isLoggedIn()) {
            ArticleModel::where_status(ArticleModel::publish);
        }
        $article = ArticleModel::fetch_by_id($article_id);
        if (empty($article)) self::error404();
        if (($article_title = HelperString::replaceSpace($article['title'])) != $title)
            Response::redirect(Url::app() . 'article/' . $article_id . '/' . $article_title);

        //load tags
        $tags = ArticleModel::fetch_all_tags_by_article_id($article_id);

        //load comments
        $comments = CommentModel::fetch_all_by_article($article_id, CommentModel::publish);
        $cmCount = count($comments);
        $tree = new Tree();
        $treeComments = $tree->createTree($comments, 'parent_id', 'comment_id');

        //store visits
        ArticleModel::update_visit($article_id);
        $key = '_pinoox_article_' . $article_id;
        if (Cookie::get($key, false) != 'visited') {
            ArticleModel::update_visitor($article_id);
            Cookie::set($key, 'visited', 60 * 24);//expire after 1 day
        }

        self::$template->set('_title', $article['title']);
        self::$template->set('_description', $article['summary']);

        self::$template->set('tags', $tags);
        self::$template->set('cmCount', $cmCount);
        self::$template->set('comments', $treeComments);
        self::$template->set('article', $article);
        self::$template->show('pages>article');
    }

    private function newestArticles()
    {
        $limitCount = SettingsModel::getFromCache('newest_article_count', 10);
        ArticleModel::where_status(ArticleModel::publish);
        $articles = ArticleModel::fetch_all($limitCount);
        return $articles;
    }

    private function featuresArticles()
    {
        ArticleModel::where_status(ArticleModel::publish);
        ArticleModel::where_is_feature(1);
        $articles = ArticleModel::fetch_all(8);
        return $articles;
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

    public function sendComment($article_id)
    {
        if (!Request::isPost()) self::error404();

        ArticleModel::where_status(ArticleModel::publish);
        $article = ArticleModel::fetch_by_id($article_id);
        if (empty($article)) self::error404();

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

        $formData['article_id'] = $article_id;
        $insert_id = CommentModel::insert($formData);
        if ($insert_id > 0) {
            Response::jsonMessage(rlang('front.comment_inserted_successfully'), true);
        }
        Response::jsonMessage(rlang('front.error_to_insert_comment'), true);
    }

    public function page($page_key)
    {
        $page = PageModel::fetch_by_page_key($page_key, PageModel::publish);
        if (empty($page)) self::error404();

        //store visits
        PageModel::update_visit($page['page_id']);
        $key = '_pinoox_page_' . $page['page_id'];
        if (Cookie::get($key, false) != 'visited') {
            PageModel::update_visitor($page['page_id']);
            Cookie::set($key, 'visited', 60 * 24);//expire after 1 day
        }

        self::$template->set('page', $page);
        self::$template->show('pages>page');
    }

    public function contact()
    {
        self::$template->show('pages>contact');
    }

}
