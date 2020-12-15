<?php
//paper functions file

use pinoox\app\com_pinoox_paper\component\TemplateHelper;
use pinoox\app\com_pinoox_paper\model\PostModel;
use pinoox\app\com_pinoox_paper\model\SettingsModel;

function setting($name = null, $theme = null)
{
    return SettingsModel::getData($name, $theme);
}

function paper_head()
{
    TemplateHelper::printHead();
}

function paper_footer()
{

}

function posts($value,$option = [])
{
    PostModel::where_post_type(PostModel::post_type);
    PostModel::where_status(PostModel::publish_status);
    $posts = PostModel::fetcher($value,$option);

    if(!is_array($posts))
        return $posts;

    $posts = array_map(function ($post) {
        return PostModel::getInfoPost($post);
    }, $posts);

    return $posts;
}

function hot_tags($limit = 10)
{
    return PostModel::hot_tags($limit);
}