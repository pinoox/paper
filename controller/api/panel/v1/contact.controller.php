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

namespace pinoox\app\com_pinoox_paper\controller\api\panel\v1;

use pinoox\app\com_pinoox_paper\component\Helper;
use pinoox\app\com_pinoox_paper\model\ContactModel;
use pinoox\component\Date;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;


class ContactController extends LoginConfiguration
{
    public function get($contact_id)
    {
        $contact = ContactModel::fetch_by_id($contact_id);
        $contact = $this->getInfoContact($contact);
        Response::json($contact);
    }

    public function getAll()
    {
        $form = Request::input('keyword,sort,status=all,perPage=10,page=1', null, '!empty');

        $this->filterSearch($form);
        $count = ContactModel::fetch_all(null, null, true);

        // pagination
        $pagination = new Pagination($count, $form['perPage']);
        $pagination->setCurrentPage($form['page']);

        $this->filterSearch($form);
        $contacts = ContactModel::fetch_all(null, $pagination->getArrayLimit());

        $contacts = array_map(function ($contact) {
            return $contact = $this->getInfoContact($contact);
        }, $contacts);

        Response::json(['contacts' => $contacts, 'pages' => $pagination->getInfoPage()['page']]);
    }

    private function filterSearch($form)
    {
        ContactModel::where_search($form['keyword']);
        ContactModel::where_status($form['status']);
        ContactModel::sort($form['sort']);
    }

    private function getInfoContact($contact)
    {
        if (empty($contact)) return $contact;
        $contact['approx_insert_date'] = Helper::getLocaleDate('l d F Y (H:i)', $contact['insert_date']);
        return $contact;
    }

    public function delete()
    {
        $contact_id = Request::inputOne('contact_id', null, '!empty');

        if (ContactModel::fetch_by_id($contact_id) != false) {
            $status = ContactModel::delete($contact_id);
            if ($status)
                Response::jsonMessage(rlang('panel.delete_successfully'), true);
        }

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }


}
