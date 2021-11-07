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

use pinoox\app\com_pinoox_manager\component\Wizard;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\app\com_pinoox_paper\model\UserModel;
use pinoox\model\UserModel as UserModelCore;
use pinoox\component\interfaces\ServiceInterface;
use pinoox\component\User;
use pinoox\model\FileModel;

class InstallService implements ServiceInterface
{

    public function _run()
    {
        $user = UserModel::fetch_by_app();
        if (!$user) {
            $package_name = app('package-name');
            $user = UserModel::fetch_by_app('com_pinoox_manager');
            if ($user) {
                UserModelCore::copy($user['user_id'], $package_name);
            }
        }

        UserModel::insert_self([
            'user_id' => $user['user_id'],
            'group_key' => 'administrator',
        ]);

        $hash_id = 'bt3a3w';

        // add image
        $image_id = FileModel::insert([
            "user_id" => User::get('user_id'),
            "group" => 'post',
            "realname" => 'paper.jpg',
            "uploadname" => 'paper.jpg',
            "ext" => 'jpg',
            "dir_file" => 'apps/com_pinoox_paper/resources/',
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

        $this->addPost($data);

        $hash_id = 'pt3a2w';

        // add about page
        $data = [
            'title' => rlang('db.about_page.title'),
            'context' => '<p style="text-align:center;">' . rlang('db.about_page.context') . '</p>',
            'hash_id' => $hash_id,
            'user_id' => isset($user['user_id']) ? $user['user_id'] : User::get('user_id'),
            'summary' => null,
            'time' => 0,
            'characters' => rlang('db.about_page.characters'),
            'words' => rlang('db.about_page.words'),
            'status' => $status,
            'post_type' => PostModel::page_type,
            'image' => null,
        ];

        $this->addPost($data);
    }

    private function addPost($data)
    {
        $data['post_id'] = PostModel::insert($data);
        PostModel::save_draft($data);
        PostModel::post_history_insert($data, $data['status']);
        PostModel::update_publish_post($data['post_id']);
        PostModel::post_draft_update_synced($data['post_id'], 1);
    }

    public function _stop()
    {
    }
}