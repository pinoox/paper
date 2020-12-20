<?php
/**
 *      ****  *  *     *  ****  ****  *    *
 *      *  *  *  * *   *  *  *  *  *   *  *
 *      ****  *  *  *  *  *  *  *  *    *
 *      *     *  *   * *  *  *  *  *   *  *
 *      *     *  *    **  ****  ****  *    *
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       pinoox.com
 * @copyright  pinoox
 */

namespace pinoox\app\com_pinoox_paper\service\app;

use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\app\com_pinoox_paper\model\UserModel;
use pinoox\component\interfaces\ServiceInterface;
use pinoox\component\User;
use pinoox\model\FileModel;

class InstallService implements ServiceInterface
{

    public function _run()
    {
        $hash_id = 'bt3a3w';

        // add image
        $image_id = FileModel::insert([
            "user_id" => User::get('user_id'),
            "group" => 'post',
            "realname" => 'paper.jpg',
            "uploadname" => 'paper.jpg',
            "ext" => 'jpg',
            "dir_file" => 'apps/com_pinoox_paper/uploads/post/' . $hash_id . '/',
            "size" => 16636,
            "access" => $hash_id,
        ]);

        // add post
        //$image = Url::upload($image_id);
        //$context = '<figure class="image image_resized" style="width:60%;"><img src="' . $image . '"></figure>';
        $context = '';
        $texts = rlang('db.welcome_post.context');
        foreach ($texts as $text) {
            $context .= '<p style="text-align:center;">' . $text . '</p>';
        }

        $title = rlang('db.welcome_post.title');
        $words = rlang('db.welcome_post.words');
        $characters = rlang('db.welcome_post.characters');
        $status = PostModel::publish_status;

        $user = UserModel::fetch_by_app();
        $data = [
            'title' => $title,
            'context' => $context,
            'hash_id' => $hash_id,
            'user_id' => isset($user['user_id']) ? $user['user_id'] : User::get('user_id'),
            'summary' => null,
            'time' => 0,
            'characters' => $characters,
            'words' => $words,
            'status' => $status,
            'post_type' => PostModel::post_type,
            'image' => $image_id,
        ];
        $data['post_id'] = PostModel::insert($data);
        PostModel::save_draft($data);
        PostModel::post_history_insert($data, $status);
        PostModel::update_publish_post($data['post_id']);
        PostModel::post_draft_update_synced($data['post_id'], 1);
    }

    public function _stop()
    {
    }
}