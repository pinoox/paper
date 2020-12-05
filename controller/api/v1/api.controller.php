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

use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Validation;
use pinoox\app\com_pinoox_paper\model\ContactModel;

class ApiController extends MasterConfiguration
{
    public function newContact()
    {
        $form = Request::input('full_name,email,message', null, '!empty');
        $valid = Validation::check($form, [
            'full_name' => ['required|length:>2', rlang('user.full_name')],
            'email' => ['required|email', rlang('user.email')],
            'message' => ['required|length:>10', rlang('paper.message')],
        ]);

        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        $contact_id = ContactModel::insert($form);
        if ($contact_id > 0)
            Response::jsonMessage(rlang('paper.message_received_successfully'), true);

        Response::jsonMessage(rlang('paper.error_happened'), false);
    }

}
    
