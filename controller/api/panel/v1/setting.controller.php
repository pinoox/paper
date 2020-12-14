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

use pinoox\app\com_pinoox_paper\model\LangModel;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\app\com_pinoox_paper\model\SettingsModel;
use pinoox\component\app\AppProvider;
use pinoox\component\Lang;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Uploader;
use pinoox\component\Url;
use pinoox\component\User;

class SettingController extends LoginConfiguration
{
    private static $pathUpload = 'uploads/setting/images/';

    public function __construct()
    {
        parent::__construct();

        $headers = apache_request_headers();
        if (isset($headers['theme_name'])) {
            SettingsModel::setTheme($headers['theme_name']);
        }
    }

    public function get($name)
    {
        $items = SettingsModel::get($name);
        $items = !empty($items) ? $items : [];
        Response::json($items);
    }

    public function getAll()
    {
        $configs = SettingsModel::getAll();
        $configs = !empty($configs) ? $configs : [];
        Response::json($configs);
    }

    public function save($name)
    {
        $inputs = Request::input('*', null, '!empty');
        SettingsModel::save($name, $inputs);
        Response::json(rlang('post.save_successfully'), true);
    }

    public function getViews($lang = null)
    {
        $lang = !empty($lang) ? strtolower($lang) : Lang::current();
        Lang::change($lang);
        $views = SettingsModel::getViews();
        Response::json($views);
    }

    public function changeLang($lang)
    {
        $lang = strtolower($lang);
        AppProvider::set('lang', $lang);
        AppProvider::save();
        Lang::change($lang);
        $lang = LangModel::fetch_all();
        $direction = $lang['panel']['direction'];
        Response::json(['lang' => $lang, 'direction' => $direction]);
    }

    public function getImages()
    {
        $images = SettingsModel::fetch_images();
        $images = array_map(function ($image) {
            $img = self::$pathUpload . $image['file_name'];
            return [
                'id' => $image['file_id'],
                'img' => $img,
                'link' => Url::file($img),
            ];
        }, $images);
        Response::json($images);
    }

    public function uploadImage($theme = null)
    {
        if (Request::isFile('image')) {

            $path = path(self::$pathUpload);
            $upload = Uploader::init('image', $path)
                ->insert(null, 'setting', User::get('user_id'))
                ->allowedTypes('png,jpg,jpeg,svg', 5)
                ->changeName('time')
                ->finish(true);

            if ($result = $upload->result()) {
                $result['id'] = $upload->getInsertId();
                $result['img'] = self::$pathUpload . $result['uploadname'];
                Response::json([
                    'id' => $result['id'],
                    'img' => $result['img'],
                    'link' => Url::file($result['img']),
                ], true);
            } else {
                Response::json($upload->error('first'), false);
            }
        }
    }

    public function deleteImage($file_id)
    {
        Uploader::init()->actRemoveRow($file_id);
        Response::json(null, true);
    }

    public function getPosts()
    {
        $posts = Request::inputOne('posts', null, '!empty');
        $posts = PostModel::fetch_by_ids($posts);
        $posts = !empty($posts)? $posts : [];
        $posts = array_map(function ($post) {
            return PostModel::getInfoPost($post);
        }, $posts);
        Response::json($posts);

    }
}
