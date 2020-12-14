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

function posts($ids)
{
    $posts = PostModel::fetch_by_ids($ids);
    $posts = array_map(function ($post) {
        return PostModel::getInfoPost($post);
    }, $posts);

    return $posts;
}