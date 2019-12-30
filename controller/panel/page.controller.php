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

use pinoox\app\com_pinoox_paper\model\PageModel;
use pinoox\component\Dir;
use pinoox\component\HelperString;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Uploader;
use pinoox\component\User;
use pinoox\component\Validation;

class PageController extends MasterConfiguration
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
        $page = new Pagination(PageModel::fetch_all(null, true), 10);
        $page->setCurrentPage($form['page']);

        $this->filter($form);
        $pages = PageModel::fetch_all($page->getArrayLimit());

        self::$template->set('pages', $pages);
        self::$template->set('page', $page);
        self::$template->show('page>list');
    }

    private function filter($form)
    {
        PageModel::where_search($form['keyword']);
    }

    public function add()
    {
        if (!Request::isAjax()) self::error404();

        $page_key = Request::postOne('page_key');
        if (empty($page_key))
            Response::jsonMessage(rlang('panel.enter_page_key'), false);

        if(HelperString::has($page_key,' '))
            Response::jsonMessage(rlang('panel.error_space_in_page_key'), false);

        $page = PageModel::fetch_by_page_key($page_key);
        if (!empty($page))
            Response::jsonMessage(rlang('panel.error_already_exists_this_page_key'), false);

        $page_id = PageModel::insert($page_key);
        if ($page_id)
            Response::jsonMessage(rlang('panel.page_create_successfully'), true, $page_id);

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    public function edit($page_id)
    {
        $page = PageModel::fetch_by_id($page_id);
        if (empty($page)) self::error404();

        if (Request::isAjax()) {
            $this->editCheck($page);
        } else {
            self::$template->set('page', $page);
            self::$template->show('page>edit');
        }
    }

    private function editCheck($page)
    {
        $form = Request::post('page_key,title,!content,status');
        $valid = Validation::check($form, [
            'page_key' => ['required|length:2>', rlang('panel.page_key')],
        ]);

        if ($valid->isFail()) {
            Response::jsonMessage($valid->first(), false);
        }

        $check = PageModel::fetch_by_page_key($form['page_key']);
        if (!empty($check) && $check['page_id'] != $page['page_id'])
            Response::jsonMessage(rlang('panel.error_already_exists_this_page_key'), false);

        if(HelperString::has($form['page_key'],' '))
            Response::jsonMessage(rlang('panel.error_space_in_page_key'), false);

        $status = PageModel::update($page['page_id'], $form);
        if ($status) {
            Response::jsonMessage(rlang('panel.page_changed_successfully'), true);
        }
        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    public function delete()
    {
        if (Request::isAjax()) {
            $id = Request::postOne('page_id');
            $page = PageModel::fetch_by_id($id);
            if (empty($page)) self::error404();
            $status = PageModel::delete($id);

            if ($status) {
                Response::json(rlang('panel.delete_successfully'), true);
            }
        }
        Response::json(rlang('panel.error_happened'), false);
    }

    public function updateStatus($id)
    {
        if (Request::isPost()) {
            $page = PageModel::fetch_by_id($id);
            if (empty($page)) self::error404();
            $status = ($page['status'] == PageModel::pending || $page['status'] == PageModel::suspend || $page['status'] == PageModel::draft) ? PageModel::publish : PageModel::suspend;
            $result = PageModel::update_status($id, $status);

            if ($result)
                Response::json(['pageStatus' => $status, 'title' => rlang('panel.' . $status)], true);
        }
        Response::json(rlang('panel.error_happened'), false);
    }

    public function imageUpload($page_id)
    {
        $appPath = Dir::path('upload/pages/u' . User::get('user_id') . '/' . $page_id . '/');
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

}