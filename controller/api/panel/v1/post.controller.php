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

use pinoox\app\com_pinoox_paper\model\PaperDatabase;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\component\Dir;
use pinoox\component\File;
use pinoox\component\Request;
use pinoox\component\Response;
use pinoox\component\Uploader;
use pinoox\component\Url;
use pinoox\component\User;
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
        $input = Request::post('post_id,image,hash_id,title,summary,!context,tags,status=draft', null, '!empty');

        $valid = Validation::check($input, [
            'title' => ['required', rlang('panel.title')],
            'context' => ['required', rlang('panel.context')],
        ]);

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

    public function searchTags($keyword = null)
    {
        PostModel::where_tag_name($keyword);
        $tags = PostModel::fetch_all_tags(8);
        $tags = empty($tags) ? [] : $tags;
        Response::json($tags);
    }

    public function imageUpload()
    {
        $hash_id = Request::postOne('hash_id',null,'!empty');
        if(empty($hash_id) || !Request::isFile('upload'))
            $this->printMessageUpload(rlang('post.error_happened'),false);

        $path = Dir::path('uploads/post/' . htmlspecialchars($hash_id) . '/');
        $up = Uploader::init('upload', $path)
            ->insert($hash_id, 'post', User::get('user_id'))
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
        $images = array_map(function ($image){
            return [
                'file_id' => $image['file_id'],
                'link' => Url::link('~'.$image['path']),
            ];
        },$images);

        Response::json($images);
    }
}
