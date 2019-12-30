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

use pinoox\app\com_pinoox_paper\model\ArticleModel;
use pinoox\app\com_pinoox_paper\model\PaperDatabase;
use pinoox\app\com_pinoox_paper\model\TagModel;
use pinoox\component\Dir;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Uploader;
use pinoox\component\Url;
use pinoox\component\User;
use pinoox\component\Validation;

class ArticleController extends MasterConfiguration
{

    private $appPathBase;

    public function __construct()
    {
        parent::__construct();
        $this->appPathBase = 'upload/articles/u' . User::get('user_id') . '/';
    }

    public function _main()
    {
        $this->show();
    }

    private function show()
    {
        $form = Request::post('page=1,keyword');

        $this->filter($form);
        $page = new Pagination(ArticleModel::fetch_all(null, true), 10);
        $page->setCurrentPage($form['page']);

        $this->filter($form);
        $articles = ArticleModel::fetch_all($page->getArrayLimit());

        self::$template->set('articles', $articles);
        self::$template->set('page', $page);
        self::$template->show('article>list');
    }

    private function filter($form)
    {
        ArticleModel::where_search($form['keyword']);
    }

    public function add()
    {
        if (!Request::isAjax()) self::error404();

        $title = Request::postOne('title');
        if (empty($title))
            Response::jsonMessage(rlang('panel.enter_article_title'), false);

        $article_id = ArticleModel::insert($title);
        if ($article_id)
            Response::jsonMessage(rlang('panel.article_create_successfully'), true, $article_id);

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    public function edit($article_id)
    {
        $article = ArticleModel::fetch_by_id($article_id);
        if (empty($article)) self::error404();

        if (Request::isAjax()) {
            $this->editCheck($article);
        } else {
            $article['image'] = Url::upload($article['image_id']);
            self::$template->set('isPreview', !empty($article['image']));
            self::$template->set('tags', TagModel::fetch_all_by_article_id($article_id));
            self::$template->set('article', $article);
            self::$template->show('article>edit');
        }
    }

    private function editCheck($article)
    {
        $form = Request::post('title,summary,tags,!content,status,isDeleteImage');
        $form['image_id'] = $article['image_id'];
        $valid = Validation::check($form, [
            'title' => ['required|length:2>', rlang('panel.article_title')],
        ]);

        if ($valid->isFail()) {
            Response::jsonMessage($valid->first(), false);
        }

        PaperDatabase::startTransaction();

        //upload image thumbnail
        $uploader = Uploader::init()->transaction();
        if ($form['isDeleteImage'] == 'true') {
            $uploader = $uploader->thumb(['128f', '512f', '800f'], 'thumbs/{name}_{size}.{ext}')->actRemoveRow($article['image_id']);
            $form['image_id'] = null;
        } else if (Request::isFile('image')) {
            $appPath = Dir::path($this->appPathBase . $article['article_id'] . '/');
            $uploader = $uploader->create('image', $appPath);
            $uploader = $uploader->removeRow($article['image_id']);
            $uploader = $uploader->allowedTypes('jpg,png', 20);
            $uploader = $uploader->insert('none', 'article/thumb');
            $uploader = $uploader->thumb([128, 512, 800], 'thumbs/{name}_{size}.{ext}');
            $uploader = $uploader->changeName('time');
            $uploader = $uploader->finish(true);
            if (!empty($err = $uploader->error(true))) {
                Response::json($err, false);
            }
            $id = $uploader->getInsertId();
            $form['image_id'] = $id;
        }

        TagModel::insert_for_article($article['article_id'], $form['tags']);
        $form['insert_date'] = $article['insert_date'];
        $status = ArticleModel::update($article['article_id'], $form);
        if ($status) {
            $uploader->commit();
            PaperDatabase::commit();
            Response::jsonMessage(rlang('panel.article_changed_successfully'), true);
        }
        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    public function delete()
    {
        if (Request::isAjax()) {
            $id = Request::postOne('article_id');
            $article = ArticleModel::fetch_by_id($id);
            if (empty($article)) self::error404();
            PaperDatabase::startTransaction();
            $status = ArticleModel::delete($id);
            $up = Uploader::init()->transaction()->thumb([128, 512, 800], 'thumbs/{name}_{size}.{ext}')->actRemoveRow($article['image_id']);

            if ($status) {
                if ($up->isCommit())
                    $up->commit();
                PaperDatabase::commit();
                Response::json(rlang('panel.delete_successfully'), true);
            }
        }
        Response::json(rlang('panel.error_happened'), false);
    }

    public function imageUpload($article_id)
    {
        $appPath = Dir::path($this->appPathBase . $article_id . '/');
        if (!Request::isFile('file')) self::error404();

        $uploader = Uploader::init();
        $image_id = null;
        $uploader->create('file', $appPath)
            ->allowedTypes('jpg,png', 20)
            ->insert('none', 'article/content')
            ->changeName('time')
            ->finish(true);
        if (!empty($err = $uploader->error(true))) {
            Response::json($err, false);
        }
        $image = $uploader->getResultInsert();
        echo json_encode(array('location' => $image['link']));

    }

    public function saveAsFeature($article_id)
    {
        if (!Request::isAjax()) self::error404();

        $status = ArticleModel::update_feature($article_id);
        Response::jsonMessage('', $status);
    }

    public function updateStatus($id)
    {
        if (Request::isPost()) {
            $article = ArticleModel::fetch_by_id($id);
            if (empty($article)) self::error404();
            $status = ($article['status'] == ArticleModel::pending || $article['status'] == ArticleModel::suspend || $article['status'] == ArticleModel::draft) ? ArticleModel::publish : ArticleModel::suspend;
            $result = ArticleModel::update_status($id, $status);

            if ($result)
                Response::json(['articleStatus'=>$status,'title'=>rlang('panel.'.$status)], true);
        }
        Response::json(rlang('panel.error_happened'), false);
    }

}