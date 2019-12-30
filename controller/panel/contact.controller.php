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

use pinoox\app\com_pinoox_paper\model\ContactModel;
use pinoox\app\com_pinoox_paper\model\PageModel;
use pinoox\component\Dir;
use pinoox\component\HelperString;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Uploader;
use pinoox\component\User;
use pinoox\component\Validation;

class ContactController extends MasterConfiguration
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
        $page = new Pagination(ContactModel::fetch_all(null, null, true), 10);
        $page->setCurrentPage($form['page']);

        $this->filter($form);
        $contacts = ContactModel::fetch_all(null, $page->getArrayLimit());

        self::$template->set('contacts', $contacts);
        self::$template->set('page', $page);
        self::$template->show('contact>list');
    }

    private function filter($form)
    {
        ContactModel::where_search($form['keyword']);
    }

    public function delete()
    {
        if (Request::isAjax()) {
            $id = Request::postOne('contact_id');
            $page = ContactModel::fetch_by_id($id);
            if (empty($page)) self::error404();
            $status = ContactModel::delete($id);

            if ($status) {
                Response::json(rlang('panel.delete_successfully'), true);
            }
        }
        Response::json(rlang('panel.error_happened'), false);
    }

    public function updateStatus($id)
    {
        if (Request::isPost()) {
            $contact = ContactModel::fetch_by_id($id);
            if (empty($contact)) self::error404();
            $status = ($contact['status'] == ContactModel::seen) ? ContactModel::unseen : ContactModel::seen;
            $result = ContactModel::update_status($id, $status);

            if ($result)
                Response::json(['contactStatus' => $status, 'title' => rlang('panel.' . $status)], true);
        }
        Response::json(rlang('panel.error_happened'), false);
    }

}