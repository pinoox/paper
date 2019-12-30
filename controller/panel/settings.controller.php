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
use pinoox\app\com_pinoox_paper\model\SettingsModel;
use pinoox\component\app\AppProvider;
use pinoox\component\Cache;
use pinoox\component\Request;
use pinoox\component\Response;

class SettingsController extends MasterConfiguration
{
    public function _main()
    {
        $this->showSettings();
    }

    public function _exception($group = null)
    {
        $this->showSettings($group);
    }

    private function showSettings($group = 'general')
    {
        $groups = SettingsModel::fetch_all_groups();
        $settings = SettingsModel::fetch_all($group);

        self::$template->set('active', $group);
        self::$template->set('groups', $groups);
        self::$template->set('settings', $settings);
        self::$template->show('settings>main');
    }

    public function save($activeGroup)
    {
        if (Request::isPost()) {
            $formData = Request::post('*');
            PaperDatabase::startTransaction();
            foreach ($formData as $key => $value) {
                SettingsModel::update($key, $value);
            }
            PaperDatabase::commit();
            Cache::clean('settings');
            $lang = isset($formData['site_lang']) ? $formData['site_lang'] : 'fa';
            $lang = $lang != 'en' && $lang != 'fa' ? 'fa' : $lang;
            AppProvider::set('lang', $lang);
            AppProvider::save();
        }


        Response::redirect(url('panel/settings/' . $activeGroup));
    }
}
