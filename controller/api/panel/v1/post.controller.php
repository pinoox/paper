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
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Validation;


class PostController extends LoginConfiguration
{
    public function get($post_id)
    {
        $post = PostModel::fetch_by_id($post_id);
        $post = $this->getInfoPost($post);
        Response::json($post);
    }

    private function getInfoPost($post)
    {
        if (empty($post)) return $post;
        $post['tags'] = PostModel::fetch_tags_by_post_id($post['post_id']);
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

    public function searchTags($keyword = null)
    {
        PostModel::where_tag_name($keyword);
        $tags = PostModel::fetch_all_tags(8);
        $tags = empty($tags) ? [] : $tags;
        Response::json($tags);
    }
}
