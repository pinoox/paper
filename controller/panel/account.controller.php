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

use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Router;
use pinoox\component\User;

class AccountController extends MasterConfiguration
{
    public function __construct()
    {
        parent::__construct();
        $this->checkLogin();
        self::$template->removeFromHeader('toolbar');
        self::$template->removeFromHeader('sidebar');
        self::$template->removeFromFooter('footer');
    }

    private function checkLogin(){
        if(User::isLoggedIn() && Router::method() != 'logout'){
            Response::redirect(url('panel'));
        }
    }

    public function _main()
    {
        $this->loginPage();
    }

    private function loginPage($formData = null, $error = null)
    {
        self::$template->set('formData', $formData);
        self::$template->set('error', $error);
        self::$template->show('account>login');
        exit;
    }

    public function login()
    {
        if (!Request::isPost()) Response::redirect(\url('panel/account'));

        $form = Request::post('username,password', null, '!empty');

        if (empty($form['username'])) {
            $this->loginPage($form, rlang('~user.enter_username'));
        }
        if (User::login($form['username'], $form['password'])) {
            Response::redirect(\url('panel'));
        } else {
            $this->loginPage($form, User::getMessage());
        }
    }

    public function logout()
    {
        if (User::isLoggedIn()) {
            User::logout('');
        } else {
            Response::redirect(\url('panel/account'));
        }
    }

}