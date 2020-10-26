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

use pinoox\app\com_pinoox_paper\component\Browser;
use pinoox\app\com_pinoox_paper\model\PaperDatabase;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\app\com_pinoox_paper\model\StatisticModel;
use pinoox\component\Date;
use pinoox\component\Pagination;
use pinoox\app\com_pinoox_paper\model\UserSettingModel;
use pinoox\component\Dir;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Url;
use pinoox\component\User;
use pinoox\component\Uploader;
use pinoox\component\Validation;
use pinoox\model\FileModel;


class PostController extends LoginConfiguration
{
    public function get($post_id)
    {
        $post = PostModel::fetch_by_id($post_id);
        $post = $this->getInfoPost($post);
        Response::json($post);
    }

    public function getAll()
    {
        $form = Request::input('keyword,sort,status,perPage=10,page=1', null, '!empty');

        $this->filterSearch($form);
        $count = PostModel::fetch_all(null, true);

        // pagination
        $pagination = new Pagination($count, $form['perPage']);
        $pagination->setCurrentPage($form['page']);

        $this->filterSearch($form);
        $posts = PostModel::fetch_all($pagination->getArrayLimit());

        $posts = array_map(function ($post) {
            return $post = $this->getInfoPost($post);
        }, $posts);

        Response::json(['posts' => $posts, 'pages' => $pagination->getInfoPage()['page']]);
    }

    private function filterSearch($form)
    {
        PostModel::search_keyword($form['keyword']);
        PostModel::where_status($form['status']);
        PostModel::sort($form['sort']);
    }

    private function getInfoPost($post)
    {
        $placeHolder = Url::file('resources/image-placeholder.jpg');

        if (empty($post)) return $post;
        $post['tags'] = PostModel::fetch_tags_by_post_id($post['post_id']);
        $post['approx_insert_date'] = Date::j('l d F Y (H:i)', $post['insert_date']);
        $post['publish_date'] = Date::j('Y/m/d H:i', $post['publish_date']);
        $file = FileModel::fetch_by_id($post['image_id']);
        $post['image'] = Url::upload($file, $placeHolder);
        $post['thumb_128'] = Url::thumb($file, 128, $placeHolder);
        return $post;
    }

    public function save()
    {
        $input = Request::post('post_id,image,hash_id,title,summary,!context,tags,status=draft', null, '!empty');

        $validations = [
            'context' => ['required', rlang('panel.context')],
        ];

        if ($input['status'] == 'publish')
            $validations['title'] = ['required', rlang('panel.title')];

        $valid = Validation::check($input, $validations);

        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        PaperDatabase::startTransaction();
        $isEdit = !empty($input['post_id']);
        if ($isEdit) {
            PostModel::update($input);
        } else {
            $input['post_id'] = PostModel::insert($input);
        }

        // tags
        PostModel::insert_tags($input['post_id'], $input['tags']);

        PaperDatabase::commit();
        Response::jsonMessage(rlang('post.save_successfully'), true, $input['post_id']);
    }

    public function delete()
    {
        $post_id = Request::inputOne('post_id', null, '!empty');

        if (PostModel::fetch_by_id($post_id) != false) {
            $status = PostModel::delete($post_id);
            if ($status)
                Response::jsonMessage(rlang('panel.delete_successfully'), true);
        }

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    public function searchTags($keyword = null)
    {
        PostModel::where_tag_name($keyword);
        $tags = PostModel::fetch_all_tags(8);
        $tags = empty($tags) ? [] : $tags;
        Response::json($tags);
    }

    public function imageUpload()
    {
        $hash_id = Request::postOne('hash_id', null, '!empty');
        if (empty($hash_id) || !Request::isFile('upload'))
            $this->printMessageUpload(rlang('post.error_happened'), false);

        $path = Dir::path('uploads/post/' . htmlspecialchars($hash_id) . '/');
        $up = Uploader::init('upload', $path)
            ->insert($hash_id, 'post', User::get('user_id'))
            ->thumb('128')
            ->allowedTypes('jpg,jfif,jpeg,pjpeg,pjp,png,gif,bmp,webp,tiff,tif', 10)
            ->changeName('none')
            ->finish(true);

        $result = $up->result();
        if ($result) {
            $result['id'] = $up->getInsertId();
            $result['link'] = Url::upload($up->getInsertId());
            $this->printMessageUpload($result);
        } else {
            $err = $up->error('first');
            $this->printMessageUpload($err, false);
        }
    }

    private function printMessageUpload($result, $status = true)
    {
        if ($status) {
            $message = [
                "url" => $result['link'],
                'file' => [
                    'file_id' => $result['id'],
                    "name" => $result['realname'],
                    "size" => $result['size'],
                    "printSize" => $result['formattedSize'],
                    "ext" => $result['ext'],
                    'mimeType' => $result['mimeType'],
                    "link" => $result['link'],
                ]
            ];
        } else {
            $message = [
                'error' => [
                    'message' => $result,
                ]
            ];
        }

        Response::json($message);
    }

    public function getHashId()
    {
        $id = PostModel::getHashId();
        Response::json($id, true);
    }

    public function getImages($hash_id)
    {
        $images = PostModel::fetch_images($hash_id);
        $images = array_map(function ($image) {
            return [
                'file_id' => $image['file_id'],
                'link' => Url::link('~' . $image['path']),
            ];
        }, $images);

        Response::json($images);
    }

    public function saveSettings()
    {
        $input = Request::input('autosave', '', '!empty');
        UserSettingModel::save_data(User::get('user_id'), $input, 'post');
        Response::json(rlang('post.save_successfully'), true);
    }

    public function getSettings()
    {
        $data = UserSettingModel::get_data(User::get('user_id'), 'post');
        Response::json($data);
    }

    public function deleteImage()
    {
        $input = Request::input('file_id,hash_id', null, '!empty');
        if (!PostModel::fetch_image($input['file_id'], $input['hash_id']))
            self::error();

        Uploader::init()
            ->thumb('128')
            ->actRemoveRow($input['file_id']);
        Response::json(rlang('post.delete_successfully'), true);
    }

    public function visit($post_id)
    {
        $b = new Browser();
        $data = [
            'browser' => $b->getBrowser(),
            'browser_version' => $b->getVersion(),
            'platform' => $b->getPlatform(),
            'os' => $b->getOS(),
            'device' => $b->getDevice(),
        ];
        StatisticModel::visit($post_id, $data);
    }

    public function getStats($post_id)
    {
        $post = PostModel::fetch_by_id($post_id);
        if (empty($post)) return null;

        //visits
        $visits = StatisticModel::fetch_visits($post_id, 7);
        $visitsSeries = StatisticModel::createRangeData($visits['series'], 6, true);

        //visitors
        $visitors = StatisticModel::fetch_visitors($post_id, 7);
        $visitorsSeries = StatisticModel::createRangeData($visitors['series'], 6, true);

        $result = [
            'visits' => [
                'total' => $visits['total'],
                'series' => [['data' => $visitsSeries]]
            ],
            'visitors' => [
                'total' => $visitors['total'],
                'series' => [['data' => $visitorsSeries]]
            ],
        ];

        Response::json($result);
    }

    public function getDevices($post_id)
    {
        $post = PostModel::fetch_by_id($post_id);
        if (empty($post)) return null;

        $devices = StatisticModel::fetch_devices($post_id);
        $total = StatisticModel::fetch_total_devices($post_id);
        $percents = StatisticModel::calc_percents($devices, $total);

        $data = [
            'percents' => array_column($percents, 'percent'),
            'labels' => array_column($percents, 'device'),
            'total' => $total
        ];

        Response::json($data);
    }

}
