<?php
//paper functions file

use pinoox\app\com_pinoox_paper\model\CategoryModel;
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
    TemplateHelper::printFooter();
}

function posts($value, $option = [])
{

    PostModel::where_post_type(PostModel::post_type);
    PostModel::where_status(PostModel::publish_status);
    $posts = PostModel::fetcher($value, $option);

    if (!is_array($posts))
        return $posts;

    $date_format = isset($option['date_format']) ? $option['date_format'] : null;
    $posts = array_map(function ($post) use ($date_format) {
        return PostModel::getInfoPost($post, $date_format);
    }, $posts);

    return $posts;
}

function hot_tags($limit = 10)
{
    return PostModel::hot_tags($limit);
}

function category($key = null, $into = false)
{
    if ($into) {
        $cat = CategoryModel::fetch_by_id_or_key($key);
        if (!$cat)
            return null;
        CategoryModel::where_parent($cat['cat_id']);
        return CategoryModel::fetch_all();
    } else {
        return CategoryModel::fetch_by_id_or_key($key);
    }
}

function category_tree()
{
    $category = CategoryModel::fetch_all();
    return CategoryModel::tree($category);
}

function breadcrumb($key)
{
    return CategoryModel::get_breadcrumb($key);
}

function paper_menu($items = null)
{
    $items = empty($items) ? setting('general.menu') : $items;
    if (isset($items) && !empty($items)) {
        foreach ($items as $menu) {

            if (empty($menu['link']))
                $menu['link'] = '';

            $link = !filter_var(@$menu['link'], FILTER_VALIDATE_URL) ? url($menu['link']) : @$menu['link'];
            echo '<a href="' . $link . '">' . "\n";
            if (!empty($menu['icon'])) {
                echo '<i class="' . @$menu['icon'] . '"></i>' . "\n";
            } else if (!empty($menu['image'])) {
                echo '<img alt="' . @$menu['label'] . '" src="' . furl($menu['image']) . '"/>' . "\n";
            }
            echo $menu['label'] . "\n";
            echo '</a>' . "\n";
        }
    }
}