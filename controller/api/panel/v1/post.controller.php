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

use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\component\Date;
use pinoox\component\Pagination;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Url;
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
        $post['title'] = empty($post['title']) ? rlang('post.no_title') : $post['title'];

        return $post;
    }

    public function save()
    {
        $input = Request::post('post_id,title,summary,!context,tags,status=draft', null, '!empty');

        $valid = Validation::check($input, [
            'title' => ['required', rlang('panel.title')],
            'context' => ['required', rlang('panel.context')],
        ]);

        if ($valid->isFail())
            Response::jsonMessage($valid->first(), false);

        $isEdit = !empty($input['post_id']);
        if ($isEdit) {
            PostModel::update($input);
        } else {
            $input['post_id'] = PostModel::insert($input);
        }

        PostModel::insert_tags($input['post_id'], $input['tags']);

        Response::jsonMessage('با موفقیت اضافه شد', true, $input['post_id']);
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
}
