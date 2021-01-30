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
use pinoox\app\com_pinoox_paper\model\CommentModel;
use pinoox\app\com_pinoox_paper\model\PaperDatabase;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\app\com_pinoox_paper\model\PostStatisticModel;
use pinoox\component\Date;
use pinoox\component\Dir;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Uploader;
use pinoox\component\Url;
use pinoox\component\User;
use pinoox\component\Validation;


class PostController extends LoginConfiguration
{
    public function get($post_id, $post_type = null)
    {
        PostModel::where_post_type($post_type);
        $post = PostModel::post_draft_fetch_by_id($post_id);
        $post = PostModel::getInfoPost($post);

        Response::json($post);
    }

    public function getPostHistory($post_id)
    {
        $items = PostModel::fetch_history_by_post_id($post_id, 30);
        $items = array_map(function ($item) {
            $item['approx_insert_date'] = Date::j('l d F Y (H:i)', $item['insert_date']);
            return $item;
        }, $items);
        Response::json($items);
    }

    public function getAll()
    {
        $form = Request::input('keyword,type,sort,status=all,perPage=10,page=1', null, '!empty');

        $this->filterSearch($form);
        $count = PostModel::fetch_all_posts(null, true);

        // pagination
        $pagination = new Pagination($count, $form['perPage']);
        $pagination->setCurrentPage($form['page']);

        $this->filterSearch($form);
        $posts = PostModel::fetch_all_posts($pagination->getArrayLimit());

        $posts = array_map(function ($post) {
            return $post = PostModel::getInfoPost($post);
        }, $posts);

        Response::json(['posts' => $posts, 'pages' => $pagination->getInfoPage()['page']]);
    }

    private function filterSearch($form)
    {
        PostModel::where_post_type($form['type']);
        PostModel::search_keyword($form['keyword']);
        PostModel::where_status($form['status']);
        PostModel::sort($form['sort']);
    }

    public function getLatestPosts()
    {
        $posts = PostModel::fetch_all(10);

        $posts = array_map(function ($post) {
            return $post = PostModel::getInfoPost($post);
        }, $posts);

        Response::json($posts);
    }

    public function changeCategory()
    {
        $input = Request::input('cat_id,post_id', null, '!empty');

        $status = PostModel::update_category($input['cat_id'], $input['post_id']);

        if ($status)
            Response::json(rlang('post.save_successfully'), true);
        else
            Response::json(rlang('post.error_happened'), false);
    }

    public function save()
    {
        $input = Request::post(['post_id', 'post_type' => PostModel::post_type, 'status' => false, 'post_key', 'image', 'hash_id', 'title', 'summary', '!context', 'tags', 'characters' => 0, 'words' => 0, 'time' => 0, 'hook' => 'disable'], null, '!empty');

        $validations = [
            'context' => ['required', rlang('panel.context')],
        ];

        $valid = Validation::check($input, $validations);

        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        PaperDatabase::startTransaction();
        $isEdit = !empty($input['post_id']);

        // save data
        if ($isEdit) {
            PostModel::update($input);
        } else {
            $input['post_id'] = PostModel::insert($input);
        }

        // save draft
        PostModel::save_draft($input);

        // change status
        $this->changeStatus($input);

        // save tags
        PostModel::insert_tags($input['post_id'], $input['tags']);

        PaperDatabase::commit();

        if ($input['hook'] === 'enable') {
            $address = setting('developer.hook.address');
            $token = setting('developer.hook.token');

            if (Validation::checkOne($address, '!empty|url')) {
                @Request::sendPost($address, $input, [
                    'headers' => [
                        'paper_token' => $token,
                    ],
                    'background' => true,
                ]);
            }
        }

        Response::jsonMessage(rlang('post.save_successfully'), true, $input['post_id']);
    }

    private function changeStatus($input)
    {
        if (!$input['status'])
            return;

        if ($input['status'] === PostModel::publish_status) {
            $valid = Validation::check($input, [
                'title' => ['required', rlang('panel.title')],
                'context' => ['required', rlang('panel.context')],
            ]);
            if ($valid->isFail())
                Response::jsonMessage($valid->first(), false);

            $post = PostModel::fetch_by_id($input['post_id']);

            if ($input['post_type'] === PostModel::page_type) {
                if (empty($input['post_key']))
                    Response::jsonMessage(rlang('post.err_page_key_empty'), false);

                if (PostModel::fetch_by_key($input['post_key'], $input['post_id']))
                    Response::jsonMessage(rlang('post.err_repeat_key'), false);
            }

            $status = $post['status'] === PostModel::publish_status ? PostModel::synced_status : PostModel::publish_status;

            PostModel::post_history_insert($input, $status);
            $result = PostModel::update_publish_post($input['post_id']);
            PostModel::post_draft_update_synced($input['post_id'], 1);
        } else {
            PostModel::post_history_insert($input, PostModel::cancel_publish_status);
            $result = PostModel::update_status($input['post_id'], $input['status']);
        }

        if (!$result) {
            Response::json(rlang('post.error_happened'), false);
        }
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
        PostModel::search_tag_name($keyword);
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
            ->thumb(['128f', '512f'], PINOOX_PATH_THUMB)
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
        $input = Request::input(
            [
                'post_id',
                'comment_status' => PostModel::open_status
            ],
            null, '!empty'
        );
        PostModel::update_setting($input['post_id'], $input);
        Response::json(rlang('post.save_successfully'), true);
    }

    public function deleteImage()
    {
        $input = Request::input('file_id,hash_id', null, '!empty');
        if (!PostModel::fetch_image($input['file_id'], $input['hash_id']))
            self::error();

        Uploader::init()
            ->thumb(['128f', '512f'], PINOOX_PATH_THUMB)
            ->actRemoveRow($input['file_id']);
        Response::json(rlang('post.delete_successfully'), true);
    }

    public function visit($post_id)
    {
        PostStatisticModel::visit($post_id);
        Response::json('done');
    }

    public function getStats($post_id)
    {
        $post = PostModel::fetch_by_id($post_id);
        if (empty($post)) return self::error();

        $days = 7;

        //visits
        $visits = PostStatisticModel::fetch_visits($post_id, $days);
        $visitsSeries = Helper::createRangeDate(@$visits['series'], $days, true);

        $commentStats = CommentModel::fetch_stats_by_post($post_id);

        //comments
        $comments = CommentModel::fetch_stats_post_comment($post_id, $days);
        $commentSeries = Helper::createRangeDate(@$comments['series'], $days, true);

        $result = [
            'visits' => [
                'total' => $visits['total'],
                'series' => [['data' => $visitsSeries]]
            ],
            'commentsDays' => [
                'total' => $comments['total'],
                'series' => [['data' => $commentSeries]]
            ],
            'timeTracking' => Helper::timePrint($post['time_tracking']),
            'comments' => $commentStats,
        ];

        Response::json($result);
    }

    public function getMonthly($post_id = null)
    {
        if (!is_null($post_id)) {
            $post = PostModel::fetch_by_id($post_id);
            if (empty($post)) return null;
        }

        $lastDay = Request::inputOne('lastDay', 5, '!empty');

        if ($lastDay == 7)
            $lastDay = 7;
        else if ($lastDay == 21)
            $lastDay = 21;
        else
            $lastDay = 5;

        $days = $lastDay - 1;

        $rangeDate = Date::betweenGDate(Date::g('Y-m-d', '-' . $days . ' days'), Date::g('Y-m-d', '+1 days'));
        $rangeDate = array_map(function ($d) {
            return Helper::getLocaleDate('F d', $d);
        }, $rangeDate);

        $rangeDate[count($rangeDate) - 1] = rlang('post.today');
        $rangeDate[count($rangeDate) - 2] = rlang('post.yesterday');

        //visits
        $visits = PostStatisticModel::fetch_visits($post_id, $days);
        $visitsSeries = Helper::createRangeDate(@$visits['series'], $days, true);

        //comments
        $comments = CommentModel::fetch_stats_post_comment($post_id, $days);
        $commentSeries = Helper::createRangeDate(@$comments['series'], $days, true);

        $result = [
            [
                'name' => rlang('post.visits'),
                'data' => $visitsSeries
            ],
            [
                'name' => rlang('panel.comments'),
                'data' => $commentSeries
            ],
        ];

        Response::json(['series' => $result, 'date' => $rangeDate]);
    }

    public function hasStats($post_id)
    {
        Response::json(PostStatisticModel::has_stats($post_id));
    }

    public function deleteAllHistory($post_id)
    {
        if (PostModel::delete_all_history($post_id))
            Response::jsonMessage(rlang('panel.delete_successfully'), true);

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }

    public function deleteHistory($ph_id)
    {
        if (PostModel::delete_history_by_id($ph_id))
            Response::jsonMessage(rlang('panel.delete_successfully'), true);

        Response::jsonMessage(rlang('panel.error_happened'), false);
    }
}
