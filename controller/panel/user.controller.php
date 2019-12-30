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

use pinoox\app\com_pinoox_paper\model\PaperDatabase;
use pinoox\component\Date;
use pinoox\component\Dir;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Uploader;
use pinoox\component\Url;
use pinoox\component\Validation;
use pinoox\model\UserModel;

class UserController extends MasterConfiguration
{

    public function _main()
    {
        $this->show();
    }

    private function show()
    {
        $form = Request::post('page=1,keyword');

        $this->filter($form);
        $page = new Pagination(UserModel::count(), 20);
        $page->setCurrentPage($form['page']);

        $this->filter($form);
        $users = UserModel::fetch_all($page->getArrayLimit());

        self::$template->set('users', $users);
        self::$template->set('page', $page);
        self::$template->show('user>list');
    }

    private function filter($form)
    {
        UserModel::where_search($form['keyword']);
    }

    public function add()
    {
        if (Request::isAjax()) {
            $this->addCheck();
        } else {
            self::$template->show('user>add');
        }
    }

    private function addCheck()
    {
        if (!Request::isAjax()) self::error404();

        $form = Request::post('fname,lname,email,password');
        $valid = Validation::check($form, [
            'fname' => ['required|length:>2', rlang('panel.first_name')],
            'lname' => ['required|length:>2', rlang('panel.last_name')],
            'email' => ['required|email', rlang('panel.email')],
            'password' => ['required|length:>5', rlang('panel.password')],
        ]);
        if ($valid->isFail())
            Response::json($valid->first(), false);
        $user = UserModel::fetch_user_by_email($form['email']);
        if (!empty($user)) {
            Response::json(rlang('panel.this_email_already_exists'), false);
        }

        $status = UserModel::insert($form);
        if ($status)
            Response::json(rlang('panel.user_added_successfully'), true);

        Response::json(rlang('panel.error_happened'), false);
    }

    public function edit($user_id)
    {
        $user = UserModel::fetch_by_id($user_id);
        if (empty($user)) self::error404();

        if (Request::isAjax()) {
            $this->editCheck($user);
        } else {
            $user['image'] = Url::upload($user['avatar_id']);
            self::$template->set('isPreview', !empty($user['image']));
            self::$template->set('user', $user);
            self::$template->show('user>edit');
        }
    }

    private function editCheck($user)
    {
        if (!Request::isAjax()) self::error404();

        $form = Request::post('fname,lname,email,password,status,isDeleteImage');
        $form['avatar_id'] = $user['avatar_id'];
        $valid = Validation::check($form, [
            'fname' => ['length:>2', rlang('panel.first_name')],
            'lname' => ['required|length:>2', rlang('panel.last_name')],
            'email' => ['required|email', rlang('panel.email')],
            'password' => ['length:5>', rlang('panel.password')],
        ]);
        if ($valid->isFail())
            Response::json($valid->first(), false);

        PaperDatabase::startTransaction();
        //upload image thumbnail
        $uploader = Uploader::init()->transaction();
        if ($form['isDeleteImage'] == 'true') {
            $uploader = $uploader->init()->transaction()->thumb([128, 512, 800], 'thumbs/{name}_{size}.{ext}')->actRemoveRow($user['avatar_id']);
            $form['avatar_id'] = null;
        } else if (Request::isFile('image')) {
            $appPath = Dir::path('upload/users/');
            $uploader = $uploader->create('image', $appPath);
            $uploader = $uploader->removeRow($user['avatar_id']);
            $uploader = $uploader->allowedTypes('jpg,png', 2);
            $uploader = $uploader->insert('none', 'user/profile');
            $uploader = $uploader->thumb(['128f', '512f', '800f'], 'thumbs/{name}_{size}.{ext}');
            $uploader = $uploader->changeName('time');
            $uploader = $uploader->finish(true);
            if (!empty($err = $uploader->error(true))) {
                Response::json($err, false);
            }
            $id = $uploader->getInsertId();
            $form['avatar_id'] = $id;
        }

        $checkEmail = UserModel::fetch_user_by_email($form['email'], $user['user_id']);
        if (!empty($checkEmail)) {
            Response::json(rlang('panel.this_email_already_exists'), false);
        }

        $status = UserModel::update($user['user_id'], $form);
        if ($status) {
            $uploader->commit();
            PaperDatabase::commit();
            Response::json(rlang('panel.user_edit_successfully'), true);
        }

        Response::json(rlang('panel.error_happened'), false);
    }

    public function delete()
    {
        if (Request::isAjax()) {
            $user_id = Request::postOne('user_id');
            $user = UserModel::fetch_by_id($user_id);
            if (empty($user)) self::error404();
            PaperDatabase::startTransaction();
            $status = UserModel::delete($user_id);
            $up = Uploader::init()->transaction()->thumb(['128f', '512f', '800f'], 'thumbs/{name}_{size}.{ext}')->actRemoveRow($user['avatar_id']);

            if ($status) {
                if ($up->isCommit())
                    $up->commit();
                PaperDatabase::commit();
                Response::json(rlang('panel.delete_successfully'), true);
            }
        }
        Response::json(rlang('panel.error_happened'), false);
    }
}